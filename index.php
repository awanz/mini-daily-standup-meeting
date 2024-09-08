<?php
  session_start();
  include_once('mysql.php');
  include_once('device.php');

  $max_attempts = 5;
  function getClientIp() {
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        // IP dari client jika berada di belakang proxy
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        // IP jika melalui proxy
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        // IP default
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    // Mengambil alamat IP pertama jika terdapat beberapa IP dalam header X-Forwarded-For
    $ip = explode(',', $ip)[0];
    
    return $ip;
}

  $ip_address = getClientIp();

  if (isset($_SESSION['email'])) {
    header("Location: standup-meeting", false, 301); // 301 for permanent redirect
    exit();
  }

  if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
  }

  $alert = null;
  if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $secretKey = '0x4AAAAAAAircL6Hp4KH3VpAvQx0duDTJ7U';
    $turnstileResponse = $_POST['cf-turnstile-response'];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://challenges.cloudflare.com/turnstile/v0/siteverify');
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query([
      'secret' => $secretKey,
      'response' => $turnstileResponse,
      'remoteip' => $_SERVER['REMOTE_ADDR'],
    ]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $responseData = json_decode($response, true);

    if ($responseData['success']) {
      if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = preg_replace('/[^a-zA-Z0-9.@]/', '', $_POST['email']);
        $password = md5(md5(preg_replace('/[^a-zA-Z0-9]/', '', $_POST['password'])));
        
        $db = new MySQLBase();
        $param = [
          'email' => $email,
          'password' => $password,
          'is_active' => 1
        ];
        $result = $db->getByArray("users", $param)->fetch_object();
        if (is_null($result)) {
          $_SESSION['login_attempts']++;
          $alert = 'Email atau password salah, Anda memiliki ' . ($max_attempts - $_SESSION['login_attempts']) . ' percobaan lagi.';
          if (($max_attempts - $_SESSION['login_attempts']) <= 0) {
            file_put_contents('.htaccess', "Deny from $ip_address\n", FILE_APPEND);
            $_SESSION['login_attempts'] = 0;
            $alert = 'Anda telah diblokir karena terlalu banyak percobaan login yang gagal, silahkan hubungi administrator dengan memberikan info IP berikut: '. $ip_address;
            $data = [
              "device_id" => $device_id,
              "code_id" => $code_id,
            ];
            $insertDevice = $db->insert("device_locks", $data);
            header("Location: index.php", false, 301);
          }
        }else{
          $_SESSION['fullname'] = $result->fullname;
          $_SESSION['email'] = $result->email;
          $_SESSION['login_attempts'] = 0;
          
          if ($_SESSION['email']) {
            header("Location: standup-meeting", false, 301);
            exit();
          }
        }
      }else {
        $alert = "Email atau password yang dikirim kosong";
      }
    } else {
      $alert = "Captcha gagal, coba lagi";
    }

    
  }
?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Daily stand-up meetings</title>
    <link href="../assets/favicon/apple-touch-icon.png" rel="apple-touch-icon" sizes="180x180" />
    <link href="../assets/favicon/favicon-32x32.png" rel="icon" sizes="32x32" type="image/png" />
    <link href="../assets/favicon/favicon-16x16.png" rel="icon" sizes="16x16" type="image/png" />
    <link href="../assets/favicon/favicon.ico" rel="icon" type="image/ico" />
    <link href="../assets/favicon/site.webmanifest" rel="manifest" />
  </head>
  <body>
    <div id="login">
      <div class="container">
        <?php if (isset($alert)) { ?>
        <div class="alert alert-danger m-2 mb-4" role="alert">
          <?= $alert; ?>
        </div>
        <?php } ?>
        <div id="login-row" class="row justify-content-center align-items-center">
            <div id="login-column" class="col-md-6">
                <div id="login-box" class="col-md-12">
                    <form id="login-form" class="form" action="" method="POST">
                        <h3 class="text-center text-primary">Daily stand-up meetings</h3>
                        <p><center><u>Ada perubahaan login, silahkan check email, atau hubungi koordinator</u></center></p>
                        <div class="form-group">
                          <label for="email" class="text-primary">Email:</label><br>
                          <input type="email" name="email" id="email" class="form-control">
                        </div>
                        <div class="form-group">
                          <label for="password" class="text-primary">Password:</label><br>
                          <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="form-group mt-4">
                          <div class="cf-turnstile" data-sitekey="0x4AAAAAAAircNL7y3k9G2c4"></div>
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary mt-2">Login</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div>
    </div>  
    <script src="https://challenges.cloudflare.com/turnstile/v0/api.js" async defer></script>
  </body>
</html>
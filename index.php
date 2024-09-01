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

  if (isset($_SESSION['token'])) {
    header("Location: standup-meeting", false, 301); // 301 for permanent redirect
    exit();
  }

  if (!isset($_SESSION['login_attempts'])) {
    $_SESSION['login_attempts'] = 0;
  }

  $alert = null;
  if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if ($_POST['token']) {
      $token = preg_replace('/[^a-zA-Z0-9]/', '', $_POST['token']);
      
      $db = new MySQLBase();
      $param = [
        'token' => $token,
        'is_active' => 1
      ];
      $result = $db->getByArray("users", $param)->fetch_object();
      if (is_null($result)) {
        $_SESSION['login_attempts']++;
        $alert = 'Token tidak valid, Anda memiliki ' . ($max_attempts - $_SESSION['login_attempts']) . ' percobaan lagi.';
        if (($max_attempts - $_SESSION['login_attempts']) <= 0) {
          file_put_contents('.htaccess', "Deny from $ip_address\n", FILE_APPEND);
          $_SESSION['login_attempts'] = 0;
          $alert = 'Anda telah diblokir karena terlalu banyak percobaan login yang gagal, silahkan hubungi administrator dengan memberikan info IP berikut: '. $ip_address;
          $data = [
            "device_id" => $device_id,
            "code_id" => $code_id,
          ];
          $insertDevice = $db->insert("device_locks", $data);
        }
      }else{
        $_SESSION['token'] = $result->token;
        $_SESSION['fullname'] = $result->fullname;
        $_SESSION['email'] = $result->email;
        $_SESSION['login_attempts'] = 0;
        
        if ($_SESSION['token']) {
          header("Location: standup-meeting", false, 301); // 301 for permanent redirect
          exit();
        }
      }
    }else {
      $alert = "Token yang dikirim kosong";
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
                        <p>Your IP: <?= $ip_address ?></p>
                        <div class="form-group">
                            <label for="token" class="text-primary">Token:</label><br>
                            <input type="password" name="token" id="token" class="form-control">
                        </div>
                        <div class="text-center">
                            <button class="btn btn-primary mt-2">Access</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div>
    </div>  
  </body>
</html>
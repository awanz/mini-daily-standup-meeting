<?php
  session_start();
  if (isset($_SESSION['token'])) {
    header("Location: standup-meeting.php", false, 301); // 301 for permanent redirect
    exit();
  }

  $alert = null;
  if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if ($_POST['token']) {
      $token = $_POST['token'];
      include_once('mysql.php');
      
      $db = new MySQLBase();
      $result = $db->getBy("users", "token", $token)->fetch_object();
      if (is_null($result)) {
        $alert = "Token tidak valid";
      }else{
        $_SESSION['token'] = $result->token;
        $_SESSION['fullname'] = $result->fullname;
        
        if ($_SESSION['token']) {
          header("Location: standup-meeting.php", false, 301); // 301 for permanent redirect
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
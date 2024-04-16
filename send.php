<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  
  require 'PHPMailer/Exception.php';
  require 'PHPMailer/PHPMailer.php';
  require 'PHPMailer/SMTP.php';

  session_start();
  $token = $_SESSION['token'];
  $isAdmin = false;
  
  if (!$token || is_null($_GET['email'])) {
    header("Location: index.php", false, 301);
    exit();
  }

  include_once('mysql.php');      
  $db = new MySQLBase();
  $result = $db->getBy("users", "token", $token)->fetch_object();
  
  if (is_null($result)) {
    header("Location: logout.php", false, 301);
    exit();
  }

  if ($result->fullname == "awan") {
    $isAdmin = true;
  }

  if (!$isAdmin) {
    header("Location: index.php", false, 301);
    exit();
  }

  $email = $_GET['email'];
  $target = $db->getBy("users", "email", $email)->fetch_object();
  if (isset($target)) {
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        $mail->Host       = 'mail.smtp2go.com';
        $mail->Port       = 2525;
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kawankerja.id';
        $mail->Password   = 'jCI11i8DIlwQ4GQm';
        $mail->SMTPSecure = 'tls';
        $mail->IsHTML(true);
        
        $mail->setFrom('daily@kawankerja.id', 'Kawan Kerja');
        $mail->addAddress($target->email, $target->fullname);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Token Daily Kawan Kerja';
        $mail->Body    = '
        Hello, '.$target->fullname.'<br>
        Berikut kode token yang bisa digunakan untuk mengakses <i><a href="https://kawankerja.id/daily">standup meeting</a></i>:
        <h3 style="text-align: center; padding: 50px 50px 50px 50px; color: white; background-color: black; font-size: 30px;"> <strong>'.$target->token.'</strong> </h3>
        Pastikan kode token tidak diberikan kepada orang lain dan dijaga kerahasiaannya!<br><br>
        <b>Kawan Kerja</b>
        ';

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
  }else{
    echo "Email tidak terdaftar";
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Send email</title>
</head>
<body>
  <script>//window.close();</script>
</body>
</html>
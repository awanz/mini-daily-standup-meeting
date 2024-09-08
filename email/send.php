<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  
  require '../PHPMailer/Exception.php';
  require '../PHPMailer/PHPMailer.php';
  require '../PHPMailer/SMTP.php';
  include_once('../mysql.php');      
  $db = new MySQLBase();

  session_start();
  $email = null;
  if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
  }
  $isAdmin = false;
  
  if (!$email || is_null($_GET['email'])) {
    header("Location: ../index.php", false, 301);
    exit();
  }

  $result = $db->getBy("users", "email", $email)->fetch_object();
  
  if (is_null($result)) {
    header("Location: ../logout.php", false, 301);
    exit();
  }

  if ($result->fullname == "awan") {
    $isAdmin = true;
  }

  if (!$isAdmin) {
    header("Location: ../index.php", false, 301);
    exit();
  } 

  $email = $db->escape($_GET['email']);
  $target = $db->getBy("users", "email", $email)->fetch_object();

  $randomLetterss = substr($target->email, 0, 2);
  $randomNumber = str_pad(mt_rand(0, 999), 3, '0', STR_PAD_LEFT);
  $letters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randomLetter = $letters[mt_rand(0, strlen($letters) - 1)];
  $randomLetter2 = $letters[mt_rand(0, strlen($letters) - 1)];
  $newPasswordRaw = $randomLetter . $randomLetterss . $randomNumber . $randomLetter2;
  
  if (isset($target)) {
    //Create an instance; passing `true` enables exceptions
    $mail = new PHPMailer(true);

    try {
        //Server settings
        $mail->SMTPDebug = 2;
        $mail->isSMTP();
        // $mail->Host       = 'mail.smtp2go.com';
        // $mail->Port       = 2525;
        // $mail->SMTPAuth   = true;
        // $mail->Username   = 'kawankerja.id';
        // $mail->Password   = 'jCI11i8DIlwQ4GQm';

        $mail->Host       = 'mail.kawankerja.id';
        $mail->Port       = 587;
        $mail->SMTPAuth   = true;
        $mail->Username   = 'daily@kawankerja.id';
        $mail->Password   = 'bandung1234!';

        // $mail->SMTPSecure = 'tls';
        $mail->IsHTML(true);
        
        $mail->setFrom('daily@kawankerja.id', 'Kawan Kerja');
        $mail->addAddress($target->email, $target->fullname);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Credential Daily Kawan Kerja';
        $mail->Body    = '
        Hello, '.$target->fullname.'<br>
        Berikut credential yang bisa digunakan untuk mengakses <i><a href="https://kawankerja.id/daily">standup meeting</a></i>:
        <br><br>
        <hr>
        Email: <b>'.$target->email.'</b><br>
        Password: <b>'.$newPasswordRaw.'</b><br>
        <hr>
        <br><br>
        <b>Pastikan credential tidak diberikan kepada orang lain dan dijaga kerahasiaannya!</b><br><br>
        <p>
          Hormat kami,<br>
          <b> PT Kawan Kerja Indonesia </b>
        </p>
        ';
        print_r($mail);

        $mail->send();
        $data = [
          "password" => md5(md5($newPasswordRaw)),
        ];
        $insert = $db->update("users", $data, 'id', $target->id);
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
  <script>window.close();</script>
</body>
</html>
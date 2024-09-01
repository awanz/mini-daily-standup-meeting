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
        $mail->SMTPSecure = 'tls';
        $mail->IsHTML(true);
        
        $mail->setFrom('daily@kawankerja.id', 'Kawan Kerja');
        $mail->addAddress($target->email, $target->fullname);

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Peringatan Ketidakaktifan Magang';
        $mail->Body    = '
        <h2>Peringatan Ketidakaktifan Magang</h2>
        <p>
            <strong>Kepada Yth.</strong><br>
            '.$target->fullname.'<br>
        </p>
        <p>
            <strong>Perihal: Peringatan Ketidakaktifan Magang</strong>
        </p>
        <p>
            Dengan hormat,<br><br>
            Melalui surat elektronik ini, kami ingin menyampaikan peringatan terkait ketidakaktifan magang berdasarkan daily standup meeting Anda selama 10 hari (akumulasi).<br><br>
            Berdasarkan informasi dari meeting sebelumnya, ketidakaktifan tanpa alasan yang sah dan pemberitahuan terlebih dahulu merupakan pelanggaran disiplin yang serius.
        </p>
        <p>
            Oleh karena itu, kami mohon Anda untuk mengisi daily standup meeting selambat-lambatnya <b>07 Mei 2024</b>.
        </p>
        <p>
            Jika Anda tidak mengisi daily standup meeting dalam waktu yang ditentukan, perusahaan berhak untuk mengambil tindakan disiplin selanjutnya, sesuai dengan peraturan yang berlaku.
        </p>
        <p>
            Kami harap Anda dapat memahami dan mematuhi peraturan perusahaan dengan baik.
        </p>
        <p>
            Hormat kami,<br>
        </p>
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
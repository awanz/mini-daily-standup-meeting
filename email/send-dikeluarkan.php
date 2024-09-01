<?php
  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;
  
  require '../PHPMailer/Exception.php';
  require '../PHPMailer/PHPMailer.php';
  require '../PHPMailer/SMTP.php';

  session_start();
  $token = null;
  if (isset($_SESSION['token'])) {
    $token = $_SESSION['token'];
  }
  $isAdmin = false;
  
  if (!$token || is_null($_GET['email'])) {
    header("Location: ../index.php", false, 301);
    exit();
  }

  include_once('../mysql.php');      
  $db = new MySQLBase();
  $result = $db->getBy("users", "token", $token)->fetch_object();

  $warning = $db->getBy("warnings", "user_id", $result->id)->fetch_object();
  if (empty($warning->counter)) {
    die('User belum mendapatkan surat peringatan pertama, tidak bisa langsung dikeluarkan');
  }
  
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
        $mail->AddCC('daily@kawankerja.id');

        //Content
        $mail->isHTML(true);
        $mail->Subject = 'Pemutusan Hubungan Kerja (Magang) - ' . $target->fullname.' ('. $target->email .')';
        $mail->Body    = '
        <p>
            <strong>Kepada Yth.</strong><br>
            '.$target->fullname.'<br>
        </p>
        <p>
            Melalui surat ini, kami sampaikan bahwa Anda akan menerima <b>Surat Peringatan (SP) Kedua</b> terkait dengan ketidakaktifan Anda dalam kegiatan magang di PT Kawan Kerja Indonesia yang sebelumnya Anda telah menerima SP Pertama.
        </p>
        <p>
            <b>SP Kedua</b> ini merupakan peringatan terakhir yang kami berikan. Oleh karena itu, dengan berat hati kami terpaksa mengambil tindakan tegas berupa pemutusan hubungan kerja sebagai peserta magang di PT Kawan Kerja Indonesia
        </p>
        <p>
            Kami harap Anda dapat memahami keputusan ini.
        </p>
        <p>
            <b>Jika Anda memiliki keberatan atau ingin mengajukan banding, Anda dapat menghubungi Koordinator di setiap Departemen.</b>
        </p>
        <p>
            Hormat kami,<br>
            <b> PT Kawan Kerja Indonesia </b>
        </p>
        ';

        $mail->send();
        try {
          $data = [
            "user_id" => $result->id,
            "email" => $email,
            "counter" => 2,            
          ];
          $db->insert("warnings", $data);
        } catch (\Throwable $th) {
          $alert = $th->getMessage();
          echo $alert;
        }
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
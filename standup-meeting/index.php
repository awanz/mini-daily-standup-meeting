<?php
  session_start();
  
  $dateGet = null;
  if (isset($_GET['tanggal'])) {
    $dateGet = $_GET['tanggal'];
    if (strtotime($dateGet) < strtotime(date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d')))))) {
      header("Location: standup-meeting.php", false, 301);
    }
  }

  $token = null;
  if (isset($_SESSION['token'])) {
    $token = $_SESSION['token'];
  }
  $email = $_SESSION['email'];
  $yesterday = null;
  $today = null;
  $problem = null;
  $alert = null;
  $insertData = null;
  $isAlready = false;
  $isAdmin = false;

  if (!$token) {
    header("Location: ../index.php", false, 301); // 301 for permanent redirect
    exit();
  }

  include_once('../mysql.php');      
  $db = new MySQLBase();
  $result = $db->getBy("users", "token", $token)->fetch_object();
  
  if (is_null($result)) {
    $alert = 'Session kamu telah expired, coba <a href="logout.php">re-login</a>';
  }

  if ($result->fullname == "awan") {
    $isAdmin = true;
  }
  
  $dailyFind = [
    "user_id" => $result->id,
    "date_activity" => $dateGet ? $dateGet : date('Y-m-d')
  ];

  $resultDaily = $db->getByArray("dailys", $dailyFind)->fetch_object();
  if (isset($resultDaily)) {
    $yesterday = $resultDaily->yesterday;
    $today = $resultDaily->today;
    $problem = $resultDaily->problem;
    $isAlready = true;
  }

  if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $yesterday = htmlspecialchars(trim($_POST['yesterday']), ENT_QUOTES, 'UTF-8');
    $today = htmlspecialchars(trim($_POST['today']), ENT_QUOTES, 'UTF-8');
    $problem = htmlspecialchars(trim($_POST['problem']), ENT_QUOTES, 'UTF-8');
    if (str_word_count($yesterday) > 1 && str_word_count($today) > 1 && str_word_count($problem) > 1) {
      try {
        if (is_null($resultDaily)) {
          $data = [
            "user_id" => $result->id,
            "yesterday" => $yesterday,
            "today" => $today,
            "problem" => $problem,
            "date_activity" => $dateGet ? $dateGet : date('Y-m-d'),
            "email" => $email,
          ];
          $insert = $db->insert("dailys", $data);
          $insertData = $insert;
        }
      } catch (\Throwable $th) {
        $alert = $th->getMessage();
      }
    }else{
      $alert = "Semua masukan harus diisi minimal 2 kata, cek kembali.";
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
    <style>
      .form-group{
        margin-bottom: 20px;
      }
    </style>
  </head>
  <body>
    <div class="container">
    <div class="d-flex justify-content-between">
      <div>
        <?php if ($isAdmin) { ?>
        <a href="../users/index.php">User</a>
        <?php } ?>
      </div>
      <div>
        <a href="../logout.php">Keluar</a>
      </div>
    </div>
    <div class="card mt-2">
      <div class="card-body">
          <h5 class="card-title">Daily stand-up meetings - (<?= $dateGet ? $dateGet : date('Y-m-d') ?>) [<a href="history.php">Histori</a>]</h5>
          <p>Hello, <b><?= $_SESSION['fullname'] ?></b> <u>(<?= $_SESSION['email'] ?>)</u></p>
          <h6>Pastikan email yang tercantum benar, karna info maupun teguran akan dikirim melalui e-mail.</h6>
          <h6>Setiap bulan minimal mengisi 15 kali daily standup meeting, meskipun SAKIT, IZIN wajib isi.</h6>
          <hr>
        <form action="" method="GET">
          <input type="date" id="tanggal" name="tanggal" value="<?= $dateGet ? $dateGet : date('Y-m-d') ?>" max="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d', strtotime('-30 day', strtotime(date('Y-m-d')))) ?>">
          <input type="submit" value="Ubah">
        </form>
        
        <div class="m-1">
          <?php if (isset($alert)) { ?>
          <div class="alert alert-danger m-2 mb-4" role="alert">
            <?= $alert; ?>
          </div>
          <?php } ?>
          <?php if (isset($insertData)) { ?>
          <div class="alert alert-info m-2 mb-4" role="alert">
            <?= $insertData['message']; ?>
          </div>
          <?php } ?>
          <form action="" method="post">
            <div class="form-group">
            </div>
            <div class="form-group">
              <label for="yesterday">Apa yang kamu kerjakan kemarin?</label>
              <textarea class="form-control" name="yesterday" rows="3"><?= $yesterday ?></textarea>
            </div>
            <div class="form-group">
              <label for="today">Apa yang akan kamu kerjakan hari ini?</label>
              <textarea class="form-control" name="today" rows="3"><?= $today ?></textarea>
            </div>
            <div class="form-group">
              <label for="problem">Apakah ada kendala yang kamu hadapi?</label>
              <textarea class="form-control" name="problem" rows="3"><?= $problem ?></textarea>
            </div>
            <?php if (is_null($insertData) && !$isAlready) { ?>
            <div class="form-group">
              <div class="text-center">
                <button class="btn btn-primary mt-2">Lapor</button>
              </div>
            </div>
            <?php } ?>
          </form>
          <h6 class="card-subtitle mb-4"><small><i>*Mohon isi semua bagian, jika lupa isi atau salah isi bisa info ke Project Manager/Koordinator</i></small></h6>
        </div>
      </div>
  </body>
</html>
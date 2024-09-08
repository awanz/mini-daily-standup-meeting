<?php
  session_start();
  include_once('../mysql.php');      
  $db = new MySQLBase();

  $getEmail = null;
  if (isset($_GET['email'])) {
    $getEmail = $db->escape($_GET['email']);
  }


  $email = null;
  if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
  }
  $isAdmin = false;
  
  if (!$email) {
    header("Location: ../index.php", false, 301);
    exit();
  }

  $result = $db->getBy("users", "email", $email)->fetch_object();
  
  if (is_null($result)) {
    header("Location: ../logout.php", false, 301);
    exit();
  }

  $admin = array("awan", "opik", "shasa", "angga", "teguh");
  if (in_array($result->fullname, $admin)) {
    $isAdmin = true;
  }

  if ($isAdmin) {
    if ($getEmail) {
      $query = "SELECT d.id, d.user_id, d.date_activity, d.yesterday, d.today, d.problem, d.created_at, u.email, u.fullname FROM dailys d INNER JOIN users u ON d.user_id = u.id WHERE u.is_active = 1 and d.email = '".$getEmail."';";
    }else{
      $query = 'SELECT d.id, d.user_id, d.date_activity, d.yesterday, d.today, d.problem, d.created_at, u.email, u.fullname FROM dailys d INNER JOIN users u ON d.user_id = u.id WHERE u.is_active = 1;';
    }
    // die($query);
    $resultDaily = $db->raw($query)->fetch_all();
  }else{
    $resultDaily = $db->getBy("dailys", "user_id", $result->id, "date_activity DESC")->fetch_all();
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
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css" />
  </head>
  <body>
    <div class="container">
      
      <div class="d-flex justify-content-between">
        <div>
          <?php if ($isAdmin) { ?>
          <a href="../users">User</a>
          <?php } ?>
        </div>
        <div>
          <a href="../logout.php">Keluar</a>
        </div>
      </div>
      <div class="card mt-2">
        <div class="card-body">
          <h5 class="card-title">Daily stand-up meetings - (<?= date('d M Y') ?>) [<a href="../standup-meeting">Lapor</a>]</h5>
          <div>
            <p>Hello, <b><?= $_SESSION['fullname'] ?></b></p>
            <h6>Pastikan setiap bulan minimal mengisi 15 kali daily standup meeting, meskipun SAKIT, IZIN wajib isi.</h6>
          </div>
          <div class="table-responsive">
            <table id="history">
              <thead>
                  <tr>
                      <th>Tanggal</th>
                      <?php if ($isAdmin) { ?>
                      <th>Nama</th>
                      <?php } ?>
                      <th>Aktifitas Kemarin</th>
                      <th>Hari ini</th>
                      <th>Permasalahan</th>
                      <?php if ($isAdmin) { ?>
                      <th>Delete</th>
                      <?php } ?>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($resultDaily as $value) { ?>
                    
                  <tr>
                      <td><?= $value[2] ?></td>
                      <?php if ($isAdmin) { ?>
                      <th><a href="history.php?email=<?= $value[7] ?>"><?= $value[8] ?></a></th>
                      <?php } ?>
                      <td><?= $value[3] ?></td>
                      <td><?= $value[4] ?></td>
                      <td><?= $value[5] ?></td>
                      <?php if ($isAdmin) { ?>
                      <td><a href="delete.php?id=<?= $value[0] ?>">Delete</a></td>
                      <?php } ?>
                  </tr>
                  <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="https://cdn.datatables.net/2.0.3/js/dataTables.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/dataTables.buttons.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.dataTables.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.html5.min.js"></script>
  <script src="https://cdn.datatables.net/buttons/3.0.1/js/buttons.print.min.js"></script>
    <script>
        new DataTable('#history', {
            layout: {
                topStart: {
                    buttons: ['excel']
                }
            },
            order: [[0, 'desc']]
        });
    </script>
  </body>
</html>
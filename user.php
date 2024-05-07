<?php
  session_start();
  $token = $_SESSION['token'];
  $isAdmin = false;
  
  if (!$token) {
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

  $resultUser = $db->getAll("view_user_daily")->fetch_all();
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
          <a href="history.php">Histori</a>
          <?php } ?>
        </div>
        <div>
          <a href="logout.php">Keluar</a>
        </div>
      </div>
      <div class="card mt-2">
        <div class="card-body">
          <div>
            <p>Hello, <b><?= $_SESSION['fullname'] ?></b></p>
          </div>
          <div class="table-responsive">
            <table id="history">
              <thead>
                  <tr>
                      <th>ID</th>
                      <th>Fullname</th>
                      <th>Email</th>
                      <th>Action</th>
                      <th>Total Daily</th>
                      <th>Surat Peringatan</th>
                      <th>Surat Pemecatan</th>
                  </tr>
              </thead>
              <tbody>
                  <?php foreach ($resultUser as $key => $value) { ?>
                  <tr>
                      <td><?= $value[0] ?></td>
                      <td><?= $value[1] ?></td>
                      <td><?= $value[2] ?></td>
                      <td><a target="_BLANK" href="send.php?email=<?= $value[2] ?>">Send</a></td>
                      <td><?= $value[3] ?></td>
                      <td><a target="_BLANK" href="send-peringatan.php?email=<?= $value[2] ?>">Send</a></td>
                      <td><a target="_BLANK" href="send-dikeluarkan.php?email=<?= $value[2] ?>">Send</a></td>
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
    <script>
      $('#history').dataTable({
        paging: false
      });
    </script>
  </body>
</html>
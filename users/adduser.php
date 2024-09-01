<?php
  session_start();
  $token = null;
  if (isset($_SESSION['token'])) {
    $token = $_SESSION['token'];
  }
  $isAdmin = false;
  
  if (!$token) {
    header("Location: ../index.php", false, 301);
    exit();
  }

  include_once('../mysql.php');      
  $db = new MySQLBase();
  $result = $db->getBy("users", "token", $token)->fetch_object();
  
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

  if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $email = trim($_POST['email']);
    $fullname = trim($_POST['fullname']);
    $token = trim($_POST['token']);
    $projects = trim($_POST['projects']);
    $notes = trim($_POST['notes']);
    
    try {
      $data = [
        "email" => $email,
        "fullname" => $fullname,
        "token" => $token,
        "projects" => $projects,
        "notes" => $notes,
      ];
      $db->insert("users", $data);
    } catch (\Throwable $th) {
      $alert = $th->getMessage();
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
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.dataTables.css" />
  </head>
  <body>
    <div class="container">
      
      <div class="d-flex justify-content-between">
        <div>
          <?php if ($isAdmin) { ?>
          <a href="index.php">User</a>
          <?php } ?>
        </div>
        <div>
          <a href="../logout.php">Keluar</a>
        </div>
      </div>
      <div class="card mt-2">
        <div class="card-body">
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
        <form method="POST" action="">
            <div class="form-group">
                <label for="email">Email address</label>
                <input name="email" type="email" class="form-control" id="email" placeholder="Masukan email">
            </div>
            <div class="form-group">
                <label for="fullname">Fullname</label>
                <input name="fullname" type="text" class="form-control" id="fullname" placeholder="Masukan fullname">
            </div>
            <div class="form-group">
                <label for="token">Token</label>
                <input name="token" type="text" class="form-control" id="token" placeholder="Masukan token">
            </div>
            <div class="form-group">
                <label for="token">Projects</label>
                <input name="projects" type="text" class="form-control" id="token" placeholder="Masukan projects">
            </div>
            <div class="form-group">
                <label for="token">Notes</label>
                <textarea name="notes" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary my-2">Daftar</button>
            </form>
        </div>
      </div>
    </div>
  </body>
</html>
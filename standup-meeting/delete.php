<?php
  session_start();
  include_once('../mysql.php');
  $db = new MySQLBase();

  $email = null;
  if (isset($_SESSION['email'])) {
    $email = $db->escape($_SESSION['email']);
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

  if ($result->fullname == "awan") {
    $isAdmin = true;
  }

  if (!$isAdmin) {
    header("Location: ../index.php", false, 301);
    exit();
  }

  $id = $db->escape($_GET['id']);
  $daily = $db->getBy("dailys", "id", $id)->fetch_object();
  
  if (empty($daily)) {
    header("Location: history.php", false, 301);
    exit();
  }


  try {
    $insert = $db->delete("dailys", 'id', $id);
    header("Location: history.php", false, 301);
    exit();
  } catch (\Throwable $th) {
    header("Location: history.php", false, 301);
    exit();
  }
?>
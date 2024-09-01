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
    die('qwe');
    header("Location: ../logout.php", false, 301);
    exit();
  }

  if ($result->fullname == "awan") {
    $isAdmin = true;
  }

  if (!$isAdmin) {
    die('zxcxz');
    header("Location: ../index.php", false, 301);
    exit();
  }

  $id = $_GET['id'];
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
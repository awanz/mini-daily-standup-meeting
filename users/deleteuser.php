<?php
  session_start();
  $email = null;
  if (isset($_SESSION['email'])) {
    $email = $_SESSION['email'];
  }
  $isAdmin = false;
  
  if (!$email) {
    header("Location: ../index.php", false, 301);
    exit();
  }

  include_once('../mysql.php');      
  $db = new MySQLBase();
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

  $id = $_GET['id'];
  $user = $db->getBy("users", "id", $id)->fetch_object();
  
  if (empty($user)) {
    header("Location: index.php", false, 301);
    exit();
  }


    try {
        $data = [
            "is_active" => 0,
        ];
        $insert = $db->update("users", $data, 'id', $id);
        header("Location: index.php", false, 301);
        exit();
    } catch (\Throwable $th) {
        header("Location: index.php", false, 301);
        exit();
    }
?>
<?php
  session_start();
  session_destroy();
  header("Location: index.php", false, 301); // 301 for permanent redirect
  exit();
?>
<?php
    include_once('mysql.php');
    include_once('device.php');
    if (empty($result->device_id)) {
        header("Location: index.php", false, 301);
    }
    function getClientIp() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            // IP dari client jika berada di belakang proxy
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            // IP jika melalui proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            // IP default
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    
        // Mengambil alamat IP pertama jika terdapat beberapa IP dalam header X-Forwarded-For
        $ip = explode(',', $ip)[0];
        
        return $ip;
    }
    
    $client_ip = getClientIp();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Diblokir</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 50px;
        }
        h1 {
            color: #ff0000;
        }
        p {
            font-size: 18px;
        }
    </style>
</head>
<body>
    <h1>Akses Diblokir</h1>
    <p>Anda telah diblokir dari mengakses situs ini karena terlalu banyak percobaan login yang gagal.</p>
    <p>Silakan hubungi administrator jika Anda merasa ini adalah kesalahan.</p>
    <p>Your IP: <?= $client_ip ?></p>
    <p>Your Code: <?= $code_id ?></p>
</body>
</html>

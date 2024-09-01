<?php
    $current_date = new DateTime();
    $key = 'ndje3h4fs5duichj7e45wbi7d8s8uf9o9isd';
    $device_id = null;
    $code_id = null;

    if (isset($_COOKIE[$key])) {
        $device_id = $_COOKIE[$key];
        $dbs = new MySQLBase();
        $result = $dbs->getBy("device_locks", 'device_id', $device_id)->fetch_object();
        if (isset($result)) {
            $code_id = $result->code_id;
        }else{
            $code_id = md5($current_date->format('Y-m-d H:i:s') . 'awan');
        }

        $request_uri = $_SERVER['REQUEST_URI'];
        $file_name = basename(parse_url($request_uri, PHP_URL_PATH));

        if (isset($result->device_id) && $file_name != 'blocked.php') {
            header("Location: blocked.php", false, 301);
        }
    }else{
        $device_id = md5($current_date->format('Y-m-d H:i:s'));
        $code_id = md5($current_date->format('Y-m-d H:i:s') . 'awan');
        setcookie($key, $device_id);
    }
    

<?php

try {
    require_once 'config.php';
    require_once 'route/web.php';

    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    $uri = str_replace(SUB_PATH, '', $uri);
    // print_r($uri); die();

    handleRoute($uri);
} catch (\Throwable $th) {
    // echo "<pre>";
    // print_r($th);
    // die();
    // if (DEBUG) {
    //     echo '<pre>';
    //     print_r($th);
    //     die();
    // }
    $result = [
        'status' => 'FAILED',
        'message' => $th->getMessage(),
    ];
    $_SESSION['flash_message_alert'] = $result;
    // echo $_SERVER['HTTP_REFERER'];
    header("Location: ". BASE_URL, false, 301);
}


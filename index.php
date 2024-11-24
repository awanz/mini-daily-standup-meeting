<?php

require_once 'config.php';
require_once 'router.php';

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = str_replace(SUB_PATH, '', $uri);
// print_r($uri); die();

handleRoute($uri);

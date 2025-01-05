<?php
    ini_set('session.gc_maxlifetime', 3600);
    session_start();
    $config = parse_ini_file('.env');

    foreach ($config as $key => $value) {
        define(strtoupper($key), $value);
    }

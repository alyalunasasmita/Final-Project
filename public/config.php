<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

define('PUBLIC_PATH', __DIR__); // .../finalProject/public
define('ROOT_PATH', dirname(__DIR__));   // .../finalProject
define('BASE_URL', '/'); // URL dasar aplikasi

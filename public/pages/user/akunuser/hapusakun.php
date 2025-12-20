<?php
require_once __DIR__ . "/../../../config.php";
require_once ROOT_PATH . '/backend/userAcc.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';

use App\AuthMiddleware;
use App\User;

$userAuth = AuthMiddleware::authUser();
$userModel = new User();

if ($userModel->deleteById($userAuth['id'])) {

    AuthMiddleware::logout();

    header("Location: /finalProject/public/frontend/login.php");
    exit;
}

header("Location: /finalProject/public/frontend/user/profile_view.php");
exit;

<?php
use App\AuthMiddleware;
use App\User;

require_once __DIR__."/../../../../backend/AuthMiddleware.php";
require_once __DIR__. '/../../../../backend/userAcc.php';

/* ================= AUTH ================= */
$userAuth = AuthMiddleware::authUser();
$userModel = new User();

/* ================= LOG DULU ================= */

/* ================= DELETE ================= */
if ($userModel->deleteById($userAuth['id'])) {

    AuthMiddleware::logout();

    header("Location: /finalProject/public/frontend/login.php");
    exit;
}

header("Location: /finalProject/public/frontend/user/profile_view.php");
exit;

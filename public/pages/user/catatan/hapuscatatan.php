<?php
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/backend/catatanUser.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';
use App\AuthMiddleware;
AuthMiddleware::authUser();

use App\catat\Catatan;

if (!isset($_GET['id'])) {
    header('Location: lihatCatatan.php');
    exit();
}

$catat = new Catatan($_SESSION['user_id']);
$id = intval($_GET['id']);

$catat->deleteCatatan($id);
header('Location: lihatCatatan.php');
exit();

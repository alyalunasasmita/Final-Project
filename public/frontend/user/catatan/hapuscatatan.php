<?php
// hapus_catatan.php
require_once __DIR__ . '/../../../../backend/AuthMiddleware.php';
use App\AuthMiddleware;
AuthMiddleware::authUser();

require_once __DIR__ . '/../../../../backend/catatanUser.php';
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

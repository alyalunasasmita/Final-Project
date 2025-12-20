<?php
session_start();

require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/backend/tugas.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';

use App\AuthMiddleware;
use App\Tugas;

// auth user
$userAuth = AuthMiddleware::authUser();
$userId = $userAuth['id'] ?? 0;

$tugas = new Tugas((int)$userId);

// ambil id dari form
$idCatatan = isset($_POST['id_catatan']) ? (int)$_POST['id_catatan'] : 0;

// jalankan hapus
$res = $tugas->deleteTugas($idCatatan);

// simpan flash message
$_SESSION['flash'] = $res;

// balik ke halaman list
header("Location: lihatTugas.php");
exit;

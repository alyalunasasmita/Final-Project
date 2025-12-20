<?php
require_once __DIR__ . "/../../config.php";
require_once ROOT_PATH . "/backend/materi.php";
require_once ROOT_PATH . "/backend/AuthMiddleware.php";
use App\AuthMiddleware;
AuthMiddleware::authAdmin();

use App\Materi\Materi;

$tambah_materi = new Materi(); 
$tambah_materi->archiveMateri($_GET['id']);

header ('Location: lihatMateri.php');
?>
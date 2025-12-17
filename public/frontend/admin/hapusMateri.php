<?php
require_once __DIR__ . "/../../../backend/AuthMiddleware.php";
use App\AuthMiddleware;
AuthMiddleware::authAdmin();

require_once __DIR__ . "/../../../backend/materi.php";
use App\Materi\Materi;

$tambah_materi = new Materi(); 
$tambah_materi->deleteMateri($_GET['id']);

header ('Location: lihatMateri.php');
?>
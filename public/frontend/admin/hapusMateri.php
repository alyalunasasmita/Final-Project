<?php
require_once __DIR__ . "/../../../backend/AuthMiddleware.php";
use App\AuthMiddleware;

AuthMiddleware::authAdmin();

require_once __DIR__ . "/../../../backend/materi.php";
use App\Materi\Materi;

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id <= 0) {
    header("Location: lihatMateri.php?err=invalid_id");
    exit;
}

$materi = new Materi();
$ok = $materi->deleteHardMateri($id);

if ($ok) {
    header("Location: lihatMateri.php?ok=deleted");
} else {
    header("Location: lihatMateri.php?err=delete_failed");
}
exit;

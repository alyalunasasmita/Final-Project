<?php
require_once __DIR__ . "/../../../backend/AuthMiddleware.php";
use App\AuthMiddleware;

AuthMiddleware::authAdmin();

require_once __DIR__ . "/../../../backend/materi.php";
use App\Materi\Materi;

$materi = new Materi();
$action = $_POST['action'] ?? '';
$id = (int)($_POST['id_materi'] ?? 0);

if ($id <= 0) {
  header("Location: materiArsip.php?err=invalid_id");
  exit;
}

if ($action === 'restore') {
  $materi->restoreMateri($id);
  header("Location: lihatMateri.php?ok=restored");
  exit;
}

header("Location: materiArsip.php?err=invalid_action");
exit;

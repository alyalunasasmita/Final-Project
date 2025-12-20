<?php
require_once __DIR__ . "/../../config.php";
require_once ROOT_PATH. "/backend/materi.php";
require_once ROOT_PATH . "/backend/AuthMiddleware.php";

use App\AuthMiddleware;
AuthMiddleware::authAdmin();
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

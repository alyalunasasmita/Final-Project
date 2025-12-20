<?php
require_once __DIR__ . "/../../config.php";
require_once ROOT_PATH . '/backend/subMateri.php';
require_once ROOT_PATH . "/backend/AuthMiddleware.php";

use App\AuthMiddleware;

AuthMiddleware::authAdmin();


use App\Submateri\Submateri;


$id = $_GET['id'] ?? null;
$materiId = $_GET['materi'] ?? null;

if (!$id || !$materiId) {
    die("ID submateri atau ID materi tidak ditemukan.");
}

$sub = new Submateri();

$result = $sub->deleteSubmateri($id);

if ($result) {
    header("Location: lihatSubmateri.php?id=" . urlencode($materiId) . "&msg=deleted");
    exit;
} else {
    echo "<h3>Gagal menghapus submateri!</h3>";
    echo "<p>ID: " . htmlspecialchars($id) . "</p>";
    echo "<p>Pastikan ID sesuai dan database terkoneksi dengan benar.</p>";
}
?>

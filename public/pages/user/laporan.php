<?php
session_set_cookie_params([
  'path' => '/',
  'httponly' => true,
  'samesite' => 'Lax'
]);
session_start();

require_once __DIR__ . '/../../config.php';
require_once ROOT_PATH . '/vendor/autoload.php';
require_once ROOT_PATH . '/config/nyambung.php';
require_once ROOT_PATH . '/backend/service/analiticCSV.php';
require_once ROOT_PATH . '/backend/service/analiticPDF.php'; // <-- bikin file ini (PDF exporter)

use App\Database\Database;
use App\Services\LaporanBelajarCsvExporter;
use App\Services\LaporanBelajarPdfExporter;

// user login wajib
$userId = (int)($_SESSION['user_id'] ?? 0);
if ($userId <= 0) {
    http_response_code(401);
    exit('Unauthorized');
}

// pilih format (default csv)
$type = strtolower($_GET['type'] ?? 'csv');
if (!in_array($type, ['csv', 'pdf'], true)) {
    http_response_code(400);
    exit('Format tidak valid. Gunakan ?type=csv atau ?type=pdf');
}

// filter periode (opsional)
$start = $_GET['start'] ?? null; // format: YYYY-MM-DD
$end   = $_GET['end'] ?? null;

if ($start && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $start)) $start = null;
if ($end && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end)) $end = null;

// cegah output nyangkut sebelum header download
if (ob_get_length()) {
    ob_end_clean();
}

// koneksi DB
$database = new Database();
$db = $database->connect(); // harus return mysqli

if (!$db instanceof mysqli) {
    http_response_code(500);
    exit('DB connection bukan mysqli. Cek Database::connect()');
}

// Jalankan exporter sesuai pilihan
if ($type === 'pdf') {
    $exporter = new LaporanBelajarPdfExporter($db);

    // kalau class PDF kamu belum support start/end, panggil yang simple dulu
    // $exporter->download($userId);

    // kalau sudah support periode:
    $exporter->download($userId, $start, $end);

} else {
    $exporter = new LaporanBelajarCsvExporter($db);

    // kalau class CSV kamu belum support start/end, panggil yang simple dulu
    // $exporter->download($userId);

    // kalau sudah support periode:
    $exporter->download($userId, $start, $end);
}

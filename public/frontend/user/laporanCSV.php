<?php
session_set_cookie_params([
  'path' => '/',
  'httponly' => true,
  'samesite' => 'Lax'
]);
session_start();
require_once __DIR__ . '/../../../config/nyambung.php';
require_once __DIR__ . '/../../../backend/analitic.php';
require_once __DIR__ . '/../../../backend/service/analiticCSV.php';

use App\Database\Database;
use App\Repositories\LaporanBelajarRepository;
use App\Services\LaporanBelajarCsvExporter;





// user login wajib
$userId = (int)($_SESSION['user_id'] ?? 0);
if ($userId <= 0) {
    http_response_code(401);
    exit('Unauthorized');
}

// filter periode (opsional)
$start = $_GET['start'] ?? null;
$end   = $_GET['end'] ?? null;

// optional: validasi format tanggal
if ($start && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $start)) $start = null;
if ($end && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $end)) $end = null;

$database = new Database();
$repo = new LaporanBelajarRepository($database);
$exporter = new LaporanBelajarCsvExporter($repo);

$exporter->download($userId, $start, $end);

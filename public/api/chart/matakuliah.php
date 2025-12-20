<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';
require_once ROOT_PATH . '/backend/service/chartService.php';

use App\AuthMiddleware;
use App\ChartService;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// auth khusus API (nggak redirect HTML)
$user = AuthMiddleware::authUserJson();
$userId = $user['id'];

$chart = new ChartService();
$data = $chart->getDurasiPerMataKuliah($userId);

echo json_encode($data);
exit;

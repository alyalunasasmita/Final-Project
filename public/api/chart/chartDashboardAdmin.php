<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config.php';                 // dari public/api/admin/chart -> public
require_once ROOT_PATH . '/backend/AuthMiddleware.php';     // cukup sekali
require_once ROOT_PATH . '/backend/service/chartAdmin.php'; // pastikan di sini ada DashboardAnalytics

use App\AuthMiddleware;
use App\Dashboard\DashboardAnalytics;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

AuthMiddleware::authAdmin();

$svc = new DashboardAnalytics();
echo json_encode($svc->getAllCharts(), JSON_UNESCAPED_UNICODE);
exit;

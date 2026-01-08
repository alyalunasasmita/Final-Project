<?php
header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../config.php';                
require_once ROOT_PATH . '/backend/AuthMiddleware.php';    
require_once ROOT_PATH . '/backend/service/chartAdmin.php'; 

use App\AuthMiddleware;
use App\Dashboard\DashboardAnalytics;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

AuthMiddleware::authAdmin();

$svc = new DashboardAnalytics();
echo json_encode($svc->getAllCharts(), JSON_UNESCAPED_UNICODE);
exit;

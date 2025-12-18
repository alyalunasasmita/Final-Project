<?php
require_once __DIR__ . "/../../backend/AuthMiddleware.php";
use App\AuthMiddleware;

AuthMiddleware::authAdmin();

require_once __DIR__ . "/../../backend/service/chartAdmin.php";

use App\Dashboard\DashboardAnalytics;

header('Content-Type: application/json; charset=utf-8');

$svc = new DashboardAnalytics();
echo json_encode($svc->getAllCharts(), JSON_UNESCAPED_UNICODE);

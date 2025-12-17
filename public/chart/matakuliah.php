<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../../backend/AuthMiddleware.php';

require_once __DIR__ . '/../../backend/service/chartService.php';

use App\AuthMiddleware;
use App\ChartService;

$user = AuthMiddleware::authUser();
$userId = $user['id'];

$chart = new ChartService();
$data = $chart->getDurasiPerMataKuliah($userId);

echo json_encode($data);

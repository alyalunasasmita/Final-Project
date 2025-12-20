<?php
header('Content-Type: application/json; charset=utf-8');

// 1️⃣ PATH CONFIG BENAR (naik 2 folder)
require_once __DIR__ . '/../../config.php';

// 2️⃣ BACKEND
require_once ROOT_PATH . '/backend/AuthMiddleware.php';
require_once ROOT_PATH . '/backend/service/chartService.php';

use App\AuthMiddleware;
use App\ChartService;

// 3️⃣ SESSION (aman untuk API)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    // 4️⃣ AUTH KHUSUS API (JANGAN REDIRECT HTML)
    $user = AuthMiddleware::authUserJson(); // ⬅️ GANTI INI
    $userId = $user['id'];

    $chart = new ChartService();
    $data = $chart->getDurasiMingguan($userId);

    echo json_encode($data);
    exit;
} catch (\Throwable $e) {
    http_response_code(401);
    echo json_encode([
        'error' => 'Unauthorized'
    ]);
    exit;
}

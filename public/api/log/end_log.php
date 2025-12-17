<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once __DIR__ . '/../../../config/nyambung.php';
require_once __DIR__ . '/../../../backend/activityBelajar.php';

use App\database\Database;

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthenticated']);
    exit;
}

$log_id = (int)($_POST['log_id'] ?? 0);
if ($log_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid log_id']);
    exit;
}

$db = new Database();
$conn = $db->connect();

$log = new LogBelajar($conn);
$ok = $log->end($user_id, $log_id);

echo json_encode(['success' => (bool)$ok]);

<?php
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/backend/eventUser.php';
require_once ROOT_PATH . '/backend/AuthMiddleware.php';


use App\Schedule\Schedule;
use App\AuthMiddleware;

$user = AuthMiddleware::authUser();
$userId = $user['id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$schedule = new Schedule($userId);
$schedule->deleteSchedule((int) $_POST['id']);

AuthMiddleware::logCRUD('delete', 'schedule', $_POST['id']);
header("Location: listJadwal.php");
exit;
?>
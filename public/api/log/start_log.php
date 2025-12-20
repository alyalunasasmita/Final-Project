<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once __DIR__ . '/../../config.php';
require_once ROOT_PATH . '/config/nyambung.php';
require_once ROOT_PATH . '/backend/activityBelajar.php';
require_once ROOT_PATH . '/backend/api/apiyoutube.php';

use App\database\Database;
use App\Api\ApiYouTube;

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthenticated']);
    exit;
}

$materi_id    = (int)($_POST['materi_id'] ?? 0);
$submateri_id = (int)($_POST['submateri_id'] ?? 0);

$youtube_id  = trim($_POST['youtube_id'] ?? '');
$youtube_url = trim($_POST['youtube_url'] ?? '');

if ($materi_id <= 0) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid materi_id']);
    exit;
}

// db conn
$db = new Database();
$conn = $db->connect();

$log = new LogBelajar($conn);

// kalau cuma punya url, extract jadi video id
if (!$youtube_id && $youtube_url) {
    try {
        $yt = new ApiYouTube();
        $info = $yt->extractYouTubeData($youtube_url);
        if ($info && ($info['type'] ?? '') === 'video') {
            $youtube_id = $info['id'];
        }
    } catch (\Throwable $e) {
        // ignore - tetap start log
    }
}

// start session (1 row per sesi)
$log_id = $log->start($user_id, $materi_id, $submateri_id, $youtube_id ?: null);

if (!$log_id) {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Start failed']);
    exit;
}

// ambil durasi video dan simpan
if ($youtube_id) {
    try {
        if (!isset($yt)) $yt = new ApiYouTube();
        $detail = $yt->getVideoDetail($youtube_id);
        $item = $detail['items'][0] ?? null;

        if ($item) {
            $iso = $item['contentDetails']['duration'] ?? 'PT0S';
            $durSec = ApiYouTube::iso8601ToSeconds($iso);

            // butuh kolom youtube_id + yt_duration_seconds di log_belajar
            $log->attachYouTubeMeta($user_id, $log_id, $youtube_id, $durSec);
        }
    } catch (\Throwable $e) {
        // ignore - metadata gagal tidak menggagalkan start
    }
}

echo json_encode([
    'success' => true,
    'log_id' => $log_id,
    'youtube_id' => $youtube_id ?: null
]);

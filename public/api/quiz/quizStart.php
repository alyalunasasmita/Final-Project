<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}


require_once __DIR__ . '/../../config.php';
require_once ROOT_PATH . '/config/nyambung.php';
require_once ROOT_PATH . "/backend/service/quizRepository.php";
require_once ROOT_PATH . "/backend/service/quizService.php";

use App\service\QuizRepository;
use App\service\QuizService;

header("Content-Type: application/json");

$userId = (int)($_SESSION['user_id'] ?? 0);
$submateriId = (int)($_GET['submateri_id'] ?? 0);

if (!$userId || !$submateriId) {
  http_response_code(400);
  echo json_encode(["error" => "invalid_request"]);
  exit;
}

$repo = new QuizRepository(); // <-- penting: masukin koneksi mysqli
$attemptId = $repo->createAttempt($userId, $submateriId);

// 1) ambil 3 soal dari submateri
$questions = $repo->getRandomSoalBatch($submateriId, 3);

// 2) fallback: kalau kurang dari 3, ambil dari pool materi
if (count($questions) < 3) {
  $materiId = $repo->getMateriIdBySubmateri($submateriId);
  if ($materiId > 0) {
    $fallback = $repo->getRandomSoalBatchByMateri($materiId, 3);

    // gabungkan + unik berdasarkan id
    $map = [];
    foreach ($questions as $q) $map[(int)$q['id']] = $q;
    foreach ($fallback as $q) $map[(int)$q['id']] = $q;

    $questions = array_values($map);
    // potong jadi max 3
    $questions = array_slice($questions, 0, 3);
  }
}

// attach options
foreach ($questions as &$q) {
  $q['options'] = $repo->getOpsi((int)$q['id']);
}

echo json_encode([
  "attempt_id" => $attemptId,
  "questions" => $questions
]);

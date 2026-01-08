<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

require_once __DIR__ . '/../../config.php';
require_once ROOT_PATH . '/config/nyambung.php';

require_once ROOT_PATH . "/backend/service/quizRepository.php";
require_once ROOT_PATH . "/backend/service/quizService.php";

use app\service\QuizRepository;
use app\service\QuizService;
header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

$attemptId = (int)($data['attempt_id'] ?? 0);
$questionId = (int)($data['question_id'] ?? 0);
$optionId = (int)($data['selected_option_id'] ?? 0);

$repo = new QuizRepository();
$service = new QuizService($repo);

echo json_encode(
    $service->submit($attemptId, $questionId, $optionId)
);

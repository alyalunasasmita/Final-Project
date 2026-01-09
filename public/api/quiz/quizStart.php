<?php
require_once __DIR__ . '/../../config.php';
require_once ROOT_PATH . '/config/nyambung.php';
require_once ROOT_PATH . "/backend/service/quizRepository.php";
require_once ROOT_PATH . "/backend/service/quizService.php";

use app\service\QuizRepository;
use app\service\QuizService;

header("Content-Type: application/json");

$userId = $_SESSION['user_id'] ?? 0;
$submateriId = (int)($_GET['submateri_id'] ?? 0);

$repo = new QuizRepository();
$service = new QuizService($repo);

echo json_encode(
    $service->start($userId, $submateriId)
);
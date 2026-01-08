<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once ROOT_PATH . "/backend/AuthMiddleware.php";
require_once ROOT_PATH . '/backend/service/quizAdminService.php';

use App\AuthMiddleware;
AuthMiddleware::authAdmin();
use App\Service\QuizAdminService;


$sub = (int)($_GET["submateri_id"] ?? 0);
$soal = (int)($_GET["soal_id"] ?? 0);

$svc = new QuizAdminService($conn);
$svc->deleteQuestion($soal, $sub);

header("Location: quizList.php?submateri_id={$sub}");
exit;

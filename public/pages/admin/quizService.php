<?php
session_start();
require_once __DIR__ . '/../../config.php';
require_once ROOT_PATH . '/config/nyambung.php';

require_once ROOT_PATH . "/backend/AuthMiddleware.php";
require_once ROOT_PATH . '/backend/service/quizAdminService.php';

use App\AuthMiddleware;
AuthMiddleware::authAdmin();
use App\Service\QuizAdminService;

$svc = new QuizAdminService();

try {
  $soalId = $svc->saveQuestion([
    "submateri_id"   => (int)($_POST["submateri_id"] ?? 0),
    "soal_id"        => (int)($_POST["soal_id"] ?? 0),
    "question"       => $_POST["question"] ?? "",
    "explanation"    => $_POST["explanation"] ?? "",
    "is_active"      => isset($_POST["is_active"]) ? 1 : 0,
    "options"        => $_POST["options"] ?? [],
    "correct_index"  => (int)($_POST["correct_index"] ?? 0),
  ]);

  $sub = (int)($_POST["submateri_id"] ?? 0);
  header("Location: quizList.php?submateri_id={$sub}&success=1");
  exit;

} catch (Throwable $e) {
  $sub = (int)($_POST["submateri_id"] ?? 0);
  header("Location: quizForm.php?submateri_id={$sub}&error=save_failed");
  exit;
}

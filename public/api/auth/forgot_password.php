<?php
header('Content-Type: application/json; charset=utf-8');
session_start();

require_once __DIR__ . '/../../config.php';
require_once ROOT_PATH . '/backend/service/resetPassword.php';

use App\Service\PasswordResetService;

$email = trim($_POST['email'] ?? '');
if ($email === '') {
  echo json_encode(['success' => false, 'message' => 'Email wajib diisi']);
  exit;
}

$service = new PasswordResetService();
$result = $service->sendOtp($email);

if (!$result['success']) {
  echo json_encode($result);
  exit;
}

$_SESSION['reset_email'] = strtolower(trim($email));

echo json_encode([
  'success' => true,
  'message' => 'OTP berhasil dikirim. Silakan cek email Anda.'
]);

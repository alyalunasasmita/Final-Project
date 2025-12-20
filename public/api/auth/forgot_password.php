<?php
header('Content-Type: application/json; charset=utf-8');
session_start();
require_once __DIR__ . '/../../../config.php';
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

// simpan email buat step verify/reset berikutnya (optional)
$_SESSION['reset_email'] = strtolower(trim($email));

$isLocal = in_array($_SERVER['HTTP_HOST'] ?? '', ['finalproject.test', 'localhost'], true);

$response = [
  'success' => true,
  'message' => 'OTP dikirim. Cek email kamu.',
];

// ✅ hanya untuk TESTING di local
if ($isLocal && isset($result['otp'])) {
  $response['otp'] = $result['otp'];
}

echo json_encode($response);

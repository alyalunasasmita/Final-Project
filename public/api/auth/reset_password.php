<?php
session_start();
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/backend/service/resetPassword.php';

header('Content-Type: application/json');


use App\Service\PasswordResetService;

$email = $_SESSION['reset_email'] ?? null;
$verified = $_SESSION['otp_verified'] ?? false;

$password = $_POST['password'] ?? '';
$confirm  = $_POST['confirm'] ?? '';

if (!$email) {
  echo json_encode(['success' => false, 'message' => 'Session email tidak ada. Ulangi forgot password.']);
  exit;
}

if (!$verified) {
  echo json_encode(['success' => false, 'message' => 'OTP belum diverifikasi.']);
  exit;
}

if ($password === '' || $confirm === '') {
  echo json_encode(['success' => false, 'message' => 'Password wajib diisi.']);
  exit;
}

if ($password !== $confirm) {
  echo json_encode(['success' => false, 'message' => 'Konfirmasi password tidak sama.']);
  exit;
}

$service = new PasswordResetService();
$result = $service->resetPasswordFinal($email, $password);

if ($result['success']) {
  // bersihin session reset
  unset($_SESSION['reset_email'], $_SESSION['otp_verified']);
  echo json_encode(['success' => true, 'message' => 'Password berhasil direset']);
} else {
  echo json_encode($result);
}

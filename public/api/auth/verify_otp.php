<?php
session_start();
require_once __DIR__ . '/../../../config.php';
require_once ROOT_PATH . '/backend/service/resetPassword.php';

use App\Service\PasswordResetService;

$email = $_SESSION['reset_email'] ?? null;
$otp   = trim($_POST['otp'] ?? '');

header('Content-Type: application/json');

if (!$email || $otp === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Data tidak lengkap'
    ]);
    exit;
}

$service = new PasswordResetService();
$result = $service->verifyOtp($email, $otp);

if ($result['success']) {
    $_SESSION['otp_verified'] = true;

    echo json_encode([
        'success' => true,
        'message' => 'OTP valid'
    ]);
} else {
    echo json_encode($result);
}
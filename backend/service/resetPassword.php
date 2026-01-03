<?php
namespace App\Service;

require_once __DIR__ . '/../../config/nyambung.php';
use App\database\Database;

class PasswordResetService {
    private $db;

    public function __construct() {
        $this->db = (new Database())->db;
    }

    private function generateOtp(): string {
        return strval(random_int(100000, 999999));
    }

    /* =========================
       STEP 1: SEND OTP
    ========================== */
    public function sendOtp(string $email): array {
    $email = strtolower(trim($email));

    // cek user
    $stmt = $this->db->prepare("SELECT id, nama FROM users WHERE email = ? LIMIT 1");
    if (!$stmt) return ["success" => false, "message" => "Prepare error: ".$this->db->error];

    $stmt->bind_param("s", $email);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if (!$user) {
        return ["success" => false, "message" => "Email tidak ditemukan"];
    }

    $otp = $this->generateOtp();
    $tokenHash = password_hash((string)$otp, PASSWORD_DEFAULT);
    $expiresAt = date('Y-m-d H:i:s', time() + 600); 

    $stmtUpd = $this->db->prepare(
        "UPDATE password_resets SET used_at = NOW() WHERE email = ? AND used_at IS NULL"
    );
    if (!$stmtUpd) return ["success" => false, "message" => "Prepare error: ".$this->db->error];
    $stmtUpd->bind_param("s", $email);
    $stmtUpd->execute();
    $stmtUpd->close();

    $stmtIns = $this->db->prepare(
        "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)"
    );
    if (!$stmtIns) return ["success" => false, "message" => "Prepare error: ".$this->db->error];
    $stmtIns->bind_param("sss", $email, $tokenHash, $expiresAt);
    $stmtIns->execute();
    $stmtIns->close();

    require_once __DIR__ . '/Mailer.php'; 
    $mailer = new \App\service\Mailer();

    $subject = "Kode OTP Reset Password - StudyYou";
    $safeName = htmlspecialchars($user['nama'] ?? 'User', ENT_QUOTES, 'UTF-8');

    $html = "
      <div style='font-family: Arial, sans-serif; line-height: 1.6'>
        <h2>Reset Password StudyYou</h2>
        <p>Halo <b>{$safeName}</b>,</p>
        <p>Kode OTP kamu adalah:</p>
        <div style='font-size: 28px; font-weight: bold; letter-spacing: 3px; margin: 12px 0'>
          {$otp}
        </div>
        <p>Kode ini berlaku selama <b>10 menit</b>.</p>
        <p>Jika kamu tidak meminta reset password, abaikan email ini.</p>
      </div>
    ";

    $plain = "Halo {$user['nama']}, OTP reset password kamu: {$otp}. Berlaku 10 menit.";

    $send = $mailer->send($email, $user['nama'], $subject, $html, $plain);

    if (!$send['success']) {
        return ["success" => false, "message" => "Gagal mengirim OTP: " . ($send['error'] ?? 'Unknown error')];
    }

    return ["success" => true];
}


    public function verifyOtp(string $email, string $otp): array {

    $stmt = $this->db->prepare("
        SELECT token, expires_at 
        FROM password_resets
        WHERE email = ? AND used_at IS NULL
        ORDER BY id DESC
        LIMIT 1
    ");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $row = $stmt->get_result()->fetch_assoc();

    if (!$row) {
        return [
            'success' => false,
            'message' => 'OTP tidak ditemukan'
        ];
    }

    if (strtotime($row['expires_at']) < time()) {
        return [
            'success' => false,
            'message' => 'OTP sudah kadaluarsa'
        ];
    }

    if (!password_verify($otp, $row['token'])) {
        return [
            'success' => false,
            'message' => 'OTP salah'
        ];
    }

    return [
        'success' => true
    ];
}


    public function resetPasswordFinal(string $email, string $newPassword): array {
    $email = strtolower(trim($email));
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);
    $stmt = $this->db->prepare(
        "UPDATE users SET password = ? WHERE email = ?"
    );
    if (!$stmt) {
        return ["success" => false, "message" => $this->db->error];
    }

    $stmt->bind_param("ss", $hash, $email);
    $stmt->execute();
    $stmt->close();
    $stmt2 = $this->db->prepare(
        "UPDATE password_resets SET used_at = NOW()
         WHERE email = ? AND used_at IS NULL"
    );
    $stmt2->bind_param("s", $email);
    $stmt2->execute();
    $stmt2->close();

    return ["success" => true];
}

}

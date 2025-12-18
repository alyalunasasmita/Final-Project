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

    $otp = $this->generateOtp(); // string/angka 6 digit
    $tokenHash = password_hash((string)$otp, PASSWORD_DEFAULT);
    $expiresAt = date('Y-m-d H:i:s', time() + 600); // 10 menit

    // ✅ invalidate OTP lama
    $stmtUpd = $this->db->prepare(
        "UPDATE password_resets SET used_at = NOW() WHERE email = ? AND used_at IS NULL"
    );
    if (!$stmtUpd) return ["success" => false, "message" => "Prepare error: ".$this->db->error];

    $stmtUpd->bind_param("s", $email);
    $stmtUpd->execute();
    $stmtUpd->close();

    // ✅ insert OTP baru
    $stmtIns = $this->db->prepare(
        "INSERT INTO password_resets (email, token, expires_at) VALUES (?, ?, ?)"
    );
    if (!$stmtIns) return ["success" => false, "message" => "Prepare error: ".$this->db->error];

    $stmtIns->bind_param("sss", $email, $tokenHash, $expiresAt);
    $stmtIns->execute();
    $stmtIns->close();

    return [
        "success" => true,
        "otp" => $otp,       // ⛔ hapus di production
        "name" => $user['nama']
    ];
}

    /* =========================
       STEP 2: VERIFY OTP
    ========================== */
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


    /* =========================
       STEP 3: RESET PASSWORD
    ========================== */
    public function resetPasswordFinal(string $email, string $newPassword): array {
    $email = strtolower(trim($email));
    $hash = password_hash($newPassword, PASSWORD_DEFAULT);

    // update password
    $stmt = $this->db->prepare(
        "UPDATE users SET password = ? WHERE email = ?"
    );
    if (!$stmt) {
        return ["success" => false, "message" => $this->db->error];
    }

    $stmt->bind_param("ss", $hash, $email);
    $stmt->execute();
    $stmt->close();

    // invalidate OTP
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

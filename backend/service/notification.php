<?php
//log data pengiriman notifikasi
namespace App;

require_once __DIR__ . '/../../config/nyambung.php';

use App\Database\Database;

class NotificationLogger {
    private $db;

    public function __construct() {
        $conn = new Database();
        $this->db = $conn->db;
    }

    public function logEmail(
        int $userId,
        string $tujuan,
        string $subject,
        string $message,
        string $status,
        ?string $error = null
    ): bool {
        $channel = 'email';

        $stmt = $this->db->prepare("
            INSERT INTO notifications (users_id, channel, tujuan, subject, message, status, created_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");

        $stmt->bind_param("isssss", $userId, $channel, $tujuan, $subject, $message, $status);

        return $stmt->execute();
    }
}

<?php
//untuk mengirim notifikasi belajar kepada user melalui email
declare(strict_types=1);

require_once __DIR__ . '/../../config/nyambung.php';
require_once __DIR__ . '/../service/mailer.php';
require_once __DIR__ . '/../service/notification.php';

use App\Database\Database;
use App\Mailer;
use App\NotificationLogger;

date_default_timezone_set('Asia/Jakarta');

header('Content-Type: application/json');

$conn = new Database();
$db = $conn->db;

$mailer = new Mailer();
$logger = new NotificationLogger();

$now = new DateTime('now');
$checked = 0;
$sent = 0;
$failed = 0;

/**
 * Ambil schedule yang:
 * - remind_before_minutes ada
 * - reminder_sent_at masih NULL (belum pernah dikirim)
 * - jadwalnya belum lewat
 */
$sql = "
SELECT 
  s.id_schedule, s.nama_schedule, s.deskripsi, s.tanggal, s.jam_mulai, s.remind_before_minutes,
  s.users_id, u.email, u.nama
FROM schedule s
JOIN users u ON u.id = s.users_id
WHERE s.remind_before_minutes IS NOT NULL
  AND s.remind_before_minutes > 0
  AND s.reminder_sent_at IS NULL
";

$res = $db->query($sql);

while ($row = $res->fetch_assoc()) {
    $checked++;

    $eventDT = DateTime::createFromFormat('Y-m-d H:i:s', $row['tanggal'] . ' ' . $row['jam_mulai']);

    if (!$eventDT) {
        continue;
    }

    $remindMinutes = (int)$row['remind_before_minutes'];
    $sendAt = (clone $eventDT)->modify("-{$remindMinutes} minutes");

    // kalau sekarang sudah melewati waktu remind, kirim
    if ($now >= $sendAt && $now < $eventDT) {

        $toEmail = $row['email'];
        $toName  = $row['nama'] ?? $row['email'];

        $subject = "Reminder: " . $row['nama_schedule'];
        $msgHtml = "
            <h3>Reminder Jadwal Belajar</h3>
            <p><b>Schedule:</b> ".htmlspecialchars($row['nama_schedule'])."</p>
            <p><b>Tanggal:</b> ".htmlspecialchars($row['tanggal'])."</p>
            <p><b>Jam Mulai:</b> ".htmlspecialchars($row['jam_mulai'])."</p>
            <p><b>Deskripsi:</b> ".nl2br(htmlspecialchars($row['deskripsi'] ?? '-'))."</p>
            <p>Ini dikirim {$remindMinutes} menit sebelum jadwal dimulai.</p>
        ";

        $result = $mailer->send($toEmail, $toName, $subject, $msgHtml);

        $status = $result['success'] ? 'sent' : 'failed';
        $error  = $result['error'] ?? null;

        // log notifications
        $logger->logEmail(
            (int)$row['users_id'],
            $toEmail,
            $subject,
            strip_tags($msgHtml),
            $status,
            $error
        );

        // kalau sukses -> tandai reminder_sent_at
        if ($result['success']) {
            $upd = $db->prepare("UPDATE schedule SET reminder_sent_at = ? WHERE id_schedule = ?");
            $ts = $now->format('Y-m-d H:i:s');
            $idSchedule = (int)$row['id_schedule'];
            $upd->bind_param("si", $ts, $idSchedule);
            $upd->execute();

            $sent++;
        } else {
            $failed++;
        }
    }
}

echo json_encode([
    "success" => true,
    "result" => [
        "checked" => $checked,
        "sent" => $sent,
        "failed" => $failed,
        "time" => $now->format('Y-m-d H:i:s')
    ]
]);

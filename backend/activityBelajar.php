<?php
namespace App;

require_once __DIR__ . '/../config/nyambung.php';
use App\database\Database;

class LogBelajar
{
    private $conn;

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->db;
    }

   /// mulai dan berhenti log belajar

    public function start($user_id, $materi_id, $submateri_id)
    {
        $sql = "SELECT id_logbelajar FROM log_belajar
                WHERE users_id = ?
                AND materi_id = ?
                AND submateri_id = ?
                AND waktu_selesai IS NULL
                ORDER BY id_logbelajar DESC LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("iii", $user_id, $materi_id, $submateri_id);
        $stmt->execute();
        $res = $stmt->get_result();

        if ($row = $res->fetch_assoc()) {
            $stmt->close();
            return (int)$row['id_logbelajar'];
        }
        $stmt->close();

        $sqlIns = "INSERT INTO log_belajar
                   (users_id, materi_id, submateri_id, waktu_mulai, waktu_selesai)
                   VALUES (?, ?, ?, NOW(), NULL)";

        $stmtIns = $this->conn->prepare($sqlIns);
        if (!$stmtIns) return false;

        $stmtIns->bind_param("iii", $user_id, $materi_id, $submateri_id);
        $stmtIns->execute();
        $insertId = $this->conn->insert_id;
        $stmtIns->close();

        return $insertId;
    }

    public function end($user_id, $id_log)
    {
        $sql = "UPDATE log_belajar
                SET waktu_selesai = NOW()
                WHERE id_logbelajar = ?
                AND users_id = ?
                AND waktu_selesai IS NULL";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("ii", $id_log, $user_id);
        $stmt->execute();
        $stmt->close();

        return true;
    }

   ///status submateri

    public function materisudahdibuka($user_id, $submateri_id): bool
    {
        $sql = "SELECT 1 FROM log_belajar
                WHERE users_id = ?
                AND submateri_id = ?
                LIMIT 1";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $submateri_id);
        $stmt->execute();

        return $stmt->get_result()->num_rows > 0;
    }

    public function submateriSelesai(int $userId, int $submateriId): bool
    {
        $sql = "SELECT COUNT(*) AS total
                FROM log_belajar
                WHERE users_id = ?
                AND submateri_id = ?
                AND waktu_selesai IS NOT NULL";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ii", $userId, $submateriId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();
        return $result['total'] > 0;
    }

    ///tanda materi selesai dipelajari
    public function materiSelesai(int $user_id, int $materi_id): bool
{
    $sql = "
        SELECT
            COUNT(sm.id_subMateri) AS total_sub,
            COALESCE(SUM(CASE WHEN lb.waktu_selesai IS NOT NULL THEN 1 ELSE 0 END), 0) AS selesai_sub
        FROM submateri sm
        LEFT JOIN log_belajar lb
            ON lb.submateri_id = sm.id_subMateri
           AND lb.users_id = ?
           AND lb.materi_id = ?
        WHERE sm.materi_id_materi = ?
          AND sm.deleted_at IS NULL
    ";

    $stmt = $this->conn->prepare($sql);
    if (!$stmt) {
        // ini akan nunjukin error SQL aslinya (penting buat debug)
        die("PREPARE ERROR: " . $this->conn->error);
    }

    $stmt->bind_param("iii", $user_id, $materi_id, $materi_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    if ((int)$row['total_sub'] === 0) return false;

    return (int)$row['total_sub'] === (int)$row['selesai_sub'];
}

public function getProgressMateriSelesaiFromLog(int $user_id, int $materi_id): array
{
    $sql = "
        SELECT
            COUNT(sm.id_subMateri) AS total_sub,
            COUNT(DISTINCT CASE 
                WHEN lb.waktu_selesai IS NOT NULL THEN sm.id_subMateri 
            END) AS selesai_sub
        FROM submateri sm
        LEFT JOIN log_belajar lb
            ON lb.submateri_id = sm.id_subMateri
           AND lb.users_id = ?
           AND lb.materi_id = ?
        WHERE sm.materi_id_materi = ?
          AND sm.deleted_at IS NULL
    ";

    $stmt = $this->conn->prepare($sql);
    $stmt->bind_param("iii", $user_id, $materi_id, $materi_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $stmt->close();

    $total   = (int)($row['total_sub'] ?? 0);
    $selesai = (int)($row['selesai_sub'] ?? 0);
    $persen  = ($total > 0) ? (int)floor(($selesai / $total) * 100) : 0;

    return ['total' => $total, 'selesai' => $selesai, 'persen' => $persen];
}


}

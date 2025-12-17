<?php
class LogBelajar {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function start($user_id, $materi_id, $submateri_id) {
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

        // Tidak ada sesi aktif -> buat sesi baru
        $sqlIns = "INSERT INTO log_belajar
                   (users_id, materi_id, submateri_id, waktu_mulai, waktu_selesai)
                   VALUES (?, ?, ?, NOW(), NULL)";
        
        $stmtIns = $this->conn->prepare($sqlIns);
        if (!$stmtIns) return false;

        $stmtIns->bind_param("iii", $user_id, $materi_id, $submateri_id);
        $ok = $stmtIns->execute();
        $insertId = $ok ? $this->conn->insert_id : false;
        $stmtIns->close();

        return $insertId;
    }

    public function end($user_id, $id_log) {
        $sql = "UPDATE log_belajar
                SET waktu_selesai = NOW()
                WHERE id_logbelajar = ?
                AND users_id = ?
                AND waktu_selesai IS NULL";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("ii", $id_log, $user_id);
        $ok = $stmt->execute();
        $stmt->close();

        return $ok;
    }

    public function getActive($user_id) {
        $sql = "SELECT * FROM log_belajar
                WHERE users_id = ? 
                AND waktu_selesai IS NULL
                ORDER BY id_logbelajar DESC LIMIT 1";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;
        
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res ? $res->fetch_assoc() : null;
        $stmt->close();
        
        return $row;
    }

    // METHOD BARU UNTUK ANALYTICS
    public function getLogByUser($user_id, $limit = 100) {
        $sql = "SELECT * FROM log_belajar
                WHERE users_id = ?
                ORDER BY waktu_mulai DESC
                LIMIT ?";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return [];
        
        $stmt->bind_param("ii", $user_id, $limit);
        $stmt->execute();
        $res = $stmt->get_result();
        
        $logs = [];
        while ($row = $res->fetch_assoc()) {
            $logs[] = $row;
        }
        
        $stmt->close();
        return $logs;
    }

    public function getTotalDurationByUser($user_id, $start_date = null, $end_date = null) {
        // Hitung total durasi belajar dalam menit
        $sql = "SELECT 
                SEC_TO_SEC(TIMEDIFF(waktu_selesai, waktu_mulai)) / 60 AS durasi_menit
                FROM log_belajar
                WHERE users_id = ?
                AND waktu_selesai IS NOT NULL";
        
        // Tambahkan filter tanggal jika ada
        if ($start_date && $end_date) {
            $sql .= " AND DATE(waktu_mulai) BETWEEN ? AND ?";
        } elseif ($start_date) {
            $sql .= " AND DATE(waktu_mulai) >= ?";
        } elseif ($end_date) {
            $sql .= " AND DATE(waktu_mulai) <= ?";
        }
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return 0;
        
        // Bind parameters berdasarkan kondisi
        if ($start_date && $end_date) {
            $stmt->bind_param("iss", $user_id, $start_date, $end_date);
        } elseif ($start_date) {
            $stmt->bind_param("is", $user_id, $start_date);
        } elseif ($end_date) {
            $stmt->bind_param("is", $user_id, $end_date);
        } else {
            $stmt->bind_param("i", $user_id);
        }
        
        $stmt->execute();
        $res = $stmt->get_result();
        
        $total_duration = 0;
        while ($row = $res->fetch_assoc()) {
            $total_duration += (float)$row['durasi_menit'];
        }
        
        $stmt->close();
        return $total_duration;
    }

    // Method untuk membersihkan sesi yang tidak selesai (zombie sessions)
    public function cleanupStaleSessions($hours = 24) {
        $sql = "UPDATE log_belajar
                SET waktu_selesai = DATE_ADD(waktu_mulai, INTERVAL 2 HOUR)
                WHERE waktu_selesai IS NULL
                AND waktu_mulai < DATE_SUB(NOW(), INTERVAL ? HOUR)";
        
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;
        
        $stmt->bind_param("i", $hours);
        $ok = $stmt->execute();
        $affected = $stmt->affected_rows;
        $stmt->close();
        
        return $affected;
    }
}
?>
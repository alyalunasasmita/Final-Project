<?php
namespace App\activity; 

require_once __DIR__.'/../config/nyambung.php'; 
use App\database\Database;

class Activity {
    private $db; 

    public function __construct(){
        $conn = new Database(); 
        $this->db = $conn->db; 
    }

    public function logUser($aktivitas) {
        $_SESSION['log_aktivitas'] = $aktivitas; 
        $_SESSION['log_mulai'] = date("Y-m-d H:i:s"); 
        return true;
    }


    public function end_log() {
        // Kalau belum ada aktivitas, tidak usah insert
        if (!isset($_SESSION['log_aktivitas'])) return false;

        $aktivitas = $_SESSION['log_aktivitas'];
        $mulai = $_SESSION['log_mulai'];
        $selesai = date("Y-m-d H:i:s");
        
        // Ambil user ID dari session (sesuaikan dengan session yang ada)
        $user_id = $_SESSION['user_id'] ?? $_SESSION['id'] ?? 0;
        $username = $_SESSION['username'] ?? 'Guest';

        // Jika users_id tidak wajib, bisa pakai 0 untuk guest
        if ($user_id == 0) {
            $query = $this->db->prepare(
                "INSERT INTO log_activity 
                (aktivitas, waktu_mulai, waktu_selesai)
                VALUES (?, ?, ?)"
            );
            $query->bind_param("sss", $aktivitas, $mulai, $selesai);
        } else {
            $query = $this->db->prepare(
                "INSERT INTO logactivity 
                (aktivitas, waktu_mulai, waktu_selesai, users_id)
                VALUES (?, ?, ?, ?)"
            );
            $query->bind_param("sssi", $aktivitas, $mulai, $selesai, $user_id);
        }
        
        $result = $query->execute();

        // Hapus session biar tidak double insert
        unset($_SESSION['log_aktivitas']);
        unset($_SESSION['log_mulai']);
        
        return $result;
    }

    public function autoLog($aktivitas, $user_id = null) {
        $waktu_mulai = date("Y-m-d H:i:s");
        $waktu_selesai = $waktu_mulai; 
        
        // Jika user_id tidak diberikan, ambil dari session
        if ($user_id === null) {
            $user_id = $_SESSION['user_id'] ?? $_SESSION['id'] ?? 0;
        }
        
        // Log aktivitas langsung (untuk aktivitas yang tidak perlu durasi)
        if ($user_id == 0) {
            $query = $this->db->prepare(
                "INSERT INTO logactivity 
                (aktivitas, waktu_mulai, waktu_selesai)
                VALUES (?, ?, ?)"
            );
            $query->bind_param("sss", $aktivitas, $waktu_mulai, $waktu_selesai);
        } else {
            $query = $this->db->prepare(
                "INSERT INTO logactivity 
                (aktivitas, waktu_mulai, waktu_selesai, users_id)
                VALUES (?, ?, ?, ?)"
            );
            $query->bind_param("sssi", $aktivitas, $waktu_mulai, $waktu_selesai, $user_id);
        }
        
        return $query->execute();
    }


    public function startActivity($aktivitas_name) {
        $_SESSION['current_activity'] = $aktivitas_name;
        $_SESSION['activity_start'] = date("Y-m-d H:i:s");
        return true;
    }

    public function endActivity() {
        if (!isset($_SESSION['current_activity'])) return false;
        
        $aktivitas = $_SESSION['current_activity'];
        $waktu_mulai = $_SESSION['activity_start'];
        $waktu_selesai = date("Y-m-d H:i:s");
        $user_id = $_SESSION['user_id'] ?? $_SESSION['id'] ?? 0;
        
        if ($user_id == 0) {
            $query = $this->db->prepare(
                "INSERT INTO logactivity 
                (aktivitas, waktu_mulai, waktu_selesai)
                VALUES (?, ?, ?)"
            );
            $query->bind_param("sss", $aktivitas, $waktu_mulai, $waktu_selesai);
        } else {
            $query = $this->db->prepare(
                "INSERT INTO logactivity 
                (aktivitas, waktu_mulai, waktu_selesai, users_id)
                VALUES (?, ?, ?, ?)"
            );
            $query->bind_param("sssi", $aktivitas, $waktu_mulai, $waktu_selesai, $user_id);
        }
        
        $result = $query->execute();
        
        // Clear session
        unset($_SESSION['current_activity']);
        unset($_SESSION['activity_start']);
        
        return $result;
    }

    public function getActivityLogs($user_id = null, $limit = 100) {
        if ($user_id) {
            $query = $this->db->prepare(
                "SELECT * FROM logactivity 
                WHERE users_id = ? 
                ORDER BY waktu_mulai DESC 
                LIMIT ?"
            );
            $query->bind_param("ii", $user_id, $limit);
        } else {
            $query = $this->db->prepare(
                "SELECT * FROM logactivity 
                ORDER BY waktu_mulai DESC 
                LIMIT ?"
            );
            $query->bind_param("i", $limit);
        }
        
        $query->execute();
        $result = $query->get_result();
        
        $logs = [];
        while ($row = $result->fetch_assoc()) {
            $logs[] = $row;
        }
        
        return $logs;
    }

    
    public function getUserActivitySummary($user_id, $days = 7) {
        $query = $this->db->prepare(
            "SELECT 
                DATE(waktu_mulai) as tanggal,
                COUNT(*) as total_aktivitas,
                GROUP_CONCAT(DISTINCT aktivitas SEPARATOR ', ') as aktivitas_list
            FROM logactivity 
            WHERE users_id = ? 
            AND waktu_mulai >= DATE_SUB(NOW(), INTERVAL ? DAY)
            GROUP BY DATE(waktu_mulai)
            ORDER BY tanggal DESC"
        );
        $query->bind_param("ii", $user_id, $days);
        $query->execute();
        $result = $query->get_result();
        
        $summary = [];
        while ($row = $result->fetch_assoc()) {
            $summary[] = $row;
        }
        
        return $summary;
    }
}
<?php
//CRUD catatan user 
namespace App\catat;

require_once __DIR__.'/../config/nyambung.php'; 
use App\Database\Database;

class Catatan {
    private $db;
    private $user_id;

    public function __construct($user_id = null) {
        if ($user_id === null && session_status() === PHP_SESSION_ACTIVE) {
            $this->user_id = $_SESSION['user_id'] ?? null;
        } else {
            $this->user_id = $user_id;
        }

        if (!$this->user_id) {
            throw new \Exception("User belum login.");
        }

        $conn = new Database();
        $this->db = $conn->db;
    }

    public function tambahCatatan($catatan, $judul = null) {
        $stmt = $this->db->prepare(
            "INSERT INTO catatan (judul, catatan, users_id, created_at, updated_at)
             VALUES (?, ?, ?, NOW(), NOW())"
        );
        $stmt->bind_param("ssi", $judul, $catatan, $this->user_id);
        return $stmt->execute();
    }

    public function lihatCatatan($limit = null) {
        $sql = "SELECT * FROM catatan WHERE users_id = ? ORDER BY created_at DESC";
        if ($limit) {
            $sql .= " LIMIT ?";
        }

        $stmt = $this->db->prepare($sql);
        
        if ($limit) {
            $stmt->bind_param("ii", $this->user_id, $limit);
        } else {
            $stmt->bind_param("i", $this->user_id);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $data = [];

        while ($row = $result->fetch_assoc()) {
            // Format tanggal
            if (isset($row['created_at'])) {
                $row['formatted_date'] = $this->formatDate($row['created_at']);
                $row['time_ago'] = $this->timeAgo($row['created_at']);
            }
            $data[] = $row;
        }

        return $data;
    }

    public function getCatatanById($id_catatan) {
        $stmt = $this->db->prepare(
            "SELECT * FROM catatan WHERE id_catatan = ? AND users_id = ?"
        );
        $stmt->bind_param("ii", $id_catatan, $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (isset($row['created_at'])) {
                $row['formatted_date'] = $this->formatDate($row['created_at']);
                $row['time_ago'] = $this->timeAgo($row['created_at']);
            }
            return $row;
        }
        
        return null;
    }

    public function updateCatatan($id_catatan, $catatan, $judul = null) {
        $stmt = $this->db->prepare(
            "UPDATE catatan 
             SET catatan = ?, judul = ?, updated_at = NOW()
             WHERE id_catatan = ? AND users_id = ?"
        );
        $stmt->bind_param("ssii", $catatan, $judul, $id_catatan, $this->user_id);
        return $stmt->execute();
    }

    public function deleteCatatan($id_catatan) {
        $stmt = $this->db->prepare(
            "DELETE FROM catatan WHERE id_catatan = ? AND users_id = ?"
        );
        $stmt->bind_param("ii", $id_catatan, $this->user_id);
        return $stmt->execute();
    }

    public function getStats() {
        $stmt = $this->db->prepare(
            "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN DATE(created_at) = CURDATE() THEN 1 ELSE 0 END) as today,
                SUM(CASE WHEN WEEK(created_at) = WEEK(NOW()) THEN 1 ELSE 0 END) as this_week,
                SUM(CASE WHEN MONTH(created_at) = MONTH(NOW()) THEN 1 ELSE 0 END) as this_month
             FROM catatan 
             WHERE users_id = ?"
        );
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    private function formatDate($date) {
        return date('d F, Y', strtotime($date));
    }

    private function timeAgo($date) {
        $time = strtotime($date);
        $time_difference = time() - $time;

        if ($time_difference < 1) { return 'just now'; }
        
        $condition = array(
            12 * 30 * 24 * 60 * 60 => 'year',
            30 * 24 * 60 * 60      => 'month',
            24 * 60 * 60           => 'day',
            60 * 60                => 'hour',
            60                     => 'minute',
            1                      => 'second'
        );

        foreach ($condition as $secs => $str) {
            $d = $time_difference / $secs;
            if ($d >= 1) {
                $t = round($d);
                return $t . ' ' . $str . ($t > 1 ? 's' : '') . ' ago';
            }
        }
    }
}
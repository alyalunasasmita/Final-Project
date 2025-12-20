<?php
namespace App;
require_once __DIR__ . '/../../config/nyambung.php';
use App\database\Database;

class DashboardService {

    private $db;

    public function __construct() {
        $database = new Database();
        $this->db = $database->db;
    }

    /**
     * Ringkasan statistik dashboard
     */
    public function getSummary($userId) {
        $sql = "
        SELECT id_schedule, nama_schedule, deskripsi, tanggal, jam_mulai, jam_selesai, durasi
        FROM schedule
        WHERE users_id = ?
            AND (
            tanggal > CURDATE()
            OR (tanggal = CURDATE() AND jam_mulai >= CURTIME())
            )
        ORDER BY tanggal ASC, jam_mulai ASC
        LIMIT 1
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Materi terakhir dipelajari
     */
    public function getLastStudiedMaterial($userId) {
        $sql = "
            SELECT materi.nama_materi
            FROM materi
            WHERE materi.id_materi = (
            SELECT materi_id
            FROM log_belajar
            WHERE users_id = ?
            ORDER BY waktu_mulai DESC
            LIMIT 1
        );

        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    /**
     * Deadline terdekat
     */
    public function deadline($userId, $limit = 3) {
        $sql = "
            SELECT id_catatan, title, description, status, deadline
            FROM tasks
            WHERE id_user = ?
            AND status <> 'selesai'
            AND deadline >= NOW()
            ORDER BY deadline ASC
            LIMIT ?;

        ";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("ii", $userId, $limit);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function durasiBelajar($userId){
        $sql = 
        "SELECT IFNULL(SUM(durasi), 0) AS durasibelajar
        FROM log_belajar
        WHERE users_id = ?
        AND tanggal >= CURDATE() - INTERVAL 6 DAY;";

        $stmt = $this->db->prepare($sql);
        $stmt->bind_param('i',$userId); 
        $stmt->execute();
         return $stmt->get_result()->fetch_assoc();

    }
}

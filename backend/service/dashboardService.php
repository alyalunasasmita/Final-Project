<?php
namespace App;

require_once __DIR__ . '/../../config/nyambung.php'; 
use App\database\Database;

class DashboardService
{
    private $db;

    public function __construct()
    {
        $conn = new Database();
        $this->db = $conn->connect();
    }

    public function getSummary($userId)
    {
        $data = [];

        // Total materi
        $q1 = $this->db->query("SELECT COUNT(*) AS total FROM materi");
        $data['total_materi'] = (int)$q1->fetch_assoc()['total'];

        // Materi dipelajari user
        $q2 = $this->db->query("
            SELECT COUNT(DISTINCT materi_id) AS dipelajari 
            FROM log_belajar 
            WHERE users_id = {$userId}
        ");
        $data['materi_dipelajari'] = (int)$q2->fetch_assoc()['dipelajari'];

        // Total catatan user
        $q3 = $this->db->query("
            SELECT COUNT(*) AS total 
            FROM catatan 
            WHERE users_id = {$userId}
        ");
        $data['total_catatan'] = (int)$q3->fetch_assoc()['total'];

        // Total durasi belajar user (menit)
        $q4 = $this->db->query("
            SELECT SUM(durasi) AS total 
            FROM log_belajar 
            WHERE users_id = {$userId}
        ");
        $totalDurasi = (int)$q4->fetch_assoc()['total'];

        // Konversi ke persen (target 10 jam = 600 menit)
        $target = 600;
        $persen = $totalDurasi > 0 ? round(($totalDurasi / $target) * 100) : 0;

        $data['total_durasi'] = $totalDurasi;
        $data['persen_jam'] = $persen;

        return $data;
    }


    public function getLastStudiedMaterial($userId)
{
    $sql = "SELECT m.nama_materi as last_materi
            FROM log_belajar l
            LEFT JOIN materi m ON m.id_materi = l.materi_id
            WHERE l.users_id = ?
            ORDER BY l.tanggal DESC
            LIMIT 1";

    $stmt = $this->db->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
    
    return $data ?: ['last_materi' => null];
}
   public function deadline($userId, $days = 3)
    {
        $sql = "
            SELECT 
                nama_schedule as nama_tugas,
                deskripsi as keterangan,
                DATE_FORMAT(tanggal, '%d/%m/%Y') as tanggal,
                tipe_event as mata_kuliah, 
                jam_mulai,
                jam_selesai
            FROM schedule
            WHERE users_id = {$userId}
            AND tipe_event = 'deadline'  
            AND tanggal BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL {$days} DAY)
            ORDER BY tanggal, jam_mulai ASC
            LIMIT 5 
        ";

        $result = $this->db->query($sql);
        $tasks = [];
        while ($row = $result->fetch_assoc()) {
            $tasks[] = $row;
        }

        return $tasks;
    }

}

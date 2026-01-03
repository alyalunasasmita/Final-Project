<?php
namespace app;

require_once __DIR__ . '/../../config/nyambung.php'; 

use app\database\Database; 

class ChartService
{
    private $db;

    public function __construct()
    {
        $conn = new Database();
        $this->db = $conn->connect();
    }

    
    public function getDurasiPerMataKuliah($userId)
    {
        $sql = "
        SELECT
            m.nama_materi,
            COALESCE(SUM(lb.durasi), 0) AS total_durasi
        FROM materi m
        LEFT JOIN log_belajar lb
            ON m.id_materi = lb.materi_id
            AND lb.users_id = {$userId}
        GROUP BY m.id_materi, m.nama_materi
        ORDER BY total_durasi DESC;
        ";

        return $this->fetchData($sql);
    }

    public function getDurasiMingguan($userId)
    {
        $sql = "
        SELECT 
            DATE(tanggal) AS hari,
            COALESCE(SUM(durasi), 0) AS total_durasi
        FROM log_belajar
        WHERE users_id = {$userId}
          AND tanggal >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
        GROUP BY DATE(tanggal)
        ORDER BY hari;
        ";

        return $this->fetchData($sql);
    }

    private function fetchData($sql)
    {
        $result = $this->db->query($sql);

        $data = [];
        while ($row = $result->fetch_assoc()) {
            if (isset($row['total_durasi'])) {
                $row['total_durasi'] = (int)$row['total_durasi'];
            }
            $data[] = $row;
        }

        return $data;
    }
}


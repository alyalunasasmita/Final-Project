<?php
//chart dashboard admin
namespace App\Dashboard;
require_once __DIR__ . '/../../config/nyambung.php';
use App\database\Database;

class DashboardAnalytics
{
    private $db;
    
    public function __construct()
    {
        $this->db = (new Database())->db;
    }

    public function aktivitasHarian(int $days = 14): array
    {
        $stmt = $this->db->prepare("
        SELECT
            DATE(waktu_mulai) AS tanggal,
            COUNT(*) AS total_sesi,
            ROUND(SUM(durasi), 2) AS total_jam
        FROM log_belajar
        WHERE waktu_selesai IS NOT NULL
            AND waktu_mulai >= DATE_SUB(CURDATE(), INTERVAL 14 DAY)
        GROUP BY DATE(waktu_mulai)
        ORDER BY tanggal ASC
        ");
        $stmt->execute();
        $res = $stmt->get_result();

        $labels = [];
        $totalSesi = [];
        $totalJam = [];
        while ($row = $res->fetch_assoc()) {
            $labels[] = $row['tanggal'];
            $totalSesi[] = (int)$row['total_sesi'];
            $totalJam[] = (float)$row['total_jam'];
        }
        $stmt->close();

        return [
            'labels' => $labels,
            'datasets' => [
                ['label' => 'Total Sesi', 'data' => $totalSesi],
                ['label' => 'Total Jam', 'data' => $totalJam],
            ]
        ];
    }

    public function sesiStatus(): array
    {
        $sql = "
            SELECT
                SUM(CASE WHEN waktu_selesai IS NULL THEN 1 ELSE 0 END) AS belum_selesai,
                SUM(CASE WHEN waktu_selesai IS NOT NULL THEN 1 ELSE 0 END) AS selesai
            FROM log_belajar
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc() ?: ['belum_selesai' => 0, 'selesai' => 0];
        $stmt->close();

        return [
            'labels' => ['Tidak Selesai', 'Selesai'],
            'datasets' => [
                [
                    'label' => 'Jumlah Sesi',
                    'data' => [(int)$row['belum_selesai'], (int)$row['selesai']]
                ]
            ]
        ];
    }


    public function getAllCharts(): array
    {
        return [
            'aktivitas_14_hari' => $this->aktivitasHarian(14),
            'sesiStatus' => $this->sesiStatus(),
        ];
    }
}

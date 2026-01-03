<?php
namespace App\Dashboard;
require_once __DIR__ . '/../../config/nyambung.php';
use App\database\Database;

class DashboardCards
{
    private $db;

    public function __construct()
    {
        $this->db = (new Database())->db;
    }

    public function cardTotalMateriAktif(): array
    {
        $sql = "SELECT COUNT(*) AS total
                FROM materi
                WHERE deleted_at IS NULL";
        $res = $this->db->query($sql);
        $row = $res ? $res->fetch_assoc() : ['total' => 0];

        return [
            'title' => 'Total Materi',
            'value' => (int)($row['total'] ?? 0),
            'meta'  => 'Active Courses',
        ];
    }

    public function cardTotalUsers(): array
    {
        $resTotal = $this->db->query("SELECT COUNT(*) AS total FROM users");
        $rowTotal = $resTotal ? $resTotal->fetch_assoc() : ['total' => 0];

        $resNew = $this->db->query("
            SELECT COUNT(*) AS new_week
            FROM users
            WHERE create_time >= DATE_SUB(NOW(), INTERVAL 7 DAY)
        ");
        $rowNew = $resNew ? $resNew->fetch_assoc() : ['new_week' => 0];

        return [
            'title' => 'Total Users',
            'value' => (int)$rowTotal['total'],
            'meta'  => '+' . (int)$rowNew['new_week'] . ' this week'
        ];
    }

    public function cardActiveSessions(): array
    {
        $sql = "SELECT COUNT(*) AS total
                FROM log_belajar
                WHERE waktu_selesai IS NULL";
        $res = $this->db->query($sql);
        $row = $res ? $res->fetch_assoc() : ['total' => 0];

        return [
            'title' => 'Active Sessions',
            'value' => (int)($row['total'] ?? 0),
            'meta'  => 'Real-time',
        ];
    }

    public function cardCompletionRate(): array
    {
        $sql = "
            SELECT
              SUM(CASE WHEN waktu_selesai IS NOT NULL THEN 1 ELSE 0 END) AS selesai,
              COUNT(*) AS total
            FROM log_belajar
        ";
        $res = $this->db->query($sql);
        $row = $res ? $res->fetch_assoc() : ['selesai' => 0, 'total' => 0];

        $selesai = (int)($row['selesai'] ?? 0);
        $total   = (int)($row['total'] ?? 0);

        $rate = ($total > 0) ? round(($selesai / $total) * 100) : 0;

        return [
            'title' => 'Completion Rate',
            'value' => $rate . '%',
            'meta'  => null,
        ];
    }
}

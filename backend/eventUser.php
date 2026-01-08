<?php
//CRUD jadwal belajar user 
namespace App\Schedule;
require_once __DIR__ . '/../config/nyambung.php';

use App\Database\Database;
use Exception;


class Schedule
{
    private $db;
    private int $userId;

    public function __construct(int $userId)
    {
        if ($userId <= 0) {
            throw new Exception("User ID tidak valid");
        }

        $this->userId = $userId;

        $database = new Database();
        $this->db = $database->connect();
    }



    private function hitungDurasi(string $jamMulai, string $jamSelesai): string
    {
        $start = strtotime($jamMulai);
        $end   = strtotime($jamSelesai);

        if ($start === false || $end === false) {
            throw new Exception("Format jam tidak valid");
        }

        $diff = $end - $start;

        // Support event lewat tengah malam
        if ($diff <= 0) {
            $diff += 86400;
        }

        return gmdate('H:i:s', $diff);
    }

    private function validasiDasar(
        string $nama,
        string $tanggal,
        string $jamMulai,
        string $jamSelesai
    ): void {
        if (trim($nama) === '') {
            throw new Exception("Nama schedule wajib diisi");
        }

        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tanggal)) {
            throw new Exception("Format tanggal tidak valid");
        }

        if (strtotime($jamMulai) === false || strtotime($jamSelesai) === false) {
            throw new Exception("Format jam tidak valid");
        }
    }

    
    public function tambahSchedule(
        string $nama,
        ?string $deskripsi,
        string $tanggal,
        string $jamMulai,
        string $jamSelesai,
        ?int $remindBeforeMinutes
    ): bool {

        $this->validasiDasar($nama, $tanggal, $jamMulai, $jamSelesai);
        $durasi = $this->hitungDurasi($jamMulai, $jamSelesai);

        $stmt = $this->db->prepare("
            INSERT INTO schedule
            (nama_schedule, deskripsi, tanggal, jam_mulai, jam_selesai, durasi, users_id, remind_before_minutes, reminder_sent_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULL)
        ");

        $stmt->bind_param(
            "ssssssii",
            $nama,
            $deskripsi,
            $tanggal,
            $jamMulai,
            $jamSelesai,
            $durasi,
            $this->userId,
            $remindBeforeMinutes
        );

        return $stmt->execute();
    }


    public function listSchedule(): array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM schedule
            WHERE users_id = ?
            ORDER BY tanggal ASC, jam_mulai ASC
        ");

        $stmt->bind_param("i", $this->userId);
        $stmt->execute();

        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function updateSchedule(
        int $idSchedule,
        string $nama,
        ?string $deskripsi,
        string $tanggal,
        string $jamMulai,
        string $jamSelesai,
        ?int $remindBeforeMinutes
    ): bool {

        $this->validasiDasar($nama, $tanggal, $jamMulai, $jamSelesai);
        $durasi = $this->hitungDurasi($jamMulai, $jamSelesai);

        $stmt = $this->db->prepare("
            UPDATE schedule
            SET nama_schedule = ?,
                deskripsi = ?,
                tanggal = ?,
                jam_mulai = ?,
                jam_selesai = ?,
                durasi = ?,
                remind_before_minutes = ?,
                reminder_sent_at = NULL
            WHERE id_schedule = ? AND users_id = ?
        ");

        $stmt->bind_param(
            "ssssssiii",
            $nama,
            $deskripsi,
            $tanggal,
            $jamMulai,
            $jamSelesai,
            $durasi,
            $remindBeforeMinutes,
            $idSchedule,
            $this->userId
        );

        return $stmt->execute();
    }

    public function deleteSchedule(int $idSchedule): bool
    {
        $stmt = $this->db->prepare("
            DELETE FROM schedule
            WHERE id_schedule = ? AND users_id = ?
        ");

        $stmt->bind_param("ii", $idSchedule, $this->userId);

        return $stmt->execute();
    }

    public function getScheduleById(int $idSchedule): ?array
    {
        $stmt = $this->db->prepare("
            SELECT *
            FROM schedule
            WHERE id_schedule = ? AND users_id = ?
            LIMIT 1
        ");

        $stmt->bind_param("ii", $idSchedule, $this->userId);
        $stmt->execute();

        $result = $stmt->get_result()->fetch_assoc();

        return $result ?: null;
    }

}

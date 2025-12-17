<?php
namespace App\Services;

use App\Repositories\LaporanBelajarRepository;

final class LaporanBelajarCsvExporter
{
    public function __construct(private LaporanBelajarRepository $repo) {}

    public function download(int $userId, ?string $start, ?string $end): void
    {
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=laporan_belajar.csv');

        $fh = fopen('php://output', 'w');


        $insight = $this->repo->getInsight($userId, $start, $end);

        fputcsv($fh, ['RINGKASAN_NARASI', 'Keterangan']);
        $totalJam  = $insight['Total Jam'] ?? 0;
        $totalSesi = $insight['Total Sesi'] ?? 0;
        $hariTop   = $insight['Hari Terproduktif'] ?? '-';
        $jamFav    = $insight['Jam Favorit Mulai'] ?? '-';

        fputcsv($fh, ['RINGKASAN_NARASI', "Kamu telah belajar {$totalJam} jam dari {$totalSesi} sesi belajar."]);
        if ($hariTop !== '-') fputcsv($fh, ['RINGKASAN_NARASI', "Hari paling produktif kamu adalah {$hariTop}."]);
        if ($jamFav !== '-') fputcsv($fh, ['RINGKASAN_NARASI', "Kamu paling sering memulai belajar pada pukul {$jamFav}."]);
        fputcsv($fh, []);

        // ===== INSIGHT (angka) =====
        fputcsv($fh, ['TIPE_DATA','KETERANGAN','NILAI']);
        foreach ($insight as $k => $v) {
            fputcsv($fh, ['INSIGHT', $k, (string)$v]);
        }
        fputcsv($fh, []);

        // ===== SUMMARY HARIAN =====
        fputcsv($fh, ['TIPE_DATA','Tanggal','Jumlah Sesi','Total Jam','Rata-rata Jam']);
        foreach ($this->repo->getSummaryHarian($userId, $start, $end) as $r) {
            fputcsv($fh, ['SUMMARY_HARIAN', $r['tanggal'], $r['sesi'], $r['total_jam'], $r['rata_jam']]);
        }
        fputcsv($fh, []);

        // ===== STATISTIK MATERI =====
        fputcsv($fh, ['TIPE_DATA','Materi','Jumlah Sesi','Total Jam','Rata-rata Jam']);
        foreach ($this->repo->getStatistikMateri($userId, $start, $end) as $r) {
            fputcsv($fh, ['STATISTIK_MATERI', $r['nama_materi'], $r['sesi'], $r['total_jam'], $r['rata_jam']]);
        }
        fputcsv($fh, []);

        // ===== DETAIL SESI =====
        fputcsv($fh, ['TIPE_DATA','Tanggal','Mulai','Selesai','Durasi (Jam)','Materi','Submateri']);
        foreach ($this->repo->getDetailSesi($userId, $start, $end) as $r) {
            fputcsv($fh, [
                'DETAIL_SESI',
                $r['tanggal'],
                date('H:i', strtotime($r['waktu_mulai'])),
                date('H:i', strtotime($r['waktu_selesai'])),
                $r['durasi'],
                $r['nama_materi'],
                $r['nama_subMateri'],
            ]);
        }

        fclose($fh);
        exit;
    }
}

<?php
namespace App\Service;

class QuizService {
    private QuizRepository $repo;

    public function __construct(QuizRepository $repo) {
        $this->repo = $repo;
    }

    public function start(int $userId, int $submateriId): array {
        $attemptId = $this->repo->createAttempt($userId, $submateriId);

        // 1) cari soal aktif untuk submateri ini
        $soal = $this->repo->getRandomSoal($submateriId);
        $source = "submateri";

        // 2) fallback: kalau kosong, ambil dari pool materi (semua submateri dalam materi tsb)
        if (!$soal) {
            $materiId = $this->repo->getMateriIdBySubmateri($submateriId);
            if ($materiId > 0) {
            $soal = $this->repo->getRandomSoalByMateri($materiId);
            $source = "materi";
            }
        }

        if (!$soal) {
            return ["attempt_id" => $attemptId, "question" => null, "source" => "none"];
        }

        $soal["options"] = $this->repo->getOpsi((int)$soal["id"]);

        return [
            "attempt_id" => $attemptId,
            "question" => $soal,
            "source" => $source
        ];
    }


    public function submit(int $attemptId, int $soalId, int $opsiId): array {
        $benar = $this->repo->cekJawaban($opsiId, $soalId) ? 1 : 0;
        $nilai = $benar ? 100 : 0;

        $this->repo->finishAttempt($attemptId, $nilai);

        return [
            "is_correct" => (bool)$benar,
            "score_percent" => $nilai,
            "explanation" => $this->repo->getPembahasan($soalId)
        ];
    }
}

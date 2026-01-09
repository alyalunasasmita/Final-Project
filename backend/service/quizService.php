<?php
namespace app\service;

class QuizService {
    private QuizRepository $repo;

    public function __construct(QuizRepository $repo) {
        $this->repo = $repo;
    }

    public function start(int $userId, int $submateriId): array {
        // ambil soal dari submateri
        $soalList = $this->repo->getRandomSoal($submateriId, 5);
        $source = "submateri";

        // kalau kurang dari 5, ambil tambahan dari materi
        if (count($soalList) < 5) {
            $materiId = $this->repo->getMateriIdBySubmateri($submateriId);
            if ($materiId > 0) {
                $extra = $this->repo->getRandomSoalByMateri($materiId, 5 - count($soalList));
                $soalList = array_merge($soalList, $extra);
                $source = "mixed";
            }
        }

        $attemptId = $this->repo->createAttempt($userId, $submateriId, count($soalList));

        foreach ($soalList as &$soal) {
            $soal["options"] = $this->repo->getOpsi((int)$soal["id"]);
        }

        return [
            "attempt_id" => $attemptId,
            "questions"  => $soalList,
            "source"     => $source
        ];
    }

    public function submit(int $attemptId, int $soalId, int $opsiId): array {
        $isCorrect = $this->repo->cekJawaban($opsiId, $soalId);

        $attempt = $this->repo->getAttempt($attemptId);
        $currentCorrectCount = (int)($attempt['correct_count'] ?? 0);
        $totalQuestions      = (int)($attempt['total_questions'] ?? 0);

        $correctCount = $currentCorrectCount + ($isCorrect ? 1 : 0);

        $this->repo->finishAttempt($attemptId, $correctCount, $totalQuestions);

        return [
            "is_correct"    => $isCorrect,
            "score_percent" => ($totalQuestions > 0) ? ($correctCount / $totalQuestions) * 100 : 0,
            "explanation"   => $this->repo->getPembahasan($soalId)
        ];
    }
}
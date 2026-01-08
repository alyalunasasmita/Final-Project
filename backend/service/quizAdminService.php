<?php
namespace App\Service;

require_once __DIR__ . '/../../config/nyambung.php'; 
use app\database\Database; 

class QuizAdminService {
  private \mysqli $db;

  public function __construct() {
    $this->db = (new Database())->db; // ✅ koneksi otomatis
  }

  public function listQuestionsBySubmateri(int $submateriId): array {
    $stmt = $this->db->prepare("
      SELECT id, question, explanation, is_active, created_at
      FROM soal_kuis
      WHERE submateri_id=?
      ORDER BY id DESC
    ");
    $stmt->bind_param("i", $submateriId);
    $stmt->execute();
    return $stmt->get_result()->fetch_all(MYSQLI_ASSOC) ?: [];
  }


  public function getQuestionWithOptions(int $soalId): ?array {
    $q = $this->db->prepare("SELECT * FROM soal_kuis WHERE id=?");
    $q->bind_param("i", $soalId);
    $q->execute();
    $row = $q->get_result()->fetch_assoc();
    if (!$row) return null;

    $o = $this->db->prepare("SELECT id, option_text, is_correct FROM opsi_jawaban WHERE soal_id=? ORDER BY id ASC");
    $o->bind_param("i", $soalId);
    $o->execute();
    $row["options"] = $o->get_result()->fetch_all(MYSQLI_ASSOC) ?: [];
    return $row;
  }

  /** Save (insert/update) soal + 4 opsi */
  public function saveQuestion(array $payload): int {
    $submateriId = (int)($payload["submateri_id"] ?? 0);
    $soalId      = (int)($payload["soal_id"] ?? 0);
    $question    = trim((string)($payload["question"] ?? ""));
    $explanation = trim((string)($payload["explanation"] ?? ""));
    $isActive    = !empty($payload["is_active"]) ? 1 : 0;

    $options     = $payload["options"] ?? [];
    $correctIdx  = (int)($payload["correct_index"] ?? 0); // 1..4

    if ($submateriId <= 0 || $question === "" || $correctIdx < 1 || $correctIdx > 4) {
      throw new Exception("Invalid payload");
    }
    if (!is_array($options) || count($options) < 4) {
      throw new Exception("Options must be 4 items");
    }

    $this->db->begin_transaction();
    try {
      if ($soalId > 0) {
        $u = $this->db->prepare("
          UPDATE soal_kuis
          SET question=?, explanation=?, is_active=?
          WHERE id=? AND submateri_id=?
        ");
        $u->bind_param("ssiii", $question, $explanation, $isActive, $soalId, $submateriId);
        $u->execute();

        $del = $this->db->prepare("DELETE FROM opsi_jawaban WHERE soal_id=?");
        $del->bind_param("i", $soalId);
        $del->execute();
      } else {
        $i = $this->db->prepare("
          INSERT INTO soal_kuis (submateri_id, question, explanation, is_active)
          VALUES (?, ?, ?, ?)
        ");
        $i->bind_param("issi", $submateriId, $question, $explanation, $isActive);
        $i->execute();
        $soalId = (int)$i->insert_id;
      }

      $ins = $this->db->prepare("
        INSERT INTO opsi_jawaban (soal_id, option_text, is_correct)
        VALUES (?, ?, ?)
      ");
      for ($k = 0; $k < 4; $k++) {
        $text = trim((string)($options[$k] ?? ""));
        if ($text === "") $text = "-";
        $isCorrect = (($k + 1) === $correctIdx) ? 1 : 0;
        $ins->bind_param("isi", $soalId, $text, $isCorrect);
        $ins->execute();
      }

      $this->db->commit();
      return $soalId;

    } catch (Throwable $e) {
      $this->db->rollback();
      throw $e;
    }
  }

  public function toggleActive(int $soalId, int $submateriId): void {
    $stmt = $this->db->prepare("
      UPDATE soal_kuis
      SET is_active = CASE WHEN is_active=1 THEN 0 ELSE 1 END
      WHERE id=? AND submateri_id=?
    ");
    $stmt->bind_param("ii", $soalId, $submateriId);
    $stmt->execute();
  }

  public function deleteQuestion(int $soalId, int $submateriId): void {
    $this->db->begin_transaction();
    try {
      $delOpt = $this->db->prepare("DELETE FROM opsi_jawaban WHERE soal_id=?");
      $delOpt->bind_param("i", $soalId);
      $delOpt->execute();

      $delQ = $this->db->prepare("DELETE FROM soal_kuis WHERE id=? AND submateri_id=?");
      $delQ->bind_param("ii", $soalId, $submateriId);
      $delQ->execute();

      $this->db->commit();
    } catch (Throwable $e) {
      $this->db->rollback();
      throw $e;
    }
  }
}

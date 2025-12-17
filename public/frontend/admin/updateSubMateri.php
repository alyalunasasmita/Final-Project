<?php
require_once __DIR__ . "/../../../backend/AuthMiddleware.php";
use App\AuthMiddleware;
AuthMiddleware::authAdmin();

require_once __DIR__ . "/../../../backend/subMateri.php";
use App\submateri\Submateri;

$submateri = new Submateri();

// Ambil ID submateri & ID materi
$id_subMateri = $_GET['id'] ?? $_POST['id'] ?? null;
$materiId = $_GET['materi'] ?? $_POST['materi'] ?? null;

if (!$id_subMateri || !$materiId) {
    die("ID submateri atau ID materi tidak ditemukan.");
}

// Ambil data lama
$data = $submateri->lihatSubmateriById($id_subMateri);

if (!$data) {
    die("Submateri tidak ditemukan!");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = $_POST['nama'] ?? '';
    $isi = $_POST['isi'] ?? '';

    $result = $submateri->updateSubmateri($id_subMateri, $nama, $isi, $materiId);

    if ($result) {
        header("Location: lihatSubmateri.php?id=" . $materiId . "&msg=updated");
        exit;
    } else {
        echo "Gagal update submateri!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Submateri</title>
</head>
<body>

<h2>Edit Submateri</h2>

<form action="" method="POST">
    <input type="hidden" name="id" value="<?= $id_subMateri ?>">
    <input type="hidden" name="materi" value="<?= $materiId ?>">

    <label>Nama Submateri</label><br>
    <input type="text" name="nama" value="<?= htmlspecialchars($data['nama_subMateri']) ?>" required>
    <br><br>

    <label>Isi Materi</label><br>
    <textarea name="isi" required><?= htmlspecialchars($data['isi_materi']) ?></textarea>
    <br><br>

    <button type="submit">Update</button>
</form>

<br>
<a href="lihatSubmateri.php?id=<?= $materiId ?>">Kembali</a>

</body>
</html>

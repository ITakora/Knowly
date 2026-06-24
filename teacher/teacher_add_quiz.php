<?php

require_once '../config/db.php';
include '../includes/header_teacher.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$id_material = isset($_GET['id_material']) ? intval($_GET['id_material']) : 0;


if (isset($_GET['aksi'])) {
    $aksi = $_GET['aksi'];
    if ($aksi == 'delete_soal' && isset($_GET['id_soal'])) {
        $id_soal = intval($_GET['id_soal']);

        if ($id_soal > 0) {
            $sql_del = "DELETE FROM quiz WHERE id_quiz = ?";
            $stmt_del = $conn->prepare($sql_del);
            $stmt_del->bind_param("i", $id_soal);
            $stmt_del->execute();

            $sql_check = "SELECT COUNT(*) as total FROM quiz WHERE id_material = ?";
            $stmt_check = $conn->prepare($sql_check);
            $stmt_check->bind_param("i", $id_material);
            $stmt_check->execute();
            $res_check = $stmt_check->get_result()->fetch_assoc();

            header("Location: teacher_add_quiz.php?id_material=" . $id_material);
            exit();
        }
    }


    if ($aksi == 'delete_semua') {
        $sql_del_all = "DELETE FROM quiz WHERE id_material = ?";
        $stmt_del_all = $conn->prepare($sql_del_all);
        $stmt_del_all->bind_param("i", $id_material);
        $stmt_del_all->execute();

        header("Location: teacher_add_quiz.php?id_material=" . $id_material);
        exit();
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_soal'])) {
    $pertanyaan = $_POST['question'];
    $opt_a = $_POST['option_a'];
    $opt_b = $_POST['option_b'];
    $opt_c = $_POST['option_c'];
    $opt_d = $_POST['option_d'];
    $jawaban_benar = $_POST['correct_answer'];

    $sql_insert = "INSERT INTO quiz (id_material, question, option_a, option_b, option_c, option_d, correct_answer) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt_insert = $conn->prepare($sql_insert);
    $stmt_insert->bind_param("issssss", $id_material, $pertanyaan, $opt_a, $opt_b, $opt_c, $opt_d, $jawaban_benar);
    $stmt_insert->execute();

    header("Location: teacher_add_quiz.php?id_material=" . $id_material);
    exit();
}


$sql_materi = "SELECT tittle_material, id_class FROM materials WHERE id_material = ?";
$stmt_materi = $conn->prepare($sql_materi);
$stmt_materi->bind_param("i", $id_material);
$stmt_materi->execute();
$materi = $stmt_materi->get_result()->fetch_assoc();


$sql_soal = "SELECT * FROM quiz WHERE id_material = ?";
$stmt_soal = $conn->prepare($sql_soal);
$stmt_soal->bind_param("i", $id_material);
$stmt_soal->execute();
$result_soal = $stmt_soal->get_result();
?>

<style>
    .container { max-width: 800px; margin: 40px auto; padding: 0 20px; }
    .card { background: #fff; border: 1px solid #E8EAED; border-radius: 12px; padding: 25px; margin-bottom: 25px; }
    .form-group { margin-bottom: 15px; }
    .form-group label { display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px; }
    .form-group input, .form-group textarea, .form-group select { width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 6px; }
    .btn-submit { background: #1A73E8; color: white; padding: 10px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
    .soal-item { border-bottom: 1px solid #eee; padding-bottom: 15px; margin-bottom: 15px; }

    .btn-delete-sm { background: #ea4335; color: white; text-decoration: none; padding: 4px 10px; border-radius: 4px; font-size: 12px; font-weight: 500; transition: 0.2s; }
    .btn-delete-sm:hover { background: #c5221f; }
    .btn-delete-all { background: #ea4335; color: white; text-decoration: none; padding: 8px 15px; border-radius: 6px; font-size: 13px; font-weight: 600; transition: 0.2s; }
    .btn-delete-all:hover { background: #c5221f; }
</style>

<div class="container">
    <a href="teacher_list_materi.php?modul=<?= $materi['id_class'] ?>" style="color: #1A73E8; text-decoration: none;">← Kembali ke List Materi</a>

    <h2 style="margin-top: 20px; color: #111;">Kelola Quiz: <?= htmlspecialchars($materi['tittle_material']) ?></h2>

    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px;">
            <h3 style="margin: 0;">
                Daftar Pertanyaan (<?= $result_soal->num_rows ?> Soal)
            </h3>

            <?php if ($result_soal->num_rows > 0): ?>
                <a href="?id_material=<?= $id_material ?>&aksi=delete_semua" class="btn-delete-all" onclick="return confirm('Peringatan Keras: Ini akan menghapus SELURUH SOAL pada quiz ini. Lanjutkan?')">🗑️ Hapus Semua Soal</a>
            <?php endif; ?>
        </div>

        <?php if ($result_soal->num_rows > 0): $no = 1; ?>
            <?php while($soal = $result_soal->fetch_assoc()): ?>
                <div class="soal-item">
                    <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                        <p style="margin: 0;"><strong><?= $no++; ?>. <?= htmlspecialchars($soal['question']) ?></strong></p>
                        <a href="?id_material=<?= $id_material ?>&aksi=delete_soal&id_soal=<?= $soal['id_quiz'] ?>" class="btn-delete-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus soal ini?');">Hapus</a>
                    </div>

                    <ul style="list-style: none; padding-left: 0; font-size: 14px; color: #555; margin-top: 10px;">
                        <li>A. <?= htmlspecialchars($soal['option_a']) ?></li>
                        <li>B. <?= htmlspecialchars($soal['option_b']) ?></li>
                        <li>C. <?= htmlspecialchars($soal['option_c']) ?></li>
                        <li>D. <?= htmlspecialchars($soal['option_d']) ?></li>
                    </ul>
                    <p style="font-size: 13px; color: #16A34A; margin-top: 5px;">
                        <strong>Jawaban Benar: <?= strtoupper($soal['correct_answer']) ?></strong>
                    </p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p style="color: #888;">Belum ada soal untuk materi ini.</p>
        <?php endif; ?>
    </div>


    <div class="card" style="background: #F8FAFC; border-color: #E2E8F0;">
        <h3 style="margin-bottom: 20px;">+ Tambah Soal Baru</h3>
        <form method="POST" action="">
            <div class="form-group">
                <label>Pertanyaan</label>
                <textarea name="question" rows="3" required placeholder="Tuliskan pertanyaan disini..."></textarea>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px;">
                <div class="form-group">
                    <label>Pilihan A</label>
                    <input type="text" name="option_a" required>
                </div>
                <div class="form-group">
                    <label>Pilihan B</label>
                    <input type="text" name="option_b" required>
                </div>
                <div class="form-group">
                    <label>Pilihan C</label>
                    <input type="text" name="option_c" required>
                </div>
                <div class="form-group">
                    <label>Pilihan D</label>
                    <input type="text" name="option_d" required>
                </div>
            </div>

            <div class="form-group" style="margin-top: 10px;">
                <label>Kunci Jawaban Benar</label>
                <select name="correct_answer" required>
                    <option value="a">A</option>
                    <option value="b">B</option>
                    <option value="c">C</option>
                    <option value="d">D</option>
                </select>
            </div>

            <button type="submit" name="tambah_soal" class="btn-submit">Simpan Pertanyaan</button>
            <div style="text-align: right; margin-bottom: 40px;">
                <a href="teacher_list_materi.php?modul=<?= $materi['id_class'] ?>"
                   style="background: #34a853; color: white; text-decoration: none; padding: 10px 25px; border-radius: 6px; font-weight: 600; font-size: 14px;">
                    ✓ Selesai & Kembali ke List Materi
                </a>
            </div>
        </form>
    </div>
</div>
<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit();
}

$id_material = isset($_GET['id_materi']) ? intval($_GET['id_materi']) : 0;
$id_student = $_SESSION['user_id'];

$sql_cek = "SELECT score FROM quiz_results WHERE id_material = ? AND id_user = ?";
$stmt_cek = $conn->prepare($sql_cek);
$stmt_cek->bind_param("ii", $id_material, $id_student);
$stmt_cek->execute();
$hasil_cek = $stmt_cek->get_result();

$sudah_mengerjakan = false;
$nilai_akhir = 0;

if ($hasil_cek->num_rows > 0) {
    $sudah_mengerjakan = true;
    $row_nilai = $hasil_cek->fetch_assoc();
    $nilai_akhir = $row_nilai['score'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$sudah_mengerjakan) {
    $jawaban_student = $_POST['answers'] ;
    $sql_kunci = "SELECT id_quiz, correct_answer FROM quiz WHERE id_material = ?";
    $stmt_kunci = $conn->prepare($sql_kunci);
    $stmt_kunci->bind_param("i", $id_material);
    $stmt_kunci->execute();
    $result_kunci = $stmt_kunci->get_result();

    $total_soal = $result_kunci->num_rows;
    $jawaban_benar = 0;

    if ($total_soal > 0) {
        while ($row = $result_kunci->fetch_assoc()) {
            $id_soal = $row['id_quiz'];
            $kunci = $row['correct_answer'];

            if (isset($jawaban_student[$id_soal]) && $jawaban_student[$id_soal] == $kunci) {
                $jawaban_benar++;
            }
        }

        $score = round(($jawaban_benar / $total_soal) * 100);


        $sql_simpan = "INSERT INTO quiz_results (id_material, id_user, score, total_correct, total_questions)  VALUES (?, ?, ?, ?, ?)";
        $stmt_simpan = $conn->prepare($sql_simpan);
        $stmt_simpan->bind_param("iiiii", $id_material, $id_student, $score, $jawaban_benar, $total_soal);
        $stmt_simpan->execute();

        header("Location: student_quiz.php?id_materi=" . $id_material);
        exit();
    }
}

$sql_soal = "SELECT * FROM quiz WHERE id_material = ?";
$stmt_soal = $conn->prepare($sql_soal);
$stmt_soal->bind_param("i", $id_material);
$stmt_soal->execute();
$result_soal = $stmt_soal->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kerjakan Quiz</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #FAFAFB; font-family: 'Poppins', sans-serif; color: #111; padding: 40px 20px; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 12px; border: 1px solid #E5E7EB; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
        .soal-box { margin-bottom: 25px; padding-bottom: 20px; border-bottom: 1px solid #F3F4F6; }
        .soal-text { font-size: 16px; font-weight: 600; margin-bottom: 15px; }
        .opsi-label { display: block; padding: 10px 15px; border: 1px solid #E5E7EB; border-radius: 8px; margin-bottom: 10px; cursor: pointer; transition: 0.2s; }
        .opsi-label:hover { background: #F8FAFC; border-color: #CBD5E1; }
        input[type="radio"] { margin-right: 10px; }
        .btn-submit { background: #22C55E; color: white; padding: 12px 25px; font-size: 16px; font-weight: 600; border: none; border-radius: 8px; cursor: pointer; width: 100%; transition: 0.2s; }
        .btn-submit:hover { background: #16A34A; }
        .score-box { text-align: center; background: #F0FDF4; border: 2px solid #22C55E; padding: 30px; border-radius: 12px; margin-top: 20px; }
    </style>
</head>
<body>

<div class="container">


    <h2 style="margin-top: 15px; margin-bottom: 30px; border-bottom: 2px solid #F3F4F6; padding-bottom: 10px;">Quiz</h2>

    <?php if ($sudah_mengerjakan): ?>
        <div class="score-box">
            <h3 style="color: #166534; margin-bottom: 10px;">Anda telah menyelesaikan quiz ini!</h3>
            <p style="font-size: 14px; color: #15803D;">Nilai Akhir Anda:</p>
            <h1 style="font-size: 60px; color: #22C55E; margin: 10px 0;"><?= $nilai_akhir ?></h1>
            <p style="font-size: 12px; color: #666;">Nilai sudah tersimpan otomatis di database pengajar.</p>
        </div>

    <?php elseif ($result_soal->num_rows > 0): ?>
        <form method="POST" action="">
            <?php $no = 1; while ($soal = $result_soal->fetch_assoc()): ?>
                <div class="soal-box">

                    <p class="soal-text"><?= $no ?>. <?= htmlspecialchars($soal['question']) ?></p>

                    <label class="opsi-label">
                        <input type="radio" name="answers[<?= $soal['id_quiz'] ?>]" value="a" required>
                        A. <?= htmlspecialchars($soal['option_a']) ?>
                    </label>
                    <label class="opsi-label">
                        <input type="radio" name="answers[<?= $soal['id_quiz'] ?>]" value="b" required>
                        B. <?= htmlspecialchars($soal['option_b']) ?>
                    </label>
                    <label class="opsi-label">
                        <input type="radio" name="answers[<?= $soal['id_quiz'] ?>]" value="c" required>
                        C. <?= htmlspecialchars($soal['option_c']) ?>
                    </label>
                    <label class="opsi-label">
                        <input type="radio" name="answers[<?= $soal['id_quiz'] ?>]" value="d" required>
                        D. <?= htmlspecialchars($soal['option_d']) ?>
                    </label>
                </div>
                <?php $no++; endwhile; ?>

            <button type="submit" class="btn-submit" onclick="return confirm('Apakah Anda yakin jawaban sudah benar? Anda tidak bisa mengulang quiz ini.')">Kirim Jawaban & Lihat Nilai</button>
        </form>

    <?php else: ?>
        <p style="text-align: center; color: #9CA3AF; padding: 40px 0;">Pengajar belum menambahkan pertanyaan untuk quiz ini.</p>
    <?php endif; ?>
</div>

</body>
</html>
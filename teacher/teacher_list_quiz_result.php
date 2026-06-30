<?php

require_once '../config/db.php';
include '../includes/header_teacher_student.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$id_material = isset($_GET['id_material']) ? intval($_GET['id_material']) : 0;


if (isset($_GET['aksi']) && $_GET['aksi'] == 'reset') {
    $id_result = isset($_GET['id_result']) ? intval($_GET['id_result']) : 0;

    if ($id_result > 0) {
        $sql_reset = "DELETE FROM quiz_results WHERE id_result = ?";
        $stmt_reset = $conn->prepare($sql_reset);
        $stmt_reset->bind_param("i", $id_result);
        $stmt_reset->execute();

        header("Location: teacher_quiz_results.php?id_material=" . $id_material);
        exit();
    }
}


$sql_materi = "SELECT tittle_material, id_class FROM materials WHERE id_material = ?";
$stmt_materi = $conn->prepare($sql_materi);
$stmt_materi->bind_param("i", $id_material);
$stmt_materi->execute();
$materi = $stmt_materi->get_result()->fetch_assoc();


$sql_nilai = "SELECT qr.*, u.username 
              FROM quiz_results qr 
              JOIN users u ON qr.id_user = u.id 
              WHERE qr.id_material = ?
              ORDER BY qr.score DESC, qr.completed_at DESC";
$stmt_nilai = $conn->prepare($sql_nilai);
$stmt_nilai->bind_param("i", $id_material);
$stmt_nilai->execute();
$result_nilai = $stmt_nilai->get_result();
?>

<style>
    .container { max-width: 900px; margin: 40px auto; padding: 0 20px; }
    .card { background: #fff; border: 1px solid #E8EAED; border-radius: 12px; padding: 25px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { padding: 12px 15px; text-align: left; border-bottom: 1px solid #E5E7EB; font-size: 14px; }
    th { background-color: #F8FAFC; color: #475569; font-weight: 600; }
    .btn-reset { background: #FEE2E2; color: #991B1B; padding: 6px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: 600; transition: 0.2s; border: 1px solid #F87171; }
    .btn-reset:hover { background: #FECACA; }
    .badge-score { font-weight: 700; font-size: 15px; padding: 4px 10px; border-radius: 6px; }
    .score-good { background: #DCFCE7; color: #166534; }
    .score-bad { background: #FEF2F2; color: #991B1B; }
</style>

<div class="container">
    <a href="teacher_list_materi.php?modul=<?= $materi['id_class'] ?>" style="color: #1A73E8; text-decoration: none; font-weight: 500;">← Kembali ke List Materi</a>

    <h2 style="margin-top: 20px; color: #111;">Rekap Nilai Quiz: <?= htmlspecialchars($materi['tittle_material']) ?></h2>

    <div class="card" style="margin-top: 20px;">
        <h3 style="margin-bottom: 10px; color: #334155;">Daftar Mahasiswa yang Sudah Mengerjakan</h3>

        <?php if ($result_nilai->num_rows > 0): ?>
            <table>
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Mahasiswa</th>
                    <th>Waktu Selesai</th>
                    <th>Benar / Total</th>
                    <th>Nilai Akhir</th>
                    <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                <?php $no = 1; while($row = $result_nilai->fetch_assoc()): ?>
                    <?php

                    $is_good = $row['score'] >= 70;
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td style="font-weight: 600; color: #111;"><?= htmlspecialchars($row['username']) ?></td>
                        <td style="color: #64748B; font-size: 13px;"><?= date('d M Y, H:i', strtotime($row['completed_at'])) ?></td>
                        <td><?= $row['total_correct'] ?> / <?= $row['total_questions'] ?></td>
                        <td>
                                <span class="badge-score <?= $is_good ? 'score-good' : 'score-bad' ?>">
                                    <?= number_format($row['score'], 0) ?>
                                </span>
                        </td>
                        <td>
                            <a href="?id_material=<?= $id_material ?>&aksi=reset&id_result=<?= $row['id_result'] ?>"
                               class="btn-reset"
                               onclick="return confirm('Yakin ingin mereset nilai <?= htmlspecialchars($row['username']) ?>? Mereka harus mengerjakan ulang quiz ini.');">
                                🔄 Reset
                            </a>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div style="text-align: center; padding: 40px 0; color: #94A3B8; background: #F8FAFC; border-radius: 8px; margin-top: 15px;">
                <p>Belum ada mahasiswa yang mengerjakan quiz ini.</p>
            </div>
        <?php endif; ?>
    </div>
</div>
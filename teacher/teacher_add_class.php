<?php
require_once '../config/db.php';
include '../includes/header_teacher_student.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$error_msg = "";


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['tambah_kelas'])) {
    $name = trim($_POST['name']);
    $tipe = trim($_POST['tipe']);

    if (!empty($name) && !empty($tipe)) {
        $sql_insert = "INSERT INTO class (name, tipe) VALUES (?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("ss", $name, $tipe);

        if ($stmt->execute()) {
            header("Location: teacher_list_class.php");
            exit();
        } else {
            $error_msg = "Gagal menyimpan data ke database.";
        }
    } else {
        $error_msg = "Harap isi semua kolom!";
    }
}
?>

<style>
    .container { max-width: 600px; margin: 40px auto; padding: 0 20px; }
    .card { background: #fff; border: 1px solid #E8EAED; border-radius: 12px; padding: 25px; margin-top: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px; color: #333; }
    .form-group input, .form-group select { width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; outline: none; transition: 0.2s; }
    .form-group input:focus, .form-group select:focus { border-color: #1A73E8; }
    .btn-submit { background: #1A73E8; color: white; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; width: 100%; font-size: 15px; }
    .btn-submit:hover { background: #1557B0; }
    .alert-error { background: #FEE2E2; color: #991B1B; padding: 10px; border-radius: 6px; margin-bottom: 15px; font-size: 14px; border: 1px solid #F87171; }
</style>

<div class="container">
    <a href="teacher_list_class.php" style="color: #1A73E8; text-decoration: none; font-weight: 500;">← Kembali ke Dashboard Kelas</a>

    <h2 style="margin-top: 20px; color: #111;">+ Tambah Kelas Baru</h2>

    <div class="card">
        <?php if (!empty($error_msg)): ?>
            <div class="alert-error"><?= $error_msg; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label>Nama Kelas (Mata Kuliah / Bidang)</label>
                <input type="text" name="name" required placeholder="Contoh: Mobile, Web, Data Science...">
            </div>

            <div class="form-group">
                <label>Tipe Kategori</label>
                <select name="tipe" required>
                    <option value="" disabled selected>-- Pilih Kategori --</option>
                    <option value="Tech">Tech (Teknologi)</option>
                    <option value="Design">Design (Desain)</option>
                    <option value="Business">Business (Bisnis)</option>
                </select>
            </div>

            <button type="submit" name="tambah_kelas" class="btn-submit">Simpan Kelas</button>
        </form>
    </div>
</div>
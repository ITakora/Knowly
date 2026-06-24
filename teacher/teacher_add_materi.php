<?php
require_once '../config/db.php';
include '../includes/header_teacher.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}


$id_class = isset($_GET['modul']) ? intval($_GET['modul']) : 0;
?>

<style>

    .form-container { max-width: 600px; margin: 5px auto 50px auto; background: #ffffff; border: 1px solid #E8EAED; border-radius: 12px; padding: 30px; position: relative; }
    .btn-close-x { position: absolute; top: 25px; right: 30px; text-decoration: none; color: #5f6368; font-size: 22px; font-weight: bold; }
    .form-container h2 { font-size: 22px; margin-bottom: 25px; color: #1A73E8; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px; }
    .form-group input[type="text"], .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 1px solid #E8EAED; border-radius: 8px; font-size: 14px; outline: none; }
    .form-group input[type="file"] { font-size: 14px; }
    .footer-buttons { display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; }
    .btn-submit { background-color: #1A73E8; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: 500; font-size: 14px; }
    .btn-batal { background-color: #E8EAED; color: #333; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-weight: 500; font-size: 14px; text-align: center; }
</style>

<div style="max-width:600px; margin: 40px auto 10px auto; padding:0 20px;">
    <a href="teacher_list_materi.php?modul=<?php echo $id_class; ?>" style="text-decoration:none; color:#1A73E8; font-size:14px;">← Kembali ke Daftar Materi</a>
</div>

<div class="form-container">
    <a href="teacher_list_materi.php?modul=<?php echo $id_class; ?>" class="btn-close-x">✕</a>

    <h2>Tambah Materi Baru</h2>

    <form action="proses_materi.php?aksi=insert" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_class" value="<?php echo $id_class; ?>">

        <div class="form-group">
            <label>Nama Materi / Judul</label>
            <input type="text" name="judul" required placeholder="Contoh: Pengenalan Dasar Framework">
        </div>

        <div class="form-group" style="background: #F8FAFC; padding: 15px; border-radius: 8px; border: 1px solid #E2E8F0;">
            <label style="color: #334155;">Upload Berkas Dokumen (Kosongkan jika hanya link YouTube)</label>
            <input type="file" name="file_materi">
        </div>

        <div class="form-group" style="background: #FEF2F2; padding: 15px; border-radius: 8px; border: 1px solid #FECACA; margin-top: 20px;">
            <label style="color: #991B1B;">Tautan YouTube (Kosongkan jika hanya upload berkas)</label>
            <input type="text" name="youtube_url" placeholder="Contoh: https://youtube.com/watch?v=...">
        </div>

        <div class="footer-buttons">
            <a href="teacher_list_materi.php?modul=<?php echo $id_class; ?>" class="btn-batal">Batal</a>
            <button type="submit" class="btn-submit">Upload & Publikasikan</button>
        </div>
    </form>
</div>

</body>
</html>

<?php


require_once '../config/db.php';
include '../includes/header_teacher.php';

//if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
//    echo "<script>alert('Akses Ditolak! Halaman ini hanya khusus untuk Admin/Dosen.'); window.location='index.php';</script>";
//    exit;
//}


$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$sql = "SELECT * FROM materials WHERE id_material = ?";
$stmt = $conn->prepare($sql);

if ($stmt) {
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $data = $result->fetch_assoc();
}

if(!$data) {
    echo "<script>alert('Data materi tidak ditemukan!'); window.location='admin_materi.php';</script>";
    exit;
}
?>

<style>
    /* ini Style form edit */
    .form-container { max-width: 600px; margin: 5px auto 50px auto; background: #ffffff; border: 1px solid #E8EAED; border-radius: 12px; padding: 30px; position: relative; }
    .btn-close-x { position: absolute; top: 25px; right: 30px; text-decoration: none; color: #5f6368; font-size: 22px; font-weight: bold; }
    .form-container h2 { font-size: 22px; margin-bottom: 25px; color: #1A73E8; }
    .form-group { margin-bottom: 20px; }
    .form-group label { display: block; font-size: 14px; font-weight: 500; margin-bottom: 8px; }
    .form-group input[type="text"], .form-group textarea, .form-group select { width: 100%; padding: 12px; border: 1px solid #E8EAED; border-radius: 8px; font-size: 14px; outline: none; }
    .footer-buttons { display: flex; justify-content: flex-end; gap: 12px; margin-top: 30px; }
    .btn-submit { background-color: #1A73E8; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: 500; font-size: 14px; }
    .btn-batal { background-color: #E8EAED; color: #333; text-decoration: none; padding: 12px 25px; border-radius: 8px; font-weight: 500; font-size: 14px; text-align: center; }
</style>

<div style="max-width:600px; margin: 40px auto 10px auto; padding:0 20px;">
    <div style="max-width:600px; margin: 40px auto 10px auto; padding:0 20px;">
        <a href="teacher_list_materi.php?modul=<?php echo $data['id_class']; ?>"
           style="text-decoration:none; color:#1A73E8; font-size:14px;">
            ← Kembali ke Daftar Materi
        </a>
    </div>
</div>

<div class="form-container">
    <a href="admin_materi.php?modul=<?php echo $data['id_class']; ?>" class="btn-close-x">✕</a>

    <h2>Update Data Materi</h2>

    <?php
    // Grab the exact columns from your new materials table
    $file_name   = $data['file_name'] ;
    $youtube_url = $data['youtube_url'] ;
    ?>

    <form action="proses_materi.php?aksi=update" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_materi" value="<?php echo $data['id_material']; ?>">
        <input type="hidden" name="kode_modul" value="<?php echo $data['id_class']; ?>">

        <div class="form-group">
            <label>Nama Materi / Judul</label>
            <input type="text" name="judul" value="<?php echo htmlspecialchars($data['tittle_material']); ?>" required>
        </div>

        <div class="form-group" style="background: #F8FAFC; padding: 15px; border-radius: 8px; border: 1px solid #E2E8F0;">
            <label style="color: #334155;">Ganti Berkas Dokumen (Kosongkan jika tidak diubah)</label>
            <input type="file" name="file_materi">

            <?php if(!empty($file_name)): ?>
                <small style="color: #64748B; display:block; margin-top:8px;">
                    📄 File saat ini: <strong><?php echo htmlspecialchars($file_name); ?></strong>
                </small>
            <?php endif; ?>
        </div>

        <div class="form-group" style="background: #FEF2F2; padding: 15px; border-radius: 8px; border: 1px solid #FECACA; margin-top: 20px;">
            <label style="color: #991B1B;">Tautan YouTube (Kosongkan jika tidak ada video)</label>
            <input type="text" name="youtube_url" value="<?php echo htmlspecialchars($youtube_url); ?>" placeholder="Contoh: https://youtube.com/watch?v=...">

            <?php if(!empty($youtube_url)): ?>
                <small style="display:block; margin-top:8px;">
                    ▶️ Link saat ini: <a href="<?php echo htmlspecialchars($youtube_url); ?>" target="_blank" style="color: #DC2626; text-decoration: underline;">Test Video</a>
                </small>
            <?php endif; ?>
        </div>

        <div class="footer-buttons">
            <a href="admin_materi.php?modul=<?php echo $data['id_class']; ?>" class="btn-batal">Batal</a>
            <button type="submit" class="btn-submit">Simpan Perubahan</button>
        </div>
    </form>
</div>
</body>
</html>

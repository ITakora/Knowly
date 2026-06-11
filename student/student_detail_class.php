<?php
session_start();
require_once '../config/db.php';

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$nama_pengguna = $_SESSION['username'];
$inisial_avatar = strtoupper(substr($nama_pengguna, 0, 1));

$id = isset($_GET['id']) ? $_GET['id'] : '';


$query = mysqli_query($koneksi, "SELECT * FROM kelas WHERE id_kelas = 1");
$kelas = mysqli_fetch_array($query);

if (!$kelas) {
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $kelas['nama_kelas']; ?> - Ruang Kelas</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #FAFAFB; color: #111111; padding-bottom: 60px; }
        .navbar { background-color: #ffffff; padding: 15px 8%; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E5E7EB; }
        .logo { font-family: 'Poppins', sans-serif; font-size: 2.25rem; line-height: 2.5rem; font-weight: 700; text-decoration: none; }
        .logo .techblue { color: #1D63ED; }
        .logo .softgray { color: #9CA3AF; }
        .nav-right { display: flex; align-items: center; gap: 20px; }
        .btn-back { text-decoration: none; color: #555555; font-size: 14px; font-weight: 500; border-right: 1px solid #E5E7EB; padding-right: 20px; }
        .user-profile-container { display: flex; align-items: center; gap: 12px; cursor: pointer; }
        .profile-text { display: flex; flex-direction: column; text-align: right; }
        .greeting { font-size: 11px; color: #9CA3AF; font-weight: 500; }
        .user-name { font-size: 13px; font-weight: 600; color: #111111; }
        .profile-avatar { width: 36px; height: 36px; border-radius: 50%; background-color: rgba(29, 99, 237, 0.1); color: #1D63ED; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px; border: 1.5px solid #1D63ED; }
        .container { max-width: 1200px; margin: 40px auto; padding: 0 20px; }
        .detail-layout { display: flex; gap: 30px; }
        .sidebar { flex: 1; max-width: 320px; }
        .main-content { flex: 2.5; }
        .panel-card { background-color: #ffffff; border: 1px solid #E5E7EB; border-radius: 12px; padding: 24px; margin-bottom: 25px; }
        .sidebar-title { font-size: 11px; color: #9CA3AF; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .course-title { font-size: 20px; font-weight: 700; color: #1D63ED; margin: 5px 0 15px 0; line-height: 1.3; }
        .meta-list { border-top: 1px solid #F3F4F6; padding-top: 15px; }
        .meta-row { font-size: 13px; margin-bottom: 12px; color: #555555; }
        .meta-row strong { color: #9CA3AF; display: block; margin-bottom: 2px; font-weight: 500; }
        .meta-val { font-weight: 500; color: #333333; }
        .panel-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .panel-heading { font-size: 16px; font-weight: 700; }
        .materi-item { display: flex; justify-content: space-between; align-items: center; background: #F8FAFC; border: 1px solid #E2E8F0; padding: 14px 20px; border-radius: 8px; margin-bottom: 10px; }
        .btn-download { background: #ffffff; border: 1px solid #E2E8F0; text-decoration: none; color: #1D63ED; font-size: 12px; font-weight: 600; padding: 6px 14px; border-radius: 6px; transition: 0.2s; }
        .btn-download:hover { background: #EFF6FF; }
        .empty-materi { text-align: center; color: #94A3B8; font-size: 13px; padding: 30px 0; }
        .bottom-actions { display: flex; gap: 15px; margin-top: 25px; padding-top: 20px; border-top: 1px dashed #E5E7EB; }
        .btn-exam { flex: 1; padding: 12px; border-radius: 8px; text-align: center; text-decoration: none; font-weight: 600; font-size: 14px; color: #ffffff; transition: 0.2s; }
        .btn-uts { background-color: #1D63ED; }
        .btn-uts:hover { background-color: #154CBD; }
        .btn-uas { background-color: #1D63ED; }
        .btn-uas:hover { background-color: #154CBD; }
        @media (max-width: 768px) { .detail-layout { flex-direction: column; } .sidebar { max-width: 100%; } .bottom-actions { flex-direction: column; } }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="index.php" class="logo"><span class="techblue">Know</span> <span class="softgray">ly</span></a>
    <div class="nav-right">
        <a href="index.php" class="btn-back">← Kembali</a>
        <div class="user-profile-container">
            <div class="profile-text">
                <span class="greeting">Ruang Belajar</span>
                <span class="user-name"><?= htmlspecialchars($nama_pengguna) ?></span>
            </div>
            <div class="profile-avatar"><?= $inisial_avatar ?></div>
        </div>
    </div>
</nav>
<div class="container">
    <div class="detail-layout">
        <div class="sidebar">
            <div class="panel-card">
                <span class="sidebar-title">Modul Ruang Kelas</span>
                <h2 class="course-title"><?= $kelas['nama_kelas']; ?></h2>
                <div class="meta-list">
                    <div class="meta-row"><strong>Kode Modul</strong> <span class="meta-val"><?= $kelas['kode_kelas']; ?></span></div>
                </div>
            </div>
        </div>
        <div class="main-content">
            <div class="panel-card" style="border-top: 4px solid #1D63ED;">
                <div class="panel-header">
                    <h3 class="panel-heading">Daftar Berkas & Materi Kelas</h3>
                </div>
                <div class="materi-container">
                    <?php
                    $materi_query = mysqli_query($koneksi, "SELECT * FROM materi WHERE id_kelas = '$id' ORDER BY id_materi DESC");
                    if ($materi_query && mysqli_num_rows($materi_query) > 0) {
                        while ($materi = mysqli_fetch_array($materi_query)) {
                            ?>
                            <div class="materi-item">
                                <div>
                                    <h5 style="font-size: 13.5px; font-weight: 600; color: #111;"><?= $materi['judul_materi']; ?></h5>
                                    <span style="font-size: 11px; color: #9CA3AF;">📁 Dokumen Pembelajaran</span>
                                </div>
                                <div>
                                    <a href="uploads/<?= $materi['nama_file']; ?>" class="btn-download" download>Unduh</a>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo "<div class='empty-materi'>Belum ada dokumen materi untuk kelas ini.</div>";
                    }
                    ?>
                </div>
                <div class="bottom-actions">
                    <a href="detail_kelas.php?id=<?= $id; ?>&ujian=uts" class="btn-exam btn-uts">Ujian Tengah Semester (UTS)</a>
                    <a href="detail_kelas.php?id=<?= $id; ?>&ujian=uas" class="btn-exam btn-uas">Ujian Akhir Semester (UAS)</a>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

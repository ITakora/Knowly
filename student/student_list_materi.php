<?php
session_start();
require_once '../config/db.php';
require_once '../helper/embed_url.php';;

if (!isset($_SESSION['username'])) {
    header("Location: ../index.php");
    exit;
}

$nama_pengguna = $_SESSION['username'];
$inisial_avatar = strtoupper(substr($nama_pengguna, 0, 1));

$id = isset($_GET['id']) ? $_GET['id'] : '';


$sql_class = "SELECT * FROM class WHERE id = ?";
$stmt_class = $conn->prepare($sql_class);

if ($stmt_class) {
    $stmt_class->bind_param("i", $id);
    $stmt_class->execute();
    $result_class = $stmt_class->get_result();

    if ($result_class->num_rows > 0) {
        $class = $result_class->fetch_assoc();
    } else {
        header("Location: ../student/student_dashboard.php");
        exit;
    }
} else {
    die("Error loading class data.");
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materi</title>
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
        .detail-layout { display: flex; justify-content: center; width: 100%; }
        .sidebar { flex: 1; max-width: 320px; }
        .main-content { width: 100%; max-width: 900px; margin: 0 auto; }
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
        .btn-download {background: #ffffff; border: 1px solid #E2E8F0; text-decoration: none; color: #1D63ED; font-size: 12px; font-weight: 600; padding: 6px 14px; border-radius: 6px; transition: 0.2s;}
        .btn-download:hover {background: #EFF6FF; border-color: #BFDBFE;}
        .btn-yt { border: 1px solid #E2E8F0; color: #1D63ED; text-decoration: none; font-size: 12px; font-weight: 600; padding: 6px 14px; border-radius: 6px; transition: 0.2s; margin-right: 5px;}
        .btn-yt:hover {background: #FEE2E2;}
        .empty-materi { text-align: center; color: #94A3B8; font-size: 13px; padding: 30px 0; }
        .bottom-actions { display: flex; gap: 15px; margin-top: 25px; padding-top: 20px; border-top: 1px dashed #E5E7EB; }
        .btn-exam { flex: 1; padding: 12px; border-radius: 8px; text-align: center; text-decoration: none; font-weight: 600; font-size: 14px; color: #ffffff; transition: 0.2s; }
        .btn-uts { background-color: #1D63ED; }
        .btn-uts:hover { background-color: #154CBD; }
        .btn-uas { background-color: #1D63ED; }
        .btn-uas:hover { background-color: #154CBD; }
        .modal-overlay {display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.85);z-index: 1000; justify-content: center; align-items: center;backdrop-filter: blur(4px);}
        .modal-content {width: 90%; max-width: 800px;background: #000; border-radius: 12px;position: relative; box-shadow: 0 25px 50px rgba(0,0,0,0.25);}
        .close-modal {position: absolute;top: -20px; right: 0px; background: #DC2626; color: white; width: 35px; height: 35px;border-radius: 50%; display: flex; justify-content: center; align-items: center;font-size: 20px; font-weight: bold; cursor: pointer;border: 2px solid #fff; transition: 0.2s;z-index: 10;}
        .close-modal:hover { background: #B91C1C; transform: scale(1.1); }

        .video-container {position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 10px;}
        .video-container iframe {position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;}
        @media (max-width: 768px) { .detail-layout { flex-direction: column; } .sidebar { max-width: 100%; } .bottom-actions { flex-direction: column; } }
    </style>
</head>
<body>
<nav class="navbar">
    <a href="" class="logo"><span class="techblue">Know</span> <span class="softgray">ly</span></a>
    <div class="nav-right">
        <a href="student_list_class.php" class="btn-back">← Kembali</a>
        <div class="user-profile-container">
            <div class="profile-text">
                <span class="greeting">Halo, Selamat Datang</span>
                <span class="user-name"><?= htmlspecialchars($nama_pengguna) ?></span>
            </div>
            <div class="profile-avatar"><?= $inisial_avatar ?></div>
        </div>
    </div>
</nav>

        <div class="main-content">
            <div class="panel-card" style="border-top: 4px solid #1D63ED;">
                <div class="panel-header">
                    <h3 class="panel-heading">Daftar Berkas & Materi Kelas</h3>
                </div>
                <div class="materi-container">
                    <?php

                    $sql_material = "SELECT * FROM materials WHERE id_class = ? ORDER BY id_material ASC";
                    $stmt_material = $conn->prepare($sql_material);

                    if ($stmt_material) {
                        $stmt_material->bind_param("i", $id);
                        $stmt_material->execute();
                        $result_materi = $stmt_material->get_result();

                        if ($result_materi->num_rows > 0) {
                            while ($materi = $result_materi->fetch_assoc()) {
                                ?>
                                <div class="materi-item">
                                    <div>
                                        <h5 style="font-size: 13.5px; font-weight: 600; color: #111;">
                                            <p>
                                                <?= htmlspecialchars($materi['tittle_material']); ?>
                                            </p>
                                        </h5>

                                    </div>
                                    <div class="materi-actions">

                                        <?php if (!empty($materi['youtube_url'])): ?>
                                            <?php $embed_url = getEmbedUrl($materi['youtube_url']); ?>

                                            <button onclick="openVideo('<?= $embed_url; ?>')" class="btn-yt" style="cursor: pointer;">
                                                Lihat Video
                                            </button>
                                        <?php endif; ?>

                                        <?php if (!empty($materi['file_name'])): ?>
                                            <a href="../uploads/<?= htmlspecialchars($materi['file_name']); ?>" target="_blank" class="btn-download">
                                                Lihat File
                                            </a>
                                        <?php endif; ?>
                                        </div>



                                </div>

                                <?php


                            }
                        } else {
                            echo "<div class='empty-materi'>Belum ada dokumen materi untuk kelas ini.</div>";
                        }
                    } else {
                        echo "<div class='empty-materi text-red-500'>Gagal memuat materi.</div>";
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
<div id="videoModal" class="modal-overlay">
    <div class="modal-content">
        <div class="close-modal" onclick="closeVideo()">&times;</div>

        <div class="video-container">
            <iframe id="videoPlayer" src="" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div>
    </div>
</div>
<script>
    function openVideo(url) {
        document.getElementById('videoModal').style.display = 'flex';
        document.getElementById('videoPlayer').src = url;
    }

    function closeVideo() {
        document.getElementById('videoModal').style.display = 'none';
        document.getElementById('videoPlayer').src = '';
    }
</script>
</body>
</html>

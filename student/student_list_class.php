<?php

require_once '../config/db.php';
include '../includes/header_teacher.php';;

if (!isset($_SESSION['username']) && !isset($_SESSION['role']) && $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$sql = "SELECT * FROM class";
$stmt = $conn -> prepare($sql);
$stmt -> execute();
$result = $stmt -> get_result();




$nama_pengguna = $_SESSION['username'];
$inisial_avatar = strtoupper(substr($nama_pengguna, 0, 1));

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Know ly - Dashboard E-Learning</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Poppins', sans-serif; }
        body { background-color: #FAFAFB; color: #111111; padding-bottom: 60px; }

        .navbar { background-color: #ffffff; padding: 20px 8%; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #E5E7EB; }

        .logo { font-family: 'Poppins', sans-serif; font-size: 2.25rem; line-height: 2.5rem; font-weight: 700; text-decoration: none; }
        .logo .techblue { color: #1D63ED; }
        .logo .softgray { color: #9CA3AF; }

        .user-profile-container { display: flex; align-items: center; gap: 12px; cursor: pointer; }
        .profile-text { display: flex; flex-direction: column; text-align: right; }
        .greeting { font-size: 12px; color: #9CA3AF; font-weight: 500; }
        .user-name { font-size: 14px; font-weight: 600; color: #111111; }

        .profile-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            background-color: rgba(29, 99, 237, 0.1); color: #1D63ED;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 16px; border: 1.5px solid #1D63ED;
        }

        .welcome-section { background-color: #ffffff; border-bottom: 1px solid #E5E7EB; padding: 50px 8%; margin-bottom: 40px; }
        .welcome-section h1 { font-size: 28px; font-weight: 700; color: #111111; }
        .welcome-section p { color: #6B7280; font-size: 14px; margin-top: 6px; opacity: 0.9; }

        .container { max-width: 1200px; margin: 0 auto; padding: 0 20px; }
        .section-title { font-size: 20px; font-weight: 700; color: #111111; margin-bottom: 25px; }

        .class-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 25px; }
        .class-card { background: #ffffff; border: 1px solid #E5E7EB; border-radius: 12px; text-decoration: none; color: #111111; transition: all 0.2s ease; display: flex; flex-direction: column; justify-content: space-between; overflow: hidden; }
        .class-card:hover { transform: translateY(-4px); box-shadow: 0 12px 20px rgba(29, 99, 237, 0.06); border-color: #1D63ED; }
        .class-card-body { padding: 24px; }

        .class-badge { background-color: rgba(29, 99, 237, 0.1); color: #1D63ED; font-size: 11px; font-weight: 600; padding: 4px 10px; border-radius: 6px; display: inline-block; margin-bottom: 12px; }
        .class-card h3 { font-size: 18px; font-weight: 700; margin-bottom: 15px; line-height: 1.4; min-height: 50px; }

        .meta-group { border-top: 1px solid #F3F4F6; padding-top: 15px; }
        .class-meta { font-size: 13px; color: #555555; margin-bottom: 6px; display: flex; }
        .meta-label { width: 100px; color: #9CA3AF; font-weight: 500; }
        .meta-value { flex: 1; font-weight: 500; color: #333333; }

        .class-card-footer { background: #F9FAFB; padding: 14px 24px; font-size: 13px; font-weight: 600; color: #1D63ED; border-top: 1px solid #E5E7EB; text-align: right; transition: background 0.2s; }
        .class-card:hover .class-card-footer { background: rgba(29, 99, 237, 0.02); }
    </style>
</head>
<body>



<div class="welcome-section">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 20px;">
        <h1>Selamat Datang di Ruang Belajar Know ly</h1>
        <p>Akses materi untuk semua kalangan yang ingin mempelajari program, unduh berkas dokumen Pembelajaran, dan kelola aktivitas dengan mudah.</p>
    </div>
</div>

<div class="container">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
        <h2 class="section-title" style="margin-bottom: 0;">Daftar Kelas Pembelajaran Anda</h2>
        <div style="position: relative;">
            <i class="ti ti-search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); color: #9CA3AF; font-size: 14px;"></i>
            <input type="text" id="searchKelas" placeholder="Cari kelas..." oninput="filterKelas(this.value)"
                   style="padding: 7px 12px 7px 32px; font-size: 13px; border: 1px solid #E5E7EB; border-radius: 8px; outline: none; font-family: 'Poppins', sans-serif; width: 200px; color: #111;">
        </div>
    </div>



    <div class="class-grid">
        <?php

        if ($result->num_rows > 0) {

            while($row = $result->fetch_assoc()) {
                ?>
                <a href="student_list_materi.php?id=<?= $row['id']; ?>" class="class-card">
                    <div class="class-card-body">
                        <span class="class-badge"><?= htmlspecialchars($row['tipe']); ?></span>

                        <h3><?= htmlspecialchars($row['name']); ?></h3>

                        <div class="meta-group">
                            <div class="class-meta">
                                <span class="meta-label">Kode Modul</span>
                                <span class="meta-value">MOD-00<?= $row['id']; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="class-card-footer">
                        Masuk Ruang Kelas →
                    </div>
                </a>
                <?php
            }
        } else {
            echo "<p style='color:#6B7280; font-size:14px; grid-column: 1/-1;'>Belum ada data kelas di database.</p>";
        }
        ?>
    </div>
</div>

</body>

<script>
    function filterKelas(query) {
        const cards = document.querySelectorAll('.class-card');
        const q = query.toLowerCase();
        cards.forEach(card => {
            const name = card.querySelector('h3').textContent.toLowerCase();
            card.style.display = name.includes(q) ? '' : 'none';
        });
    }
</script>
</html>
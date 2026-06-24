<?php

require_once '../config/db.php';
include '../includes/header_teacher.php';;


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}


$role_sekarang = $_SESSION['role'];
$id_class = isset($_GET['modul']) ? intval($_GET['modul']) : 0;


$sql = "SELECT * FROM materials WHERE id_class = ?";
$params = [$id_class];
$types = "i";

// 3. If there is a search, add it
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = '%' . $_GET['search'] . '%';
    $sql .= " AND tittle_material LIKE ?";
    $params[] = $search;
    $types .= "s";
}

$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();


?>

<div class="main-container" style="max-width: 1100px; margin: 30px auto; padding: 0 20px;">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <a href="teacher_list_class.php" style="text-decoration: none; color: #1A73E8; font-weight: 500;">← Kembali ke Dashboard</a>

        <form action="" method="GET">
            <input type="hidden" name="modul" value="<?php echo $id_class; ?>">
            <input type="text" name="search" placeholder="Cari nama materi..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>" style="padding: 8px 12px; border: 1px solid #ccc; border-radius: 6px; outline: none;">
            <button type="submit" style="background: #1A73E8; color: white; border: none; padding: 8px 15px; border-radius: 6px; cursor: pointer; font-weight: 500;">Cari</button>
        </form>
    </div>



    <?php if ($role_sekarang == 'admin'): ?>
        <div style="background: #e8f0fe; border: 1px solid #1A73E8; padding: 15px; border-radius: 8px; margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">
            <span style="color: #1A73E8; font-weight: 500;">Mode Dosen Aktif: Anda dapat mengelola materi pada kelas ini.</span>
            <a href="teacher_add_materi.php?modul=<?php echo $id_class; ?>" style="background: #1A73E8; color: white; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; font-size: 14px;">+ Tambah Materi Baru</a>
        </div>
    <?php endif; ?>

    <div class="list-container" style="display: flex; flex-direction: column; gap: 20px;">
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                ?>
                <div class="shape-materi" style="border: 1px solid #E8EAED; padding: 25px; border-radius: 12px; background: white; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                    <h3 style="color: #333; margin-bottom: 5px;"><?php echo htmlspecialchars($row['tittle_material']); ?></h3>


                    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 10px;">

                        <div style="display: flex; gap: 10px;">
                            <?php

                            if (!empty($row['youtube_url'])) {
                                $path = $row['youtube_url'];
                            } else {
                                $path = "uploads/" . $row['file_name'];
                            }
                            ?>

<!--                            <a href="--><?php //echo htmlspecialchars($path); ?><!--" target="_blank" style="background: #1A73E8; color: white; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 500;">-->
<!--                                Lihat Materi-->
<!--                            </a>-->


                            <a href="#" onclick="alert('Fitur quiz sedang dalam pengembangan')" style="border: 1px solid #ccc; color: #ccc; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-size: 14px; cursor: not-allowed;">
                                Quiz Belum Tersedia
                            </a>
                        </div>
                        <?php if ($role_sekarang == 'admin'): ?>
                            <div style="display: flex; gap: 10px;">
                                <a href="teacher_update_materi.php?id=<?php echo $row['id_material']; ?>" style="background: #fbbc05; color: black; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 500;">Update</a>
                                <a href="process_materi.php?aksi=delete&id=<?php echo $row['id_material']; ?>&modul=<?php echo $id_class; ?>" style="background: #ea4335; color: white; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-size: 14px; font-weight: 500;" onclick="return confirm('Apakah Anda yakin ingin menghapus materi ini?')">Delete</a>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
                <?php
            }


            mysqli_data_seek($result, 0);
            $materi_jangkar = mysqli_fetch_assoc($result);
            $id_jangkar = $materi_jangkar['id_material'];
        } else {
            echo "<p style='text-align:center; color:#5f6368; padding: 20px;'>Belum ada data materi di kelas ini.</p>";
            $id_jangkar = 0;
        }
        ?>
    </div> <?php if($id_jangkar > 0): ?>
        <div style="background: <?php echo $role_sekarang == 'admin' ? '#fff3cd' : '#f8f9fa'; ?>; border: 2px dashed <?php echo $role_sekarang == 'admin' ? '#fbbc05' : '#1A73E8'; ?>; padding: 25px; border-radius: 12px; text-align: center; margin-top: 40px;">

            <?php if($role_sekarang == 'admin'): ?>
                <h3 style="margin-bottom: 5px; color:#856404;">⚠️ KONTROL EVALUASI UJIAN (DOSEN)</h3>
                <p style="font-size: 13px; color: #856404; margin-bottom: 15px;">Klik tombol di bawah ini untuk mengunggah soal atau memeriksa status lembar jawaban UTS/UAS mahasiswa.</p>
            <?php else: ?>
                <h3 style="margin-bottom: 5px; color:#333;">Menu Evaluasi Kelas</h3>
                <p style="font-size: 13px; color: #5f6368; margin-bottom: 15px;">Silakan akses berkas soal dan kumpulkan lembar jawaban Anda melalui link di bawah ini.</p>
            <?php endif; ?>

            <div style="display: flex; justify-content: center; gap: 15px;">
                <a href="halaman_ujian.php?id=<?php echo $id_jangkar; ?>&jenis=uts" style="border: 1px solid #34a853; background: <?php echo $role_sekarang == 'admin' ? '#34a853' : 'transparent'; ?>; color: <?php echo $role_sekarang == 'admin' ? 'white' : '#34a853'; ?>; text-decoration: none; padding: 10px 25px; font-weight:600; border-radius:6px; font-size: 14px;">
                    <?php echo $role_sekarang == 'admin' ? '⚙️ Kelola UTS' : 'Menu UTS'; ?>
                </a>
                <a href="halaman_ujian.php?id=<?php echo $id_jangkar; ?>&jenis=uas" style="border: 1px solid #ea4335; background: <?php echo $role_sekarang == 'admin' ? '#ea4335' : 'transparent'; ?>; color: <?php echo $role_sekarang == 'admin' ? 'white' : '#ea4335'; ?>; text-decoration: none; padding: 10px 25px; font-weight:600; border-radius:6px; font-size: 14px;">
                    <?php echo $role_sekarang == 'admin' ? '⚙️ Kelola UAS' : 'Menu UAS'; ?>
                </a>
            </div>
        </div>
    <?php endif; ?>

</div>

</body>
</html>

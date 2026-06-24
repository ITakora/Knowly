<?php

require_once '../config/db.php';
include '../includes/header_teacher.php';





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




        <div style=" margin-bottom: 25px; display: flex; justify-content: space-between; align-items: center;">

            <a href="teacher_add_materi.php?modul=<?php echo $id_class; ?>" style="background: #1A73E8; color: white; text-decoration: none; padding: 10px 20px; border-radius: 6px; font-weight: 600; font-size: 14px;">+ Tambah Materi Baru</a>
        </div>


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


                            <a href="teacher_add_quiz.php?id_material=<?= $row['id_material'] ?>" style="border: 1px solid #1A73E8; color: #1A73E8; text-decoration: none; padding: 8px 16px; border-radius: 6px; font-size: 14px;">
                                Buat Quiz
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
    </div>


</div>

</body>
</html>

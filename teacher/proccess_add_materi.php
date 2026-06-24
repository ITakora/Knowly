<?php
session_start();
require_once '../config/db.php';


if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$aksi = isset($_GET['aksi']) ? $_GET['aksi'] : '';

if ($aksi == 'insert') {

    $id_class    = intval($_POST['id_class']);
    $judul       = $_POST['judul'];
    $youtube_url = !empty($_POST['youtube_url']) ? $_POST['youtube_url'] : NULL;

    $file_name   = NULL;

    if (isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] == 0) {
        $target_dir = "uploads/";

        $original_name = basename($_FILES['file_materi']['name']);

        $safe_name = str_replace(" ", "_", $original_name);
        $file_name = time() . "_" . $safe_name;

        $target_file = $target_dir . $file_name;

        move_uploaded_file($_FILES['file_materi']['tmp_name'], $target_file);
    }

    $sql = "INSERT INTO materials (id_class, tittle_material, file_name, youtube_url) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("isss", $id_class, $judul, $file_name, $youtube_url);
        $stmt->execute();
    }

    header("Location: teacher_list_materi.php?modul=" . $id_class);
    exit();
}
?>

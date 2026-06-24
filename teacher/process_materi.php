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


if ($aksi == 'update') {

    $id_material = intval($_POST['id_materi']);
    $id_class    = intval($_POST['kode_modul']);
    $judul       = $_POST['judul'];
    $youtube_url = !empty($_POST['youtube_url']) ? $_POST['youtube_url'] : NULL;


    $query_old = "SELECT file_name FROM materials WHERE id_material = ?";
    $stmt_old = $conn->prepare($query_old);
    $stmt_old->bind_param("i", $id_material);
    $stmt_old->execute();
    $res_old = $stmt_old->get_result()->fetch_assoc();
    $file_name = $res_old['file_name'];

    $file_name = !empty($res_old['file_name']) ? $res_old['file_name'] : NULL;


    if (isset($_FILES['file_materi']) && $_FILES['file_materi']['name'] != '') {
        if ($_FILES['file_materi']['error'] == 0) {
            $target_dir = "uploads/";

            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            $original_name = basename($_FILES['file_materi']['name']);
            $safe_name = str_replace(" ", "_", $original_name);
            $new_file_name = time() . "_" . $safe_name;
            $target_file = $target_dir . $new_file_name;

            if (move_uploaded_file($_FILES['file_materi']['tmp_name'], $target_file)) {
                if (!empty($file_name) && file_exists($target_dir . $file_name)) {
                    unlink($target_dir . $file_name);
                }
                $file_name = $new_file_name;
            }
        }
    }


    $sql = "UPDATE materials SET tittle_material = ?, file_name = ?, youtube_url = ? WHERE id_material = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssi", $judul, $file_name, $youtube_url, $id_material);
        $stmt->execute();
    }

    header("Location: teacher_list_materi.php?modul=" . $id_class);
    exit();
}


if (isset($_GET['aksi']) && $_GET['aksi'] == 'delete') {
    $id_to_delete = isset($_GET['id']) ? intval($_GET['id']) : 0;
    $id_class_back = isset($_GET['modul']) ? intval($_GET['modul']) : 0;

    if ($id_to_delete > 0) {
        $query_file = "SELECT file_name FROM materials WHERE id_material = ?";
        $stmt_file = $conn->prepare($query_file);
        $stmt_file->bind_param("i", $id_to_delete);
        $stmt_file->execute();
        $res_file = $stmt_file->get_result()->fetch_assoc();


        if (!empty($res_file['file_name'])) {
            $target_file = "uploads/" . $res_file['file_name'];
            if (file_exists($target_file)) {
                unlink($target_file);
            }
        }


        $sql_delete = "DELETE FROM materials WHERE id_material = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        if ($stmt_delete) {
            $stmt_delete->bind_param("i", $id_to_delete);
            $stmt_delete->execute();
        }
    }

    header("Location: teacher_list_materi.php?modul=" . $id_class_back);
    exit();
}
?>

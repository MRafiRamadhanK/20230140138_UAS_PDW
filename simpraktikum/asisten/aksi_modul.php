<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}

$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';
$mk_id = $_POST['mk_id'] ?? $_GET['mk_id'] ?? ''; // Untuk redirect

switch ($aksi) {
    case 'tambah':
        $judul = $_POST['judul'];
        $deskripsi = $_POST['deskripsi'];
        $mata_praktikum_id = $_POST['mata_praktikum_id'];
        
        // Logika Upload File
        $file_materi = '';
        if (isset($_FILES['file_materi']) && $_FILES['file_materi']['error'] == 0) {
            $target_dir = "../uploads/materi/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            $file_materi = basename($_FILES["file_materi"]["name"]);
            $target_file = $target_dir . $file_materi;
            move_uploaded_file($_FILES["file_materi"]["tmp_name"], $target_file);
        }

        $stmt = $conn->prepare("INSERT INTO modul (mata_praktikum_id, judul, deskripsi, file_materi) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $mata_praktikum_id, $judul, $deskripsi, $file_materi);
        $stmt->execute();
        $stmt->close();
        break;

    case 'hapus':
        $id = $_GET['id'];
        // Optional: Hapus file fisik dari server
        $stmt_select = $conn->prepare("SELECT file_materi FROM modul WHERE id = ?");
        $stmt_select->bind_param("i", $id);
        $stmt_select->execute();
        $result = $stmt_select->get_result();
        if($row = $result->fetch_assoc()){
            if(!empty($row['file_materi']) && file_exists("../uploads/materi/" . $row['file_materi'])){
                unlink("../uploads/materi/" . $row['file_materi']);
            }
        }
        $stmt_select->close();

        $stmt = $conn->prepare("DELETE FROM modul WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        break;
}

$conn->close();
header("Location: modul.php?mk_id=" . $mk_id); // Redirect kembali ke halaman modul dengan filter aktif
exit();
?>
<?php
session_start();
require_once '../config.php';

// Pastikan hanya mahasiswa yang login yang bisa mengakses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

// Cek apakah form disubmit dengan benar
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['aksi']) && $_POST['aksi'] == 'kumpul') {
    
    $user_id = $_SESSION['user_id'];
    $modul_id = $_POST['modul_id'];
    $mk_id = $_POST['mk_id']; // Diperlukan untuk redirect kembali ke halaman yang benar

    // Proses upload file
    if (isset($_FILES['file_laporan']) && $_FILES['file_laporan']['error'] == 0) {
        $upload_dir = '../uploads/laporan/';
        
        // Buat folder jika belum ada
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Buat nama file yang unik untuk menghindari tumpang tindih
        $original_filename = basename($_FILES['file_laporan']['name']);
        $filename = "laporan_" . $user_id . "_" . $modul_id . "_" . time() . "_" . $original_filename;
        
        // Pindahkan file ke folder tujuan
        if (move_uploaded_file($_FILES['file_laporan']['tmp_name'], $upload_dir . $filename)) {
            
            // Simpan informasi file ke database
            $stmt = $conn->prepare("INSERT INTO laporan (modul_id, user_id, file_laporan, status) VALUES (?, ?, ?, 'dikumpulkan')");
            $stmt->bind_param("iis", $modul_id, $user_id, $filename);
            $stmt->execute();
            $stmt->close();

            $_SESSION['pesan_sukses'] = "Laporan berhasil dikumpulkan!";
        } else {
            $_SESSION['pesan_error'] = "Gagal memindahkan file yang diunggah.";
        }
    } else {
        $_SESSION['pesan_error'] = "Tidak ada file yang diunggah atau terjadi error.";
    }

    $conn->close();
    // Redirect kembali ke halaman detail praktikum
    header("Location: detail_praktikum.php?id=" . $mk_id);
    exit();

} else {
    // Jika diakses secara langsung, redirect ke dashboard
    header("Location: dashboard.php");
    exit();
}
?>
<?php
require_once '../config.php';
session_start();

// Pastikan hanya mahasiswa yang bisa akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'mahasiswa') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['mk_id'])) {
    $user_id = $_SESSION['user_id'];
    $mk_id = $_POST['mk_id'];

    // Cek dulu apakah sudah terdaftar
    $cek_stmt = $conn->prepare("SELECT id FROM pendaftaran WHERE user_id = ? AND mata_praktikum_id = ?");
    $cek_stmt->bind_param("ii", $user_id, $mk_id);
    $cek_stmt->execute();
    $result = $cek_stmt->get_result();

    if ($result->num_rows == 0) {
        // Jika belum, daftarkan
        $stmt = $conn->prepare("INSERT INTO pendaftaran (user_id, mata_praktikum_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $user_id, $mk_id);
        $stmt->execute();
        $stmt->close();
    }
    $cek_stmt->close();
}

$conn->close();
header("Location: my_courses.php"); // Redirect kembali
exit();
?>
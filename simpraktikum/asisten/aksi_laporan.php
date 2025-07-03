<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['aksi']) && $_POST['aksi'] == 'nilai') {
    $laporan_id = $_POST['laporan_id'];
    $nilai = $_POST['nilai'];
    $feedback = $_POST['feedback'];

    $stmt = $conn->prepare("UPDATE laporan SET nilai = ?, feedback = ?, status = 'dinilai' WHERE id = ?");
    $stmt->bind_param("isi", $nilai, $feedback, $laporan_id);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header("Location: laporan.php");
exit();
?>
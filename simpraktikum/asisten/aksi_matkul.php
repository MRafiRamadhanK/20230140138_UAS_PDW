<?php
require_once '../config.php';
session_start();

// Pastikan hanya asisten yang bisa akses
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}

$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

switch ($aksi) {
    case 'tambah':
        $kode_mk = $_POST['kode_mk'];
        $nama_mk = $_POST['nama_mk'];
        $deskripsi = $_POST['deskripsi'];
        
        $stmt = $conn->prepare("INSERT INTO mata_praktikum (kode_mk, nama_mk, deskripsi) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $kode_mk, $nama_mk, $deskripsi);
        $stmt->execute();
        $stmt->close();
        break;

    case 'edit':
        $id = $_POST['id'];
        $kode_mk = $_POST['kode_mk'];
        $nama_mk = $_POST['nama_mk'];
        $deskripsi = $_POST['deskripsi'];

        $stmt = $conn->prepare("UPDATE mata_praktikum SET kode_mk = ?, nama_mk = ?, deskripsi = ? WHERE id = ?");
        $stmt->bind_param("sssi", $kode_mk, $nama_mk, $deskripsi, $id);
        $stmt->execute();
        $stmt->close();
        break;

    case 'hapus':
        $id = $_GET['id'];
        $stmt = $conn->prepare("DELETE FROM mata_praktikum WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        break;
}

$conn->close();
header("Location: mata_praktikum.php"); // Redirect kembali ke halaman utama
exit();
?>
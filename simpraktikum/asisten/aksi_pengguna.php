<?php
require_once '../config.php';
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') {
    header("Location: ../login.php");
    exit();
}

$aksi = $_POST['aksi'] ?? $_GET['aksi'] ?? '';

switch ($aksi) {
    case 'edit': // <-- BAGIAN BARU DITAMBAHKAN
        $id = $_POST['id'];
        $nama = $_POST['nama'];
        $email = $_POST['email'];
        $role = $_POST['role'];

        $stmt = $conn->prepare("UPDATE users SET nama = ?, email = ?, role = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nama, $email, $role, $id);
        $stmt->execute();
        $stmt->close();
        break;

    case 'hapus':
        $id_to_delete = $_GET['id'];
        $current_user_id = $_SESSION['user_id'];

        if ($id_to_delete != $current_user_id) {
            $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
            $stmt->bind_param("i", $id_to_delete);
            $stmt->execute();
            $stmt->close();
        }
        break;
}

$conn->close();
header("Location: pengguna.php");
exit();
?>
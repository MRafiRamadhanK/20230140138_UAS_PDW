<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = trim($_POST["nama"]);
    $email = trim($_POST["email"]);
    $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
    $role = $_POST["role"];
    $foto_profil_filename = null;

    // Cek duplikasi email
    $stmt_check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt_check->bind_param("s", $email);
    $stmt_check->execute();
    if ($stmt_check->get_result()->num_rows > 0) {
        $_SESSION['error_message'] = "Email sudah terdaftar. Silakan gunakan email lain.";
        header("Location: register.php");
        exit();
    }
    $stmt_check->close();

    // Handle upload file foto profil
    if (isset($_FILES['foto_profil']) && $_FILES['foto_profil']['error'] == 0) {
        $allowed = ['image/jpeg', 'image/png'];
        if (in_array($_FILES['foto_profil']['type'], $allowed) && $_FILES['foto_profil']['size'] <= 2 * 1024 * 1024) { // Max 2MB
            $upload_dir = 'uploads/profile/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            $filename = uniqid() . '_' . basename($_FILES['foto_profil']['name']);
            if (move_uploaded_file($_FILES['foto_profil']['tmp_name'], $upload_dir . $filename)) {
                $foto_profil_filename = $filename;
            }
        } else {
            $_SESSION['error_message'] = "Format atau ukuran file foto profil tidak sesuai.";
            header("Location: register.php");
            exit();
        }
    }
    
    // Simpan data pengguna ke database
    $stmt_insert = $conn->prepare("INSERT INTO users (nama, email, password, role, foto_profil) VALUES (?, ?, ?, ?, ?)");
    $stmt_insert->bind_param("sssss", $nama, $email, $password, $role, $foto_profil_filename);
    
    if ($stmt_insert->execute()) {
        $_SESSION['success_message'] = "Registrasi berhasil! Silakan login.";
        header("Location: login.php");
    } else {
        $_SESSION['error_message'] = "Terjadi kesalahan saat mendaftarkan akun.";
        header("Location: register.php");
    }
    $stmt_insert->close();
    $conn->close();
    exit();
}
?>
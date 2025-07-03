<?php
session_start();
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, nama, email, password, role, foto_profil FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user["password"])) {
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["nama"] = $user["nama"];
            $_SESSION["email"] = $user["email"];
            $_SESSION["role"] = $user["role"];
            $_SESSION["foto_profil"] = $user["foto_profil"]; 

            if ($user["role"] == "asisten") {
                header("Location: asisten/index.php");
            } else {
                header("Location: mahasiswa/dashboard.php");
            }
            exit();
        } else {
            $_SESSION["error_message"] = "Password salah!";
            header("Location: login.php");
            exit();
        }
    } else {
        $_SESSION["error_message"] = "Email tidak terdaftar!";
        header("Location: login.php");
        exit();
    }
    $stmt->close();
    $conn->close();
} else {
    header("Location: login.php");
    exit();
}
?>
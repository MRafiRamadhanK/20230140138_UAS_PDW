<?php
$pageTitle = 'Dashboard';
$activePage = 'dashboard';
require_once '../config.php';
require_once 'templates/header_mahasiswa.php';

$user_id = $_SESSION['user_id'];

// Hitung jumlah praktikum yang diikuti
$stmt_praktikum = $conn->prepare("SELECT COUNT(id) as total FROM pendaftaran WHERE user_id = ?");
$stmt_praktikum->bind_param("i", $user_id);
$stmt_praktikum->execute();
$jumlah_praktikum = $stmt_praktikum->get_result()->fetch_assoc()['total'];
$stmt_praktikum->close();

// Hitung tugas yang perlu diselesaikan
$stmt_tugas = $conn->prepare(
    "SELECT COUNT(m.id) as total FROM modul m 
     JOIN pendaftaran p ON m.mata_praktikum_id = p.mata_praktikum_id 
     WHERE p.user_id = ? AND m.id NOT IN (SELECT l.modul_id FROM laporan l WHERE l.user_id = ?)"
);
$stmt_tugas->bind_param("ii", $user_id, $user_id);
$stmt_tugas->execute();
$tugas_selesai = $stmt_tugas->get_result()->fetch_assoc()['total'];
$stmt_tugas->close();
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Selamat Datang, <?php echo htmlspecialchars($_SESSION['nama']); ?>!</h1>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="font-bold text-xl mb-2">Praktikum yang Anda Ikuti</h2>
        <p class="text-4xl font-bold text-blue-600"><?php echo $jumlah_praktikum; ?></p>
        <a href="my_courses.php" class="text-blue-500 hover:underline mt-4 inline-block">Lihat Semua</a>
    </div>
    <div class="bg-white p-6 rounded-lg shadow-lg">
        <h2 class="font-bold text-xl mb-2">Tugas Perlu Diselesaikan</h2>
        <p class="text-4xl font-bold text-red-500"><?php echo $tugas_selesai; ?></p>
        <a href="my_courses.php" class="text-blue-500 hover:underline mt-4 inline-block">Kerjakan Sekarang</a>
    </div>
</div>

<?php 
$conn->close();
require_once 'templates/footer_mahasiswa.php'; 
?>
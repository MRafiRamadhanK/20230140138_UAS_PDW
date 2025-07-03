<?php
$pageTitle = 'Praktikum Saya';
$activePage = 'my_courses.php';
require_once '../config.php';
require_once 'templates/header_mahasiswa.php';

$user_id = $_SESSION['user_id'];

// Ambil semua praktikum yang diikuti oleh user
$stmt = $conn->prepare(
    "SELECT mk.id, mk.nama_mk FROM mata_praktikum mk
     JOIN pendaftaran p ON mk.id = p.mata_praktikum_id
     WHERE p.user_id = ? ORDER BY mk.nama_mk"
);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Praktikum yang Saya Ikuti</h1>
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <?php if ($result->num_rows > 0): ?>
        <?php while($mk = $result->fetch_assoc()): 
            // Query untuk progress
            $mk_id = $mk['id'];
            $total_modul_res = $conn->query("SELECT COUNT(id) as total FROM modul WHERE mata_praktikum_id = $mk_id");
            $total_modul = $total_modul_res->fetch_assoc()['total'];
            
            $modul_selesai_res = $conn->query("SELECT COUNT(l.id) as total FROM laporan l JOIN modul m ON l.modul_id = m.id WHERE l.user_id = {$_SESSION['user_id']} AND m.mata_praktikum_id = $mk_id");
            $modul_selesai = $modul_selesai_res->fetch_assoc()['total'];

            $progress = ($total_modul > 0) ? ($modul_selesai / $total_modul) * 100 : 0;
        ?>
            <div class="bg-white p-6 rounded-xl shadow-lg hover:shadow-2xl transition-shadow duration-300">
                <h3 class="text-xl font-bold text-gray-900"><?php echo htmlspecialchars($mk['nama_mk']); ?></h3>
                <p class="text-gray-500 mt-2">Progres Anda:</p>
                <div class="w-full bg-gray-200 rounded-full h-2.5 mt-2">
                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: <?php echo $progress; ?>%"></div>
                </div>
                <div class="flex justify-between text-sm text-gray-600 mt-1">
                    <span><?php echo round($progress); ?>% Selesai</span>
                    <span><?php echo $modul_selesai; ?>/<?php echo $total_modul; ?> Modul</span>
                </div>
                <a href="detail_praktikum.php?id=<?php echo $mk['id']; ?>" class="mt-4 inline-block w-full text-center bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                    Masuk ke Praktikum
                </a>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-gray-500 col-span-full">Anda belum terdaftar di praktikum manapun.</p>
    <?php endif; ?>
</div>

<?php 
$conn->close();
require_once 'templates/footer_mahasiswa.php'; 
?>
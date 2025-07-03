<?php
$pageTitle = 'Cari Praktikum';
$activePage = 'courses';
require_once '../config.php';
require_once 'templates/header_mahasiswa.php';

// Ambil semua mata praktikum
$praktikum_list = $conn->query("SELECT * FROM mata_praktikum");

// Ambil ID praktikum yang sudah diikuti user
$user_id = $_SESSION['user_id'];
$pendaftaran_result = $conn->query("SELECT mata_praktikum_id FROM pendaftaran WHERE user_id = $user_id");
$praktikum_diikuti = [];
while ($row = $pendaftaran_result->fetch_assoc()) {
    $praktikum_diikuti[] = $row['mata_praktikum_id'];
}
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Katalog Mata Praktikum</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    <?php while($mk = $praktikum_list->fetch_assoc()): ?>
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-6">
                <h3 class="text-xl font-bold mb-2"><?php echo htmlspecialchars($mk['nama_mk']); ?></h3>
                <p class="text-gray-600 mb-4 h-20"><?php echo htmlspecialchars($mk['deskripsi']); ?></p>
                
                <?php if (in_array($mk['id'], $praktikum_diikuti)): ?>
                    <button class="w-full bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed" disabled>Sudah Terdaftar</button>
                <?php else: ?>
                    <form action="aksi_pendaftaran.php" method="POST">
                        <input type="hidden" name="mk_id" value="<?php echo $mk['id']; ?>">
                        <button type="submit" class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Daftar ke Praktikum</button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    <?php endwhile; ?>
</div>

<?php
$conn->close();
require_once 'templates/footer_mahasiswa.php';
?>
<?php
$pageTitle = 'Detail Praktikum';
$activePage = 'my_courses';
require_once '../config.php';
require_once 'templates/header_mahasiswa.php';

// Validasi ID praktikum
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("ID Praktikum tidak valid.");
}
$mk_id = $_GET['id'];
$user_id = $_SESSION['user_id'];

// Ambil nama mata praktikum
$mk_stmt = $conn->prepare("SELECT nama_mk FROM mata_praktikum WHERE id = ?");
$mk_stmt->bind_param("i", $mk_id);
$mk_stmt->execute();
$nama_mk = $mk_stmt->get_result()->fetch_assoc()['nama_mk'];
$mk_stmt->close();

// Ambil semua modul untuk praktikum ini
$modul_stmt = $conn->prepare("SELECT * FROM modul WHERE mata_praktikum_id = ? ORDER BY judul");
$modul_stmt->bind_param("i", $mk_id);
$modul_stmt->execute();
$modul_result = $modul_stmt->get_result();
$modul_stmt->close();

// Ambil semua laporan yang sudah dikumpulkan user untuk praktikum ini untuk pengecekan
$laporan_stmt = $conn->prepare(
    "SELECT l.modul_id, l.file_laporan, l.nilai, l.feedback FROM laporan l
     JOIN modul m ON l.modul_id = m.id
     WHERE l.user_id = ? AND m.mata_praktikum_id = ?"
);
$laporan_stmt->bind_param("ii", $user_id, $mk_id);
$laporan_stmt->execute();
$laporan_result = $laporan_stmt->get_result();
$laporan_user = [];
while($row = $laporan_result->fetch_assoc()){
    $laporan_user[$row['modul_id']] = $row;
}
$laporan_stmt->close();
?>

<h1 class="text-3xl font-bold text-gray-800 mb-6">Detail: <?php echo htmlspecialchars($nama_mk); ?></h1>
<div class="space-y-6">
    <?php if ($modul_result->num_rows > 0): ?>
        <?php while($modul = $modul_result->fetch_assoc()): ?>
            <div class="bg-white p-6 rounded-lg shadow-md">
                <h3 class="text-xl font-bold mb-3"><?php echo htmlspecialchars($modul['judul']); ?></h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                    <div>
                        <a href="../uploads/materi/<?php echo htmlspecialchars($modul['file_materi']); ?>" target="_blank" class="text-blue-500 hover:underline">Unduh Materi</a>
                    </div>

                    <?php if (array_key_exists($modul['id'], $laporan_user)): 
                        $laporan_terkumpul = $laporan_user[$modul['id']];
                    ?>
                        <div>
                            <span class="text-gray-500">Laporan sudah diunggah: <br><i><?php echo htmlspecialchars($laporan_terkumpul['file_laporan']); ?></i></span>
                        </div>
                        <div>
                            <?php if (!is_null($laporan_terkumpul['nilai'])): ?>
                                <p class="text-lg"><strong>Nilai:</strong> <span class="font-bold text-green-600"><?php echo htmlspecialchars($laporan_terkumpul['nilai']); ?></span></p>
                                <p class="text-sm text-gray-600 mt-1"><strong>Feedback:</strong> <?php echo htmlspecialchars($laporan_terkumpul['feedback']); ?></p>
                            <?php else: ?>
                                <p class="text-gray-500">Laporan sedang dinilai.</p>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <form action="aksi_laporan.php" method="POST" enctype="multipart/form-data" class="md:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                            <input type="hidden" name="aksi" value="kumpul">
                            <input type="hidden" name="modul_id" value="<?php echo $modul['id']; ?>">
                            <input type="hidden" name="mk_id" value="<?php echo $mk_id; ?>">
                            <div>
                                <label class="block mb-1 text-sm font-medium text-gray-900">Unggah Laporan:</label>
                                <input name="file_laporan" type="file" class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50" required>
                            </div>
                            <div>
                                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded w-full">Kumpulkan</button>
                            </div>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        <?php endwhile; ?>
    <?php else: ?>
        <p class="text-gray-500">Belum ada modul yang ditambahkan untuk praktikum ini.</p>
    <?php endif; ?>
</div>

<?php 
$conn->close();
require_once 'templates/footer_mahasiswa.php'; 
?>
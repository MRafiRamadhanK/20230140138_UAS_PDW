<?php
$pageTitle = 'Manajemen Modul';
require_once '../config.php';
require_once 'templates/header.php';

$mata_praktikum_list = $conn->query("SELECT id, nama_mk FROM mata_praktikum ORDER BY nama_mk");

$selected_mk_id = $_GET['mk_id'] ?? null;
if (!$selected_mk_id && $mata_praktikum_list->num_rows > 0) {
    mysqli_data_seek($mata_praktikum_list, 0);
    $selected_mk_id = $mata_praktikum_list->fetch_assoc()['id'];
}

$modules = [];
$nama_mk_terpilih = '';
if ($selected_mk_id) {
    $stmt = $conn->prepare("SELECT * FROM modul WHERE mata_praktikum_id = ? ORDER BY judul");
    $stmt->bind_param("i", $selected_mk_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $modules[] = $row;
    }
    $stmt->close();

    $mk_stmt = $conn->prepare("SELECT nama_mk FROM mata_praktikum WHERE id = ?");
    $mk_stmt->bind_param("i", $selected_mk_id);
    $mk_stmt->execute();
    $nama_mk_terpilih = $mk_stmt->get_result()->fetch_assoc()['nama_mk'];
    $mk_stmt->close();
}
?>

<div class="bg-white p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h2 class="text-xl font-bold">Kelola Modul Praktikum</h2>
        <button onclick="openModal('addModal')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">+ Tambah Modul Baru</button>
    </div>

    <form action="modul.php" method="GET" class="mb-6">
        <label for="mataPraktikumFilter" class="block text-sm font-medium text-gray-700 mb-1">Pilih Mata Praktikum:</label>
        <div class="flex">
            <select onchange="this.form.submit()" name="mk_id" class="w-full md:w-1/3 p-2 border rounded-md">
                <?php if ($mata_praktikum_list->num_rows > 0): mysqli_data_seek($mata_praktikum_list, 0); ?>
                    <?php while($mk = $mata_praktikum_list->fetch_assoc()): ?>
                        <option value="<?php echo $mk['id']; ?>" <?php echo ($selected_mk_id == $mk['id'] ? 'selected' : ''); ?>>
                            <?php echo htmlspecialchars($mk['nama_mk']); ?>
                        </option>
                    <?php endwhile; ?>
                <?php endif; ?>
            </select>
        </div>
    </form>

    <h3 class="text-lg font-semibold mb-4">Daftar Modul untuk: <span class="text-blue-600"><?php echo htmlspecialchars($nama_mk_terpilih); ?></span></h3>
    <table class="min-w-full bg-white">
        <thead><tr class="bg-gray-200"><th class="py-2 px-4 text-left">Judul Modul</th><th class="py-2 px-4 text-left">File</th><th class="py-2 px-4 text-center">Aksi</th></tr></thead>
        <tbody>
            <?php if (!empty($modules)): ?>
                <?php foreach ($modules as $modul): ?>
                    <tr class="border-b">
                        <td class="py-3 px-4"><?php echo htmlspecialchars($modul['judul']); ?></td>
                        <td class="py-3 px-4"><a href="../uploads/materi/<?php echo htmlspecialchars($modul['file_materi']); ?>" target="_blank" class="text-blue-500 hover:underline"><?php echo htmlspecialchars($modul['file_materi']); ?></a></td>
                        <td class="py-3 px-4 text-center">
                            <a href="aksi_modul.php?aksi=hapus&id=<?php echo $modul['id']; ?>&mk_id=<?php echo $selected_mk_id; ?>" onclick="return confirm('Yakin hapus modul ini?')" class="bg-red-500 text-white py-1.5 px-3 rounded inline-block">Hapus</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="3" class="text-center py-4">Belum ada modul.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div id="addModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <form action="aksi_modul.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="aksi" value="tambah">
            <input type="hidden" name="mk_id" value="<?php echo $selected_mk_id; ?>">
            <input type="hidden" name="mata_praktikum_id" value="<?php echo $selected_mk_id; ?>">
            <h3 class="text-lg font-medium mb-4">Tambah Modul untuk <?php echo htmlspecialchars($nama_mk_terpilih); ?></h3>
            <div class="space-y-4">
                <div><label>Judul Modul</label><input type="text" name="judul" class="w-full p-2 border rounded" required></div>
                <div><label>Deskripsi</label><textarea name="deskripsi" class="w-full p-2 border rounded"></textarea></div>
                <div><label>File Materi (PDF/DOCX)</label><input type="file" name="file_materi" class="w-full p-1 border rounded" required></div>
            </div>
            <div class="mt-6 flex justify-end space-x-2">
                <button type="button" onclick="closeModal('addModal')" class="bg-gray-300 py-2 px-4 rounded">Batal</button>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(modalId) { document.getElementById(modalId).classList.remove('hidden'); }
function closeModal(modalId) { document.getElementById(modalId).classList.add('hidden'); }
</script>

<?php
$conn->close();
require_once 'templates/footer.php';
?>
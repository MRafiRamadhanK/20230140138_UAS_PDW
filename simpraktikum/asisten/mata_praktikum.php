<?php
$pageTitle = 'Kelola Mata Praktikum';
require_once '../config.php';
require_once 'templates/header.php';

$result = $conn->query("SELECT * FROM mata_praktikum ORDER BY kode_mk");
?>

<div class="bg-white p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Daftar Mata Praktikum</h2>
        <button onclick="openModal('addModal')" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
            + Tambah Baru
        </button>
    </div>
    <table class="min-w-full bg-white">
        <thead class="bg-gray-200">
            <tr>
                <th class="py-2 px-4">Kode MK</th>
                <th class="py-2 px-4">Nama Mata Praktikum</th>
                <th class="py-2 px-4">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($result && $result->num_rows > 0): ?>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr class="border-b">
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($row['kode_mk']); ?></td>
                        <td class="border px-4 py-2"><?php echo htmlspecialchars($row['nama_mk']); ?></td>
                        <td class="border px-4 py-2 text-center">
                            <button onclick="openModal('editModal', <?php echo htmlspecialchars(json_encode($row)); ?>)" class="bg-green-500 text-white py-1 px-3 rounded">Edit</button>
                            <a href="aksi_matkul.php?aksi=hapus&id=<?php echo $row['id']; ?>" onclick="return confirm('Apakah Anda yakin ingin menghapus data ini?')" class="bg-red-500 text-white py-1.5 px-3 rounded inline-block">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center py-4">Belum ada data mata praktikum.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<div id="addModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <form action="aksi_matkul.php" method="POST">
            <input type="hidden" name="aksi" value="tambah">
            <h3 class="text-lg font-medium mb-4">Tambah Mata Praktikum</h3>
            <div class="space-y-4">
                <div><label>Kode MK</label><input type="text" name="kode_mk" class="w-full p-2 border rounded" required></div>
                <div><label>Nama MK</label><input type="text" name="nama_mk" class="w-full p-2 border rounded" required></div>
                <div><label>Deskripsi</label><textarea name="deskripsi" class="w-full p-2 border rounded"></textarea></div>
            </div>
            <div class="mt-6 flex justify-end space-x-2">
                <button type="button" onclick="closeModal('addModal')" class="bg-gray-300 py-2 px-4 rounded">Batal</button>
                <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded">Simpan</button>
            </div>
        </form>
    </div>
</div>

<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <form action="aksi_matkul.php" method="POST">
            <input type="hidden" name="aksi" value="edit">
            <input type="hidden" name="id" id="edit-id">
            <h3 class="text-lg font-medium mb-4">Edit Mata Praktikum</h3>
            <div class="space-y-4">
                <div><label>Kode MK</label><input type="text" name="kode_mk" id="edit-kode_mk" class="w-full p-2 border rounded" required></div>
                <div><label>Nama MK</label><input type="text" name="nama_mk" id="edit-nama_mk" class="w-full p-2 border rounded" required></div>
                <div><label>Deskripsi</label><textarea name="deskripsi" id="edit-deskripsi" class="w-full p-2 border rounded"></textarea></div>
            </div>
            <div class="mt-6 flex justify-end space-x-2">
                <button type="button" onclick="closeModal('editModal')" class="bg-gray-300 py-2 px-4 rounded">Batal</button>
                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded">Update</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(modalId, data = null) {
    if (modalId === 'editModal' && data) {
        document.getElementById('edit-id').value = data.id;
        document.getElementById('edit-kode_mk').value = data.kode_mk;
        document.getElementById('edit-nama_mk').value = data.nama_mk;
        document.getElementById('edit-deskripsi').value = data.deskripsi;
    }
    document.getElementById(modalId).classList.remove('hidden');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
}
</script>

<?php
$conn->close();
require_once 'templates/footer.php';
?>
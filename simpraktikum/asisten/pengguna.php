<?php
$pageTitle = 'Manajemen Pengguna';
require_once '../config.php';
require_once 'templates/header.php';

$result = $conn->query("SELECT id, nama, email, role FROM users ORDER BY nama ASC");
?>
<div class="bg-white p-6 rounded-lg shadow-lg">
    <div class="flex justify-between items-center mb-6 border-b pb-4">
        <h2 class="text-xl font-bold">Daftar Pengguna Sistem</h2>
        <a href="../register.php" target="_blank" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">+ Tambah Pengguna Baru</a>
    </div>
    <table class="min-w-full bg-white">
        <thead><tr class="bg-gray-200"><th class="py-2 px-4 text-left">Nama</th><th class="py-2 px-4 text-left">Email</th><th class="py-2 px-4 text-left">Peran</th><th class="py-2 px-4 text-center">Aksi</th></tr></thead>
        <tbody>
            <?php while($user = $result->fetch_assoc()): ?>
                <tr class="border-b">
                    <td class="py-3 px-4"><?php echo htmlspecialchars($user['nama']); ?></td>
                    <td class="py-3 px-4"><?php echo htmlspecialchars($user['email']); ?></td>
                    <td class="py-3 px-4">
                        <?php if ($user['role'] == 'asisten'): ?>
                            <span class="bg-purple-200 text-purple-800 py-1 px-3 rounded-full text-xs font-medium">Asisten</span>
                        <?php else: ?>
                            <span class="bg-blue-200 text-blue-800 py-1 px-3 rounded-full text-xs font-medium">Mahasiswa</span>
                        <?php endif; ?>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <button onclick="openModal('editModal', <?php echo htmlspecialchars(json_encode($user)); ?>)" class="bg-green-500 text-white py-1 px-3 rounded text-sm hover:bg-green-600">Edit</button>
                        
                        <?php if ($_SESSION['user_id'] != $user['id']): ?>
                            <a href="aksi_pengguna.php?aksi=hapus&id=<?php echo $user['id']; ?>" onclick="return confirm('Yakin ingin menghapus pengguna ini?')" class="bg-red-500 text-white py-1.5 px-3 rounded inline-block ml-1">Hapus</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div id="editModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-lg shadow-lg rounded-md bg-white">
        <form action="aksi_pengguna.php" method="POST">
            <input type="hidden" name="aksi" value="edit">
            <input type="hidden" name="id" id="edit-id">
            <h3 class="text-lg font-medium mb-4">Edit Pengguna</h3>
            <div class="space-y-4">
                <div>
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" id="edit-nama" class="w-full p-2 border rounded" required>
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" name="email" id="edit-email" class="w-full p-2 border rounded" required>
                </div>
                <div>
                    <label>Peran</label>
                    <select name="role" id="edit-role" class="w-full p-2 border rounded">
                        <option value="mahasiswa">Mahasiswa</option>
                        <option value="asisten">Asisten</option>
                    </select>
                </div>
                <p class="text-sm text-gray-500">*Untuk mengubah password, gunakan fitur 'Lupa Password' (jika tersedia).</p>
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
        document.getElementById('edit-nama').value = data.nama;
        document.getElementById('edit-email').value = data.email;
        document.getElementById('edit-role').value = data.role;
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
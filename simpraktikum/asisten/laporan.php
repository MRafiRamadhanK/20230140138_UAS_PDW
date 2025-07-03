<?php
$pageTitle = 'Laporan Masuk';
require_once '../config.php';
require_once 'templates/header.php';

$laporan_result = $conn->query(
    "SELECT l.id, l.tgl_kumpul, l.status, l.file_laporan, u.nama AS nama_mahasiswa, m.judul AS judul_modul
     FROM laporan l
     JOIN users u ON l.user_id = u.id
     JOIN modul m ON l.modul_id = m.id
     ORDER BY l.tgl_kumpul DESC"
);
?>
<div class="bg-white p-6 rounded-lg shadow-lg">
    <h2 class="text-xl font-bold mb-4">Daftar Laporan Masuk</h2>
    <table class="min-w-full bg-white">
        <thead><tr class="bg-gray-200"><th class="py-2 px-4 text-left">Tgl Kumpul</th><th class="py-2 px-4 text-left">Mahasiswa</th><th class="py-2 px-4 text-left">Modul</th><th class="py-2 px-4 text-left">Status</th><th class="py-2 px-4 text-center">Aksi</th></tr></thead>
        <tbody>
            <?php while($lpr = $laporan_result->fetch_assoc()): ?>
                <tr class="border-b">
                    <td class="py-3 px-4"><?php echo date('d M Y, H:i', strtotime($lpr['tgl_kumpul'])); ?></td>
                    <td class="py-3 px-4"><?php echo htmlspecialchars($lpr['nama_mahasiswa']); ?></td>
                    <td class="py-3 px-4"><?php echo htmlspecialchars($lpr['judul_modul']); ?></td>
                    <td class="py-3 px-4">
                        <?php if ($lpr['status'] == 'dinilai'): ?>
                            <span class="bg-green-200 text-green-800 py-1 px-3 rounded-full text-xs">Dinilai</span>
                        <?php else: ?>
                            <span class="bg-yellow-200 text-yellow-800 py-1 px-3 rounded-full text-xs">Dikumpulkan</span>
                        <?php endif; ?>
                    </td>
                    <td class="py-3 px-4 text-center">
                        <button onclick="openModal('nilaiModal', <?php echo htmlspecialchars(json_encode($lpr)); ?>)" class="bg-blue-500 text-white py-1 px-3 rounded text-sm">Lihat & Nilai</button>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div id="nilaiModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 h-full w-full">
    <div class="relative top-10 mx-auto p-5 border w-full max-w-xl shadow-lg rounded-md bg-white">
        <form action="aksi_laporan.php" method="POST">
            <input type="hidden" name="aksi" value="nilai">
            <input type="hidden" name="laporan_id" id="laporan_id">
            <h3 class="text-lg font-medium mb-4">Detail Laporan</h3>
            <div class="space-y-3">
                <p><strong>Mahasiswa:</strong> <span id="nama_mahasiswa"></span></p>
                <p><strong>Modul:</strong> <span id="judul_modul"></span></p>
                <p><strong>File Laporan:</strong> <a id="file_laporan" href="#" target="_blank" class="text-blue-500 hover:underline"></a></p>
                <hr>
                <div><label>Nilai (0-100)</label><input type="number" name="nilai" min="0" max="100" class="w-full p-2 border rounded" required></div>
                <div><label>Feedback</label><textarea name="feedback" rows="3" class="w-full p-2 border rounded"></textarea></div>
            </div>
            <div class="mt-6 flex justify-end space-x-2">
                <button type="button" onclick="closeModal('nilaiModal')" class="bg-gray-300 py-2 px-4 rounded">Tutup</button>
                <button type="submit" class="bg-green-500 text-white py-2 px-4 rounded">Simpan Nilai</button>
            </div>
        </form>
    </div>
</div>

<script>
function openModal(modalId, data) {
    document.getElementById('laporan_id').value = data.id;
    document.getElementById('nama_mahasiswa').innerText = data.nama_mahasiswa;
    document.getElementById('judul_modul').innerText = data.judul_modul;
    const fileLink = document.getElementById('file_laporan');
    fileLink.href = `../uploads/laporan/${data.file_laporan}`; // Asumsi folder upload laporan
    fileLink.innerText = data.file_laporan;
    document.getElementById(modalId).classList.remove('hidden');
}
function closeModal(modalId) { document.getElementById(modalId).classList.add('hidden'); }
</script>

<?php
$conn->close();
require_once 'templates/footer.php';
?>
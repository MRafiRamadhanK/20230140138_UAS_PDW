<?php
$pageTitle = 'Dashboard';
require_once '../config.php';
require_once 'templates/header.php';

// Query untuk statistik
$total_mk_result = $conn->query("SELECT COUNT(*) as total FROM mata_praktikum");
$total_mk = $total_mk_result->fetch_assoc()['total'];

$total_modul_result = $conn->query("SELECT COUNT(*) as total FROM modul");
$total_modul = $total_modul_result->fetch_assoc()['total'];

$total_laporan_result = $conn->query("SELECT COUNT(*) as total FROM laporan");
$total_laporan = $total_laporan_result->fetch_assoc()['total'];

$laporan_belum_dinilai_result = $conn->query("SELECT COUNT(*) as total FROM laporan WHERE status = 'dikumpulkan'");
$laporan_belum_dinilai = $laporan_belum_dinilai_result->fetch_assoc()['total'];

// Query untuk aktivitas terbaru
$aktivitas_terbaru_result = $conn->query(
    "SELECT l.tgl_kumpul, u.nama, m.judul AS judul_modul
     FROM laporan l
     JOIN users u ON l.user_id = u.id
     JOIN modul m ON l.modul_id = m.id
     ORDER BY l.tgl_kumpul DESC
     LIMIT 5"
);
?>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between hover:shadow-xl transition-shadow duration-300">
        <div>
            <p class="text-sm font-medium text-gray-500">Total Mata Praktikum</p>
            <p class="text-4xl font-bold text-gray-800"><?php echo $total_mk; ?></p>
        </div>
        <div class="bg-blue-100 text-blue-600 p-4 rounded-full">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between hover:shadow-xl transition-shadow duration-300">
        <div>
            <p class="text-sm font-medium text-gray-500">Total Modul</p>
            <p class="text-4xl font-bold text-gray-800"><?php echo $total_modul; ?></p>
        </div>
        <div class="bg-indigo-100 text-indigo-600 p-4 rounded-full">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494M12 6.253c1.657-1.657 4.343-1.657 6 0 1.657 1.657 1.657 4.343 0 6-1.657 1.657-4.343 1.657-6 0M12 6.253c-1.657-1.657-4.343-1.657-6 0s-1.657 4.343 0 6c1.657 1.657 4.343 1.657 6 0"></path></svg>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between hover:shadow-xl transition-shadow duration-300">
        <div>
            <p class="text-sm font-medium text-gray-500">Laporan Masuk</p>
            <p class="text-4xl font-bold text-gray-800"><?php echo $total_laporan; ?></p>
        </div>
        <div class="bg-green-100 text-green-600 p-4 rounded-full">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
        </div>
    </div>
    <div class="bg-white p-6 rounded-2xl shadow-lg flex items-center justify-between hover:shadow-xl transition-shadow duration-300">
        <div>
            <p class="text-sm font-medium text-gray-500">Laporan Belum Dinilai</p>
            <p class="text-4xl font-bold text-red-500"><?php echo $laporan_belum_dinilai; ?></p>
        </div>
        <div class="bg-red-100 text-red-600 p-4 rounded-full">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>
</div>

<div class="bg-white p-6 rounded-2xl shadow-lg mt-8">
    <h3 class="text-xl font-bold mb-4">Aktivitas Laporan Terbaru</h3>
    <div class="space-y-4">
        <?php if ($aktivitas_terbaru_result && $aktivitas_terbaru_result->num_rows > 0): ?>
            <?php while($aktivitas = $aktivitas_terbaru_result->fetch_assoc()): ?>
                <div class="flex items-center p-2 rounded-lg hover:bg-gray-100">
                    <div class="w-10 h-10 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mr-4 font-bold flex-shrink-0">
                        <?php echo strtoupper(substr($aktivitas['nama'], 0, 2)); ?>
                    </div>
                    <div>
                        <p class="text-gray-800"><strong><?php echo htmlspecialchars($aktivitas['nama']); ?></strong> mengumpulkan laporan untuk <strong><?php echo htmlspecialchars($aktivitas['judul_modul']); ?></strong></p>
                        <p class="text-sm text-gray-500"><?php echo date('d M Y, H:i', strtotime($aktivitas['tgl_kumpul'])); ?> WIB</p>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p class="text-gray-500">Belum ada aktivitas laporan.</p>
        <?php endif; ?>
    </div>
</div>

<?php
$conn->close();
require_once 'templates/footer.php';
?>
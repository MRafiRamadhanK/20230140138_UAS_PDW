<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'asisten') { header("Location: ../login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Asisten - <?php echo $pageTitle ?? 'Dashboard'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #f1f1f1; }
        ::-webkit-scrollbar-thumb { background: #888; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #555; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
<div class="flex h-screen bg-gray-50 font-sans">
    <aside class="w-64 bg-gray-800 text-white flex-col hidden sm:flex shadow-2xl">
        <div class="p-6 text-center border-b border-gray-700">
            <h3 class="text-2xl font-extrabold text-white">SIMPRAK</h3>
            <p class="text-sm text-gray-400 mt-1">Panel Asisten</p>
        </div>
        <nav class="flex-grow p-2">
            <ul class="space-y-2">
                <?php 
                    $activeClass = 'bg-gray-900 text-white shadow-inner';
                    $inactiveClass = 'text-gray-300 hover:bg-gray-700 hover:text-white';
                    $current_page = basename($_SERVER['PHP_SELF']);
                ?>
                <li><a href="index.php" class="<?php echo ($current_page == 'index.php') ? $activeClass : $inactiveClass; ?> flex items-center px-4 py-2.5 rounded-md transition-all duration-300">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                    <span>Dashboard</span></a></li>
                <li><a href="mata_praktikum.php" class="<?php echo ($current_page == 'mata_praktikum.php') ? $activeClass : $inactiveClass; ?> flex items-center px-4 py-2.5 rounded-md transition-all duration-300">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    <span>Kelola Mata Praktikum</span></a></li>
                <li><a href="modul.php" class="<?php echo ($current_page == 'modul.php') ? $activeClass : $inactiveClass; ?> flex items-center px-4 py-2.5 rounded-md transition-all duration-300">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v11.494M12 6.253c1.657-1.657 4.343-1.657 6 0 1.657 1.657 1.657 4.343 0 6-1.657 1.657-4.343 1.657-6 0M12 6.253c-1.657-1.657-4.343-1.657-6 0s-1.657 4.343 0 6c1.657 1.657 4.343 1.657 6 0"></path></svg>
                    <span>Kelola Modul</span></a></li>
                <li><a href="laporan.php" class="<?php echo ($current_page == 'laporan.php') ? $activeClass : $inactiveClass; ?> flex items-center px-4 py-2.5 rounded-md transition-all duration-300">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    <span>Laporan Masuk</span></a></li>
                <li><a href="pengguna.php" class="<?php echo ($current_page == 'pengguna.php') ? $activeClass : $inactiveClass; ?> flex items-center px-4 py-2.5 rounded-md transition-all duration-300">
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    <span>Kelola Pengguna</span></a></li>
            </ul>
        </nav>
        <div class="p-4 border-t border-gray-700">
             <a href="../logout.php" class="w-full flex items-center justify-center bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition-all duration-300">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                Logout
             </a>
        </div>
    </aside>
    <main class="flex-1 flex flex-col overflow-hidden">
        <header class="bg-white shadow-md p-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800"><?php echo $pageTitle ?? 'Halaman'; ?></h1>
            <div class="flex items-center space-x-4">
                <span>Selamat datang, <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['nama']); ?></span>!</span>
                <?php if (!empty($_SESSION['foto_profil'])): ?>
                    <img src="../uploads/profile/<?php echo htmlspecialchars($_SESSION['foto_profil']); ?>" alt="Foto Profil" class="w-10 h-10 rounded-full object-cover border-2 border-blue-500">
                <?php else: ?>
                    <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 font-bold">
                        <?php echo strtoupper(substr($_SESSION['nama'], 0, 1)); ?>
                    </div>
                <?php endif; ?>
            </div>
        </header>
        <div class="flex-1 p-6 lg:p-8 overflow-y-auto">
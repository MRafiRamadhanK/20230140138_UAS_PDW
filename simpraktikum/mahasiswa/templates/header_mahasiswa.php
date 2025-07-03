<?php
if (session_status() === PHP_SESSION_NONE) { session_start(); }
if (!isset($_SESSION['user_id'])) { header("Location: ../login.php"); exit(); }
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Panel Mahasiswa - <?php echo $pageTitle ?? 'Dashboard'; ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 font-sans">
    <nav class="bg-gradient-to-r from-blue-600 to-indigo-700 shadow-lg text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <span class="text-white text-2xl font-bold">SIMPRAK</span>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-baseline space-x-1">
                            <?php 
                                $activePage = basename($_SERVER['PHP_SELF']);
                                $activeClass = 'bg-black bg-opacity-20 text-white';
                                $inactiveClass = 'text-indigo-200 hover:bg-black hover:bg-opacity-20 hover:text-white';
                            ?>
                            <a href="dashboard.php" class="<?php echo ($activePage == 'dashboard.php') ? $activeClass : $inactiveClass; ?> px-3 py-2 rounded-md text-sm font-medium">Dashboard</a>
                            <a href="my_courses.php" class="<?php echo ($activePage == 'my_courses.php') ? $activeClass : $inactiveClass; ?> px-3 py-2 rounded-md text-sm font-medium">Praktikum Saya</a>
                            <a href="courses.php" class="<?php echo ($activePage == 'courses.php') ? $activeClass : $inactiveClass; ?> px-3 py-2 rounded-md text-sm font-medium">Cari Praktikum</a>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-4 flex items-center md:ml-6">
                        <span class="mr-4">Halo, <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['nama']); ?></span></span>
                        <?php if (!empty($_SESSION['foto_profil'])): ?>
                            <img src="../uploads/profile/<?php echo htmlspecialchars($_SESSION['foto_profil']); ?>" alt="Foto Profil" class="w-10 h-10 rounded-full object-cover border-2 border-indigo-300 mr-4">
                        <?php else: ?>
                            <div class="w-10 h-10 rounded-full bg-indigo-200 text-indigo-800 flex items-center justify-center font-bold mr-4">
                                <?php echo strtoupper(substr($_SESSION['nama'], 0, 1)); ?>
                            </div>
                        <?php endif; ?>
                        <a href="../logout.php" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded-md transition-colors duration-300">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <div class="container mx-auto p-6 lg:p-8">
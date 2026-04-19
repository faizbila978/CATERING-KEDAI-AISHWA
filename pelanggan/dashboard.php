<?php
session_start();

// 🔐 Proteksi admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// 🔓 Logout
if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Admin Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body class="font-sans bg-orange-50">

<div class="flex h-screen">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-orange-700 text-white flex flex-col">
        <div class="p-6 text-center border-b border-orange-600">
            <h1 class="text-lg font-bold uppercase">Kedai Aishwa</h1>
        </div>

        <nav class="flex-1 p-4 space-y-2">
            <a href="#" class="block p-2 hover:bg-orange-600 rounded">Manajemen Produk</a>
            <a href="#" class="block p-2 hover:bg-orange-600 rounded">Manajemen Pesanan</a>
            <a href="#" class="block p-2 hover:bg-orange-600 rounded">Laporan</a>
        </nav>

        <div class="p-4 border-t border-orange-600">
            <a href="admin.php?action=logout" class="block text-center bg-red-500 p-2 rounded hover:bg-red-600">
                Logout
            </a>
        </div>
    </aside>

    <!-- CONTENT -->
    <main class="flex-1 p-6">

        <h2 class="text-2xl font-bold mb-4">
            Halo, <?php echo $_SESSION['nama']; ?> 👋
        </h2>

        <div class="bg-white p-6 rounded shadow">
            <p>Selamat datang di dashboard admin.</p>
            <p>Hari ini: <?php echo date("d F Y"); ?></p>
        </div>

    </main>

</div>

</body>
</html>
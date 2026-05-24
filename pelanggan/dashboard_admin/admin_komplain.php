<?php
session_start();
include('../koneksi.php'); // Sesuaikan path koneksi database Anda

// 🔐 Proteksi admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil data komplain dari database (Terbaru berada di paling atas)
$query = "SELECT * FROM komplain ORDER BY waktu_masuk DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Kedai Aishwa | Komplain Pelanggan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="font-sans text-gray-800 flex h-screen overflow-hidden bg-gray-100">

    <?php include('sidebar.php'); ?>

    <main class="flex-1 overflow-y-auto bg-orange-50 flex flex-col">
        
        <header class="bg-white/90 backdrop-blur-sm shadow-sm p-4 px-8 flex justify-between items-center sticky top-0 z-10 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-700">Manajemen Komplain</h2>
            <span class="text-sm text-gray-500 bg-gray-100 px-4 py-2 rounded-full font-medium">
                <i class="fa-regular fa-calendar me-2"></i><?php echo date("d F Y"); ?>
            </span>
        </header>

        <div class="p-8 flex-1">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-red-500/10 to-transparent flex justify-between items-center">
                    <div>
                        <h3 class="font-extrabold text-xl text-gray-800">Laporan Kendala Pesanan</h3>
                        <p class="text-sm text-gray-500 mt-1">Daftar keluhan atau masalah hidangan yang diajukan oleh pelanggan.</p>
                    </div>
                    <span class="bg-red-500 text-white text-xs font-bold px-3 py-1.5 rounded-full">
                        Total: <?php echo mysqli_num_rows($result); ?> Masalah
                    </span>
                </div>
                
                <div class="p-6">
                    <?php if (mysqli_num_rows($result) == 0): ?>
                        <div class="text-center py-16">
                            <div class="text-gray-300 text-6xl mb-4">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <h4 class="text-gray-400 text-lg font-medium">Luar biasa! Belum ada komplain atau kendala masuk dari pelanggan.</h4>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-200 text-sm font-bold text-gray-600 bg-gray-50/70">
                                        <th class="p-4 pl-6" width="15%">Bukti Foto</th>
                                        <th class="p-4" width="15%">ID Pesanan</th>
                                        <th class="p-4" width="20%">Pelanggan</th>
                                        <th class="p-4" width="35%">Deskripsi Masalah</th>
                                        <th class="p-4 pr-6" width="15%">Waktu Masuk</th>
                                    </tr>
                                </thead>
                                <body class="divide-y divide-gray-100 text-sm text-gray-700">
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr class="hover:bg-red-50/30 transition-colors">
                                            <td class="p-4 pl-6">
                                                <a href="../img/<?php echo htmlspecialchars($row['foto_bukti']); ?>" target="_blank" class="block w-16 h-16 group relative overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                                                    <img src="../img/<?php echo htmlspecialchars($row['foto_bukti']); ?>" alt="Bukti Komplain" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs transition">
                                                        <i class="fa-solid fa-magnifying-glass"></i>
                                                    </div>
                                                </a>
                                            </td>
                                            <td class="p-4 font-mono font-bold text-red-600">
                                                <?php echo htmlspecialchars($row['pesanan_id']); ?>
                                            </td>
                                            <td class="p-4">
                                                <div class="font-bold text-gray-900"><?php echo htmlspecialchars($row['nama_user']); ?></div>
                                                <div class="text-xs text-gray-400 mt-0.5"><?php echo htmlspecialchars($row['user_email']); ?></div>
                                            </td>
                                            <td class="p-4">
                                                <p class="text-gray-600 bg-gray-50 p-3 rounded-xl border border-gray-100 leading-relaxed">
                                                    <?php echo htmlspecialchars($row['deskripsi']); ?>
                                                </p>
                                            </td>
                                            <td class="p-4 pr-6 text-gray-500 font-medium">
                                                <div class="text-gray-800 text-xs">
                                                    <i class="fa-regular fa-clock me-1 text-gray-400"></i>
                                                    <?php echo date('d M Y', strtotime($row['waktu_masuk'])); ?>
                                                </div>
                                                <div class="text-[11px] text-gray-400 mt-0.5 ps-4">
                                                    <?php echo date('H:i', strtotime($row['waktu_masuk'])); ?> WIB
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </body>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
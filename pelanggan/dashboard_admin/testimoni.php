<?php
session_start();
require_once 'koneksi.php';

/Proteksi admin (Sesuaikan dengan variabel session login Anda)
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Ambil data testimoni dari database (Terbaru berada di paling atas)
$query = "SELECT * FROM testimoni ORDER BY waktu_masuk DESC";
$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Kedai Aishwa | Testimoni</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="font-sans text-gray-800 flex h-screen overflow-hidden">

    <!-- Memanggil Sidebar Component -->
    <?php include('sidebar.php'); ?>

    <!-- MAIN CONTENT AREA -->
    <main class="flex-1 overflow-y-auto bg-orange-50 flex flex-col">
        
        <!-- HEADER -->
        <header class="bg-white/90 backdrop-blur-sm shadow-sm p-4 px-8 flex justify-between items-center sticky top-0 z-10 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-700">Manajemen Testimoni</h2>
            <span class="text-sm text-gray-500 bg-gray-100 px-4 py-2 rounded-full font-medium">
                <i class="fa-regular fa-calendar me-2"></i><?php echo date("d F Y"); ?>
            </span>
        </header>

        <!-- KONTEN UTAMA -->
        <div class="p-8 flex-1">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-orange-500/10 to-transparent flex justify-between items-center">
                    <div>
                        <h3 class="font-extrabold text-xl text-gray-800">Ulasan & Rating Masuk</h3>
                        <p class="text-sm text-gray-500 mt-1">Daftar feedback makanan yang dikirim langsung oleh pelanggan melalui website.</p>
                    </div>
                    <span class="bg-orange-500 text-white text-xs font-bold px-3 py-1.5 rounded-full">
                        Total: <?php echo mysqli_num_rows($result); ?> Ulasan
                    </span>
                </div>
                
                <div class="p-6">
                    <?php if (mysqli_num_rows($result) == 0): ?>
                        <!-- Jika Database Kosong -->
                        <div class="text-center py-16">
                            <div class="text-gray-300 text-6xl mb-4">
                                <i class="fa-regular fa-comment-dots"></i>
                            </div>
                            <h4 class="text-gray-400 text-lg font-medium">Belum ada testimoni pelanggan di dalam database.</h4>
                        </div>
                    <?php else: ?>
                        <!-- Tabel Data Testimoni -->
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="w-full text-left border-collapse">
                                <head>
                                    <tr class="border-b border-gray-200 text-sm font-bold text-gray-600 bg-gray-50/70">
                                        <th class="p-4 pl-6" width="15%">Foto Produk</th>
                                        <th class="p-4" width="25%">Nama Produk</th>
                                        <th class="p-4" width="20%">Rating</th>
                                        <th class="p-4" width="25%">Ulasan / Deskripsi</th>
                                        <th class="p-4 pr-6" width="15%">Waktu Kirim</th>
                                    </tr>
                                </head>
                                <body class="divide-y divide-gray-100 text-sm text-gray-700">
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr class="hover:bg-orange-50/30 transition-colors">
                                            <!-- Kolom Foto Hidangan -->
                                            <td class="p-4 pl-6">
                                                <a href="<?php echo htmlspecialchars($row['foto']); ?>" target="_blank" class="block w-16 h-16 group relative overflow-hidden rounded-xl border border-gray-200 shadow-sm">
                                                    <img src="<?php echo htmlspecialchars($row['foto']); ?>" alt="Foto Hidangan" class="w-full h-full object-cover group-hover:scale-110 transition duration-300">
                                                    <div class="absolute inset-0 bg-black/20 opacity-0 group-hover:opacity-100 flex items-center justify-center text-white text-xs transition">
                                                        <i class="fa-solid fa-eye"></i>
                                                    </div>
                                                </a>
                                            </td>
                                            <!-- Kolom Nama Produk -->
                                            <td class="p-4 font-bold text-gray-900">
                                                <?php echo htmlspecialchars($row['nama_produk']); ?>
                                            </td>
                                            <!-- Kolom Rating Bintang -->
                                            <td class="p-4">
                                                <div class="text-amber-400 text-base flex gap-0.5">
                                                    <?php 
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        if ($i <= $row['rating']) {
                                                            echo '<i class="fa-solid fa-star"></i>';
                                                        } else {
                                                            echo '<i class="fa-solid fa-star text-gray-200"></i>';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <span class="text-xs text-gray-400 block mt-1">(Skor: <?php echo $row['rating']; ?> dari 5)</span>
                                            </td>
                                            <!-- Kolom Deskripsi -->
                                            <td class="p-4">
                                                <p class="text-gray-600 italic line-clamp-3 bg-gray-50 p-3 rounded-xl border border-gray-100">
                                                    "<?php echo htmlspecialchars($row['deskripsi']); ?>"
                                                </p>
                                            </td>
                                            <!-- Kolom Waktu -->
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
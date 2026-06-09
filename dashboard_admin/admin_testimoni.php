<?php
session_start(); //[cite: 7]
include('../koneksi.php'); //[cite: 7]

// 🔐 Proteksi admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') { //[cite: 7]
    // Jalur diperbaiki: Mundur 2 tingkat ke folder utama tempat login.php berada
    header("Location: ../../login.php?status=wajib_login");
    exit(); //[cite: 7]
}

// 🗑️ LOGIKA PROSES HAPUS TESTIMONI
// ... (Sisa kode ke bawah, HTML, dan query database semuanya tetap sama)

// 🗑️ LOGIKA PROSES HAPUS TESTIMONI
if (isset($_GET['hapus_id'])) {
    $id_hapus = (int)$_GET['hapus_id'];

    // 1. Ambil nama file foto testimoni sebelum datanya dihapus

    // 3. Hapus data testimoni dari database berdasarkan ID unik
    $query_delete = "DELETE FROM testimoni WHERE id = $id_hapus";
    if (mysqli_query($conn, $query_delete)) {
        $_SESSION['notif_sukses'] = "Testimoni berhasil dihapus secara permanen.";
    } else {
        $_SESSION['notif_gagal'] = "Gagal menghapus testimoni: " . mysqli_error($conn);
    }

    // Alihkan kembali agar parameter URL bersih
    header("Location: admin_testimoni.php");
    exit();
}

// Ambil data testimoni dari database (Terbaru berada di paling atas)
$query = "SELECT * FROM testimoni ORDER BY waktu_masuk DESC";
$result = mysqli_query($conn, $query);
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
<body class="font-sans text-gray-800 flex h-screen overflow-hidden bg-gray-100">

    <?php include('sidebar.php'); ?>

    <main class="flex-1 overflow-y-auto bg-orange-50 flex flex-col">
        
        <header class="bg-white/90 backdrop-blur-sm shadow-sm p-4 px-8 flex justify-between items-center sticky top-0 z-10 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-700">Manajemen Testimoni</h2>
            <span class="text-sm text-gray-500 bg-gray-100 px-4 py-2 rounded-full font-medium">
                <i class="fa-regular fa-calendar me-2"></i><?php echo date("d F Y"); ?>
            </span>
        </header>

        <div class="p-8 flex-1">
            
            <?php if (isset($_SESSION['notif_sukses'])): ?>
                <div class="mb-4 p-4 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 flex items-center justify-between shadow-sm">
                    <div><span class="font-bold">Berhasil!</span> <?php echo $_SESSION['notif_sukses']; ?></div>
                    <button onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 font-bold text-lg">×</button>
                </div>
                <?php unset($_SESSION['notif_sukses']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['notif_gagal'])): ?>
                <div class="mb-4 p-4 text-sm text-red-800 rounded-xl bg-red-50 border border-red-200 flex items-center justify-between shadow-sm">
                    <div><span class="font-bold">Gagal!</span> <?php echo $_SESSION['notif_gagal']; ?></div>
                    <button onclick="this.parentElement.remove()" class="text-red-600 hover:text-red-800 font-bold text-lg">×</button>
                </div>
                <?php unset($_SESSION['notif_gagal']); ?>
            <?php endif; ?>

            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-orange-500/10 to-transparent flex justify-between items-center">
                    <div>
                        <h3 class="font-extrabold text-xl text-gray-800">Ulasan Kepuasan Pelanggan</h3>
                        <p class="text-sm text-gray-500 mt-1">Daftar feedback, rating bintang, dan foto hidangan dari pembeli.</p>
                    </div>
                    <span class="bg-orange-600 text-white text-xs font-bold px-3 py-1.5 rounded-full">
                        Total: <?php echo mysqli_num_rows($result); ?> Ulasan
                    </span>
                </div>
                
                <div class="p-6">
                    <?php if (mysqli_num_rows($result) == 0): ?>
                        <div class="text-center py-16">
                            <div class="text-gray-300 text-6xl mb-4">
                                <i class="fa-solid fa-comment-slash"></i>
                            </div>
                            <h4 class="text-gray-400 text-lg font-medium">Belum ada testimoni masuk dari pelanggan.</h4>
                        </div>
                    <?php else: ?>
                        <div class="overflow-x-auto rounded-xl border border-gray-200">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="border-b border-gray-200 text-sm font-bold text-gray-600 bg-gray-50/70">
                                        <th class="p-4" width="18%">Produk Kuliner</th>
                                        <th class="p-4" width="15%">Rating</th>
                                        <th class="p-4" width="35%">Isi Ulasan/Deskripsi</th>
                                        <th class="p-4" width="12%">Waktu Masuk</th>
                                        <th class="p-4 pr-6 text-center" width="8%">Aksi</th>
                                    </tr>
                                </thead>
                                <body class="divide-y divide-gray-100 text-sm text-gray-700">
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr class="hover:bg-orange-50/20 transition-colors">
                                            <td class="p-4 font-bold text-gray-900">
                                                <?php echo htmlspecialchars($row['nama_produk']); ?>
                                            </td>
                                            <td class="p-4">
                                                <div class="text-amber-500 tracking-wider font-semibold">
                                                    <?php 
                                                    $rating = (int)$row['rating'];
                                                    for ($i = 1; $i <= 5; $i++) {
                                                        echo ($i <= $rating) ? '★' : '☆';
                                                    }
                                                    ?>
                                                </div>
                                            </td>
                                            <td class="p-4">
                                                <p class="text-gray-600 italic bg-gray-50 p-3 rounded-xl border border-gray-100 leading-relaxed">
                                                    "<?php echo htmlspecialchars($row['deskripsi']); ?>"
                                                </p>
                                            </td>
                                            <td class="p-4 text-gray-500 font-medium">
                                                <div class="text-gray-800 text-xs">
                                                    <i class="fa-regular fa-clock me-1 text-gray-400"></i>
                                                    <?php echo date('d M Y', strtotime($row['waktu_masuk'])); ?>
                                                </div>
                                                <div class="text-[11px] text-gray-400 mt-0.5 ps-4">
                                                    <?php echo date('H:i', strtotime($row['waktu_masuk'])); ?> WIB
                                                </div>
                                            </td>
                                            <td class="p-4 pr-6 text-center">
                                                <a href="admin_testimoni.php?hapus_id=<?php echo $row['id']; ?>" 
                                                   onclick="return confirm('Apakah Anda yakin ingin menghapus ulasan produk <?php echo htmlspecialchars($row['nama_produk']); ?> ini?')"
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition duration-200 shadow-sm">
                                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                                </a>
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
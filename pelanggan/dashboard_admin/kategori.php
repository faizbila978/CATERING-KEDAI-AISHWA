<?php
include "koneksi.php";
$query = mysqli_query($conn, "SELECT * FROM kategori ORDER BY nama_kategori ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Kategori - Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#fdf2e9] flex">
    <?php include('sidebar.php'); ?>

    <main class="flex-1 p-10 h-screen overflow-y-auto">
        <h2 class="text-3xl font-bold text-gray-800 mb-8">Manajemen Kategori</h2>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="bg-white p-6 rounded-2xl shadow-md border border-orange-100 h-fit">
                <h3 class="text-xl font-bold mb-4 text-orange-600">Tambah Kategori</h3>
                <form action="proses_kategori.php" method="POST">
                    <input type="hidden" name="aksi" value="tambah">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                        <input type="text" name="nama_kategori" required placeholder="Contoh: Snack"
                               class="w-full border-2 border-gray-100 rounded-xl p-3 focus:border-orange-500 outline-none">
                    </div>
                    <button type="submit" class="w-full bg-orange-600 text-white py-3 rounded-xl font-bold hover:bg-orange-700 transition">
                        Simpan Kategori
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white rounded-2xl shadow-md overflow-hidden border border-orange-100">
                <table class="w-full text-left">
                    <thead class="bg-orange-50">
                        <tr>
                            <th class="p-4 font-bold text-orange-800">No</th>
                            <th class="p-4 font-bold text-orange-800">Nama Kategori</th>
                            <th class="p-4 font-bold text-orange-800 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; while($row = mysqli_fetch_assoc($query)): ?>
                        <tr class="border-b border-gray-50 hover:bg-orange-50/30 transition">
                            <td class="p-4 text-gray-600"><?= $no++; ?></td>
                            <td class="p-4 font-semibold text-gray-800"><?= $row['nama_kategori']; ?></td>
                            <td class="p-4 text-center">
                                <a href="proses_kategori.php?aksi=hapus&id=<?= $row['kategori_id']; ?>" 
                                   onclick="return confirm('Yakin ingin menghapus? Menu yang menggunakan kategori ini akan menjadi NULL.')"
                                   class="bg-red-100 text-red-600 px-4 py-2 rounded-lg text-sm hover:bg-red-600 hover:text-white transition">
                                   <i class="fas fa-trash"></i> Hapus
                                </a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
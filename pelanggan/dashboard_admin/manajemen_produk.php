<?php
include "koneksi.php";

$menuUtama = mysqli_query($conn, "SELECT * FROM menu");
$tambahan = mysqli_query($conn, "SELECT * FROM tambahan");

function renderCardPHP($item, $theme) {
    // Penyesuaian aksen berdasarkan tipe menu
    $textClass = ($theme === 'orange') ? 'text-orange-600' : 'text-blue-600';
    $accentColor = ($theme === 'orange') ? 'bg-orange-100 text-orange-700' : 'bg-blue-100 text-blue-700';
    
    return '
        <!-- Card dengan Background #FFF7ED -->
        <div class="bg-[#FFF7ED] rounded-3xl overflow-hidden shadow-md border border-orange-100 flex flex-col hover:shadow-xl hover:-translate-y-2 transition-all duration-500 group">
            
            <!-- Bagian Gambar -->
            <div class="relative h-52 overflow-hidden">
                <div class="absolute top-3 left-3 bg-white/80 backdrop-blur-md text-gray-800 text-[10px] px-3 py-1 rounded-full font-bold z-10 shadow-sm">
                    ID: '.$item['id'].'
                </div>
                <img src="../img/'.$item['img'].'" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            </div>

            <!-- Konten Detail -->
            <div class="p-6 flex flex-col flex-1">
                <h4 class="font-black text-gray-800 text-xl mb-1">'.$item['n'].'</h4>
                
                <div class="mb-4">
                    <span class="text-2xl font-black '.$textClass.'">
                        Rp '.$item['p'].'
                    </span>
                </div>
                
                '.(isset($item['d']) ? '<p class="text-sm text-gray-600 line-clamp-2 mb-6 italic leading-relaxed border-l-2 border-orange-300 pl-3">'.$item['d'].'</p>' : '<div class="mb-6"></div>').'

                <!-- Tombol Aksi -->
                <div class="grid grid-cols-2 gap-3 mt-auto">
                    <a href="edit_produk.php?id='.$item['id'].'" 
                       class="flex items-center justify-center gap-2 bg-white text-gray-700 py-3 rounded-2xl text-xs font-bold hover:bg-orange-600 hover:text-white transition-all shadow-sm border border-orange-100">
                       <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="hapus_produk.php?id='.$item['id'].'" 
                       onclick="return confirm(\'Yakin hapus?\')"
                       class="flex items-center justify-center gap-2 bg-red-50 text-red-600 py-3 rounded-2xl text-xs font-bold hover:bg-red-600 hover:text-white transition-all border border-red-100">
                       <i class="fas fa-trash"></i> Hapus
                    </a>
                </div>
            </div>
        </div>';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Produk - Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-[#D1D5DB] flex min-h-screen overflow-hidden">
    <?php include('sidebar.php'); ?>

    <main class="flex-1 p-8 h-screen overflow-y-auto">
        <div class="flex justify-between items-center mb-8">
            <h2 class="text-2xl font-bold text-gray-800">
                <i class="fas fa-th-large text-orange-600 mr-2"></i> Manajemen Produk
            </h2>
            <div class="flex gap-3">
                <a href="tambah_produk_menu.php" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-xl shadow-md transition font-bold text-sm">
                    <i class="fas fa-plus mr-2"></i> Tambah Menu
                </a>
                <a href="tambah_produk_tambahan.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl shadow-md transition font-bold text-sm">
                    <i class="fas fa-plus mr-2"></i> menu tambahan
                </a>
            </div>
        </div>
        

        <h3 class="text-lg font-bold mb-4 text-orange-800 border-b-2 border-orange-200 pb-2">Menu Utama</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
            <?php 
            while($row = mysqli_fetch_assoc($menuUtama)){
                echo renderCardPHP([
                    "id" => $row['menu_id'], "n" => $row['nama_menu'],
                    "p" => number_format($row['harga_satuan'],0,',','.'),
                    "d" => $row['deskripsi'], "img" => $row['gambar']
                ], 'orange');
            }
            ?>
        </div>

        <h3 class="text-lg font-bold mb-4 text-blue-800 border-b-2 border-blue-200 pb-2">Menu Tambahan</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php 
            while($row = mysqli_fetch_assoc($tambahan)){
                echo renderCardPHP([
                    "id" => $row['tambahan_id'], "n" => $row['nama_tambahan'],
                    "p" => number_format($row['harga_satuan'],0,',','.'),
                    "img" => $row['gambar']
                ], 'blue');
            }
            ?>
        </div>
    </main>
</body>
</html>
<?php
include "koneksi.php";

// ambil data dari database
$menuUtama = mysqli_query($conn, "SELECT * FROM menu");
$tambahan = mysqli_query($conn, "SELECT * FROM tambahan");

// fungsi render card (DESAIN TETAP)
function renderCardPHP($item, $theme) {
    $textClass = ($theme === 'orange') ? 'text-orange-600' : 'text-blue-600';
    $borderClass = ($theme === 'orange') ? 'border-orange-100' : 'border-blue-100';
    $desc = isset($item['d']) ? '<p class="text-sm text-gray-500 line-clamp-3 mb-5 flex-1 leading-relaxed italic border-l-2 '.$borderClass.' pl-2">'.$item['d'].'</p>' : '<div class="mb-5"></div>';
    
    return '
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 flex flex-col hover:shadow-xl transition-all duration-300 group">
            <div class="relative h-48 overflow-hidden rounded-t-2xl">
                <div class="absolute top-2 left-2 bg-black/70 text-white text-[10px] px-2 py-1 rounded font-mono z-10">'.$item['id'].'</div>
               <img src="../img/'.$item['img'].'" class="w-full h-full object-cover">
            </div>
            <div class="p-5 flex flex-col flex-1">
                <h4 class="font-bold text-gray-800 text-lg mb-1">'.$item['n'].'</h4>
                <div class="'.$textClass.' font-bold text-xl mb-2">Rp '.$item['p'].'</div>
                '.$desc.'
                <div class="grid grid-cols-2 gap-2 mt-auto">
                    <a href="edit_produk.php?id='.$item['id'].'" 
                       class="bg-blue-100 text-blue-600 py-2 rounded-xl text-xs font-bold text-center">
                       <i class="fas fa-edit"></i> Edit
                    </a>
                    <a href="hapus_produk.php?id='.$item['id'].'" 
                       onclick="return confirm(\'Yakin hapus?\')"
                       class="bg-red-100 text-red-600 py-2 rounded-xl text-xs font-bold text-center">
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
    <title>Manajemen Produk</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-orange-50 flex">

<?php include('sidebar.php'); ?>

<main class="flex-1 p-8">

    <div class="flex justify-between mb-6">
        <h2 class="text-2xl font-bold">Manajemen Produk</h2>
        <div class="flex gap-3">
            <a href="tambah_produk_menu.php" class="bg-green-600 text-white px-4 py-2 rounded">Tambah Menu</a>
            <a href="tambah_produk_tambahan.php" class="bg-blue-600 text-white px-4 py-2 rounded">Tambah Tambahan</a>
        </div>
    </div>

    <!-- MENU UTAMA -->
    <h3 class="text-xl font-bold mb-4">Menu Utama</h3>
    <div class="grid grid-cols-4 gap-6 mb-10">
        <?php 
        while($row = mysqli_fetch_assoc($menuUtama)){
            echo renderCardPHP([
                "id" => $row['menu_id'],
                "n" => $row['nama_menu'],
                "p" => number_format($row['harga_satuan'],0,',','.'),
                "d" => $row['deskripsi'],
                "img" => $row['gambar']
            ], 'orange');
        }
        ?>
    </div>

    <!-- TAMBAHAN -->
    <h3 class="text-xl font-bold mb-4">Menu Tambahan</h3>
    <div class="grid grid-cols-4 gap-6">
        <?php 
        while($row = mysqli_fetch_assoc($tambahan)){
            echo renderCardPHP([
                "id" => $row['tambahan_id'],
                "n" => $row['nama_tambahan'],
                "p" => number_format($row['harga_satuan'],0,',','.'),
                "img" => $row['gambar']
            ], 'blue');
        }
        ?>
    </div>

</main>
</body>
</html>
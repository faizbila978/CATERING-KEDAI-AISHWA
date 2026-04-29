<?php
// 1. DATA PRODUK (Dalam PHP Array - Nantinya bisa diambil dari Database)
$menuUtama = [
    ["id" => "MNU-101", "n" => "Paket Ayam Goreng", "p" => "20.000", "d" => "Nasi, ayam (dada/paha), sambel, lalapan, aqua gelas.", "img" => "WhatsApp Image 2026-02-21 at 21.48.58.jpeg"],
    ["id" => "MNU-102", "n" => "Paket Ayam Bakar", "p" => "20.000", "d" => "Nasi, ayam (dada/paha), sambel, lalapan, aqua gelas.", "img" => "WhatsApp Image 2026-02-21 at 22.03.08.jpeg"],
    ["id" => "MNU-103", "n" => "Paket Ayam Geprek", "p" => "20.000", "d" => "Nasi, ayam (dada/paha), mie goreng jawa, sambel, lalapan, aqua gelas.", "img" => "WhatsApp Image 2026-02-21 at 22.07.53.jpeg"],
    ["id" => "MNU-104", "n" => "Rice Bowl", "p" => "10.000", "d" => "Nasi, ayam krispi, sayur/kentang, sambal (geprek, matah, tomat).", "img" => "WhatsApp Image 2026-02-16 at 22.03.25.jpeg"],
    ["id" => "MNU-105", "n" => "Tumpeng Nusantara", "p" => "200.000", "d" => "Nasi kuning, telor balado, mie goreng jawa, orek, urap, sambal.", "img" => "WhatsApp_Image_2026-02-11_at_08.53.28-removebg-preview.png"],
    ["id" => "MNU-106", "n" => "Tumpeng Nusantara Spesial", "p" => "250.000", "d" => "Nasi kuning, ayam bakar/goreng, orek, mie goreng jawa, urap, sambal.", "img" => "tumpeng.png"],
    ["id" => "MNU-107", "n" => "Tumpeng Nusantara Premium", "p" => "300.000", "d" => "Nasi kuning, ayam bakar/goreng, telor balado, mie goreng jawa, orek, urap, sambal.", "img" => "Gemini_Generated_Image_vgy9v9vgy9v9vgy9.png"]
];

$tambahan = [
    ["id" => "TMB-201", "n" => "Tempe Tahu", "p" => "2.000", "img" => "Tempe tahu goreng.jpeg"], 
    ["id" => "TMB-202", "n" => "Puding", "p" => "2.000", "img" => "puding.jpeg"],
    ["id" => "TMB-203", "n" => "Putu Ayu", "p" => "2.000", "img" => "Resep Putu Ayu Enak Dan Lembut Ala Ncc oleh BunnaBintang.jpeg"], 
    ["id" => "TMB-204", "n" => "Bolu Kukus", "p" => "2.000", "img" => "WhatsApp Image 2026-03-06 at 13.55.39.jpeg"],
    ["id" => "TMB-205", "n" => "Pisang", "p" => "2.000", "img" => "Banana Bread Muffin Tops – Oh She Glows.jpeg"], 
    ["id" => "TMB-206", "n" => "Jeruk", "p" => "2.000", "img" => "Buah buahan.jpeg"],
    ["id" => "TMB-207", "n" => "Rengginang", "p" => "1.000", "img" => "download (8).jpeg"], 
    ["id" => "TMB-208", "n" => "Bolu Jadul", "p" => "40.000", "img" => "WhatsApp Image 2026-02-21 at 21.47.17.jpeg"],
    ["id" => "TMB-209", "n" => "Bolu Meses Keju", "p" => "50.000", "img" => "WhatsApp Image 2026-02-21 at 21.45.35.jpeg"], 
    ["id" => "TMB-210", "n" => "Brownies Keju", "p" => "35.000", "img" => "WhatsApp Image 2026-02-21 at 21.43.57.jpeg"]
];

// Fungsi Helper untuk Render Kartu (Pengganti createCard di JS)
function renderCardPHP($item, $theme) {
    $textClass = ($theme === 'orange') ? 'text-orange-600' : 'text-blue-600';
    $borderClass = ($theme === 'orange') ? 'border-orange-100' : 'border-blue-100';
    $desc = isset($item['d']) ? '<p class="text-sm text-gray-500 line-clamp-3 mb-5 flex-1 leading-relaxed italic border-l-2 '.$borderClass.' pl-2">'.$item['d'].'</p>' : '<div class="mb-5"></div>';
    
    return '
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 flex flex-col hover:shadow-xl transition-all duration-300 group">
            <div class="relative h-48 overflow-hidden rounded-t-2xl">
                <div class="absolute top-2 left-2 bg-black/70 text-white text-[10px] px-2 py-1 rounded font-mono z-10">'.$item['id'].'</div>
                <img src="'.$item['img'].'" alt="'.$item['n'].'" onerror="this.src=\'https://via.placeholder.com/500x500?text=Foto+Produk\'" class="w-full h-full object-cover">
            </div>
            <div class="p-5 flex flex-col flex-1">
                <h4 class="font-bold text-gray-800 text-lg leading-tight mb-1 truncate">'.$item['n'].'</h4>
                <div class="'.$textClass.' font-bold text-xl mb-2">Rp '.$item['p'].'</div>
                '.$desc.'
                <div class="grid grid-cols-2 gap-2 mt-auto">
                    <button onclick="editProduk(\''.$item['id'].'\')" class="bg-blue-100 text-blue-600 py-2 rounded-xl text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-all">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                    <button onclick="hapusProduk(\''.$item['id'].'\')" class="bg-red-100 text-red-600 py-2 rounded-xl text-xs font-bold border border-red-200 hover:bg-red-600 hover:text-white transition-all">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>';
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Produk - Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="manajeme produk.css">
</head>
<body class="bg-orange-50 font-sans text-gray-800 flex">

    <!-- Memanggil Sidebar -->
    <?php include('sidebar.php'); ?>

    <main class="flex-1 flex flex-col min-w-0 h-screen overflow-hidden">
        <header class="bg-white shadow-sm p-4 flex flex-col sm:flex-row justify-between items-center gap-4 z-10">
            <h2 class="text-2xl font-bold text-gray-700 flex items-center">
                <i class="fas fa-boxes text-orange-600 mr-3"></i> Manajemen Produk
            </h2>
            <div class="flex flex-wrap justify-center gap-3">
                <a href="tambah_produk_tambahan.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow-md transition">
                    <i class="fas fa-plus-circle mr-2"></i> Tambah Produk Tambahan
                </a>
                <a href="tambah_produk_menu.php" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded shadow-md transition">
                    <i class="fas fa-plus mr-2"></i> Tambah Produk Menu
                </a>
            </div>
        </header>

        <div class="p-4 sm:p-8 overflow-y-auto">
            <!-- Section Menu Utama -->
            <div class="mb-12">
                <div class="flex items-center justify-between mb-6 border-b-2 border-orange-200 pb-2">
                    <h3 class="text-xl font-extrabold text-orange-800 uppercase tracking-tight">
                        <i class="fas fa-concierge-bell mr-2"></i> Menu Utama Paket
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php 
                    foreach($menuUtama as $item) {
                        echo renderCardPHP($item, 'orange');
                    }
                    ?>
                </div>
            </div>

            <!-- Section Menu Tambahan -->
            <div class="mt-8">
                <div class="flex items-center justify-between mb-6 border-b-2 border-blue-200 pb-2">
                    <h3 class="text-xl font-extrabold text-blue-800 uppercase tracking-tight">
                        <i class="fas fa-cookie-bite mr-2"></i> Menu Tambahan
                    </h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php 
                    foreach($tambahan as $item) {
                        echo renderCardPHP($item, 'blue');
                    }
                    ?>
                </div>
            </div>
        </div>
    </main>

    <!-- Script Tetap Digunakan untuk Interaksi Edit/Hapus -->
    <script>
        function editProduk(id) {
            alert("Fungsi Edit untuk ID: " + id + " (Arahkan ke form edit PHP)");
            // window.location.href = 'edit_produk.php?id=' + id;
        }

        function hapusProduk(id) {
            if(confirm("Apakah Anda yakin ingin menghapus produk " + id + "?")) {
                alert("Proses hapus via PHP dijalankan.");
            }
        }
    </script>
</body>
</html>
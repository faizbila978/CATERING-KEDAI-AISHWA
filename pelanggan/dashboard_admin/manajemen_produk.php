<?php
session_start(); // Wajib dijalankan di awal untuk membaca data session yang aktif
include "koneksi.php";

// Proteksi Halaman: Jika belum login atau role-nya bukan admin, lempar kembali ke login.php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    // Mundur 2 tingkat keluar dari folder (/pelanggan/dashboard_admin/) menuju letak file login.php
    header("Location: login.php?status=wajib_login");
    exit();
}

// 1. Logika Pencarian
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
// ... (Sisa kode ke bawah seperti query database, renderCardPHP, dan HTML di bawahnya tetap sama)

// Query Menu Utama
$queryMenu = "SELECT * FROM menu";
if ($search != '') { $queryMenu .= " WHERE nama_menu LIKE '%$search%'"; }
$menuUtama = mysqli_query($conn, $queryMenu);

// Query Menu Tambahan
$queryTambahan = "SELECT * FROM tambahan";
if ($search != '') { $queryTambahan .= " WHERE nama_tambahan LIKE '%$search%'"; }
$tambahan = mysqli_query($conn, $queryTambahan);

// Function Card
function renderCardPHP($item, $theme) {
    $textClass = ($theme === 'orange') ? 'text-orange-600' : 'text-blue-600';
    $bgCard = ($theme === 'orange') ? 'bg-[#FFF7ED]' : 'bg-[#F0F7FF]';
    return '
        <div class="'.$bgCard.' rounded-3xl overflow-hidden shadow-md border border-gray-100 flex flex-col hover:shadow-xl hover:-translate-y-2 transition-all duration-500 group">
            <div class="relative h-52 overflow-hidden">
                <div class="absolute top-3 left-3 bg-white/80 backdrop-blur-md text-gray-800 text-[10px] px-3 py-1 rounded-full font-bold z-10 shadow-sm">ID: '.$item['id'].'</div>
                <img src="../img/'.$item['img'].'" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
            </div>
            <div class="p-6 flex flex-col flex-1">
                <h4 class="font-black text-gray-800 text-xl mb-1">'.$item['n'].'</h4>
                <div class="mb-4"><span class="text-2xl font-black '.$textClass.'">Rp '.$item['p'].'</span></div>
                <div class="grid grid-cols-2 gap-3 mt-auto">
                    <a href="edit_produk.php?id='.$item['id'].'" class="flex items-center justify-center gap-2 bg-white text-gray-700 py-3 rounded-2xl text-xs font-bold hover:bg-orange-600 hover:text-white transition-all shadow-sm border border-gray-100"><i class="fas fa-edit"></i> Edit</a>
                    <a href="hapus_produk.php?id='.$item['id'].'" onclick="return confirm(\'Yakin hapus?\')" class="flex items-center justify-center gap-2 bg-red-50 text-red-600 py-3 rounded-2xl text-xs font-bold hover:bg-red-600 hover:text-white transition-all border border-red-100"><i class="fas fa-trash"></i> Hapus</a>
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
<body class="bg-[#F3F4F6] flex min-h-screen overflow-hidden">

    <?php include('sidebar.php'); ?>

    <main class="flex-1 flex flex-col h-screen overflow-hidden">
        
        <header class="bg-white shadow-sm border-b border-gray-200 p-6 flex justify-between items-center relative">
            <h1 class="absolute left-6 bottom-0 text-6xl font-black text-gray-100/50 pointer-events-none uppercase tracking-tighter select-none">PRODUK</h1>
            
            <div class="relative z-10 flex items-center">
                <div class="bg-pink-100 w-12 h-12 rounded-xl flex items-center justify-center mr-4 shadow-sm">
                    <i class="fas fa-shopping-cart text-pink-600 text-xl"></i>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 leading-none">Manajemen Produk</h2>
                    <p class="text-gray-500 text-sm mt-1 font-medium">Kelola daftar menu utama dan tambahan</p>
                </div>
            </div>

            <div class="flex gap-3 relative z-10">
                <a href="tambah_produk_menu.php" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-xl shadow-md transition font-bold text-sm flex items-center">
                    <i class="fas fa-plus mr-2"></i> Tambah Menu
                </a>
                <a href="tambah_produk_tambahan.php" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-xl shadow-md transition font-bold text-sm flex items-center">
                    <i class="fas fa-plus-circle mr-2"></i> Menu Tambahan
                </a>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-8">
            
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-gray-200 mb-10">
                <form method="GET" action="manajemen_produk.php" class="flex items-end gap-4">
                    <div class="flex-1">
                        <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-2 ml-1">Cari Nama Menu</label>
                        <div class="relative">
                            <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="search" id="searchInput" value="<?= htmlspecialchars($search) ?>" placeholder="Contoh: Ayam Bakar..." 
                                   class="w-full pl-12 pr-4 py-3.5 bg-gray-50 border border-gray-200 rounded-2xl focus:outline-none focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 transition-all text-sm">
                        </div>
                    </div>
                    
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3.5 rounded-2xl font-bold text-sm hover:bg-blue-700 transition shadow-lg shadow-blue-100">
                        <i class="fas fa-filter mr-2"></i> Tampilkan
                    </button>

                    <a href="manajemen_produk.php" class="bg-gray-100 text-gray-600 px-8 py-3.5 rounded-2xl font-bold text-sm hover:bg-gray-200 transition text-center border border-gray-200">
                        Reset
                    </a>
                </form>
            </div>

            <div class="flex items-center gap-3 mb-6">
                <div class="w-1.5 h-6 bg-orange-500 rounded-full"></div>
                <h3 class="text-xl font-bold text-gray-800">Menu Utama</h3>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
                <?php 
                if(mysqli_num_rows($menuUtama) > 0) {
                    while($row = mysqli_fetch_assoc($menuUtama)){
                        echo renderCardPHP(["id" => $row['menu_id'], "n" => $row['nama_menu'], "p" => number_format($row['harga_satuan'],0,',','.'), "img" => $row['gambar'], "d" => $row['deskripsi']], 'orange');
                    }
                } else {
                    echo '<p class="col-span-full text-gray-400 italic">Menu utama tidak ditemukan.</p>';
                }
                ?>
            </div>         
        </div>
    </main>

</body>
</html>
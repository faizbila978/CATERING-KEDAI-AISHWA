<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Memastikan session aktif
}

// 🔐 Proteksi Halaman: Jika belum login atau bukan admin, balikkan ke login.php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    // Mundur 2 tingkat keluar dari subfolder dashboard menuju letak file login.php
    header("Location: ../../login.php?status=wajib_login");
    exit();
}

include "../koneksi.php"; // Sesuaikan path koneksi database Anda[cite: 10]

$notif = ""; //[cite: 10]

// 1. PROSES UPDATE KETIKA FORM DISUBMIT
// 1. PROSES UPDATE KETIKA FORM DISUBMIT
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $hero_tagline = mysqli_real_escape_string($conn, $_POST['hero_tagline']);
    $hero_judul = mysqli_real_escape_string($conn, $_POST['hero_judul']);
    $hero_deskripsi = mysqli_real_escape_string($conn, $_POST['hero_deskripsi']);
    $no_wa = mysqli_real_escape_string($conn, $_POST['no_wa']);
    $akun_fb = mysqli_real_escape_string($conn, $_POST['akun_fb']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);

    // Mulai Transaksi Database agar data aman
    mysqli_begin_transaction($conn);

    // Reset dulu semua menu rekomendasi menjadi 0
    mysqli_query($conn, "UPDATE menu SET is_rekomendasi = 0");

    // Jika admin memilih menu rekomendasi, update menu yang dipilih menjadi 1
    if (isset($_POST['rekomendasi_menu']) && is_array($_POST['rekomendasi_menu'])) {
        foreach ($_POST['rekomendasi_menu'] as $menu_id) {
            $menu_id = (int)$menu_id;
            mysqli_query($conn, "UPDATE menu SET is_rekomendasi = 1 WHERE menu_id = $menu_id");
        }
    }

    // Update pengaturan teks website biasa
    $query_update = "UPDATE pengaturan_web SET 
                    hero_tagline = '$hero_tagline', 
                    hero_judul = '$hero_judul', 
                    hero_deskripsi = '$hero_deskripsi', 
                    no_wa = '$no_wa', 
                    akun_fb = '$akun_fb', 
                    alamat = '$alamat' 
                    WHERE id = 1";

    if (mysqli_query($conn, $query_update)) {
        mysqli_commit($conn);
        $notif = "<div class='p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50' role='alert'><span class='font-bold'>Berhasil!</span> Perubahan halaman utama dan menu rekomendasi telah disimpan.</div>";
    } else {
        mysqli_rollback($conn);
        $notif = "<div class='p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-50' role='alert'><span class='font-bold'>Gagal!</span> Terjadi kesalahan: " . mysqli_error($conn) . "</div>";
    }
}

// 2. AMBIL DATA TERBARU DARI DATABASE UNTUK DITAMPILKAN DI FORM
$query_tampil = mysqli_query($conn, "SELECT * FROM pengaturan_web WHERE id = 1");
$data = mysqli_fetch_assoc($query_tampil);

// Jika data kosong/belum ada baris pertama di DB, buat default array agar tidak error
if (!$data) {
    $data = [
        'hero_tagline' => '', 'hero_judul' => '', 'hero_deskripsi' => '',
        'no_wa' => '', 'akun_fb' => '', 'alamat' => ''
    ];
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Website | Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans flex">

    <?php include "sidebar.php"; ?>

    <main class="flex-1 p-8 h-screen overflow-y-auto">
        <div class="max-w-4xl mx-auto">
            
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Pengaturan Halaman Utama</h1>
                <p class="text-sm text-gray-500">Kelola teks informasi dan tampilan landing page depan pembeli di sini.</p>
            </div>

            <?php echo $notif; ?>

            <div class="bg-white rounded-xl shadow-md p-6 border border-gray-200">
                <form action="" method="POST" class="space-y-6">
                    
                    <div>
                        <h2 class="text-lg font-semibold text-orange-700 border-b pb-2 mb-4">
                            <i class="fas fa-bullhorn mr-2"></i>Bagian Hero (Atas)
                        </h2>
                        
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tagline Atas (Kecil)</label>
                                <input type="text" name="hero_tagline" required value="<?php echo htmlspecialchars($data['hero_tagline']); ?>" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Judul Utama (Besar)</label>
                                <input type="text" name="hero_judul" required value="<?php echo htmlspecialchars($data['hero_judul']); ?>" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm">
                                <p class="text-[11px] text-gray-400 mt-1">*Tips: Teks "Sentuhan Modern." otomatis akan diberi warna merah muda (pink) di halaman depan.</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Singkat</label>
                                <textarea name="hero_deskripsi" rows="3" required
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm"><?php echo htmlspecialchars($data['hero_deskripsi']); ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <h2 class="text-lg font-semibold text-orange-700 border-b pb-2 mb-4">
                            <i class="fas fa-address-book mr-2"></i>Informasi Kontak & Footer
                        </h2>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nomor WhatsApp</label>
                                <input type="text" name="no_wa" required value="<?php echo htmlspecialchars($data['no_wa']); ?>" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm" placeholder="Contoh: 0895323107636">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Akun Facebook</label>
                                <input type="text" name="akun_fb" required value="<?php echo htmlspecialchars($data['akun_fb']); ?>" 
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm" placeholder="Contoh: FB: Azwan Coker">
                            </div>
                            
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap Toko</label>
                                <textarea name="alamat" rows="2" required
                                          class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500 text-sm"><?php echo htmlspecialchars($data['alamat']); ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="pt-4">
                        <h2 class="text-lg font-semibold text-orange-700 border-b pb-2 mb-4">
                            <i class="fas fa-utensils mr-2"></i>Pilih 3 Menu Rekomendasi Utama
                        </h2>
                        <p class="text-xs text-gray-500 mb-4">*Centang maksimal 3 menu kuliner terbaik Anda untuk ditampilkan di halaman utama depan pembeli.</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <?php 
                            // Ambil seluruh daftar menu dari database untuk ditampilkan sebagai pilihan
                            $query_all_menu = mysqli_query($conn, "SELECT menu_id, nama_menu, harga_satuan, is_rekomendasi FROM menu ORDER BY nama_menu ASC");
                            while ($menu_row = mysqli_fetch_assoc($query_all_menu)):
                                $checked = ($menu_row['is_rekomendasi'] == 1) ? 'checked' : '';
                            ?>
                            <label class="flex items-start p-3 bg-gray-50 rounded-lg border border-gray-200 cursor-pointer hover:bg-orange-50 transition">
                                <input type="checkbox" name="rekomendasi_menu[]" value="<?php echo $menu_row['menu_id']; ?>" <?php echo $checked; ?>
                                       class="menu-checkbox mt-1 h-4 w-4 text-orange-600 border-gray-300 rounded focus:ring-orange-500">
                                <div class="ml-3">
                                    <span class="block text-sm font-semibold text-gray-800"><?php echo htmlspecialchars($menu_row['nama_menu']); ?></span>
                                    <span class="block text-xs text-gray-500">Rp <?php echo number_format($menu_row['harga_satuan'], 0, ',', '.'); ?></span>
                                </div>
                            </label>
                            <?php endwhile; ?>
                        </div>
                    </div>

                    <script>
                        const checkboxes = document.querySelectorAll('.menu-checkbox');
                        checkboxes.forEach(checkbox => {
                            checkbox.addEventListener('change', function() {
                                const checkedCount = document.querySelectorAll('.menu-checkbox:checked').length;
                                if (checkedCount > 3) {
                                    alert('Maksimal menu rekomendasi yang boleh dipilih adalah 3 menu!');
                                    this.checked = false; // Batalkan centang ke-4
                                }
                            });
                        });
                    </script>

                    <div class="pt-4 flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-md shadow transition duration-200 text-sm">
                            <i class="fas fa-save mr-2"></i> Simpan Perubahan
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </main>

</body>
</html>
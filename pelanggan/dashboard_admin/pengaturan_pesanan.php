<?php
session_start(); // Wajib ditambahkan di awal agar data login session terbaca[cite: 1, 2]

// Silakan sesuaikan nama file koneksi database Anda (misal: koneksi.php atau config.php)
include 'koneksi.php';

// 🔐 Proteksi Halaman: Jika belum login atau bukan admin, balikkan ke login.php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    // Menggunakan "../../" untuk mundur 2 tingkat keluar dari subfolder dashboard admin
    header("Location: ../../login.php?status=wajib_login");
    exit();
}

$pesan = "";
$status_pesan = "";

// Cek apakah tabel pengaturan_sistem sudah ada, jika belum buat otomatis
// ... (Sisa kode proses UPDATE database dan struktur HTML di bawahnya tetap sama) ...

// Cek apakah tabel pengaturan_sistem sudah ada, jika belum buat otomatis (bisa dihapus jika tabel sudah dibuat manual)
$conn->query("CREATE TABLE IF NOT EXISTS pengaturan_sistem (
    id INT PRIMARY KEY AUTO_INCREMENT,
    minimal_porsi INT DEFAULT 500,
    batasan_hari INT DEFAULT 2
)");

// Cek apakah sudah ada data awal (seed data)
$cek_data = $conn->query("SELECT * FROM pengaturan_sistem WHERE id = 1");
if ($cek_data->num_rows == 0) {
    $conn->query("INSERT INTO pengaturan_sistem (id, minimal_porsi, batasan_hari) VALUES (1, 500, 2)");
}

// 2. PROSES UPDATE KETIKA TOMBOL SIMPAN DIKLIK
if (isset($_POST['simpan_pengaturan'])) {
    $minimal_porsi = intval($_POST['minimal_porsi']);
    $batasan_hari = intval($_POST['batasan_hari']);

    $stmt = $conn->prepare("UPDATE pengaturan_sistem SET minimal_porsi = ?, batasan_hari = ? WHERE id = 1");
    $stmt->bind_param("ii", $minimal_porsi, $batasan_hari);

    if ($stmt->execute()) {
        $pesan = "Pengaturan batasan berhasil diperbarui!";
        $status_pesan = "sukses";
    } else {
        $pesan = "Gagal memperbarui pengaturan.";
        $status_pesan = "gagal";
    }
    $stmt->close();
}

// 3. AMBIL DATA TERBARU UNTUK DITAMPILKAN DI FORM
$ambil_data = $conn->query("SELECT * FROM pengaturan_sistem WHERE id = 1");
$data = $ambil_data->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Batasan Pesanan - Admin Kedai Aishwa</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gray-100 font-sans flex text-gray-800">

    <?php include 'sidebar.php'; ?>

    <main class="flex-1 p-8 h-screen overflow-y-auto">
        <div class="mb-8 border-b border-gray-200 pb-4">
            <h2 class="text-3xl font-bold text-gray-800 tracking-tight flex items-center">
                <i class="fas fa-sliders text-orange-600 mr-3"></i> Pengaturan Batasan Sistem
            </h2>
            <p class="text-sm text-gray-500 mt-1">Kelola batasan minimal porsi pesanan dan batas waktu (H-*) pemesanan pelanggan secara dinamis.</p>
        </div>

        <?php if ($pesan != ""): ?>
            <div class="mb-6 p-4 rounded-lg flex items-center shadow-md transition-all duration-300 <?php echo $status_pesan == 'sukses' ? 'bg-emerald-100 text-emerald-800 border-l-4 border-emerald-500' : 'bg-rose-100 text-rose-800 border-l-4 border-rose-500'; ?>">
                <i class="fas <?php echo $status_pesan == 'sukses' ? 'fa-circle-check' : 'fa-circle-xmark'; ?> mr-3 text-xl"></i>
                <span class="font-medium"><?php echo $pesan; ?></span>
            </div>
        <?php endif; ?>

        <div class="max-w-2xl bg-white rounded-xl shadow-md overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-orange-600 to-orange-700 px-6 py-4 text-white">
                <h3 class="font-semibold text-lg flex items-center">
                    <i class="fas fa-cog mr-2 animate-spin-slow"></i> Parameter Batasan Pesanan
                </h3>
            </div>
            
            <form action="" method="POST" class="p-6 space-y-6">
                <div>
                    <label for="minimal_porsi" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-utensils text-orange-500 mr-2 w-4 text-center"></i> Batasan Minimal Porsi
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="number" name="minimal_porsi" id="minimal_porsi" required min="1"
                               value="<?php echo $data['minimal_porsi']; ?>" 
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white transition duration-200">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400 font-medium text-sm">
                            Porsi
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5">Pelanggan tidak akan bisa melakukan checkout jika jumlah pesanan di sudah lebi dari ini.</p>
                </div>

                <div>
                    <label for="batasan_hari" class="block text-sm font-semibold text-gray-700 mb-2 flex items-center">
                        <i class="fas fa-calendar-day text-orange-500 mr-2 w-4 text-center"></i> Batasan Waktu Pemesanan (H-*)
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="number" name="batasan_hari" id="batasan_hari" required min="0"
                               value="<?php echo $data['batasan_hari']; ?>" 
                               class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-orange-500 focus:bg-white transition duration-200">
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none text-gray-400 font-medium text-sm">
                            Hari Sebelum Acara
                        </div>
                    </div>
                    <p class="text-xs text-gray-400 mt-1.5">Contoh: Jika diisi <b>2</b> (H-2), maka jika hari ini tanggal 26, tanggal 26 dan 27 akan terkunci. Pelanggan baru bisa memilih tanggal 28 ke atas.</p>
                </div>

                <div class="pt-4 border-t border-gray-100 flex justify-end">
                    <button type="submit" name="simpan_pengaturan" 
                            class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-bold rounded-lg shadow-md hover:shadow-lg transition duration-200 flex items-center cursor-pointer">
                        <i class="fas fa-save mr-2"></i> Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </main>

</body>
</html>
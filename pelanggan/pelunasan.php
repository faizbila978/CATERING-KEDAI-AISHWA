<?php 
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Mengambil ID pesanan dari parameter URL (GET)
if (!isset($_GET['id'])) {
    header('Location: riwayat_pesanan.php');
    exit();
}

$pesanan_id = mysqli_real_escape_string($conn, $_GET['id']);

// Ambil data rincian pembayaran katering
$query = "SELECT p.total_pesan, pb.jumlah_dp, pb.status_dp, pb.status_pembayaran 
          FROM pesanan p 
          LEFT JOIN pembayaran pb ON p.pesanan_id = pb.pesanan_id 
          WHERE p.pesanan_id = '$pesanan_id'";
$result = mysqli_query($conn, $query);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    echo "Pesanan tidak ditemukan.";
    exit();
}

$total_pesanan = $data['total_pesan'];
$jumlah_dp = $data['jumlah_dp'];
$sisa_pembayaran = $total_pesanan - $jumlah_dp;

// Validasi jika ternyata sudah lunas
if ($data['status_pembayaran'] == 'Selesai') {
    header('Location: riwayat_pesanan.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelunasan Pesanan - Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50 text-gray-800">

<div class="container mx-auto px-4 py-8 max-w-2xl">
    <a href="riwayat_pesanan.php" class="text-gray-600 hover:text-gray-800 mb-6 inline-flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Riwayat Pesanan
    </a>

    <div class="bg-white rounded-xl shadow-md p-6 border border-gray-100">
        <h2 class="text-2xl font-bold mb-2">Pelunasan Sisa Pembayaran</h2>
        <p class="text-sm text-gray-500 mb-6">Selesaikan pembayaran sisa untuk pesanan Anda #<?php echo $pesanan_id; ?></p>

        <div class="bg-gray-50 rounded-lg p-4 mb-6 space-y-3">
            <div class="flex justify-between text-sm">
                <span class="text-gray-600">Total Kontrak Katering</span>
                <span class="font-medium">Rp <?php echo number_format($total_pesanan, 0, ',', '.'); ?></span>
            </div>
            <div class="flex justify-between text-sm text-green-600">
                <span>DP yang Sudah Dibayar (50%)</span>
                <span class="font-medium">- Rp <?php echo number_format($jumlah_dp, 0, ',', '.'); ?></span>
            </div>
            <div class="border-t border-gray-200 pt-3 flex justify-between">
                <span class="font-bold text-gray-700">Sisa Yang Harus Dilunasi</span>
                <span class="font-bold text-xl text-red-600">Rp <?php echo number_format($sisa_pembayaran, 0, ',', '.'); ?></span>
            </div>
        </div>

        <div class="mb-6 p-4 border border-blue-100 bg-blue-50 rounded-lg text-sm text-blue-900">
            <h4 class="font-bold mb-1"><i class="fas fa-university mr-2"></i> Rekening Tujuan Transfer Kedai Aishwa:</h4>
            <p><strong>Bank BCA:</strong> 123-4567-890 a/n Kedai Aishwa</p>
            <p><strong>GoPay / OVO / DANA:</strong> 0895323107636</p>
        </div>

        <form action="proses_pelunasan.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="pesanan_id" value="<?php echo $pesanan_id; ?>">
            
            <div class="mb-5">
                <label class="block font-bold text-sm mb-2 text-gray-700">Pilih Metode Transaksi Pelunasan</label>
                <select name="metode_pelunasan" class="w-full border border-gray-300 p-3 rounded-lg focus:outline-none focus:ring-2 focus:ring-pink-500" required>
                    <option value="gopay">GoPay</option>
                    <option value="ovo">OVO</option>
                    <option value="dana">DANA</option>
                    <option value="bca">Bank BCA</option>
                    <option value="mandiri">Bank Mandiri</option>
                </select>
            </div>

            <div class="mb-6">
                <label class="block font-bold text-sm mb-2 text-gray-700">Upload Bukti Transfer Pelunasan</label>
                <input type="file" name="bukti_transfer" accept="image/*" class="w-full border border-gray-300 p-2 rounded-lg text-sm file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-pink-50 file:text-pink-700 hover:file:bg-pink-100" required>
                <p class="text-xs text-gray-400 mt-1">Format gambar: JPG, JPEG, atau PNG.</p>
            </div>

            <button type="submit" name="submit_pelunasan" class="w-full bg-pink-600 hover:bg-pink-700 text-white font-bold py-3 px-4 rounded-lg shadow transition duration-200">
                Kirim Bukti Pelunasan
            </button>
        </form>
    </div>
</div>

</body>
</html>
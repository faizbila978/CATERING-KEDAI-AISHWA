<?php 
session_start();
include 'koneksi.php';

// Pastikan user sudah login


// Ambil pesanan_id dari parameter URL (GET) atau dari Session
if (isset($_GET['id'])) {
    $pesanan_id = mysqli_real_escape_string($conn, $_GET['id']);
} elseif (isset($_SESSION['pesanan_id'])) {
    $pesanan_id = $_SESSION['pesanan_id'];
} else {
    header('Location: riwayat_pesanan.php');
    exit();
}

// Ambil data pesanan dan pembayaran uang muka sebelumnya
$query = "SELECT p.total_pesan, pb.status_pembayaran, pb.status_dp, pb.jumlah_dp 
          FROM pesanan p 
          LEFT JOIN pembayaran pb ON p.pesanan_id = pb.pesanan_id 
          WHERE p.pesanan_id = '$pesanan_id'";
$result = mysqli_query($conn, $query);
$pesanan = mysqli_fetch_assoc($result);

if (!$pesanan) {
    echo "Data pesanan tidak ditemukan.";
    exit();
}

$total_pesanan = $pesanan['total_pesan'];
$jumlah_dp = $pesanan['jumlah_dp'] ?? 0;

// Hitung sisa yang harus dilunasi
$sisa_pelunasan = $total_pesanan - $jumlah_dp;

// Jika sudah lunas total, kembalikan ke riwayat
if (($pesanan['status_pembayaran'] ?? '') === 'Selesai') {
    header('Location: riwayat_pesanan.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pelunasan Pembayaran - Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-pink: #FF6B8B;
            --secondary-pink: #FF8EAA;
            --dark-pink: #D84B6B;
            --light-bg: #FFF5F7;
        }
        .bg-primary-pink { background-color: var(--primary-pink); }
        .text-primary-pink { color: var(--primary-pink); }
        .border-primary-pink { border-color: var(--primary-pink); }
        
        .payment-option {
            border: 2px solid #E5E7EB;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .payment-option:hover {
            border-color: var(--secondary-pink);
            background-color: var(--light-bg);
        }
        .payment-option.selected {
            border-color: var(--primary-pink);
            background-color: var(--light-bg);
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <nav class="bg-white shadow-sm border-b sticky top-0 z-50">
        <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
            <a href="riwayat_pesanan.php" class="text-gray-600 hover:text-gray-900 flex items-center gap-2 font-medium">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
            <span class="text-lg font-bold text-gray-800">Pelunasan Pesanan ##ORD-<?php echo str_pad($pesanan_id, 3, '0', STR_PAD_LEFT); ?></span>
            <div class="w-20"></div> </div>
    </nav>

    <div class="max-w-4xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            
            <div class="md:col-span-2 space-y-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h2 class="text-xl font-bold text-gray-800 mb-2">Pilih Metode Transaksi</h2>
                    <p class="text-sm text-gray-500 mb-6">Silakan pilih salah satu opsi channel pembayaran di bawah ini untuk melunasi sisa tagihan katering Anda.</p>
                    
                    <div class="space-y-4">
                        <div>
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">E-Wallet</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <div class="payment-option p-4 rounded-xl flex items-center justify-between" data-method="gopay">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center font-bold text-blue-600">GP</div>
                                        <span class="font-medium text-sm text-gray-700">GoPay</span>
                                    </div>
                                    <i class="fas fa-circle-check text-gray-300 check-icon"></i>
                                </div>
                                <div class="payment-option p-4 rounded-xl flex items-center justify-between" data-method="ovo">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center font-bold text-purple-600">OV</div>
                                        <span class="font-medium text-sm text-gray-700">OVO</span>
                                    </div>
                                    <i class="fas fa-circle-check text-gray-300 check-icon"></i>
                                </div>
                                <div class="payment-option p-4 rounded-xl flex items-center justify-between" data-method="dana">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-sky-50 rounded-lg flex items-center justify-center font-bold text-sky-600">DN</div>
                                        <span class="font-medium text-sm text-gray-700">DANA</span>
                                    </div>
                                    <i class="fas fa-circle-check text-gray-300 check-icon"></i>
                                </div>
                            </div>
                        </div>

                        <div class="pt-2">
                            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-2">Transfer Bank</h3>
                            <div class="grid grid-cols-1 gap-3">
                                <div class="payment-option p-4 rounded-xl flex items-center justify-between" data-method="bca">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-blue-900 text-white rounded-lg flex items-center justify-center font-bold">BCA</div>
                                        <div>
                                            <span class="font-medium text-sm text-gray-700 block">Bank BCA</span>
                                            <span class="text-xs text-gray-400">Transfer Manual / M-Banking</span>
                                        </div>
                                    </div>
                                    <i class="fas fa-circle-check text-gray-300 check-icon"></i>
                                </div>
                                <div class="payment-option p-4 rounded-xl flex items-center justify-between" data-method="mandiri">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-yellow-500 text-white rounded-lg flex items-center justify-center font-bold">MDR</div>
                                        <div>
                                            <span class="font-medium text-sm text-gray-700 block">Bank Mandiri</span>
                                            <span class="text-xs text-gray-400">Transfer Manual / Livin'</span>
                                        </div>
                                    </div>
                                    <i class="fas fa-circle-check text-gray-300 check-icon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="md:col-span-1">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 sticky top-24 space-y-6">
                    <h2 class="text-lg font-bold text-gray-800 border-b pb-3">Ringkasan Pelunasan</h2>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between text-gray-500">
                            <span>Total Kontrak Katering</span>
                            <span>Rp <?php echo number_format($total_pesanan, 0, ',', '.'); ?></span>
                        </div>
                        <div class="flex justify-between text-green-600">
                            <span>Uang Muka (DP) Masuk</span>
                            <span>- Rp <?php echo number_format($jumlah_dp, 0, ',', '.'); ?></span>
                        </div>
                        <div class="border-t pt-3 flex justify-between font-bold text-gray-800">
                            <span>Sisa Tagihan</span>
                            <span class="text-xl text-red-600 font-black">Rp <?php echo number_format($sisa_pelunasan, 0, ',', '.'); ?></span>
                        </div>
                    </div>

                    <input type="hidden" id="selectedMethod" value="">

                    <button onclick="prosesPelunasan()" class="w-full bg-primary-pink hover:bg-dark-pink text-white font-bold py-3.5 px-4 rounded-xl shadow-md shadow-pink-100 transition duration-200 flex items-center justify-center gap-2 text-sm tracking-wide">
                        <i class="fas fa-lock"></i> Bayar Pelunasan Sekarang
                    </button>
                    
                    <div class="flex items-center justify-center gap-2 text-xs text-gray-400 pt-2 border-t">
                        <i class="fas fa-shield-alt"></i>
                        <span>Pembayaran Aman & Terenkripsi</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        const paymentOptions = document.querySelectorAll('.payment-option');

        paymentOptions.forEach(option => {
            option.addEventListener('click', function() {
                // Reset semua pilihan
                paymentOptions.forEach(el => {
                    el.classList.remove('selected');
                    el.querySelector('.check-icon').classList.replace('text-pink-600', 'text-gray-300');
                });
                
                // Pilih opsi yang diklik
                this.classList.add('selected');
                this.querySelector('.check-icon').classList.replace('text-gray-300', 'text-pink-600');
                
                // Masukkan value ke input hidden
                document.getElementById('selectedMethod').value = this.getAttribute('data-method');
            });
        });

        function prosesPelunasan() {
            const method = document.getElementById('selectedMethod').value;

            if (!method) {
                alert('Pilih salah satu metode pembayaran pelunasan terlebih dahulu!');
                return;
            }

            // Dialihkan ke halaman status konfirmasi dengan tipe 'pelunasan' (bukan dp atau full reguler)
            window.location.href = 'status.php?pesanan_id=<?php echo $pesanan_id; ?>&method=' + method + '&type=pelunasan';
        }
    </script>
</body>
</html>
<?php 
session_start();
include 'koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if (!isset($_SESSION['pesanan_id'])) {
    header('Location: formulir.php');
    exit();
}

$pesanan_id = $_SESSION['pesanan_id'];

// Ambil data pesanan
$query = "SELECT p.total_pesan, pb.status_pembayaran, pb.status_dp, pb.jumlah_dp 
          FROM pesanan p 
          LEFT JOIN pembayaran pb ON p.pesanan_id = pb.pesanan_id 
          WHERE p.pesanan_id = '$pesanan_id'";
$result = mysqli_query($conn, $query);
$pesanan = mysqli_fetch_assoc($result);

$total_pesanan = $pesanan['total_pesan'];
$status_pembayaran = $pesanan['status_pembayaran'] ?? 'Belum Bayar';
$status_dp = $pesanan['status_dp'] ?? 'Belum Bayar';
$jumlah_dp = $pesanan['jumlah_dp'] ?? 0;
$dp_rekomendasi = $total_pesanan * 0.5; // 50% DP
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metode Pembayaran - Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        :root {
            --primary-pink: #ad2d5e;
            --primary-hover: #8a244b;
            --soft-pink: #fdf2f6;
            --text-dark: #2d2d2d;
        }

        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #fcfcfc;
            color: var(--text-dark);
        }

        .btn-pink { 
            background-color: var(--primary-pink); 
            color: white; 
            border: none; 
            border-radius: 50px;
            font-weight: 700;
            transition: 0.3s;
        }
        
        .btn-pink:hover { 
            background-color: var(--primary-hover); 
            color: white; 
            transform: translateY(-2px);
        }

        .payment-option {
            cursor: pointer; 
            transition: all 0.3s ease; 
            border: 2px solid #eee; 
            background: white;
        }
        
        .payment-option:hover { 
            border-color: var(--primary-pink) !important; 
            transform: translateY(-3px);
        }
        
        .payment-option.selected { 
            border-color: var(--primary-pink) !important; 
            background-color: var(--soft-pink); 
        }

        .status-badge {
            padding: 0.5rem 1rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-belum {
            background-color: #fee2e2;
            color: #991b1b;
        }
        
        .status-selesai {
            background-color: #dcfce7;
            color: #15803d;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        .tab-button {
            cursor: pointer;
            padding: 1rem;
            border-bottom: 3px solid transparent;
            transition: all 0.3s;
        }

        .tab-button.active {
            border-bottom-color: var(--primary-pink);
            color: var(--primary-pink);
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="container mx-auto px-4 py-8 max-w-7xl">
    <a href="formulir.php" class="text-gray-600 hover:text-gray-800 mb-6 inline-flex items-center">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Pesanan
    </a>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Konten Utama -->
        <div class="lg:col-span-2">
            <h2 class="text-3xl font-bold mb-2">Metode Pembayaran</h2>
            <p class="text-gray-600 mb-8">Pilih metode pembayaran yang paling nyaman untuk Anda.</p>

            <!-- Tab Pembayaran -->
            <div class="mb-8">
                <div class="flex border-b border-gray-200 mb-6">
                    <button class="tab-button active" onclick="switchTab('full')">
                        <i class="fas fa-receipt mr-2"></i> Pembayaran Penuh
                    </button>
                    <button class="tab-button" onclick="switchTab('dp')">
                        <i class="fas fa-handshake mr-2"></i> Pembayaran DP
                    </button>
                </div>

                <!-- Tab Pembayaran Penuh -->
                <div id="tab-full" class="tab-content active">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded mb-6">
                        <p class="text-blue-800"><i class="fas fa-info-circle mr-2"></i> Bayar total pesanan sekarang</p>
                    </div>

                    <div class="mb-6">
                        <h6 class="font-bold mb-4 text-lg">Dompet Digital (E-Wallet)</h6>
                        <div class="space-y-3">
                            <div class="payment-option p-4 rounded-lg flex items-center" data-method="gopay">
                                <i class="fas fa-wallet text-2xl text-green-500 mr-4"></i>
                                <div>
                                    <h6 class="font-bold">GoPay</h6>
                                    <small class="text-gray-600">Bayar dengan e-wallet</small>
                                </div>
                            </div>
                            <div class="payment-option p-4 rounded-lg flex items-center" data-method="ovo">
                                <i class="fas fa-wallet text-2xl text-purple-500 mr-4"></i>
                                <div>
                                    <h6 class="font-bold">OVO</h6>
                                    <small class="text-gray-600">Bayar dengan e-wallet</small>
                                </div>
                            </div>
                            <div class="payment-option p-4 rounded-lg flex items-center" data-method="dana">
                                <i class="fas fa-wallet text-2xl text-blue-500 mr-4"></i>
                                <div>
                                    <h6 class="font-bold">DANA</h6>
                                    <small class="text-gray-600">Bayar dengan e-wallet</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h6 class="font-bold mb-4 text-lg">Transfer Bank Manual</h6>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="payment-option p-4 rounded-lg text-center cursor-pointer" data-method="bca">
                                <i class="fas fa-university text-2xl mb-2 text-blue-600"></i>
                                <p class="font-bold text-sm">BCA</p>
                            </div>
                            <div class="payment-option p-4 rounded-lg text-center cursor-pointer" data-method="mandiri">
                                <i class="fas fa-university text-2xl mb-2 text-red-600"></i>
                                <p class="font-bold text-sm">Mandiri</p>
                            </div>
                            <div class="payment-option p-4 rounded-lg text-center cursor-pointer" data-method="bni">
                                <i class="fas fa-university text-2xl mb-2 text-yellow-600"></i>
                                <p class="font-bold text-sm">BNI</p>
                            </div>
                            <div class="payment-option p-4 rounded-lg text-center cursor-pointer" data-method="cimb">
                                <i class="fas fa-university text-2xl mb-2 text-purple-600"></i>
                                <p class="font-bold text-sm">CIMB</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tab Pembayaran DP -->
                <div id="tab-dp" class="tab-content">
                    <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded mb-6">
                        <p class="text-yellow-800"><i class="fas fa-info-circle mr-2"></i> Bayar 50% (Rp <?php echo number_format($dp_rekomendasi, 0, ',', '.'); ?>) sebagai DP sekarang, sisanya saat acara</p>
                    </div>

                    <div class="mb-6">
                        <h6 class="font-bold mb-4 text-lg">Dompet Digital (E-Wallet)</h6>
                        <div class="space-y-3">
                            <div class="payment-option p-4 rounded-lg flex items-center" data-method="gopay-dp">
                                <i class="fas fa-wallet text-2xl text-green-500 mr-4"></i>
                                <div>
                                    <h6 class="font-bold">GoPay</h6>
                                    <small class="text-gray-600">DP Rp <?php echo number_format($dp_rekomendasi, 0, ',', '.'); ?></small>
                                </div>
                            </div>
                            <div class="payment-option p-4 rounded-lg flex items-center" data-method="ovo-dp">
                                <i class="fas fa-wallet text-2xl text-purple-500 mr-4"></i>
                                <div>
                                    <h6 class="font-bold">OVO</h6>
                                    <small class="text-gray-600">DP Rp <?php echo number_format($dp_rekomendasi, 0, ',', '.'); ?></small>
                                </div>
                            </div>
                            <div class="payment-option p-4 rounded-lg flex items-center" data-method="dana-dp">
                                <i class="fas fa-wallet text-2xl text-blue-500 mr-4"></i>
                                <div>
                                    <h6 class="font-bold">DANA</h6>
                                    <small class="text-gray-600">DP Rp <?php echo number_format($dp_rekomendasi, 0, ',', '.'); ?></small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h6 class="font-bold mb-4 text-lg">Transfer Bank Manual</h6>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="payment-option p-4 rounded-lg text-center cursor-pointer" data-method="bca-dp">
                                <i class="fas fa-university text-2xl mb-2 text-blue-600"></i>
                                <p class="font-bold text-sm">BCA</p>
                            </div>
                            <div class="payment-option p-4 rounded-lg text-center cursor-pointer" data-method="mandiri-dp">
                                <i class="fas fa-university text-2xl mb-2 text-red-600"></i>
                                <p class="font-bold text-sm">Mandiri</p>
                            </div>
                            <div class="payment-option p-4 rounded-lg text-center cursor-pointer" data-method="bni-dp">
                                <i class="fas fa-university text-2xl mb-2 text-yellow-600"></i>
                                <p class="font-bold text-sm">BNI</p>
                            </div>
                            <div class="payment-option p-4 rounded-lg text-center cursor-pointer" data-method="cimb-dp">
                                <i class="fas fa-university text-2xl mb-2 text-purple-600"></i>
                                <p class="font-bold text-sm">CIMB</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Ringkasan -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow-lg p-6 sticky top-4">
                <h5 class="font-bold text-lg mb-4">Ringkasan Pesanan</h5>
                
                <div class="space-y-4 mb-6 pb-6 border-b border-gray-200">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Total Pesanan</span>
                        <span class="font-bold text-lg">Rp <?php echo number_format($total_pesanan, 0, ',', '.'); ?></span>
                    </div>
                </div>

                <!-- Status Pembayaran Penuh -->
                <div class="mb-6 pb-6 border-b border-gray-200">
                    <h6 class="font-bold mb-3 text-sm">Pembayaran Penuh</h6>
                    <p class="text-xs text-gray-600 mb-2">Status</p>
                    <span class="status-badge <?php echo ($status_pembayaran === 'Selesai') ? 'status-selesai' : 'status-belum'; ?>">
                        <?php echo $status_pembayaran; ?>
                    </span>
                </div>

                <!-- Status DP -->
                <div class="mb-6">
                    <h6 class="font-bold mb-3 text-sm">Pembayaran DP (50%)</h6>
                    <p class="text-xs text-gray-600 mb-2">Status</p>
                    <span class="status-badge <?php echo ($status_dp === 'Selesai') ? 'status-selesai' : 'status-belum'; ?>">
                        <?php echo $status_dp; ?>
                    </span>
                    <p class="text-xs text-gray-600 mt-2">Jumlah DP: Rp <?php echo number_format($jumlah_dp, 0, ',', '.'); ?></p>
                </div>

                <input type="hidden" id="selectedMethod" value="">
                <button type="button" onclick="prosesePembayaran()" class="btn-pink w-full py-3 rounded-lg font-bold text-white">
                    Lanjutkan Pembayaran
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    const paymentOptions = document.querySelectorAll('.payment-option');

    paymentOptions.forEach(option => {
        option.addEventListener('click', function() {
            paymentOptions.forEach(el => el.classList.remove('selected'));
            this.classList.add('selected');
            document.getElementById('selectedMethod').value = this.getAttribute('data-method');
        });
    });

    function switchTab(tab) {
        // Hapus active dari semua tab
        document.querySelectorAll('.tab-content').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('.tab-button').forEach(el => el.classList.remove('active'));

        // Tambah active ke tab yang dipilih
        document.getElementById('tab-' + tab).classList.add('active');
        event.target.closest('.tab-button').classList.add('active');

        // Reset pilihan pembayaran
        paymentOptions.forEach(el => el.classList.remove('selected'));
        document.getElementById('selectedMethod').value = "";
    }

    function prosesePembayaran() {
        const method = document.getElementById('selectedMethod').value;

        if (!method) {
            alert('Pilih metode pembayaran terlebih dahulu!');
            return;
        }

        // Tentukan tipe pembayaran berdasarkan method
        const isDP = method.includes('-dp');
        const pembayaranType = isDP ? 'dp' : 'full';

        // Redirect ke halaman status dengan parameter
        window.location.href = 'status.php?pesanan_id=<?php echo $pesanan_id; ?>&method=' + method + '&type=' + pembayaranType;
    }
</script>

</body>
</html>
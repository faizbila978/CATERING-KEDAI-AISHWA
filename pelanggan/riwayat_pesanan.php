<?php
session_start();
include 'koneksi.php';

// Pastikan user sudah login
// Pastikan user sudah login
// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php?redirect=riwayat_pesanan.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil semua pesanan user, detail item pertama untuk thumbnail, beserta status pembayarannya
$query = "SELECT p.*, 
          (SELECT m.gambar FROM detail_pesanan dp JOIN menu m ON dp.menu_id = m.menu_id WHERE dp.pesanan_id = p.pesanan_id LIMIT 1) as gambar_menu,
          (SELECT m.nama_menu FROM detail_pesanan dp JOIN menu m ON dp.menu_id = m.menu_id WHERE dp.pesanan_id = p.pesanan_id LIMIT 1) as nama_menu,
          (SELECT SUM(dp.jumlah_menu) FROM detail_pesanan dp WHERE dp.pesanan_id = p.pesanan_id) as total_item,
          pem.status_pembayaran
          FROM pesanan p 
          LEFT JOIN pembayaran pem ON p.pesanan_id = pem.pesanan_id
          WHERE p.user_id = '$user_id' 
          ORDER BY p.tanggal_pesan DESC, p.pesanan_id DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Saya - Kedai Aishwa</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="riwayat_pesanan.css">
    <style>
    </style>
</head>
<body>

<div class="app-header">
        <div class="header-left">
            <a href="index.php" class="header-back">
                <i class="bi bi-arrow-left"></i>
            </a>
            <span class="header-title">Pesanan Saya</span>
        </div>
        
        <div class="header-right">
            <div class="profil-user">
                <small>Selamat Datang,</small>
                <span><?php echo isset($_SESSION['nama']) ? explode(' ', $_SESSION['nama'])[0] : 'User'; ?></span>
            </div>
            <div class="divider"></div>
            <a href="logout.php" class="btn-logout" onclick="return confirm('Yakin ingin keluar?')">
                <i class="bi bi-box-arrow-right"></i> <span>Logout</span>
            </a>
        </div>
    </div>

    <div class="container">
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
            
            <div class="order-card">
                <div class="card-header">
                    <div class="store-name">
                        <i class="bi bi-shop"></i> Kedai Aishwa
                    </div>
                    <div class="order-status">
                        <?php echo strtoupper($row['status_pesanan'] ?? 'MENUNGGU'); ?>
                    </div>
                </div>

                <div class="product-body">
                    <?php $gambar = !empty($row['gambar_menu']) ? $row['gambar_menu'] : 'default.png'; ?>
                    <img src="img/<?php echo htmlspecialchars($gambar); ?>" alt="Menu" class="product-img">
                    <div class="product-info">
                        <div class="product-title">
                            <?php echo htmlspecialchars($row['nama_menu'] ?? 'Menu Tidak Diketahui'); ?> 
                            <?php echo (isset($row['total_item']) && $row['total_item'] > 1) ? 'dan lainnya...' : ''; ?>
                        </div>
                        <div class="product-qty">x<?php echo $row['total_item'] ?? 1; ?></div>
                    </div>
                </div>

                <div class="total-section">
                    Total <?php echo $row['total_item'] ?? 1; ?> produk: 
                    <span class="total-price">Rp<?php echo number_format($row['total_pesan'], 0, ',', '.'); ?></span>
                </div>

                <div class="info-box">
                    <div>
                        <div class="text-highlight">Tanggal Acara: <?php echo date('d M Y', strtotime($row['tanggal_acara'])); ?></div>
                        <div class="text-address">Dikirim ke: <?php echo htmlspecialchars($row['alamat']); ?></div>
                    </div>
                    <i class="bi bi-chevron-right"></i>
                </div>

                <div class="action-buttons">
                    <?php 
                        $status_pesanan = strtolower($row['status_pesanan'] ?? '');
                        $status_pembayaran = strtolower($row['status_pembayaran'] ?? 'belum bayar');
                    ?>

                    <?php if ($status_pesanan == 'selesai'): ?>
                        <a href="index.php#isi-testimoni" class="btn btn-primary" style="background-color: var(--primary-pink); color: white;">Beri Ulasan</a>
                    <?php elseif ($status_pesanan == 'dibatalkan'): ?>
                        <button class="btn btn-disabled" disabled>Pesanan Batal</button>
                    <?php elseif ($status_pembayaran == 'belum bayar' || $status_pembayaran == ''): ?>
                        <a href="pembayaran.php?id=<?php echo $row['pesanan_id']; ?>" class="btn btn-primary" style="background-color: var(--primary-pink); color: white;">Bayar Sekarang</a>
                    <?php else: ?>
                        <button class="btn btn-disabled" disabled>Sedang Diproses</button>
                    <?php endif; ?>
                    
                    <a href="status.php?id=<?php echo $row['pesanan_id']; ?>" class="btn btn-primary">Lihat Status</a>
                </div>

            </div>
            
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <i class="bi bi-receipt"></i>
                <h5>Belum ada pesanan</h5>
                <p>Silakan lihat menu dan buat pesanan pertama Anda.</p>
                <a href="index.php" class="btn btn-empty btn">Mulai Pesan</a>
            </div>
        <?php endif; ?>

    </div>

</body>
</html>
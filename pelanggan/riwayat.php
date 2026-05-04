<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil semua riwayat pesanan user ini
$query = "SELECT p.*, pb.status_pembayaran, pb.status_dp 
          FROM pesanan p 
          LEFT JOIN pembayaran pb ON p.pesanan_id = pb.pesanan_id 
          WHERE p.user_id = '$user_id' 
          ORDER BY p.tanggal_pesan DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Riwayat Pesanan - Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --primary-pink: #ad2d5e; }
        body { background: #fdf2f6; color: #2d2d2d; }
        .card-history { border: none; border-radius: 15px; margin-bottom: 15px; transition: 0.3s; }
        .card-history:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0,0,0,0.1); }
        .badge-status { border-radius: 20px; padding: 5px 15px; font-size: 12px; }
    </style>
</head>
<body>
<div class="container py-5">
    <h3 class="fw-bold mb-4" style="color: var(--primary-pink);">Riwayat Pesanan Saya</h3>
    
    <?php while($row = mysqli_fetch_assoc($result)): ?>
    <div class="card card-history p-3">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <small class="text-muted">ID Pesanan: #ORD-<?php echo str_pad($row['pesanan_id'], 3, '0', STR_PAD_LEFT); ?></small>
                <h5 class="fw-bold mb-1"><?php echo date('d M Y', strtotime($row['tanggal_pesan'])); ?></h5>
                <p class="mb-0 text-muted">Total: <strong>Rp <?php echo number_format($row['total_pesan'], 0, ',', '.'); ?></strong></p>
            </div>
            <div class="text-end">
                <span class="badge badge-status bg-info mb-2 d-block"><?php echo $row['status_pesanan']; ?></span>
                <!-- Tombol untuk melihat detail status seperti halaman yang Anda kirim -->
                <a href="status.php?id=<?php echo $row['pesanan_id']; ?>" class="btn btn-sm btn-outline-danger">Lihat Detail</a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>
</body>
</html>
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
$method = $_GET['method'] ?? null;
$type = $_GET['type'] ?? 'full'; // 'full' atau 'dp'

// Ambil data pesanan
// Ambil data pembayaran dan pesanan terbaru
$query = "SELECT p.*, pb.status_pembayaran, pb.status_dp, pb.metode_pembayaran 
          FROM pesanan p 
          LEFT JOIN pembayaran pb ON p.pesanan_id = pb.pesanan_id 
          WHERE p.pesanan_id = '$pesanan_id'";
$result = mysqli_query($conn, $query);
$pesanan = mysqli_fetch_assoc($result);

// Logika penentu apakah pembayaran sudah benar-benar "Selesai" (Dikonfirmasi Admin)
$is_paid = ($pesanan['status_pembayaran'] == 'Selesai' || $pesanan['status_dp'] == 'Selesai');
// Logika apakah pesanan sudah dikirim
$is_shipped = ($pesanan['status_pesanan'] == 'Dikirim');

if (!$pesanan) {
    echo "<script>alert('Pesanan tidak ditemukan!'); window.location='menu.php';</script>";
    exit();
}

$total_pesanan = $pesanan['total_pesan'];
$pembayaran_id = $pesanan['pembayaran_id'];

// Tentukan jumlah pembayaran
if ($type == 'dp') {
    $jumlah_bayar = $total_pesanan * 0.5; // 50% DP
    $jenis_pembayaran = 'DP (50%)';
} else {
    $jumlah_bayar = $total_pesanan; // Full
    $jenis_pembayaran = 'FULL (100%)';
}

// PROSES: Jika ada method pembayaran, update status pembayaran
if ($method) {
    if ($type == 'dp') {
        // Jangan langsung 'Selesai', tapi 'Menunggu Konfirmasi'
        $update_query = "UPDATE pembayaran SET 
                        status_dp = 'Menunggu Konfirmasi', 
                        jumlah_dp = '$jumlah_bayar',
                        metode_pembayaran = '$method',
                        tanggal_pembayaran = NOW()
                        WHERE pembayaran_id = '$pembayaran_id'";
    } else {
        $update_query = "UPDATE pembayaran SET 
                        status_pembayaran = 'Menunggu Konfirmasi', 
                        metode_pembayaran = '$method',
                        tanggal_pembayaran = NOW()
                        WHERE pembayaran_id = '$pembayaran_id'";
    }
    
    mysqli_query($conn, $update_query);
    
    // Status pesanan juga jangan langsung dikonfirmasi
    mysqli_query($conn, "UPDATE pesanan SET status_pesanan = 'Menunggu Verifikasi' WHERE pesanan_id = '$pesanan_id'");
}

// Ambil data pembayaran yang sudah di-update
$query_pembayaran = "SELECT * FROM pembayaran WHERE pembayaran_id = '$pembayaran_id'";
$result_pembayaran = mysqli_query($conn, $query_pembayaran);
$pembayaran = mysqli_fetch_assoc($result_pembayaran);

$status_pembayaran = $pembayaran['status_pembayaran'] ?? 'Belum Bayar';
$status_dp = $pembayaran['status_dp'] ?? 'Belum Bayar';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran - Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
    --primary-pink: #ad2d5e;
    --primary-hover: #8a244b;
    --soft-pink: #fdf2f6;
    --text-dark: #2d2d2d;
}

body {
    font-family: 'Plus Jakarta Sans', sans-serif;
    background: linear-gradient(135deg, #1f2937 0%, #374151 100%);
    color: var(--text-dark);
}

        .card-custom {
            border: none;
            border-radius: 16px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.07);
        }

        .status-badge {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            display: inline-block;
        }

        .status-pending {
            background-color: #FEF3C7;
            color: #92400E;
        }

        .status-success {
            background-color: #DCFCE7;
            color: #166534;
        }

        .timeline {
            position: relative;
        }

        .timeline-item {
            display: flex;
            margin-bottom: 24px;
            position: relative;
        }

        .timeline-item.completed .timeline-marker {
            background-color: #10B981;
            color: white;
        }

        .timeline-item.pending .timeline-marker {
            background-color: var(--primary-pink);
            color: white;
        }

        .timeline-marker {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 16px;
            flex-shrink: 0;
        }

        .timeline-content h6 {
            margin-bottom: 4px;
            font-weight: 600;
        }

        .payment-method-badge {
            background-color: var(--primary-pink);
            color: white;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .btn-pink {
            background-color: var(--primary-pink);
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 25px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-pink:hover {
            background-color: var(--primary-hover);
            color: white;
            transform: translateY(-2px);
        }

        .payment-info {
            background-color: var(--soft-pink);
            border-left: 4px solid var(--primary-pink);
            padding: 16px;
            border-radius: 8px;
        }

        .success-icon {
            font-size: 64px;
            color: #10B981;
            margin-bottom: 16px;
        }

        .highlight-text {
            color: var(--primary-pink);
            font-weight: 600;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- SUCCESS OR PROCESSING -->
            <?php if ($method): ?>
                <!-- PEMBAYARAN BERHASIL -->
                <div class="card card-custom p-5 mb-4 text-center">
                    <div class="success-icon">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <h2 class="fw-bold mb-2">Pembayaran Berhasil!</h2>
                    <p class="text-muted mb-0">Pesanan Anda telah dikonfirmasi dan akan diproses oleh admin</p>
                </div>

                <!-- PAYMENT DETAILS -->
                <div class="card card-custom p-4 mb-4">
                    <h5 class="fw-bold mb-4">Detail Pembayaran</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <small class="text-muted">ID Pesanan</small>
                            <p class="fw-bold fs-5">#ORD-<?php echo str_pad($pesanan_id, 3, '0', STR_PAD_LEFT); ?></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Jenis Pembayaran</small>
                            <p class="fw-bold fs-5"><span class="payment-method-badge"><?php echo $jenis_pembayaran; ?></span></p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <small class="text-muted">Metode Pembayaran</small>
                            <p class="fw-bold"><?php echo ucfirst(str_replace('-dp', '', $method)); ?></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted">Jumlah Pembayaran</small>
                            <p class="fw-bold highlight-text">Rp <?php echo number_format($jumlah_bayar, 0, ',', '.'); ?></p>
                        </div>
                    </div>

                    <hr>

                    <div class="payment-info">
                        <i class="bi bi-info-circle me-2"></i>
                        <small>Pembayaran Anda telah tercatat. Admin akan mengkonfirmasi pesanan Anda dalam beberapa saat.</small>
                    </div>
                </div>

            <?php else: ?>
                <!-- MENUNGGU PEMBAYARAN -->
                <div class="card card-custom p-5 mb-4 text-center">
                    <h2 class="fw-bold mb-2">Menunggu Pembayaran</h2>
                    <p class="text-muted mb-0">Silakan pilih metode pembayaran di halaman sebelumnya</p>
                </div>
            <?php endif; ?>

            <!-- ORDER STATUS -->
            <div class="card card-custom p-4 mb-4">
                <h5 class="fw-bold mb-4">Status Pesanan</h5>
                
               <div class="timeline">
    <!-- STEP 1: PESANAN DITERIMA (Selalu Selesai) -->
    <div class="timeline-item completed">
        <div class="timeline-marker">
            <i class="bi bi-check-lg"></i>
        </div>
        <div class="timeline-content">
            <h6 class="fw-bold">Pesanan Diterima</h6>
            <small class="text-muted">
                <?php echo date('d/m/Y H:i', strtotime($pesanan['tanggal_pesan'])); ?> WIB
            </small>
        </div>
    </div>

    <!-- STEP 2: PEMBAYARAN (Selesai jika admin sudah konfirmasi) -->
    <div class="timeline-item <?php echo $is_paid ? 'completed' : 'pending'; ?>">
        <div class="timeline-marker">
            <i class="bi <?php echo $is_paid ? 'bi-check-lg' : 'bi-hourglass-split'; ?>"></i>
        </div>
        <div class="timeline-content">
            <h6 class="fw-bold">Pembayaran Dikonfirmasi</h6>
            <small class="text-muted">
                <?php 
                if ($is_paid) echo "Pembayaran telah diverifikasi admin";
                else echo "Menunggu verifikasi pembayaran"; 
                ?>
            </small>
        </div>
    </div>

    <!-- STEP 3: PROSES (Selesai jika sudah bayar, tapi akan berubah saat dikirim) -->
    <div class="timeline-item <?php echo ($is_paid && !$is_shipped) ? 'pending' : ($is_shipped ? 'completed' : ''); ?>">
        <div class="timeline-marker">
            <i class="bi <?php echo $is_shipped ? 'bi-check-lg' : 'bi-gear-fill'; ?>"></i>
        </div>
        <div class="timeline-content">
            <h6 class="fw-bold">Sedang Diproses</h6>
            <small class="text-muted">
                <?php 
                if ($is_shipped) echo "Pesanan selesai diproses";
                elseif ($is_paid) echo "Admin sedang menyiapkan pesanan Anda";
                else echo "Menunggu pembayaran";
                ?>
            </small>
        </div>
    </div>

    <!-- STEP 4: PENGIRIMAN (Selesai jika status_pesanan = 'Dikirim') -->
    <div class="timeline-item <?php echo $is_shipped ? 'completed' : ''; ?>">
        <div class="timeline-marker">
            <i class="bi bi-truck"></i>
        </div>
        <div class="timeline-content">
            <h6 class="fw-bold">Dalam Pengiriman</h6>
            <small class="text-muted">
                <?php echo $is_shipped ? 'Pesanan sedang dalam perjalanan ke lokasi' : 'Pesanan belum dikirim'; ?>
            </small>
        </div>
    </div>
</div>

            <!-- ORDER INFO -->
            <div class="card card-custom p-4 mb-4">
                <h5 class="fw-bold mb-4">Informasi Pesanan</h5>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted">Tanggal Acara</small>
                        <p class="fw-bold"><?php echo date('d/m/Y', strtotime($pesanan['tanggal_acara'])); ?></p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted">Waktu Acara</small>
                        <p class="fw-bold"><?php echo $pesanan['waktu_acara']; ?> WIB</p>
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted">Alamat Pengiriman</small>
                    <p class="fw-bold"><?php echo htmlspecialchars($pesanan['alamat']); ?></p>
                </div>

                <div class="mb-3">
                    <small class="text-muted">No. Handphone</small>
                    <p class="fw-bold"><?php echo htmlspecialchars($pesanan['no_handphone']); ?></p>
                </div>

                <?php if ($pesanan['catatan']): ?>
                <div>
                    <small class="text-muted">Catatan Pesanan</small>
                    <p class="fw-bold"><?php echo htmlspecialchars($pesanan['catatan']); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <!-- TOTAL PESANAN -->
            <div class="card card-custom p-4 mb-4 bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-muted mb-0">Total Pesanan</h6>
                    <h4 class="fw-bold highlight-text mb-0">Rp <?php echo number_format($total_pesanan, 0, ',', '.'); ?></h4>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="d-grid gap-2">
                <a href="menu.php" class="btn btn-pink">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Menu
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
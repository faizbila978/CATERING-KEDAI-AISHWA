<?php 
session_start();
include '../koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Tangkap ID pesanan dari URL jika ada, jika tidak gunakan dari session
if (isset($_GET['id'])) {
    $_SESSION['pesanan_id'] = mysqli_real_escape_string($conn, $_GET['id']);
}

if (!isset($_SESSION['pesanan_id'])) {
    header('Location: riwayat_pesanan.php');
    exit();
}

$pesanan_id = $_SESSION['pesanan_id'];
$method = $_GET['method'] ?? null;
$type = $_GET['type'] ?? 'full'; // 'full' atau 'dp'

// PERBAIKAN QUERY: Menambahkan pb.pembayaran_id agar tidak undefined array key
$query = "SELECT p.*, pb.pembayaran_id, pb.status_pembayaran, pb.status_dp, pb.metode_pembayaran 
          FROM pesanan p 
          LEFT JOIN pembayaran pb ON p.pesanan_id = pb.pesanan_id 
          WHERE p.pesanan_id = '$pesanan_id'";
$result = mysqli_query($conn, $query);
$pesanan = mysqli_fetch_assoc($result);

if (!$pesanan) {
    echo "<script>alert('Pesanan tidak ditemukan!'); window.location='menu.php';</script>";
    exit();
}

// Gunakan null coalescing ?? untuk menghindari notice jika record pembayaran belum terbentuk
$pembayaran_id = $pesanan['pembayaran_id'] ?? null;
$total_pesanan = $pesanan['total_pesan'];

// Logika penentu apakah pembayaran sudah benar-benar "Selesai" (Dikonfirmasi Admin)
$is_paid = ($pesanan['status_pembayaran'] == 'Selesai' || $pesanan['status_dp'] == 'Selesai');
// Logika apakah pesanan sudah dikirim
$is_shipped = ($pesanan['status_pesanan'] == 'Dikirim');

// Tentukan jumlah pembayaran
if ($type == 'dp') {
    $jumlah_bayar = $total_pesanan * 0.5; // 50% DP
    $jenis_pembayaran = 'DP (50%)';
} else {
    $jumlah_bayar = $total_pesanan; // Full
    $jenis_pembayaran = 'FULL (100%)';
}

// PROSES: Jika ada method pembayaran dari parameter URL, update/insert status pembayaran
if ($method) {
    if ($pembayaran_id) {
        if ($type == 'dp') {
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
    } else {
        if ($type == 'dp') {
            $insert_query = "INSERT INTO pembayaran (pesanan_id, metode_pembayaran, tanggal_pembayaran, total_pembayaran, status_pembayaran, jumlah_dp, status_dp) 
                             VALUES ('$pesanan_id', '$method', NOW(), '$total_pesanan', 'Belum Bayar', '$jumlah_bayar', 'Menunggu Konfirmasi')";
        } else {
            $insert_query = "INSERT INTO pembayaran (pesanan_id, metode_pembayaran, tanggal_pembayaran, total_pembayaran, status_pembayaran) 
                             VALUES ('$pesanan_id', '$method', NOW(), '$total_pesanan', 'Menunggu Konfirmasi')";
        }
        mysqli_query($conn, $insert_query);
        $pembayaran_id = mysqli_insert_id($conn);
    }
    
    mysqli_query($conn, "UPDATE pesanan SET status_pesanan = 'Menunggu Verifikasi' WHERE pesanan_id = '$pesanan_id'");
    
    header("Location: status.php?id=" . $pesanan_id . "&type=" . $type);
    exit();
}

$status_pembayaran = $pesanan['status_pembayaran'] ?? 'Belum Bayar';
$status_dp = $pesanan['status_dp'] ?? 'Belum Bayar';
$metode_pembayaran_terpilih = $pesanan['metode_pembayaran'] ?? '-';
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
    <link rel="stylesheet" type="text/css" href="status.css"> 
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <?php if ($metode_pembayaran_terpilih != '-'): ?>
                <div class="card card-custom p-5 mb-4 text-center">
                    <div class="success-icon text-success fs-1 mb-2">
                        <i class="bi bi-check-circle-fill"></i>
                    </div>
                    <h2 class="fw-bold mb-2">Pembayaran Tercatat!</h2>
                    <p class="text-muted mb-0">Pesanan Anda sedang menunggu verifikasi konfirmasi pembayaran oleh Admin.</p>
                </div>

                <div class="card card-custom p-4 mb-4">
                    <h5 class="fw-bold mb-4 border-bottom pb-2">Detail Pembayaran</h5>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <small class="text-muted mb-1 d-block font-semibold">ID PESANAN</small>
                            <p class="fw-bold fs-5 mb-0">#ORD-<?php echo str_pad($pesanan_id, 3, '0', STR_PAD_LEFT); ?></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted mb-1 d-block font-semibold">JENIS PEMBAYARAN</small>
                            <p class="fw-bold fs-5 mb-0"><span class="badge bg-secondary rounded-pill px-3"><?php echo ($status_dp != 'Belum Bayar') ? 'DP (50%)' : 'FULL (100%)'; ?></span></p>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <small class="text-muted mb-1 d-block font-semibold">METODE PEMBAYARAN</small>
                            <p class="fw-bold mb-0"><?php echo strtoupper(str_replace('-dp', '', $metode_pembayaran_terpilled ?? $metode_pembayaran_terpilih)); ?></p>
                        </div>
                        <div class="col-md-6">
                            <small class="text-muted mb-1 d-block font-semibold">JUMLAH YANG DIBAYAR</small>
                            <p class="fw-bold text-pink fs-5 mb-0">Rp <?php echo number_format(($status_dp != 'Belum Bayar' ? $total_pesanan * 0.5 : $total_pesanan), 0, ',', '.'); ?></p>
                        </div>
                    </div>

                    <hr>

                    <div class="alert alert-info-custom d-flex align-items-center mb-0 rounded-3">
                        <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                        <small>Pembayaran Anda telah terekam di sistem kami. Admin Kedai Aishwa akan segera melakukan verifikasi pesanan Anda.</small>
                    </div>
                </div>

            <?php else: ?>
                <div class="card card-custom p-5 mb-4 text-center bg-warning-subtle border-warning">
                    <div class="fs-1 text-warning mb-2"><i class="bi bi-hourglass-split"></i></div>
                    <h2 class="fw-bold mb-2">Menunggu Pembayaran</h2>
                    <p class="text-muted mb-0">Silakan pilih metode pembayaran di halaman checkout / sebelumnya.</p>
                </div>
            <?php endif; ?>

            <div class="card card-custom p-4 mb-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Status Pesanan</h5>
                
                <div class="position-relative ps-4" style="border-left: 2px solid #dee2e6;">
                    <div class="mb-4 position-relative">
                        <div class="position-absolute bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width:24px; height:24px; left:-33px; top:0;">
                            <i class="bi bi-check-lg small"></i>
                        </div>
                        <h6 class="fw-bold mb-0">Pesanan Diterima</h6>
                        <small class="text-muted">
                            <?php echo date('d/m/Y H:i', strtotime($pesanan['tanggal_pesan'])); ?> WIB
                        </small>
                    </div>

                    <div class="mb-4 position-relative">
                        <div class="position-absolute <?php echo $is_paid ? 'bg-success' : 'bg-warning'; ?> text-white rounded-circle d-flex align-items-center justify-content-center" style="width:24px; height:24px; left:-33px; top:0;">
                            <i class="bi <?php echo $is_paid ? 'bi-check-lg' : 'bi-hourglass-split'; ?> small"></i>
                        </div>
                        <h6 class="fw-bold mb-0">Pembayaran Dikonfirmasi</h6>
                        <small class="text-muted">
                            <?php 
                            if ($is_paid) echo "Pembayaran telah diverifikasi oleh admin Kedai Aishwa";
                            else echo "Menunggu verifikasi pembayaran / Belum lunas"; 
                            ?>
                        </small>
                    </div>

                    <div class="mb-4 position-relative">
                        <div class="position-absolute <?php echo ($is_paid && !$is_shipped) ? 'bg-warning' : ($is_shipped ? 'bg-success' : 'bg-secondary'); ?> text-white rounded-circle d-flex align-items-center justify-content-center" style="width:24px; height:24px; left:-33px; top:0;">
                            <i class="bi bi-gear-fill small"></i>
                        </div>
                        <h6 class="fw-bold mb-0">Sedang Diproses</h6>
                        <small class="text-muted">
                            <?php 
                            if ($is_shipped) echo "Pesanan selesai diproses masakan";
                            elseif ($is_paid) echo "Dapur kami sedang menyiapkan pesanan hidangan Anda";
                            else echo "Menunggu penyelesaian pembayaran";
                            ?>
                        </small>
                    </div>

                    <div class="position-relative">
                        <div class="position-absolute <?php echo $is_shipped ? 'bg-success' : 'bg-secondary'; ?> text-white rounded-circle d-flex align-items-center justify-content-center" style="width:24px; height:24px; left:-33px; top:0;">
                            <i class="bi bi-truck small"></i>
                        </div>
                        <h6 class="fw-bold mb-0">Dalam Pengiriman / Selesai</h6>
                        <small class="text-muted">
                            <?php echo $is_shipped ? 'Pesanan sedang dalam perjalanan ke lokasi acara Anda' : 'Pesanan belum dikirim'; ?>
                        </small>
                    </div>
                </div>
            </div>

            <div class="card card-custom p-4 mb-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Informasi Pesanan</h5>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <small class="text-muted small fw-bold">TANGGAL ACARA</small>
                        <p class="fw-bold mb-0"><?php echo date('d/m/Y', strtotime($pesanan['tanggal_acara'])); ?></p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted small fw-bold">WAKTU ACARA</small>
                        <p class="fw-bold mb-0"><?php echo $pesanan['waktu_acara']; ?> WIB</p>
                    </div>
                </div>

                <div class="mb-3">
                    <small class="text-muted small fw-bold">ALAMAT PENGIRIMAN</small>
                    <p class="fw-bold mb-0"><?php echo htmlspecialchars($pesanan['alamat']); ?></p>
                </div>

                <div class="mb-3">
                    <small class="text-muted small fw-bold">NO. HANDPHONE</small>
                    <p class="fw-bold mb-0"><?php echo htmlspecialchars($pesanan['no_handphone']); ?></p>
                </div>

                <?php if ($pesanan['catatan']): ?>
                <div>
                    <small class="text-muted small fw-bold">CATATAN PESANAN</small>
                    <p class="fw-bold mb-0"><?php echo htmlspecialchars($pesanan['catatan']); ?></p>
                </div>
                <?php endif; ?>
            </div>

            <div class="bg-light p-3 rounded-4 mb-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-muted mb-0">Total Tagihan Pesanan</h6>
                    <h4 class="fw-bold text-pink mb-0">Rp <?php echo number_format($total_pesanan, 0, ',', '.'); ?></h4>
                </div>
            </div>

            <div class="d-grid gap-2 mt-4">
                <?php if ($pesanan['status_pesanan'] != 'Dikirim' && $pesanan['status_pesanan'] != 'Diproses' && !$is_shipped): ?>
                    <a href="edit_pesanan.php?id=<?php echo $pesanan_id; ?>" class="btn btn-warning fw-bold text-white mb-2 py-2" style="border-radius: 25px;">
                        <i class="bi bi-pencil-square me-2"></i> Edit Detail Pesanan
                    </a>
                <?php endif; ?>
                
                <a href="riwayat_pesanan.php" class="btn btn-pink py-3 rounded-pill fw-bold shadow-sm">
                    <i class="bi bi-arrow-left me-2"></i> Kembali ke Pesanan Saya
                </a>
            </div>

        </div>
    </div>
</div>

</body>
</html>
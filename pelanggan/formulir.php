<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    echo "<script>alert('Pilih menu dulu ya!'); window.location='menu.php';</script>";
    exit();
}
$total_bayar = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan - Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="formulir.css">
    <style>
        .alert-info-custom {
            background-color: #E0F2FE;
            border-color: #0284C7;
            color: #0C4A6E;
            border-left: 4px solid #0284C7;
        }
        .processing-indicator {
            display: none;
            text-align: center;
            margin: 20px 0;
        }
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #ad2d5e;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .text-pink {
            color: #ad2d5e;
        }
        .btn-pink {
            background-color: #ad2d5e;
            color: white;
            border: none;
        }
        .btn-pink:hover {
            background-color: #8a244b;
            color: white;
        }
        .card-custom {
            border: 1px solid #e0e0e0;
            border-radius: 16px;
        }
    </style>
</head>
<body>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="menu.php" class="btn btn-outline-dark rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        <a href="menu_tambahan.php" class="btn btn-pink rounded-pill px-4 shadow">
            <i class="bi bi-plus-lg me-2"></i>Tambah Menu
        </a>
    </div>

    <div class="row g-4">

        <div class="col-lg-7">
            <div class="card card-custom shadow-sm p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Detail Pesanan</h5>

                <?php 
                foreach ($_SESSION['keranjang'] as $id => $jumlah): 
                    $query = mysqli_query($conn, "SELECT * FROM menu WHERE menu_id = '$id'");
                    $menu = mysqli_fetch_assoc($query);

                    if ($menu):
                        $subtotal = $menu['harga_satuan'] * $jumlah;
                        $total_bayar += $subtotal;
                ?>
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                    
                    <img src="img/<?php echo $menu['gambar']; ?>" 
                         class="rounded-3 shadow-sm me-3" 
                         style="width: 80px; height: 80px; object-fit: cover;">

                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1"><?php echo $menu['nama_menu']; ?></h6>
                        <p class="text-pink fw-bold mb-0">
                            Rp <?php echo number_format($menu['harga_satuan'], 0, ',', '.'); ?>
                        </p>
                    </div>

                    <form action="keranjang_update.php" method="POST" class="d-flex align-items-center bg-light rounded-pill px-2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">

                        <button type="button" class="btn btn-sm fw-bold text-pink" onclick="kurangiItem(this)">-</button>

                        <input type="number" 
                               name="jumlah" 
                               value="<?php echo $jumlah; ?>" 
                               min="0"
                               class="form-control form-control-sm border-0 bg-transparent text-center mx-1" 
                               style="width: 40px; font-weight: bold;"
                               title="Isi 0 untuk hapus item"
                               onchange="this.form.submit()">

                        <button type="button" class="btn btn-sm fw-bold text-pink" onclick="this.previousElementSibling.stepUp(); this.previousElementSibling.onchange()">+</button>
                    </form>
                </div>
                <?php endif; endforeach; ?>

            </div>
        </div>

        <div class="col-lg-5">
            <div class="card card-custom shadow-sm p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Informasi Pengiriman</h5>

                <div class="alert alert-info-custom mb-4 rounded-3" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Petunjuk:</strong> Setelah konfirmasi, pesanan Anda akan muncul di admin untuk verifikasi pembayaran.
                </div>

                <form id="pesananForm" action="proses_pesan.php" method="POST">
                    <input type="hidden" name="total_harga" value="<?php echo $total_bayar; ?>">

                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">NAMA PENERIMA</label>
                        <input type="text" name="nama" class="form-control rounded-3" value="<?php echo isset($_SESSION['nama']) ? htmlspecialchars($_SESSION['nama']) : ''; ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">NOMOR HANDPHONE</label>
                        <input type="text" name="no_hp" class="form-control rounded-3" placeholder="Contoh: 08123456789" required>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">TANGGAL ACARA</label>
                        <input type="date" name="tanggal_acara" class="form-control rounded-3" required>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">WAKTU ACARA</label>
                        <input type="time" name="waktu_acara" class="form-control rounded-3" required>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">DETAIL ALAMAT</label>
                        <textarea name="alamat" class="form-control rounded-3" placeholder="Contoh: Jl Mawar No 10, dekat masjid" required rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">CATATAN PESANAN (OPSIONAL)</label>
                        <textarea name="catatan" class="form-control rounded-3" rows="2" placeholder="Contoh: Sambal dipisah ya"></textarea>
                    </div>

                    <div class="bg-light p-3 rounded-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-muted">Total Pembayaran</span>
                            <h4 class="fw-bold text-pink mb-0">
                                Rp <?php echo number_format($total_bayar, 0, ',', '.'); ?>
                            </h4>
                        </div>
                    </div>

                    <div class="processing-indicator" id="processingIndicator">
                        <div class="spinner"></div>
                        <p class="text-muted small">Memproses pesanan Anda...</p>
                    </div>

                    <button type="submit" class="btn btn-pink w-100 py-3 rounded-pill fw-bold shadow-sm" id="submitBtn">
                        <i class="bi bi-check-circle me-2"></i> KONFIRMASI SEKARANG
                    </button>

                    <p class="text-center text-muted small mt-3" style="font-size: 0.8rem;">
                        <i class="bi bi-shield-check me-1"></i> 
                        Pesanan akan ditampilkan kepada admin untuk verifikasi pembayaran
                    </p>
                </form>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function kurangiItem(btn) {
    let input = btn.nextElementSibling;
    let jumlah = parseInt(input.value);

    if (jumlah <= 1) {
        if (confirm("Item akan dihapus dari keranjang. Lanjutkan?")) {
            input.value = 0;
            input.form.submit();
        }
    } else {
        input.stepDown();
        input.form.submit();
    }
}

// Handle form submission
document.getElementById('pesananForm').addEventListener('submit', function(e) {
    // Tampilkan processing indicator
    document.getElementById('processingIndicator').style.display = 'block';
    document.getElementById('submitBtn').disabled = true;
});
</script>

</body>
</html>
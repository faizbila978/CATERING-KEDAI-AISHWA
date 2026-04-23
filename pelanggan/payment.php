<?php require_once 'data-payment.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Metode Pembayaran - Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="payment.css">
    <style>
        <style>
    :root {
        --primary-pink: #ad2d5e;
        --primary-hover: #8a244b;
        --soft-pink: #fdf2f6;
        --text-dark: #2d2d2d;
        --soft-gold: #e2b04a;
    }

    body { 
        font-family: 'Plus Jakarta Sans', sans-serif; 
        background-color: #fcfcfc;
        color: var(--text-dark);
    }

    /* --- Typography & Colors --- */
    .text-pink { color: var(--primary-pink) !important; }
    .bg-soft-pink { background-color: var(--soft-pink) !important; }
    
    /* --- Navbar --- */
    .navbar { 
        background: rgba(255, 255, 255, 0.9); 
        backdrop-filter: blur(10px);
        box-shadow: 0 2px 10px rgba(0,0,0,0.05); 
    }

    /* --- Buttons --- */
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

    /* --- Payment Cards --- */
    .clickable-method { 
        cursor: pointer; 
        transition: all 0.3s ease; 
        border: 2px solid #eee; 
        background: white;
    }
    .clickable-method:hover { 
        border-color: var(--primary-pink) !important; 
        transform: translateY(-3px);
    }
    .clickable-method.selected { 
        border-color: var(--primary-pink) !important; 
        background-color: var(--soft-pink); 
    }

    /* --- Order Summary Card --- */
    .card-summary {
        border: none;
        border-radius: 24px;
        box-shadow: 0 15px 35px rgba(0,0,0,0.08);
    }
    .border-pink-top { 
        border-top: 6px solid var(--primary-pink); 
        margin: -24px -24px 20px -24px; 
        border-radius: 4px 4px 0 0; 
    }
    .sticky-summary { position: sticky; top: 110px; }

    .custom-radio:checked {
        background-color: var(--primary-pink);
        border-color: var(--primary-pink);
    }
</style>
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg fixed-top py-3">
    <div class="container">
        <a class="navbar-brand fw-bold fs-3 text-maroon" href="#">Kedai Aishwa</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link mx-3" href="#">Beranda</a></li>
                <li class="nav-item"><a class="nav-link mx-3" href="#">Lihat Menu</a></li>
                <li class="nav-item"><a href="login.php" class="btn btn-maroon ms-lg-3 px-4">Pesan Sekarang</a></li>
            </ul>
        </div>
    </div>
</nav>

<div class="container py-5 mt-5">
    <a href="formulir.php" class="text-decoration-none text-muted small mb-4 d-inline-block">
        <i class="bi bi-chevron-left"></i> Kembali ke Detail Pesanan
    </a>

    <div class="row">
        <div class="col-lg-7">
            <h2 class="fw-bold mb-2">Metode Pembayaran</h2>
            <p class="text-muted mb-5">Pilih metode pembayaran yang paling nyaman untuk Anda.</p>

            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">Dompet Digital (E-Wallet)</h6>
                    <span class="badge bg-soft-pink text-maroon rounded-pill px-3">Rekomendasi</span>
                </div>
                
                <?php foreach ($ewallets as $wallet): ?>
                <div class="payment-option clickable-method d-flex align-items-center p-3 mb-3 rounded-4 shadow-sm" data-radio="<?php echo $wallet['id']; ?>">
                    <div class="payment-icon me-3 bg-light rounded-3 p-2">
                        <i class="bi <?php echo $wallet['icon']; ?> text-maroon fs-4"></i>
                    </div>
                    <div class="flex-grow-1">
                        <h6 class="mb-0 fw-bold"><?php echo $wallet['name']; ?></h6>
                        <small class="text-muted"><?php echo $wallet['desc']; ?></small>
                    </div>
                    <input type="radio" name="payment" id="<?php echo $wallet['id']; ?>" class="form-check-input custom-radio">
                </div>
                <?php endforeach; ?>
            </div>

            <div class="mb-5">
                <h6 class="fw-bold mb-3">Transfer Bank (Manual)</h6>
                <div class="row g-3">
                    <?php foreach ($banks as $bank): ?>
                    <div class="col-md-4">
                        <div class="payment-box clickable-method text-center p-4 rounded-4 shadow-sm" data-radio="<?php echo $bank['id']; ?>">
                            <i class="bi bi-bank fs-2 mb-2 text-maroon"></i>
                            <h6 class="mb-0 fw-bold small"><?php echo $bank['name']; ?></h6>
                            <input type="radio" name="payment" id="<?php echo $bank['id']; ?>" class="d-none">
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="mb-4">
                <h6 class="fw-bold mb-3">Virtual Account</h6>
                <div class="p-3 border rounded-4 d-flex justify-content-between align-items-center border-dashed bg-light opacity-75">
                    <div class="d-flex align-items-center gap-3">
                        <i class="bi bi-qr-code-scan fs-4"></i>
                        <span class="fw-bold">Semua Virtual Account Bank</span>
                    </div>
                    <span class="badge bg-secondary">PERBAIKAN</span>
                </div>
            </div>
        </div>

        <div class="col-lg-5 ps-lg-5 mt-4 mt-lg-0">
            <div class="card border-0 shadow-lg p-4 rounded-4 sticky-summary">
                <div class="border-maroon-top"></div>
                <h5 class="fw-bold mb-4">Ringkasan Pesanan</h5>
                <p class="text-muted small">Tinjau kembali pesanan Anda sebelum melakukan pembayaran.</p>

                <div class="summary-details my-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted"><?php echo $order_item; ?></span>
                        <span class="fw-bold">Rp <?php echo number_format($price, 0, ',', '.'); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span class="text-muted">Biaya Layanan (<?php echo $service_fee_percent; ?>%)</span>
                        <span class="fw-bold">Rp <?php echo number_format($service_fee, 0, ',', '.'); ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h4 class="fw-bold mb-0">Total</h4>
                        <h3 class="fw-bold text-maroon mb-0">Rp <?php echo number_format($total_price, 0, ',', '.'); ?></h3>
                    </div>
                </div>

                <div class="alert bg-soft-pink border-0 rounded-3 d-flex gap-3 mb-4">
                    <i class="bi bi-shield-lock-fill text-maroon"></i>
                    <small class="text-muted">Pembayaran Anda diproses melalui saluran terenkripsi yang aman.</small>
                </div>

                <a href="status.php" class="btn btn-maroon w-100 py-3 rounded-3 fw-bold mb-3">Selesaikan Pembayaran</a>
                <p class="text-center text-muted small" style="font-size: 0.7rem;">PEMBAYARAN AMAN DIDUKUNG OLEH ATELIERPAY</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Logika pemilihan metode pembayaran
    document.querySelectorAll('.clickable-method').forEach(box => {
        box.addEventListener('click', function() {
            // Hapus kelas 'selected' dari semua pilihan
            document.querySelectorAll('.clickable-method').forEach(el => el.classList.remove('selected'));
            // Tambah kelas 'selected' pada yang diklik
            this.classList.add('selected');
            
            // Centang radio button yang sesuai
            const radioId = this.getAttribute('data-radio');
            const radio = document.getElementById(radioId);
            if(radio) radio.checked = true;
        });
    });
</script>

</body>
</html>
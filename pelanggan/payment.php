<?php require_once 'data-payment.php'; ?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Method - Culinary Atelier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="payment.css">
    <style>
<<<<<<< HEAD
        .text-pink { color: #800000; }
        .bg-soft-pink { background-color: #fff0f0; }
        .border-pink-top { border-top: 5px solid #800000; margin-top: -24px; margin-left: -24px; margin-right: -24px; border-radius: 4px 4px 0 0; }
        .btn-pink { background-color: #800000; color: white; }
        .btn-pink:hover { background-color: #600000; color: white; }
=======
        .text-maroon { color: #800000; }
        .bg-soft-pink { background-color: #fff0f0; }
        .border-maroon-top { border-top: 5px solid #800000; margin-top: -24px; margin-left: -24px; margin-right: -24px; border-radius: 4px 4px 0 0; }
        .btn-maroon { background-color: #800000; color: white; }
        .btn-maroon:hover { background-color: #600000; color: white; }
>>>>>>> 9c32336805fc3f254a23636c348bef075027d3ba
        .clickable-method { cursor: pointer; transition: 0.3s; }
        .clickable-method:hover { border-color: #800000 !important; }
        .clickable-method.selected { border-color: #800000 !important; background-color: #fffafa; }
    </style>
</head>
<body>

<<<<<<< HEAD
<nav class="navbar navbar-expand-lg fixed-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 text-maroon" href="#">Kedai Aishwa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link mx-3" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link mx-3" href="#">Lihat Menu</a></li>
                    <li class="nav-item"><a href="login.php" class="btn btn-maroon ms-lg-3">Order Now</a></li>
                </ul>
            </div>
        </div>
    </nav>

=======
<nav class="navbar navbar-light bg-white border-bottom py-3">
    <div class="container">
        <a class="navbar-brand fw-bold text-maroon" href="index.php">Culinary Atelier</a>
        <div class="ms-auto d-flex gap-4">
            <a href="index.php" class="nav-link">Home</a>
            <a href="menu.php" class="nav-link text-maroon border-bottom border-maroon border-2">Order Now</a>
            <a href="login.php" class="nav-link">Login</a>
        </div>
    </div>
</nav>
>>>>>>> 9c32336805fc3f254a23636c348bef075027d3ba

<div class="container py-5">
    <a href="formulir.php" class="text-decoration-none text-muted small mb-4 d-inline-block">
        <i class="bi bi-chevron-left"></i> Back to Request Details
    </a>

    <div class="row d-flex align-items-start">
        <div class="col-lg-7">
            <h2 class="fw-bold mb-2">Payment Method</h2>
            <p class="text-muted mb-5">Select your preferred way to finalize your culinary experience.</p>

            <div class="mb-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">E-Wallet</h6>
                    <span class="badge bg-soft-pink text-maroon rounded-pill px-3">Recommended</span>
                </div>
                
                <?php foreach ($ewallets as $wallet): ?>
                <div class="payment-option clickable-method d-flex align-items-center p-3 mb-3 border rounded-4 shadow-sm" data-radio="<?php echo $wallet['id']; ?>">
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
                <h6 class="fw-bold mb-3">Transfer Bank</h6>
                <div class="row g-3">
                    <?php foreach ($banks as $bank): ?>
                    <div class="col-md-4">
                        <div class="payment-box clickable-method text-center p-4 border rounded-4 shadow-sm" data-radio="<?php echo $bank['id']; ?>">
                            <i class="bi bi-bank fs-2 mb-2"></i>
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
                        <span class="fw-bold">All Bank Virtual Accounts</span>
                    </div>
                    <span class="badge bg-secondary">MAINTENANCE</span>
                </div>
            </div>
        </div>

        <div class="col-lg-5 ps-lg-5 mt-4 mt-lg-0">
            <div class="card border-0 shadow-lg p-4 rounded-4 sticky-summary">
                <div class="border-maroon-top"></div>
                <h5 class="fw-bold mb-4">Order Summary</h5>
                <p class="text-muted small">Review your selection before proceeding to payment.</p>

                <div class="summary-details my-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted"><?php echo $order_item; ?></span>
                        <span class="fw-bold">$<?php echo number_format($price, 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                        <span class="text-muted">Service Fee (<?php echo $service_fee_percent; ?>%)</span>
                        <span class="fw-bold">$<?php echo number_format($service_fee, 2); ?></span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <h4 class="fw-bold mb-0">Total</h4>
                        <h3 class="fw-bold text-maroon mb-0">$<?php echo number_format($total_price, 2); ?></h3>
                    </div>
                </div>

                <div class="alert alert-soft-pink border-0 rounded-3 d-flex gap-3 mb-4">
                    <i class="bi bi-shield-lock-fill text-maroon"></i>
                    <small class="text-muted">Your payment is processed through encrypted channels.</small>
                </div>

                <a href="status.php" class="btn btn-maroon w-100 py-3 rounded-3 fw-bold mb-3">Complete Payment</a>
                <p class="text-center text-muted small" style="font-size: 0.7rem;">SECURE CHECKOUT POWERED BY ATELIERPAY</p>
            </div>
        </div>
    </div>
</div>

<script>
    // Logic seleksi payment (tetap menggunakan JS)
    document.querySelectorAll('.clickable-method').forEach(box => {
        box.addEventListener('click', function() {
            document.querySelectorAll('.clickable-method').forEach(el => el.classList.remove('selected'));
            this.classList.add('selected');
            
            const radioId = this.getAttribute('data-radio');
            const radio = document.getElementById(radioId);
            if(radio) radio.checked = true;
        });
    });
</script>

</body>
</html>
<?php
$site_title = "Kedai Aishwa | Authentic Taste, Modern Presentation";
$brand_name = "Kedai Aishwa";
$established_year = 2018;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_title; ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="Landing Page.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg fixed-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 text-pink" href="#">Kedai Aishwa</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link mx-3" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link mx-3" href="#">Lihat Menu</a></li>
                    <li class="nav-item"><a href="login.php" class="btn btn-pink ms-lg-3">Order Now</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 px-4">
                    <span class="hero-tagline text-uppercase mb-3 d-block">Premium Catering Service</span>
                    <h1 class="display-3 fw-bold mb-4">Kelezatan Tradisi, <br><span class="text-pink">Sentuhan Modern.</span></h1>
                    <p class="lead text-muted mb-5">
                        Menghadirkan hidangan Nusantara dengan standar kualitas bintang lima. Sempurna untuk acara kantor, syukuran, hingga momen spesial keluarga Anda.
                    </p>
                    
<div class="d-flex gap-3 align-items-center">
    <a href="login.php" class="btn btn-pink shadow-lg">Lihat Katalog Menu</a>
    
    <a href="status.php" class="btn btn-outline-dark shadow-sm">
        Lihat Pesanan
    </a>
</div>
                </div>

                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="image-wrapper p-4 text-center">
                        <img src="img/ayambakar.png" alt="Featured Dish" class="img-fluid main-img shadow-lg">
                        
                        <div class="stat-card">
                            <div>
                                <h6 class="mb-0 fw-bold">1.200+ Pesanan</h6>
                                <small class="text-muted">Terjual Bulan Ini</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="menu" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-pink fw-bold text-uppercase small" style="letter-spacing: 2px;">Pilihan Terbaik</span>
                <h2 class="display-5 fw-bold mt-2">Menu Favorit Kami</h2>
                <div class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: var(--soft-gold);"></div>
            </div>

            <div class="row g-4">
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden menu-card">
                        <div class="position-relative">
                            <img src="img/ayambakar.png" class="card-img-top" alt="Ayam Bakar Madu" style="height: 250px; object-fit: cover;">
                            <span class="position-absolute top-0 end-0 m-3 badge bg-pink px-3 py-2 rounded-pill">Best Seller</span>
                        </div>
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold">Ayam Bakar Madu </h5>
                            <p class="card-text text-muted small">nasi, ayam, mie goreng jawa, sambel,lalapan, aqua gelas.</p>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <span class="fw-bold text-pink fs-5">Rp 20.000</span>
                                <a href="login.php" class="btn btn-outline-dark btn-sm rounded-pill px-3">Pesan</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden menu-card">
                        <img src="img/kentang.png" class="card-img-top" alt="Nasi Kotak Premium" style="height: 250px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold">Beef Teriyaki Bowl</h5>
                            <p class="card-text text-muted small">Daging sapi slice dengan saus premium.</p>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <span class="fw-bold text-pink fs-5">Rp 45.000</span>
                                <a href="login.php" class="btn btn-outline-dark btn-sm rounded-pill px-3">Pesan</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden menu-card">
                        <img src="img/tumpeng.png" class="card-img-top" alt="Tumpeng Mini" style="height: 250px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold">Tumpeng nusantara spesial</h5>
                            <p class="card-text text-muted small">nasi kuning, mie jawa, telur, ayam suwir.</p>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <span class="fw-bold text-pink fs-5">Rp 50.000</span>
                                <a href="login.php" class="btn btn-outline-dark btn-sm rounded-pill px-3">Pesan</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <a href="login.php" class="btn btn-pink px-5 py-3 shadow">Lihat Semua Menu Lengkap</a>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
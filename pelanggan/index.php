<?php
// 1. Variabel Konfigurasi (Memudahkan update data di satu tempat)
$site_title = "Catering Atelier - Kedai Aishwa";
$brand_name = "CateringAtelier";
$established_year = 2018;
$order_count = "1.200+";

// 2. Logika Navigasi Aktif
// Fungsi ini mendeteksi nama file yang sedang dibuka
$current_page = basename($_SERVER['PHP_SELF']); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $site_title; ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="landing page.css">
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-maroon" href="index.php"><?php echo $brand_name; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'menu.php') ? 'active' : ''; ?>" href="menu.php">Order Now</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link <?php echo ($current_page == 'login.php') ? 'active' : ''; ?>" href="login.php">Login</a>
                    </li>
                    <li class="nav-item ms-lg-3">
                        <a href="#" class="btn btn-maroon px-4 rounded-pill">Request Quote</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-5 mb-lg-0">
                    <span class="badge-established mb-3">ESTABLISHED SINCE <?php echo $established_year; ?></span>
                    <h1 class="display-4 fw-bold mb-4">Catering <span class="text-maroon">Kedai Aishwa.</span></h1>
                    <p class="text-secondary mb-5 pe-lg-5">
                        Sajian otentik dengan sentuhan kontemporer. Kami menghadirkan seni kuliner ke dalam setiap kotak hidangan, dikurasi khusus untuk momen istimewa Anda.
                    </p>
                    
                    <div class="d-flex gap-3">
                        <a href="menu.php" class="btn btn-maroon btn-lg px-4 py-3">Pesan Sekarang &rarr;</a>
                        <a href="login.php" class="btn btn-outline-secondary btn-lg px-4 py-3">Cek Pesanan</a>
                    </div>

                    <div class="mt-5 d-flex align-items-center gap-3">
                        <div class="user-avatars">
                            <img src="https://via.placeholder.com/40" class="rounded-circle border border-2 border-white" alt="user">
                            <img src="https://via.placeholder.com/40" class="rounded-circle border border-2 border-white ms-n2" alt="user">
                            <div class="avatar-count">+24</div>
                        </div>
                        <small class="text-muted"><strong><?php echo $order_count; ?> Pesanan</strong> berhasil diantar minggu ini</small>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="image-grid">
                        <div class="main-img-card">
                            <img src="img/food-1.jpg" alt="Dish 1" class="img-fluid rounded-4 shadow">
                            <div class="floating-badge shadow-sm">
                                <span class="text-warning">★</span> <strong>Premium Taste</strong><br>
                                <small>Bahan lokal berkualitas tinggi</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
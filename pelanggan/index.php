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
<<<<<<< HEAD
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="landing page.css">
=======
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="Landing Page.css">
>>>>>>> 1a02f5e (menghapus dasbord)
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top py-3">
        <div class="container">
<<<<<<< HEAD
            <a class="navbar-brand fw-bold text-maroon" href="index.php"><?php echo $brand_name; ?></a>
=======
            <a class="navbar-brand fw-bold fs-3 text-pink" href="#">Kedai Aishwa</a>
>>>>>>> 1a02f5e (menghapus dasbord)
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
<<<<<<< HEAD
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
=======
                    <li class="nav-item"><a class="nav-link mx-3" href="#">Home</a></li>
                    <li class="nav-item"><a class="nav-link mx-3" href="#">Lihat Menu</a></li>
                    <li class="nav-item"><a href="login.php" class="btn btn-pink ms-lg-3">Order Now</a></li>
>>>>>>> 1a02f5e (menghapus dasbord)
                </ul>
            </div>
        </div>
    </nav>

    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
<<<<<<< HEAD
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
=======
                <div class="col-lg-6 px-4">
                    <span class="hero-tagline text-uppercase mb-3 d-block">Premium Catering Service</span>
                    <h1 class="display-3 fw-bold mb-4">Kelezatan Tradisi, <br><span class="text-pink">Sentuhan Modern.</span></h1>
                    <p class="lead text-muted mb-5">
                        Menghadirkan hidangan Nusantara dengan standar kualitas bintang lima. Sempurna untuk acara kantor, syukuran, hingga momen spesial keluarga Anda.
                    </p>
                    
                    <div class="d-flex gap-3 align-items-center">
                        <a href="login.php" class="btn btn-pink shadow-lg">Lihat Katalog Menu</a>
                    </div>
                </div>

                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="image-wrapper p-4 text-center">
                        <img src="ayambakar.png" alt="Featured Dish" class="img-fluid main-img shadow-lg">
                        
                        <div class="stat-card">
                            <div class="bg-pink p-3 rounded-circle text-white">
                                🍽️
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">1.200+ Pesanan</h6>
                                <small class="text-muted">Terjual Bulan Ini</small>
>>>>>>> 1a02f5e (menghapus dasbord)
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<<<<<<< HEAD
=======

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
                            <img src="ayambakar.png" class="card-img-top" alt="Ayam Bakar Madu" style="height: 250px; object-fit: cover;">
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
                        <img src="nasikotak.png" class="card-img-top" alt="Nasi Kotak Premium" style="height: 250px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold">Nasi Kotak Premium B</h5>
                            <p class="card-text text-muted small">Nasi putih, Daging Rendang, Sambal Goreng Ati, Perkedel, dan Kerupuk Udang. Lengkap & Higienis.</p>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <span class="fw-bold text-pink fs-5">Rp 45.000</span>
                                <a href="login.php" class="btn btn-outline-dark btn-sm rounded-pill px-3">Pesan</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden menu-card">
                        <img src="tumpeng.png" class="card-img-top" alt="Tumpeng Mini" style="height: 250px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold">Tumpeng Mini Syukuran</h5>
                            <p class="card-text text-muted small">Sajian tumpeng porsi personal untuk acara syukuran. Estetik dan rasa yang tak terlupakan.</p>
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
>>>>>>> 1a02f5e (menghapus dasbord)

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
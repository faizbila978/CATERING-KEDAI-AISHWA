<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- LOGIKA MENANGKAP KIRIMAN FORM ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Jika yang dikirim adalah FORM TESTIMONI
    if (isset($_POST['rating'])) {
        // Tempatkan logika query INSERT INTO tabel_testimoni kamu di sini jika ada
        
        $_SESSION['notif_sukses'] = "Terima kasih! Testimoni kuliner Anda berhasil dikirim.";
        header("Location: index.php#isi-testimoni");
        exit();
    }
    
    // Jika yang dikirim adalah FORM KOMPLAIN
    if (isset($_POST['pesanan_id']) && !isset($_POST['rating'])) {
        // Tempatkan logika query INSERT INTO tabel_komplain dan upload foto di sini jika ada
        
        $_SESSION['notif_sukses'] = "Komplain berhasil diajukan. Tim Kedai Aishwa akan segera memeriksa kendala Anda.";
        header("Location: index.php#isi-testimoni");
        exit();
    }
}

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

    <nav class="navbar navbar-expand-lg fixed-top py-3" style="background: rgba(255, 255, 255, 0.9) !important; backdrop-filter: blur(15px); border-bottom: 1px solid rgba(0,0,0,0.05);">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 text-pink" href="index.php"><?php echo $brand_name; ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link mx-3 fw-semibold" href="index.php">Home</a></li>
                    
                    <?php if(isset($_SESSION['user_email'])): ?>
                        <li class="nav-item"><a class="nav-link mx-3 fw-semibold" href="menu.php">Lihat Menu</a></li>
                        
                        <li class="nav-item ms-lg-3 d-flex align-items-center gap-3 mt-3 mt-lg-0">
                            <div class="d-none d-lg-block text-end lh-1">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Selamat Datang,</small>
                                <span class="fw-bold text-dark"><?php echo isset($_SESSION['nama']) ? explode(' ', $_SESSION['nama'])[0] : 'User'; ?></span>
                            </div>
                            
                            <div class="vr mx-1 d-none d-lg-block text-secondary"></div>
                            
                            <a href="menu.php" class="btn btn-pink rounded-pill px-4 shadow-sm">Menu Utama</a>
                            <a href="logout.php" class="btn btn-outline-danger rounded-pill px-3 shadow-sm" onclick="return confirm('Yakin ingin keluar?')">
                                Logout
                            </a>
                        </li>

                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link mx-3 fw-semibold" href="login.php">Lihat Menu</a></li>
                        <li class="nav-item ms-lg-3 mt-3 mt-lg-0">
                            <a href="login.php" class="btn btn-pink rounded-pill px-4 shadow-sm">Login / Order</a>
                        </li>
                    <?php endif; ?>
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
                        <a href="riwayat_pesanan.php" class="btn btn-outline-dark shadow-sm">Look Orders</a>
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
                            <h5 class="card-title fw-bold">Paket Ayam Bakar</h5>
                            <p class="card-text text-muted small">nasi, ayam, mie goreng jawa, sambel, lalapan, aqua gelas.</p>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <span class="fw-bold text-pink fs-5">Rp 20.000</span>
                                <a href="login.php" class="btn btn-outline-dark btn-sm rounded-pill px-3">Pesan</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden menu-card">
                        <img src="img/kentang.png" class="card-img-top" alt="Beef Teriyaki Bowl" style="height: 250px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold">Rice Bowl</h5>
                            <p class="card-text text-muted small">Daging slice dengan saus premium.</p>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <span class="fw-bold text-pink fs-5">Rp 10.000</span>
                                <a href="login.php" class="btn btn-outline-dark btn-sm rounded-pill px-3">Pesan</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden menu-card">
                        <img src="img/tumpeng.png" class="card-img-top" alt="Tumpeng Nusantara" style="height: 250px; object-fit: cover;">
                        <div class="card-body p-4">
                            <h5 class="card-title fw-bold">Tumpeng nusantara spesial</h5>
                            <p class="card-text text-muted small">nasi kuning, mie jawa, telur, ayam suwir.</p>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <span class="fw-bold text-pink fs-5">Rp 250.000</span>
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

    <?php
    $testimonials = [
        [
            "nama_produk" => "Paket Ayam Bakar",
            "username" => "@susan_lia",
            "deskripsi" => "Ayam bakarnya beneran meresap sampai ke dalam! Manis gurihnya pas banget, lauk pauk pendampingnya juga lengkap dan segar. Sangat direkomendasikan!",
            "rating" => 5,
            "foto" => "img/ayambakar.png" 
        ],
        [
            "nama_produk" => "RiceBowl",
            "username" => "@rean_setiawan",
            "deskripsi" => "Dagingnya empuk banget dan saus teriyakinya berasa premium. Porsi pas untuk makan siang di kantor, kemasannya juga rapi dan higienis.",
            "rating" => 4,
            "foto" => "img/kentang.png"
        ],
        [
            "nama_produk" => "Tumpeng Nusantara Spesial",
            "username" => "@amalia_putri",
            "deskripsi" => "Pesan ini untuk acara syukuran keluarga, tampilannya sangat cantik dan estetik. Rasa nasi kuningnya gurih pulen, semua tamu undangan memujinya.",
            "rating" => 5,
            "foto" => "img/tumpeng.png"
        ]
    ];
    ?>

    <section id="testimoni" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-pink fw-bold text-uppercase small" style="letter-spacing: 2px;">Testimoni Pelanggan</span>
                <h2 class="display-5 fw-bold mt-2">Apa Kata Mereka?</h2>
                <div class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: var(--soft-gold, #ffc107);"></div>
            </div>

            <div class="row g-4">
                <?php foreach ($testimonials as $testi): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center test-card">
                        
                        <div class="mx-auto mb-3 overflow-hidden rounded-circle shadow-sm" style="width: 90px; height: 90px; border: 3px solid #ffc107;">
                            <img src="<?php echo $testi['foto']; ?>" alt="<?php echo $testi['nama_produk']; ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        
                        <h5 class="fw-bold text-dark mb-0"><?php echo $testi['nama_produk']; ?></h5>
                        <p class="text-pink small mb-2" style="font-size: 0.85rem; font-weight: 600;"><?php echo $testi['username']; ?></p>
                        
                        <div class="text-warning mb-3">
                            <?php 
                            for ($i = 1; $i <= 5; $i++) {
                                if ($i <= $testi['rating']) {
                                    echo '<span class="star-icon">★</span>';
                                } else {
                                    echo '<span class="star-icon-empty">☆</span>';
                                }
                            }
                            ?>
                        </div>
                        
                        <p class="card-text text-muted small fst-italic mb-0">
                            "<?php echo $testi['deskripsi']; ?>"
                        </p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="isi-testimoni" class="py-5 bg-light border-top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="card border-0 shadow-lg p-4 p-md-5" style="border-radius: 20px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
                        
                        <?php if (isset($_SESSION['user_email'])): ?>
                            <ul class="nav nav-pills custom-pills mb-4 justify-content-center gap-2" id="formTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active px-4 py-2 fw-bold" id="testi-tab" data-bs-toggle="tab" data-bs-target="#testi-pane" type="button" role="tab">
                                        Beri Testimoni
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link px-4 py-2 fw-bold" id="komplain-tab" data-bs-toggle="tab" data-bs-target="#komplain-pane" type="button" role="tab">
                                        Ajukan Komplain
                                    </button>
                                </li>
                            </ul> 
                            <p class="text-muted">Kritik, saran, maupun testimoni Anda sangat berharga untuk menjaga kualitas hidangan premium kami.</p>

<?php if (isset($_SESSION['notif_sukses'])): ?>
    <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mx-auto mb-4 text-start" role="alert" style="max-width: 600px; background-color: #e6f6ec; color: #155724; border-radius: 15px;">
        <div class="d-flex align-items-center">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle-fill me-2 flex-shrink-0" viewBox="0 0 16 16">
                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
            </svg>
            <div>
                <strong>Berhasil!</strong> <?php echo $_SESSION['notif_sukses']; ?>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['notif_sukses']); // Hapus notifikasi setelah direfresh ?>
<?php endif; ?>
<ul class="nav nav-pills custom-pills mb-4 justify-content-center gap-2" id="formTab" role="tablist">
                            <div class="tab-content" id="formTabContent">
                                <div class="tab-pane fade show active" id="testi-pane" role="tabpanel" tabindex="0">
                                    <div class="text-center mb-4">
                                        <h3 class="fw-bold text-dark mb-1">Bagikan Pengalaman Anda</h3>

                                        <p class="text-muted small">Ulasan Anda sangat berarti untuk meningkatkan kualitas pelayanan kami.</p>
                                    </div>
                                    <form action="" method="POST">
                                        <div class="mb-3">
                                            <label class="form-label fw-600">Nama Lengkap</label>
                                            <input type="text" name="nama" class="form-control custom-input" value="<?php echo htmlspecialchars($_SESSION['nama']); ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-600">Rating Kepuasan</label>
                                            <select name="rating" class="form-select custom-input" required>
                                                <option value="" disabled selected>Pilih Bintang...</option>
                                                <option value="5">⭐⭐⭐⭐⭐ Sangat Puas</option>
                                                <option value="4">⭐⭐⭐⭐ Puas</option>
                                                <option value="3">⭐⭐⭐ Cukup</option>
                                                <option value="2">⭐⭐ Kurang Puas</option>
                                                <option value="1">⭐ Kecewa</option>
                                            </select>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label fw-600">Pesan Testimoni</label>
                                            <textarea name="pesan" class="form-control custom-input" rows="4" placeholder="Tuliskan kesan pesan Anda terhadap hidangan dan pelayanan kami..." required></textarea>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-pink py-3 fw-bold shadow-sm">Kirim Testimoni Juara</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="komplain-pane" role="tabpanel" tabindex="0">
                                    <div class="text-center mb-4">
                                        <h3 class="fw-bold text-dark mb-1">Pusat Bantuan & Komplain</h3>
                                        <p class="text-muted small">Ada kendala dengan pesanan Anda? Laporkan di sini, kami siap membantu.</p>
                                    </div>
                                    <form action="" method="POST" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label class="form-label fw-600">ID Pesanan (Invoice)</label>
                                            <input type="text" name="pesanan_id" class="form-control custom-input" placeholder="Contoh: ORD-20231005" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-600">Deskripsi Masalah / Komplain</label>
                                            <textarea name="deskripsi" class="form-control custom-input" rows="4" placeholder="Mohon jelaskan secara detail kendala atau kekurangan pesanan yang Anda terima..." required></textarea>
                                        </div>
                                        <div class="mb-4">
                                            <label class="form-label fw-600">Foto Bukti Hidangan</label>
                                            <input type="file" name="foto_produk" class="form-control custom-input" accept="image/*" required>
                                            <div class="form-text">Wajib unggah foto kondisi produk sebagai bukti (Format: JPG/PNG).</div>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-dark py-3 fw-bold shadow-sm">Kirim Komplain Masukan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        <?php else: ?>
                            <div class="text-center py-5">
                                <div class="mb-4">
                                    <span class="p-3 bg-light rounded-circle d-inline-block shadow-sm">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="45" height="45" fill="#ad2d5e" class="bi bi-lock-fill" viewBox="0 0 16 16">
                                            <path d="M8 1a2 2 0 0 1 2 2v4H6V3a2 2 0 0 1 2-2zm3 6V3a3 3 0 0 0-6 0v4a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2z"/>
                                        </svg>
                                    </span>
                                </div>
                                <h4 class="fw-bold text-dark mb-2">Ingin Memberikan Ulasan?</h4>
                                <p class="text-muted mx-auto" style="max-width: 420px;">Silakan masuk ke akun Anda terlebih dahulu untuk membagikan testimoni kuliner atau mengajukan komplain ke Kedai Aishwa.</p>
                                <a href="login.php" class="btn btn-pink rounded-pill px-5 py-2.5 mt-3 shadow-sm fw-bold">
                                    Login Sekarang
                                </a>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
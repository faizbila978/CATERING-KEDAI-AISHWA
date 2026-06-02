<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include "koneksi.php"; // Pastikan koneksi database dipanggil

// Ambil data pengaturan web
$query_pengaturan = mysqli_query($conn, "SELECT * FROM pengaturan_web WHERE id = 1");
$web = mysqli_fetch_assoc($query_pengaturan);

// --- LOGIKA MENANGKAP KIRIMAN FORM ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Jika yang dikirim adalah FORM TESTIMONI
    if (isset($_POST['rating'])) {
        $nama = mysqli_real_escape_string($conn, $_POST['nama']); 
        $nama_produk = mysqli_real_escape_string($conn, $_POST['nama_produk']);
        $rating = (int)$_POST['rating'];
        
        // Menggunakan isset untuk mencegah error undefined key aman
        $pesan = isset($_POST['pesan']) ? mysqli_real_escape_string($conn, $_POST['pesan']) : '';
        
        // Menyesuaikan dengan kolom asli database Anda (nama_produk, rating, deskripsi)
        $query_testi = "INSERT INTO testimoni (nama_produk, rating, deskripsi) VALUES ('$nama_produk', $rating, '$pesan')";
        
        if (mysqli_query($conn, $query_testi)) {
            $_SESSION['notif_sukses'] = "Terima kasih! Testimoni kuliner Anda berhasil dikirim.";
            header("Location: index.php#isi-testimoni");
            exit();
        }
    }
    
    // Jika yang dikirim adalah FORM KOMPLAIN
    if (isset($_POST['pesanan_id']) && !isset($_POST['rating'])) {
        $pesanan_id = mysqli_real_escape_string($conn, $_POST['pesanan_id']);
        $deskripsi = mysqli_real_escape_string($conn, $_POST['deskripsi']);
        $nama_user = $_SESSION['nama'];
        $user_email = $_SESSION['user_email'];

        // Proses Upload Foto Bukti
        $nama_file = $_FILES['foto_produk']['name'];
        $tmp_file = $_FILES['foto_produk']['tmp_name'];
        
        // Buat nama file unik agar tidak bentrok
        $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        $nama_file_baru = time() . "_bukti." . $ekstensi;
        $path_upload = "img/" . $nama_file_baru;

        if (move_uploaded_file($tmp_file, $path_upload)) {
            // Masukkan data komplain ke database
            $query_komplain = "INSERT INTO komplain (pesanan_id, nama_user, user_email, deskripsi, foto_bukti) 
                               VALUES ('$pesanan_id', '$nama_user', '$user_email', '$deskripsi', '$nama_file_baru')";
            
            if (mysqli_query($conn, $query_komplain)) {
                $_SESSION['notif_sukses'] = "Komplain berhasil diajukan. Tim Kedai Aishwa akan segera memeriksa kendala Anda.";
                header("Location: index.php#isi-testimoni");
                exit();
            }
        }
    }
}

$site_title = "Catering Kedai Aishwa | Authentic Taste, Modern Presentation";
$brand_name = "Catering Kedai Aishwa";
$established_year = 2018;

// Link dinamis berdasarkan status login
$menu_link = isset($_SESSION['user_email']) ? 'menu.php' : 'login.php';
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
    
    <style>
        .clickable-card {
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none !important;
            color: inherit !important;
        }
        .clickable-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08) !important;
        }
        .text-hover-pink {
            transition: color 0.2s ease;
        }
        .clickable-card:hover .text-hover-pink {
            color: #ad2d5e !important;
        }
    </style>
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
                        <li class="nav-item"><a class="nav-link mx-3 fw-semibold" href="riwayat_pesanan.php">riwayat pesanan</a></li>
                        
                        <li class="nav-item ms-lg-3 d-flex align-items-center gap-3 mt-3 mt-lg-0">
                            <div class="d-none d-lg-block text-end lh-1">
                                <small class="text-muted d-block" style="font-size: 0.7rem;">Selamat Datang,</small>
                                <span class="fw-bold text-dark"><?php echo isset($_SESSION['nama']) ? explode(' ', $_SESSION['nama'])[0] : 'User'; ?></span>
                            </div>
                            
                            <div class="vr mx-1 d-none d-lg-block text-secondary"></div>
                            
                            <a href="logout.php" class="btn btn-outline-danger rounded-pill px-3 shadow-sm" onclick="return confirm('Yakin ingin keluar?')">
                                Logout
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link mx-3 fw-semibold" href="login.php">riwayatpesanan</a></li>
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
                    <span class="hero-tagline text-uppercase mb-3 d-block" style="letter-spacing: 2px; font-size: 0.85rem; font-weight: 700; color: #ad2d5e;">
                        <?php echo $web['hero_tagline']; ?>
                    </span>
                    <h1 class="display-3 fw-bold mb-4" style="line-height: 1.15;">
                        <?php echo str_replace('Sentuhan Modern.', '<span class="text-pink">Sentuhan Modern.</span>', $web['hero_judul']); ?>
                    </h1>
                    <p class="lead text-muted mb-5" style="font-size: 1.1rem; line-height: 1.7;">
                        <?php echo $web['hero_deskripsi']; ?>
                    </p>
                    
                    <div class="d-flex gap-3 align-items-center">
                        <a href="<?php echo $menu_link; ?>" class="btn btn-pink shadow-lg px-4 py-3 fw-bold rounded-pill">Eksplor Pilihan Menu</a>
                        <a href="riwayat_pesanan.php" class="btn btn-outline-dark shadow-sm px-4 py-3 fw-bold rounded-pill">riwayat pesanan</a>
                    </div>
                </div>

                <div class="col-lg-6 mt-5 mt-lg-0">
                    <div class="image-wrapper p-4 text-center">
                        <img src="img/ayambakar.png" alt="Featured Dish" class="img-fluid main-img shadow-lg">
                        <div class="stat-card">
                            <div>
                                <h6 class="mb-0 fw-bold">1.200+ Porsi</h6>
                                <small class="text-muted">Dinikmati Bulan Ini</small>
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
                <span class="text-pink fw-bold text-uppercase small" style="letter-spacing: 2px;">Cita Rasa Istimewa</span>
                <h2 class="display-5 fw-bold mt-2">Mahakarya Kuliner Terfavorit</h2>
                <div class="mx-auto mt-3" style="width: 60px; height: 3px; background-color: var(--soft-gold, #ffc107);"></div>
                <p class="text-muted mt-3 small">Klik pada menu pilihan Anda untuk langsung masuk ke halaman pemesanan</p>
            </div>

            <div class="row g-4">
                <?php 
                $query_rekomendasi = mysqli_query($conn, "SELECT * FROM menu WHERE is_rekomendasi = 1 LIMIT 3");
                
                if (mysqli_num_rows($query_rekomendasi) > 0):
                    while ($menu_fav = mysqli_fetch_assoc($query_rekomendasi)): 
                ?>
                    <div class="col-md-6 col-lg-4">
                        <a href="<?php echo $menu_link; ?>" class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden clickable-card">
                            <div class="position-relative">
                                <img src="img/<?php echo $menu_fav['gambar']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($menu_fav['nama_menu']); ?>" style="height: 250px; object-fit: cover;">
                                <span class="position-absolute top-0 end-0 m-3 badge bg-pink px-3 py-2 rounded-pill">Rekomendasi</span>
                            </div>
                            <div class="card-body p-4 d-flex flex-column justify-content-between">
                                <div>
                                    <h5 class="card-title fw-bold text-dark text-hover-pink mb-2"><?php echo htmlspecialchars($menu_fav['nama_menu']); ?></h5>
                                    <p class="card-text text-muted small">
                                        <?php echo htmlspecialchars($menu_fav['deskripsi']); ?>
                                    </p>
                                </div>
                                <div class="mt-3">
                                    <span class="fw-bold text-pink fs-5">Rp <?php echo number_format($menu_fav['harga_satuan'], 0, ',', '.'); ?> <small class="text-muted fs-6 fw-normal">/ porsi</small></span>
                                </div>
                            </div>
                        </a>
                    </div>
                <?php 
                    endwhile; 
                else:
                    echo '<div class="col-12 text-center py-4"><p class="text-muted">Belum ada menu rekomendasi yang dipilih oleh admin.</p></div>';
                endif;
                ?>
            </div>

            <div class="text-center mt-5">
                <a href="<?php echo $menu_link; ?>" class="btn btn-pink px-5 py-3 shadow-lg fw-bold rounded-pill">
                    Lihat 20+ Variasi Menu Lengkap Kami
                </a>
            </div>
        </div>
    </section>

    <section id="testimoni" class="py-5 bg-light">
        <div class="container">
            <div class="text-center mb-5">
                <span class="text-pink fw-bold text-uppercase small" style="letter-spacing: 2px;">Testimoni Pelanggan</span>
                <h2 class="display-5 fw-bold mt-2">Cerita Kepuasan Mereka</h2>
                <div class="mx-auto mt-3" style="width: 80px; height: 3px; background-color: var(--soft-gold, #ffc107);"></div>
            </div>

            <div class="row g-4">
                <?php 
                $query_testi = mysqli_query($conn, "SELECT * FROM testimoni ORDER BY id DESC LIMIT 3");
                while($testi = mysqli_fetch_assoc($query_testi)): 
                ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-sm rounded-4 p-4 text-center test-card">
                        <h5 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($testi['nama_user'] ?? 'Pelanggan'); ?></h5>
                        <div class="text-warning mb-3">
                            <?php 
                            $rating = $testi['rating'] ?? 5;
                            for ($i = 1; $i <= 5; $i++) {
                                echo ($i <= $rating) ? '<span class="star-icon">★</span>' : '<span class="star-icon-empty">☆</span>';
                            }
                            ?>
                        </div>
                        <p class="card-text text-muted small fst-italic mb-0">"<?php echo htmlspecialchars($testi['deskripsi']); ?>"</p>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

    <section id="isi-testimoni" class="py-5 bg-light border-top">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-10">
                    <div class="card border-0 shadow-lg p-4 p-md-5" style="border-radius: 20px; background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(10px);">
                        
                        <?php if (isset($_SESSION['notif_sukses'])): ?>
                            <div class="alert alert-success border-0 shadow-sm mx-auto mb-4 text-start alert-dismissible fade show" role="alert" style="max-width: 600px; background-color: #e6f6ec; color: #155724; border-radius: 15px;">
                                <div class="d-flex align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="bi bi-check-circle-fill me-2 flex-shrink-0" viewBox="0 0 16 16">
                                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                                    </svg>
                                    <div><strong>Berhasil!</strong> <?php echo $_SESSION['notif_sukses']; ?></div>
                                </div>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php unset($_SESSION['notif_sukses']); ?>
                        <?php endif; ?>

                        <?php if(isset($_SESSION['user_email'])): ?>
                            <ul class="nav nav-tabs nav-justified border-0 mb-4" id="feedbackTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active fs-5 border-0 bg-transparent pb-3" id="testi-tab" data-bs-toggle="tab" data-bs-target="#testi-pane" type="button" role="tab" aria-controls="testi-pane" aria-selected="true">✍️ Kirim Testimoni</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link fs-5 border-0 bg-transparent pb-3" id="komplain-tab" data-bs-toggle="tab" data-bs-target="#komplain-pane" type="button" role="tab" aria-controls="komplain-pane" aria-selected="false">⚠️ Kirim Komplain</button>
                                </li>
                            </ul>

                            <div class="tab-content" id="feedbackTabContent">
                                <div class="tab-pane fade show active" id="testi-pane" role="tabpanel" aria-labelledby="testi-tab" tabindex="0">
                                    <div class="text-center mb-4">
                                        <p class="text-muted">Bagikan kepuasan Anda setelah menikmati hidangan kami</p>
                                    </div>
                                    <form action="" method="POST">
                                        <div class="mb-3">
                                            <label class="form-label fw-600">Nama Lengkap</label>
                                            <input type="text" name="nama" class="form-control custom-input" value="<?php echo htmlspecialchars($_SESSION['nama'] ?? ''); ?>" readonly>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-muted">Produk yang Dibeli</label>
                                            <select name="nama_produk" class="form-select rounded-3" required>
                                                <option value="" disabled selected>-- Pilih Kuliner Yang Anda Pesan --</option>
                                                <?php
                                                $query_produk_testi = mysqli_query($conn, "SELECT nama_menu FROM menu ORDER BY nama_menu ASC");
                                                if (mysqli_num_rows($query_produk_testi) > 0) {
                                                    while ($produk_row = mysqli_fetch_assoc($query_produk_testi)) {
                                                        echo '<option value="' . htmlspecialchars($produk_row['nama_menu']) . '">' . htmlspecialchars($produk_row['nama_menu']) . '</option>';
                                                    }
                                                } else {
                                                    echo '<option value="" disabled>Belum ada produk di database</option>';
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-600">Rating Bintang</label>
                                            <select name="rating" class="form-select custom-input" required>
                                                <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                                                <option value="4">⭐⭐⭐⭐ (Puas)</option>
                                                <option value="3">⭐⭐⭐ (Cukup)</option>
                                                <option value="2">⭐⭐ (Kurang)</option>
                                                <option value="1">⭐ (Buruk)</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label fw-600">Ulasan / Deskripsi</label>
                                            <textarea name="pesan" class="form-control custom-input" rows="4" placeholder="Ceritakan rasa dan kualitas pelayanan kami..." required></textarea>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-pink py-3 fw-bold shadow-sm">Kirim Testimoni Sekarang</button>
                                        </div>
                                    </form>
                                </div>

                                <div class="tab-pane fade" id="komplain-pane" role="tabpanel" aria-labelledby="komplain-tab" tabindex="0">
                                    <div class="text-center mb-4">
                                        <p class="text-muted">Ada kendala dengan pesanan Anda? Beritahu kami agar kami bisa segera memperbaikinya</p>
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
                                <a href="login.php" class="btn btn-pink rounded-pill px-5 py-2.5 mt-3 shadow-sm fw-bold">Login Sekarang</a>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer id="kontak" class="py-4 bg-pink text-white">
        <div class="container">
            <div class="row g-3 justify-content-between align-items-center text-center text-md-start">
                <div class="col-md-4">
                    <h5 class="fw-bold mb-1"><?php echo $brand_name; ?></h5>
                    <p class="small mb-0 opacity-75">PREMIUM CATERING SERVICE</p>
                </div>
                <div class="col-md-8">
                    <div class="row g-2 justify-content-md-end text-md-end">
                        <div class="col-12 col-sm-auto px-3">
                            <span class="me-1">📱</span> 
                            <a href="https://wa.me/<?php echo preg_replace('/[^0-9]/', '', $web['no_wa']); ?>" target="_blank" class="text-white text-decoration-none fw-semibold small"><?php echo $web['no_wa']; ?></a>
                        </div>
                        <div class="col-12 col-sm-auto px-3 border-sm-start border-white-50">
                            <span class="me-1">👥</span> 
                            <span class="fw-semibold small"><?php echo $web['akun_fb']; ?></span>
                        </div>
                        <div class="col-12 px-3 mt-1 text-md-end">
                            <span class="me-1">📍</span> 
                            <span class="small opacity-90" style="font-size: 0.85rem;"><?php echo $web['alamat']; ?></span>
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-3 border-white-50">
            <div class="text-center text-white-50" style="font-size: 0.8rem;">
                &copy; <?php echo date("Y"); ?> <?php echo $brand_name; ?>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
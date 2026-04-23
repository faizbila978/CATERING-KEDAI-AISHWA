<?php 
session_start();

// Proteksi: Jika tidak ada session email, tendang ke login
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

include "koneksi.php";
// Logika Pencarian Sederhana
$keyword = isset($_GET['search']) ? $_GET['search'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu | <?php echo $_SESSION['nama']; ?> - Kedai Aishwa</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        :root {
            --primary-pink: #ad2d5e;
            --primary-hover: #8a244b;
            --soft-pink: #fdf2f6;
            --text-dark: #2d2d2d;
            --text-muted: #6c757d;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--text-dark);
            background-color: #fcfcfc;
        }

        /* --- Navbar --- */
        .navbar {
            background: rgba(255, 255, 255, 0.9) !important;
            backdrop-filter: blur(15px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .text-pink { color: var(--primary-pink) !important; }

        /* --- Buttons --- */
        .btn-pink {
            background-color: var(--primary-pink);
            color: white;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s;
        }

        .btn-pink:hover {
            background-color: var(--primary-hover);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(173, 45, 94, 0.3);
        }

        .btn-outline-pink {
            border: 2px solid var(--primary-pink);
            color: var(--primary-pink);
            font-weight: 700;
            border-radius: 50px;
            transition: 0.3s;
        }

        .btn-outline-pink:hover {
            background-color: var(--primary-pink);
            color: white;
        }

        /* --- Pills/Tabs --- */
        .nav-pills .nav-link {
            color: var(--text-muted);
            font-weight: 600;
            border: 1px solid #eee;
            background: white;
            margin-right: 8px;
            padding: 10px 25px;
            transition: 0.3s;
        }

        .nav-pills .nav-link.active {
            background-color: var(--primary-pink) !important;
            border-color: var(--primary-pink);
            color: white !important;
            box-shadow: 0 4px 12px rgba(173, 45, 94, 0.2);
        }

        /* --- Cards --- */
        .menu-card {
            border: none;
            border-radius: 20px;
            transition: all 0.4s ease;
            background: white;
        }

        .menu-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.08) !important;
        }

        .card-img-top {
            border-radius: 20px 20px 0 0;
            object-fit: cover;
        }

        .price-tag {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--primary-pink);
        }

        .accent-line {
            width: 60px;
            height: 4px;
            background-color: #e2b04a; /* Soft Gold dari index.php */
            border-radius: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg sticky-top py-3">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3 text-pink" href="index.php">Kedai Aishwa</a>
            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="d-none d-md-block text-end">
                    <small class="text-muted d-block" style="font-size: 0.7rem;">Selamat Datang,</small>
                    <span class="fw-bold"><?php echo explode(' ', $_SESSION['nama'])[0]; ?></span>
                </div>
                <div class="vr mx-2 d-none d-md-block"></div>
                <a href="logout.php" class="btn btn-sm btn-outline-danger px-3 rounded-pill" onclick="return confirm('Yakin ingin keluar?')">
                    <i class="bi bi-box-arrow-right me-1"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <header class="mb-5">
            <span class="text-pink fw-bold text-uppercase small" style="letter-spacing: 2px;">Koleksi Kami</span>
            <h1 class="display-5 fw-bold mt-2">Pilih <span class="text-pink">Menu</span> Favoritmu</h1>
            <div class="accent-line"></div>
            <p class="text-muted">Sajian spesial dengan cita rasa tradisional dan presentasi modern.</p>
        </header>

        <ul class="nav nav-pills mb-5" id="pills-tab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active rounded-pill" data-bs-toggle="pill" data-bs-target="#all">Semua Menu</button>
            </li>
            <li class="nav-item">
                <button class="nav-link rounded-pill" data-bs-toggle="pill" data-bs-target="#nasi-kotak">Nasi Kotak</button>
            </li>
            <li class="nav-item">
                <button class="nav-link rounded-pill" data-bs-toggle="pill" data-bs-target="#tumpeng">Tumpeng</button>
            </li>
            <li class="nav-item">
                <button class="nav-link rounded-pill" data-bs-toggle="pill" data-bs-target="#kue">Aneka Kue</button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
        <?php 
        $categories = [
            'all' => 'Semua', 
            'nasi kotak' => 'nasi-kotak', 
            'tumpeng' => 'tumpeng', 
            'kue' => 'kue'
        ];
        
        foreach ($categories as $db_cat => $tab_id): 
            $active_class = ($db_cat == 'all') ? 'show active' : '';
        ?>
            <div class="tab-pane fade <?php echo $active_class; ?>" id="<?php echo $tab_id; ?>">
                <div class="row g-4">
                    <?php 
                    if ($db_cat == 'all') {
                        $sql = "SELECT * FROM menu WHERE nama_menu LIKE '%$keyword%'";
                    } else {
                        $sql = "SELECT * FROM menu WHERE kategori = '$db_cat' AND nama_menu LIKE '%$keyword%'";
                    }

                    $result = mysqli_query($conn, $sql);
                    if(mysqli_num_rows($result) > 0):
                        while ($item = mysqli_fetch_assoc($result)): 
                    ?>
                        <div class="col-md-6 col-lg-3">
                            <div class="card menu-card h-100 shadow-sm">
                                <div class="position-relative">
                                    <img src="img/<?php echo $item['gambar']; ?>" 
                                         class="card-img-top" 
                                         style="height: 220px;" 
                                         alt="<?php echo $item['nama_menu']; ?>">
                                </div>
                                <div class="card-body p-4">
                                    <h6 class="fw-bold mb-2"><?php echo $item['nama_menu']; ?></h6>
                                    <p class="text-muted small mb-4" style="height: 40px; overflow: hidden;">
                                        <?php echo $item['deskripsi']; ?>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center mt-auto">
                                        <span class="price-tag">
                                            Rp <?php echo number_format($item['harga_satuan'], 0, ',', '.'); ?>
                                        </span>
                                        <a href="keranjang_tambah.php?id=<?php echo $item['menu_id']; ?>" 
                                           class="btn btn-outline-pink btn-sm px-4">
                                            Pesan
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endwhile; 
                    else:
                        echo "<div class='col-12 text-center py-5'><p class='text-muted'>Menu tidak ditemukan.</p></div>";
                    endif;
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    </main>

    <footer class="py-4 mt-5 text-center text-muted border-top">
        <small>&copy; <?php echo date("Y"); ?> Kedai Aishwa.</small>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
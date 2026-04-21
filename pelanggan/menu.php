<?php 
session_start();

// Proteksi: Jika tidak ada session email, tendang ke login
if (!isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

require_once 'data-menu.php'; 

// Logika Pencarian Sederhana (Skenario 2)
$keyword = isset($_GET['search']) ? $_GET['search'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="menu.css">
    <style>
        :root { --maroon: #800000; --pink-soft: #fff0f5; }
        .text-maroon { color: var(--maroon); }
        .btn-maroon-outline { border: 1px solid var(--maroon); color: var(--maroon); transition: 0.3s; }
        .btn-maroon-outline:hover { background: var(--maroon); color: #fff; }
        .nav-pills .nav-link.active { background-color: var(--maroon); }
        .nav-link { color: #666; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top py-3 shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold text-maroon" href="index.php">Kedai Catering Aishwa</a>
        
        <div class="ms-auto d-flex align-items-center gap-2">
            <span class="text-muted small d-none d-md-block me-2">
                Hi, <strong><?php echo isset($_SESSION['nama']) ? explode(' ', $_SESSION['nama'])[0] : 'Tamu'; ?></strong>
            </span>
            
            <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">Home</a>
            
            <a href="logout.php" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm" onclick="return confirm('Yakin ingin keluar?')">
    <i class="bi bi-box-arrow-right"></i> Logout
</a>
        </div>
    </div>
</nav>

    <main class="container py-5">
        <header class="row align-items-center mb-5">
            <div class="col-lg-7">
                <h1 class="fw-bold">Pilih <span class="text-maroon">Menu</span> Favoritmu</h1>
                <p class="text-muted">Sajian spesial dari Kedai Aishwa untuk setiap momen berhargamu.</p>
            </div>
            
            <div class="col-lg-5">
                <form action="" method="GET" class="input-group">
                    <input type="text" name="search" class="form-control border-end-0 rounded-start-pill ps-4" 
                           placeholder="Cari menu nasi atau snack..." value="<?php echo htmlspecialchars($keyword); ?>">
                    <button class="btn btn-maroon border-start-0 rounded-end-pill px-4 text-white" type="submit" style="background: var(--maroon);">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
            </div>
        </header>

        <ul class="nav nav-pills mb-5 gap-2" id="pills-tab" role="tablist">
            <?php 
            $tabs = ['all' => 'All Menus', 'nasi' => 'Nasi Kotak', 'ricebowl' => 'Rice Bowls', 'snack' => 'Snack & Drink'];
            foreach ($tabs as $key => $label): 
            ?>
            <li class="nav-item">
                <button class="nav-link <?php echo ($key == 'all') ? 'active' : ''; ?> rounded-pill px-4 fw-600" 
                        data-bs-toggle="pill" data-bs-target="#<?php echo $key; ?>">
                    <?php echo $label; ?>
                </button>
            </li>
            <?php endforeach; ?>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <?php 
            foreach ($tabs as $cat => $label): 
                $active_class = ($cat == 'all') ? 'show active' : '';
            ?>
            <div class="tab-pane fade <?php echo $active_class; ?>" id="<?php echo $cat; ?>">
                <div class="row g-4">
                    <?php 
                    $filtered = filterMenu($menus, $cat);
                    
                    // Filter berdasarkan keyword pencarian jika ada
                    $display_count = 0;
                    foreach ($filtered as $item): 
                        if ($keyword !== '' && stripos($item['nama'], $keyword) === false) continue;
                        $display_count++;
                    ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden">
                            <img src="<?php echo $item['gambar']; ?>" class="card-img-top" alt="Menu" style="height: 200px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h6 class="fw-bold mb-2"><?php echo $item['nama']; ?></h6>
                                <p class="text-muted small flex-grow-1" style="font-size: 0.75rem;"><?php echo $item['deskripsi']; ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="fw-bold text-maroon">Rp <?php echo number_format($item['harga'], 0, ',', '.'); ?></span>
                                    <a href="formulir.php?id=<?php echo $item['id']; ?>" class="btn btn-maroon-outline btn-sm px-3 rounded-pill">Pesan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>

                    <?php if ($display_count === 0): ?>
                        <div class="col-12 text-center py-5">
                            <p class="text-muted">Menu "<strong><?php echo htmlspecialchars($keyword); ?></strong>" tidak ditemukan.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div> 
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
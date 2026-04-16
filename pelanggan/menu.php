<?php 
// Memanggil data dari file eksternal
require_once 'data-menu.php'; 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?
    family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="menu.css">
    <style>
        .text-maroon { color: #800000; }
        .btn-maroon-outline { border: 1px solid #800000; color: #800000; }
        .btn-maroon-outline:hover { background: #800000; color: #fff; }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top py-3 shadow-sm">
        <div class="container">
            <a class="navbar-brand fw-bold text-maroon" href="index.php">Kedai Catering Aishwa</a>
            <div class="ms-auto">
                <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill">Kembali</a>
            </div>
        </div>
    </nav>

    <main class="container py-5">
        <header class="mb-5">
            <h1 class="fw-bold">Pilih <span class="text-maroon">Menu</span> Favoritmu</h1>
            <p class="text-muted">Sajian spesial dari Kedai Aishwa untuk setiap momen berhargamu.</p>
        </header>

        <ul class="nav nav-pills mb-5 gap-2" id="pills-tab" role="tablist">
            <li class="nav-item">
                <button class="nav-link active rounded-pill px-4" id="all-tab" data-bs-toggle="pill" data-bs-target="#all">All Menus</button>
            </li>
            <li class="nav-item">
                <button class="nav-link rounded-pill px-4" id="nasi-tab" data-bs-toggle="pill" data-bs-target="#nasi">Nasi Kotak</button>
            </li>
            <li class="nav-item">
                <button class="nav-link rounded-pill px-4" id="ricebowl-tab" data-bs-toggle="pill" data-bs-target="#ricebowl">Rice Bowls</button>
            </li>
            <li class="nav-item">
                <button class="nav-link rounded-pill px-4" id="snack-tab" data-bs-toggle="pill" data-bs-target="#snack">Snack & Drink</button>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <?php 
            // Daftar kategori yang akan ditampilkan di tab
            $categories = ['all', 'nasi', 'ricebowl', 'snack'];
            
            foreach ($categories as $cat): 
                $active_class = ($cat == 'all') ? 'show active' : '';
            ?>
            <div class="tab-pane fade <?php echo $active_class; ?>" id="<?php echo $cat; ?>">
                <div class="row g-4">
                    <?php 
                    $filtered = filterMenu($menus, $cat);
                    foreach ($filtered as $item): 
                    ?>
                    <div class="col-md-4 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm rounded-4">
                            <img src="<?php echo $item['gambar']; ?>" class="card-img-top" alt="Menu">
                            <div class="card-body">
                                <h6 class="fw-bold"><?php echo $item['nama']; ?></h6>
                                <p class="text-muted small"><?php echo $item['deskripsi']; ?></p>
                                <div class="d-flex justify-content-between align-items-center mt-3">
                                    <span class="fw-bold text-maroon">Rp <?php echo $item['harga']; ?></span>
                                    <a href="formulir.php" class="btn btn-maroon-outline btn-sm px-3">Pesan</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div> 
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
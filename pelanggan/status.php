<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pesanan - Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="status.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom py-3">
    <div class="container">

        <!-- Logo -->
        <a class="navbar-brand fw-bold text-maroon" href="index.php">
            CateringAtelier
        </a>

        <!-- Kanan -->
        <div class="ms-auto d-flex align-items-center gap-3">

            <!-- Login info -->
            <span class="text-muted small d-none d-md-block">
                Login sebagai <strong>Julian</strong>
            </span>

            <!-- Kembali Button -->
            <a href="index.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>


        </div>

    </div>
</nav>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">

            <div class="d-flex justify-content-between align-items-end mb-4">
                <div>
                    <h2 class="fw-bold mb-1">Status Pesanan</h2>
                    <p class="text-muted mb-0">ID Pesanan: <span class="text-maroon fw-bold">#KA-928374</span></p>
                </div>
                <div class="text-end">
                    <span class="badge bg-soft-pink text-maroon px-3 py-2 rounded-pill">
                        ESTIMASI SAMPAI: 12:45 WIB
                    </span>
                </div>
            </div>

            <div class="row g-4">

                <div class="col-md-7">
                    <div class="card border-0 shadow-sm p-4 rounded-4 h-100">

                        <div class="order-track">

                            <div class="order-track-step completed">
                                <div class="order-track-status">
                                    <span class="order-track-status-dot"><i class="bi bi-check-lg"></i></span>
                                    <span class="order-track-status-line"></span>
                                </div>
                                <div class="order-track-text">
                                    <p class="mb-0 fw-bold">Pesanan Dibuat</p>
                                    <span class="text-muted small">Pesanan diterima pada 09:30 WIB</span>
                                </div>
                            </div>

                            <div class="order-track-step completed">
                                <div class="order-track-status">
                                    <span class="order-track-status-dot"><i class="bi bi-check-lg"></i></span>
                                    <span class="order-track-status-line"></span>
                                </div>
                                <div class="order-track-text">
                                    <p class="mb-0 fw-bold">Sedang Dimasak</p>
                                    <span class="text-muted small">Chef sedang menyiapkan pesanan Anda</span>
                                </div>
                            </div>

                            <div class="order-track-step active">
                                <div class="order-track-status">
                                    <span class="order-track-status-dot"></span>
                                    <span class="order-track-status-line"></span>
                                </div>
                                <div class="order-track-text">
                                    <p class="mb-0 fw-bold">Dalam Perjalanan</p>
                                    <span class="text-muted small">Pesanan menuju lokasi Anda</span>
                                </div>
                            </div>

                            <div class="order-track-step">
                                <div class="order-track-status">
                                    <span class="order-track-status-dot"></span>
                                </div>
                                <div class="order-track-text">
                                    <p class="mb-0 fw-bold">Terkirim</p>
                                    <span class="text-muted small">Selamat menikmati makanan Anda</span>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>

                <div class="col-md-5">

                    <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
                        <h6 class="fw-bold mb-3">Alamat Pengiriman</h6>
                        <div class="d-flex gap-3">
                            <i class="bi bi-geo-alt text-maroon fs-4"></i>
                            <p class="small text-muted mb-0">
                                Jalan Merdeka No. 123, Blok C, Kebayoran Baru, Jakarta Selatan, 12110.
                            </p>
                        </div>
                    </div>

                    <a href="index.php" class="btn btn-outline-maroon w-100 py-3 rounded-3 fw-bold">
                        Kembali ke Beranda
                    </a>

                </div>

            </div>

        </div>
    </div>
</div>

</body>
</html>
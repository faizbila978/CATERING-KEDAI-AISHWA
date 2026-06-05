<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Kedai Aishwa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
    body{
        font-family:'Plus Jakarta Sans', sans-serif;
        background:#fff0f5;
        overflow: hidden; /* Mencegah scroll saat animasi welcome berjalan */
    }

    /* === ANIMASI WELCOME OVERLAY === */
    .welcome-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100vh;
        background: linear-gradient(135deg, #ff4f81, #ff85a2);
        z-index: 9999;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: white;
        animation: fadeOutBg 1s cubic-bezier(0.77, 0, 0.175, 1) forwards;
        animation-delay: 2.2s; /* Durasi background menetap sebelum menghilang */
    }

    .welcome-text {
        font-size: 2.5rem;
        font-weight: 700;
        letter-spacing: 2px;
        opacity: 0;
        transform: translateY(30px);
        animation: fadeInUp 0.8s ease forwards;
        animation-delay: 0.3s;
    }

    .welcome-subtext {
        font-size: 1.1rem;
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.8s ease forwards;
        animation-delay: 0.8s;
    }

    /* Animasi text loading ring */
    .welcome-loader {
        margin-top: 25px;
        width: 40px;
        height: 40px;
        border: 4px solid rgba(255, 255, 255, 0.3);
        border-top: 4px solid white;
        border-radius: 50%;
        animation: spin 1s linear infinite;
    }

    /* Keyframes Animasi */
    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes spin {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }

    @keyframes fadeOutBg {
        0% {
            transform: translateY(0);
        }
        100% {
            transform: translateY(-100%);
            visibility: hidden;
        }
    }

    /* === ANIMASI HALAMAN LOGIN (KONTEN UTAMA) === */
    .main-content {
        width: 100%;
        opacity: 0;
        transform: scale(0.95);
        animation: fadeInContent 1s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        animation-delay: 2.6s; /* Muncul tepat setelah tirai welcome naik */
    }

    @keyframes fadeInContent {
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    /* === STYLE LOGIN CARD === */
    .login-card{
        width:950px;
        border-radius:25px;
    }

    .bg-admin{
        background:linear-gradient(135deg,#ff4f81,#ff85a2);
    }

    .text-maroon{
        color:#ff4f81;
    }

    .custom-input{
        height:55px;
        border-radius:12px;
        border:1px solid #ffd1dc;
        padding-left:15px;
        transition:.3s;
    }

    .custom-input:focus{
        box-shadow:0 0 10px rgba(255,79,129,0.2);
        border-color:#ff4f81;
    }

    .btn-admin{
        background:#ff4f81;
        color:white;
        border:none;
        border-radius:12px;
        transition:.3s;
    }

    .btn-admin:hover{
        background:#e63e6d;
        color:white;
    }

    .card{
        background:white;
    }

    a{
        color:#ff4f81 !important;
    }
</style>
</head>
<body>

<!-- 1. LAYER ANIMASI WELCOME -->
<div class="welcome-overlay">
    <h1 class="welcome-text">Selamat Datang</h1>
    <p class="welcome-subtext">di Culinary Atelier Kedai Aishwa</p>
    <div class="welcome-loader"></div>
</div>

<!-- 2. KONTEN UTAMA LOGIN (Sudah diperbaiki posisi Flexbox-nya agar presisi di tengah layar) -->
<div class="main-content container d-flex align-items-center justify-content-center min-vh-100">

    <div class="card login-card shadow-lg border-0 overflow-hidden">

        <div class="row g-0">

            <!-- KIRI -->
            <div class="col-lg-5 d-none d-lg-flex bg-admin text-white p-5 align-items-center">

                <div>
                    <h2 class="fw-bold">
                        Admin Kedai Aishwa
                    </h2>

                    <p class="opacity-75 mt-3">
                        Kelola pesanan pelanggan, produk catering,
                        pembayaran, dan laporan transaksi dengan mudah.
                    </p>
                </div>

            </div>

            <!-- KANAN -->
            <div class="col-lg-7 bg-white p-4 p-md-5">

                <div class="text-center mb-4">

                    <h3 class="fw-bold text-maroon">
                        Login Admin
                    </h3>

                    <p class="text-muted small">
                        Silakan masuk ke dashboard admin
                    </p>

                </div>

                <form action="login_process.php" method="POST">

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">
                            EMAIL ADMIN
                        </label>

                        <input type="email"
                               name="email"
                               class="form-control custom-input"
                               placeholder="admin@gmail.com"
                               required>
                    </div>

                    <div class="mb-4">

                        <label class="form-label fw-bold small text-secondary">
                            PASSWORD
                        </label>

                        <input type="password"
                               name="password"
                               class="form-control custom-input"
                               placeholder="••••••••"
                               required>
                    </div>

                    <button type="submit"
                            class="btn btn-admin w-100 py-3 fw-bold shadow-sm">

                        Login Sekarang

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<!-- JavaScript untuk mengembalikan scroll setelah animasi selesai -->
<script>
    setTimeout(() => {
        document.body.style.overflow = 'auto';
    }, 3200); // Sesuai dengan total durasi transisi selesai
</script>

</body>
</html>
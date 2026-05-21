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
    }

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

<div class="container d-flex align-items-center justify-content-center min-vh-100">

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

</body>
</html>
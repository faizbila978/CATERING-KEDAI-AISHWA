<?php
// Session bisa dimulai di sini jika sudah masuk ke tahap autentikasi
// session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Back - Catering Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root { --maroon: #800000; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; }
        .text-maroon { color: var(--maroon); }
        .btn-maroon { background-color: var(--maroon); color: white; border: none; transition: 0.3s; }
        .btn-maroon:hover { background-color: #600000; color: white; transform: translateY(-2px); }
        
        /* Perbaikan agar tidak gepeng */
        .login-card { 
            max-width: 1000px; 
            width: 100%; 
            border-radius: 24px; 
            border: none;
        }
        
        .img-side {
            min-height: 100%;
            object-fit: cover;
        }

        .divider hr { border-top: 1px solid #dee2e6; opacity: 1; }
        
        /* Pastikan container mengambil tinggi layar */
        .main-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-maroon" href="index.php">Kedai Aishwa</a>
            <div class="ms-auto d-none d-lg-block">
                <a href="index.php" class="nav-link d-inline me-4">Home</a>
                <a href="menu.php" class="nav-link d-inline me-4">Order Now</a>
                <a href="#" class="btn btn-maroon px-4 rounded-pill">Request Quote</a>
            </div>
        </div>
    </nav>

    <div class="main-wrapper">
        <div class="card login-card shadow-lg overflow-hidden mt-5">
            <div class="row g-0">
                
                <div class="col-lg-6 d-none d-lg-block position-relative">
                    <img src="img/login-bg.jpg" class="img-side w-100 h-100" alt="Fine Dining">
                    <div class="position-absolute bottom-0 start-0 p-5 text-white" style="background: linear-gradient(transparent, rgba(0,0,0,0.9)); width: 100%;">
                        <h2 class="fw-bold">Crafting Memories Through Fine Dining</h2>
                        <p class="text-white-50 mb-0">Join our exclusive culinary circle and manage your bespoke catering events with ease.</p>
                    </div>
                </div>

                <div class="col-lg-6 p-4 p-md-5 bg-white">
                    <div class="mb-4 mt-2">
                        <h3 class="fw-bold mb-1">Welcome Back</h3>
                        <p class="text-muted">Sign in to Catering Kedai Aishwa</p>
                    </div>

                    <form action="login_process.php" method="POST">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
                            <input type="email" name="email" class="form-control bg-light border-0 py-3 px-4" placeholder="name@example.com" required>
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between">
                                <label class="form-label small fw-bold text-muted text-uppercase">Password</label>
                                <a href="forgot.php" class="text-maroon small text-decoration-none fw-semibold">Lupa Password?</a>
                            </div>
                            <input type="password" name="password" class="form-control bg-light border-0 py-3 px-4" placeholder="••••••••" required>
                        </div>

                        <button type="submit" name="login" class="btn btn-maroon w-100 py-3 rounded-3 fw-bold shadow-sm mb-3">
                            Masuk Sekarang <i class="bi bi-arrow-right ms-2"></i>
                        </button>
                    </form>

                    <p class="text-center small text-muted">
                        Belum punya akun? <a href="register.php" class="text-maroon fw-bold text-decoration-none">Daftar di sini</a>
                    </p>

                    <div class="divider my-4 text-center position-relative">
                        <hr>
                        <span class="bg-white px-3 text-muted position-absolute top-50 start-50 translate-middle" style="font-size: 11px; font-weight: 700;">ATAU LANJUTKAN DENGAN</span>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <button class="btn btn-outline-dark w-100 py-2 d-flex align-items-center justify-content-center gap-2" style="border-color: #dee2e6;">
                                <img src="https://www.google.com/favicon.ico" width="16" alt="google"> Google
                            </button>
                        </div>
                        <div class="col-6">
                            <button class="btn btn-outline-dark w-100 py-2 d-flex align-items-center justify-content-center gap-2" style="border-color: #dee2e6;">
                                <i class="bi bi-apple"></i> Apple
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
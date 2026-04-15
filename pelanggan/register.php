<?php
// Di sini nanti tempat menaruh logika autentikasi
// Contoh: if(isset($_POST['login'])) { ... cek database ... }
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Back - Catering Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="login.css">
    <style>
        .text-maroon { color: #800000; }
        .btn-maroon { background-color: #800000; color: white; border: none; }
        .btn-maroon:hover { background-color: #600000; color: white; }
        .login-card { max-width: 900px; width: 100%; }
        .login-container { min-height: 90vh; }
        .image-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(transparent, rgba(0,0,0,0.8));
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
        <div class="container">
            <a class="navbar-brand fw-bold text-maroon" href="index.php">CateringAtelier</a>
            <div class="ms-auto d-none d-lg-block">
                <a href="index.php" class="nav-link d-inline me-4">Home</a>
                <a href="menu.php" class="nav-link d-inline me-4">Order Now</a>
                <a href="#" class="btn btn-maroon px-4 rounded-pill">Request Quote</a>
            </div>
        </div>
    </nav>

    <div class="container login-container d-flex align-items-center justify-content-center py-5">
        <div class="card login-card shadow-lg border-0 overflow-hidden">
            <div class="row g-0">
                <div class="col-lg-6 d-none d-lg-block position-relative">
                    <img src="img/login-bg.jpg" class="h-100 w-100 object-fit-cover" alt="Fine Dining">
                    <div class="image-overlay p-5 d-flex flex-column justify-content-end">
                        <h2 class="text-white fw-bold">Crafting Memories Through Fine Dining</h2>
                        <p class="text-white-50">Join our exclusive culinary circle and manage your bespoke catering events with ease.</p>
                    </div>
                </div>

                <div class="col-lg-6 p-lg-5 p-4 bg-white">
                    <div class="w-100">
                        <div class="mb-4">
                            <h3 class="fw-bold mb-1">Welcome Back</h3>
                            <p class="text-muted">Sign in to Catering Kedai Aishwa</p>
                        </div>

                        <form action="login_process.php" method="POST">
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Email</label>
                                <input type="email" name="email" class="form-control bg-light border-0 py-3 px-4" placeholder="Email anda" required>
                            </div>

                            <div class="mb-4">
                                <div class="d-flex justify-content-between">
                                    <label class="form-label small fw-bold text-muted">Password</label>
                                    <a href="forgot_password.php" class="text-maroon small text-decoration-none">Lupa Password?</a>
                                </div>
                                <input type="password" name="password" class="form-control bg-light border-0 py-3 px-4" placeholder="••••••••" required>
                            </div>

                            <button type="submit" name="login" class="btn btn-maroon w-100 py-3 rounded-3 fw-bold shadow-sm">
                                Masuk Sekarang <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </form>

                        <div class="text-center mt-4">
                            <p class="small text-muted">
                                Belum punya akun? <a href="register.php" class="text-maroon fw-bold text-decoration-none">Daftar di sini</a>
                            </p>
                        </div>

                        <div class="divider my-4 text-center position-relative">
                            <hr class="text-muted">
                            <span class="bg-white px-3 text-muted small position-absolute top-50 start-50 translate-middle">OR CONTINUE WITH</span>
                        </div>

                        <div class="row g-2">
                            <div class="col-6">
                                <button class="btn btn-outline-light text-dark w-100 border py-2 d-flex align-items-center justify-content-center gap-2">
                                    <img src="https://www.google.com/favicon.ico" width="16" alt="google"> Google
                                </button>
                            </div>
                            <div class="col-6">
                                <button class="btn btn-outline-light text-dark w-100 border py-2 d-flex align-items-center justify-content-center gap-2">
                                    <i class="bi bi-apple"></i> Apple
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
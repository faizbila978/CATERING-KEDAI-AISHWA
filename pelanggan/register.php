<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="register.css">
</head>
<body>

<div class="container d-flex align-items-center justify-content-center min-vh-100">
    <div class="card register-card shadow-lg border-0 overflow-hidden">
        <div class="row g-0">
            <div class="col-lg-5 d-none d-lg-block bg-pink-gradient p-5 text-white d-flex align-items-center">
                <div>
                    <h2 class="fw-bold">Gabung Sekarang</h2>
                    <p class="opacity-75">Buat akun untuk menikmati layanan catering terbaik dengan menu nusantara autentik.</p>
                </div>
            </div>
            
            <div class="col-lg-7 p-4 p-md-5 bg-white">
                <div class="text-center mb-4">
                    <h3 class="fw-bold text-pink">Buat Akun</h3>
                    <p class="text-muted small">Lengkapi data diri Anda untuk mulai memesan</p>
                </div>

                <form action="register_process.php" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">NAMA LENGKAP</label>
                        <input type="text" name="nama_lengkap" class="form-control custom-input" placeholder="Masukkan nama lengkap" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-secondary">EMAIL</label>
                        <input type="email" name="email" class="form-control custom-input" placeholder="name@example.com" required>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label fw-bold small text-secondary">PASSWORD</label>
                        <input type="password" name="password" class="form-control custom-input" placeholder="••••••••" required>
                    </div>

                    <button type="submit" name="register" class="btn btn-pink-action w-100 py-3 fw-bold shadow-sm">
                        Daftar Sekarang
                    </button>
                </form>
                
                <div class="text-center mt-4">
                    <p class="small text-muted">Sudah punya akun? 
                        <a href="login.php" class="text-pink fw-bold text-decoration-none">Login di sini</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
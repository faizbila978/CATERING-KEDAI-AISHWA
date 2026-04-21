<?php
session_start();
// Tangkap ID menu jika ada kiriman dari tombol pesan
$checkout_id = isset($_GET['checkout_id']) ? $_GET['checkout_id'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --maroon: #800000; }
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; min-height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { max-width: 450px; width: 100%; border-radius: 20px; border: none; }
        .btn-maroon { background-color: var(--maroon); color: white; border: none; }
        .btn-maroon:hover { background-color: #600000; color: white; }
    </style>
</head>
<body>

<div class="card login-card shadow-lg p-4">
    <div class="text-center mb-4">
        <h3 class="fw-bold">Welcome Back</h3>
        <p class="text-muted">Silakan login untuk memesan</p>
    </div>

    <form action="login_process.php" method="POST">
        <input type="hidden" name="redirect_id" value="<?php echo $checkout_id; ?>">

        <div class="mb-3">
            <label class="form-label fw-bold small">EMAIL</label>
            <input type="email" name="email" class="form-control py-2" placeholder="email@gmail.com" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold small">PASSWORD</label>
            <input type="password" name="password" class="form-control py-2" placeholder="••••••••" required>
        </div>
        <button type="submit" name="login" class="btn btn-maroon w-100 py-2 fw-bold">Masuk Sekarang</button>
    </form>
    
    <p class="text-center mt-3 small">Belum punya akun? <a href="register.php" class="text-danger fw-bold">Daftar</a></p>
</div>

</body>
</html>
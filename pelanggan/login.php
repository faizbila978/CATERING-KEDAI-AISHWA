<?php
session_start();
$checkout_id = isset($_GET['checkout_id']) ? $_GET['checkout_id'] : '';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { --pink-primary: #ff69b4; --pink-hover: #e0559f; }
        body { 
            font-family: 'Plus Jakarta Sans', sans-serif; 
            background-color: #fff0f5; 
            min-height: 100vh; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
        }
        .login-card { max-width: 400px; width: 100%; border-radius: 20px; border: none; }
        .btn-pink { background-color: var(--pink-primary); color: white; border: none; }
        .btn-pink:hover { background-color: var(--pink-hover); color: white; }
        .text-pink { color: var(--pink-primary); }
    </style>
</head>
<body>

<div class="card login-card shadow-lg p-4">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-pink">Welcome Back</h3>
        <p class="text-muted">Silakan login untuk memesan</p>
    </div>

    <form action="login_process.php" method="POST">
        <input type="hidden" name="redirect_id" value="<?php echo htmlspecialchars($checkout_id); ?>">

        <div class="mb-3">
            <label class="form-label fw-bold small">EMAIL</label>
            <input type="email" name="email" class="form-control py-2" placeholder="email@gmail.com" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-bold small">PASSWORD</label>
            <input type="password" name="password" class="form-control py-2" placeholder="••••••••" required>
        </div>
        <button type="submit" name="login" class="btn btn-pink w-100 py-2 fw-bold shadow-sm">Masuk Sekarang</button>
    </form>
    
    <p class="text-center mt-4 small">Belum punya akun? <a href="register.php" class="text-pink fw-bold text-decoration-none">Daftar</a></p>
</div>

</body>
</html>
<<<<<<< HEAD
=======
<<<<<<< HEAD
=======
<?php
// Di sini nanti tempat menaruh logika autentikasi
// Contoh: if(isset($_POST['login'])) { ... cek database ... }
?>

>>>>>>> 9c32336805fc3f254a23636c348bef075027d3ba
>>>>>>> 47e0988
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
<<<<<<< HEAD
    <title>Register - Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root { --pink: #ff69b4; } /* warna utama pink */

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fff0f5; /* background soft pink */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .card { 
            max-width: 450px; 
            width: 100%; 
            border-radius: 20px; 
        }

        .btn-pink { 
            background-color: var(--pink); 
            color: white; 
            border: none;
        }

        .btn-pink:hover { 
            background-color: #e0559f; 
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

<div class="card p-4 shadow" style="width:400px;">
    <h3 class="text-center mb-3">Daftar</h3>

    <form action="register_process.php" method="POST">

        <div class="mb-3">
            <label>Nama Lengkap</label>
            <input type="text" name="nama_lengkap" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-pink w-100">Daftar</button>
    </form>

    <p class="text-center mt-3">
        Sudah punya akun? <a href="login.php">Login</a>
    </p>
</div>

=======
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root { 
            --pink-main: #ff69b4; 
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #fff0f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-card { max-width: 400px; width: 100%; border-radius: 20px; border: none; }
        .text-pink { color: var(--pink-main) !important; }
        .btn-pink { background-color: var(--pink-main); color: white; border: none; transition: 0.3s; }
        .btn-pink:hover { background-color: #e0559f; color: white; transform: translateY(-2px); }
    </style>
</head>
<body>

<div class="card register-card p-4 shadow-lg">
    <div class="text-center mb-4">
        <h3 class="fw-bold text-pink">Buat Akun</h3>
        <p class="text-muted">Daftar untuk mulai memesan</p>
    </div>

<<<<<<< HEAD
    <form action="register_process.php" method="POST">
        <div class="mb-3">
            <label class="form-label fw-bold small text-muted">NAMA LENGKAP</label>
            <input type="text" name="nama_lengkap" class="form-control py-2" placeholder="Nama Lengkap" required>
        </div>

        <div class="mb-3">
            <label class="form-label fw-bold small text-muted">EMAIL</label>
            <input type="email" name="email" class="form-control py-2" placeholder="email@gmail.com" required>
        </div>

        <div class="mb-4">
            <label class="form-label fw-bold small text-muted">PASSWORD</label>
            <input type="password" name="password" class="form-control py-2" placeholder="••••••••" required>
        </div>

        <button type="submit" class="btn btn-pink w-100 py-2 fw-bold shadow-sm rounded-pill">Daftar Sekarang</button>
    </form>

    <p class="text-center mt-4 small">
        Sudah punya akun? <a href="login.php" class="text-pink fw-bold text-decoration-none">Login</a>
    </p>
</div>

=======
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
>>>>>>> 9c32336805fc3f254a23636c348bef075027d3ba
>>>>>>> 47e0988
</body>
</html>
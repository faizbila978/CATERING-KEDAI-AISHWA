<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
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

</body>
</html>
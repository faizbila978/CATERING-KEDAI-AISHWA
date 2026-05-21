<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "catering_kedai_aishwa");

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = mysqli_real_escape_string($koneksi, $_POST['email']);
    $password = mysqli_real_escape_string($koneksi, $_POST['password']);

    // Cek email, password, dan role admin di tabel users
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password' AND role = 'admin'";
    $result = mysqli_query($koneksi, $query);

    if (mysqli_num_rows($result) > 0) {
        $data = mysqli_fetch_assoc($result);
        
        // Simpan data ke session
        $_SESSION['user_id'] = $data['user_id'];
        $_SESSION['role'] = $data['role'];
        $_SESSION['nama_lengkap'] = $data['nama_lengkap'];

        header("Location: index.php");
        exit();
    } else {
        // Jika gagal, kembali ke login dengan pesan error
        header("Location: login.php?status=gagal");
        exit();
    }
}
?>
<?php
session_start();

$conn = mysqli_connect("localhost", "root", "", "catering_kedai_aishwa");

// cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// ambil data dari form
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// ambil data user berdasarkan email
$query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
$data = mysqli_fetch_assoc($query);

// cek user
if ($data) {

    // cek password (karena pakai hash)
    if (password_verify($password, $data['password'])) {

        // simpan session
        $_SESSION['user_id'] = $data['user_id'];
        $_SESSION['nama'] = $data['nama_lengkap'];
        $_SESSION['role'] = $data['role'];
        $_SESSION['user_email'] = $data['email'];

        // 🔥 BEDAIN DI SINI
        if ($data['role'] == 'admin') {
            header("Location: dashboard.php");
        } else {
            header("Location: menu.php");
        }
        exit;

    } else {
        echo "<script>alert('Password salah!'); window.location='login.php';</script>";
    }

} else {
    echo "<script>alert('Email tidak ditemukan!'); window.location='login.php';</script>";
}
?>

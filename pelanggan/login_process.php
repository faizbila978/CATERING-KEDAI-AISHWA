<?php
session_start();
include 'koneksi.php';

if (isset($_POST['login'])) {
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    // Query ke tabel 'user' sesuai database kamu
    $query = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $data   = mysqli_fetch_assoc($result);

    if ($data) {
        // Cek password (mendukung plain text 'admin123' atau password_verify)
        if ($password === $data['password'] || password_verify($password, $data['password'])) {
            $_SESSION['login']   = true;
            $_SESSION['user_id'] = $data['user_id'];
            $_SESSION['nama']    = $data['nama_lengkap'];
            $_SESSION['role']    = $data['role'];

            // Arahkan sesuai role
            if ($data['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            echo "<script>alert('Password salah!'); window.location='login.php';</script>";
        }
    } else {
        echo "<script>alert('Email tidak terdaftar!'); window.location='login.php';</script>";
    }
}
?>
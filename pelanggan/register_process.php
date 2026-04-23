<?php
$conn = mysqli_connect("localhost", "root", "", "catering_kedai_aishwa");

// cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// ambil data dari form
$nama = $_POST['nama_lengkap'] ?? '';
$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// 🔥 TANPA HASH
$password_plain = $password;

// cek email
$cek = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");

if (!$cek) {
    die("Query error: " . mysqli_error($conn));
}

if (mysqli_num_rows($cek) > 0) {
    echo "<script>
    alert('Email sudah terdaftar!');
    window.location='register.php';
    </script>";
    exit;
}

// insert plaintext password
$insert = mysqli_query($conn, "INSERT INTO users (nama_lengkap, email, password) 
VALUES ('$nama', '$email', '$password_plain')");

if (!$insert) {
    die("Insert error: " . mysqli_error($conn));
}

// sukses
echo "<script>
alert('Registrasi berhasil!');
window.location='login.php';
</script>";
?>
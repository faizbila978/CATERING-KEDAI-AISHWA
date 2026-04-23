<?php
session_start();

// Ambil ID menu dari URL
$id = $_GET['id'];

// Jika keranjang belum ada, buat array kosong
if (!isset($_SESSION['keranjang'])) {
    $_SESSION['keranjang'] = [];
}

// Tambahkan menu ke keranjang (jika sudah ada, tambah jumlahnya)
if (isset($_SESSION['keranjang'][$id])) {
    $_SESSION['keranjang'][$id]++;
} else {
    $_SESSION['keranjang'][$id] = 1;
}

// Langsung lempar ke halaman formulir
header("Location: formulir.php");
exit();
<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location='login.php';</script>";
    exit();
}

$pembayaran_id = $_POST['pembayaran_id'] ?? null;
$aksi = $_POST['aksi'] ?? null; // Menangkap parameter aksi

if (!$pembayaran_id) {
    echo "<script>alert('ID Pembayaran tidak ditemukan!'); window.history.back();</script>";
    exit();
}

// Cari pesanan_id terkait
$res = mysqli_query($conn, "SELECT pesanan_id FROM pembayaran WHERE pembayaran_id = '$pembayaran_id'");
$data = mysqli_fetch_assoc($res);
$pesanan_id = $data['pesanan_id'];

if ($aksi == 'konfirmasi_pembayaran') {
    // TAHAP 1: Konfirmasi Uang Masuk
    $q1 = "UPDATE pembayaran SET status_pembayaran = 'Selesai' WHERE pembayaran_id = '$pembayaran_id'";
    $q2 = "UPDATE pesanan SET status_pesanan = 'Sedang Diproses' WHERE pesanan_id = '$pesanan_id'";
    
    if (mysqli_query($conn, $q1) && mysqli_query($conn, $q2)) {
        echo "<script>alert('Pembayaran Selesai! Pesanan kini dalam status diproses.'); window.location='manajemen_pesanan.php';</script>";
    }
} 
elseif ($aksi == 'kirim_pesanan') {
    // TAHAP 2: Pengiriman Barang
    $q3 = "UPDATE pesanan SET status_pesanan = 'Dikirim' WHERE pesanan_id = '$pesanan_id'";
    
    if (mysqli_query($conn, $q3)) {
        echo "<script>alert('Pesanan berhasil ditandai sebagai Dikirim!'); window.location='manajemen_pesanan.php';</script>";
    }
}
?>
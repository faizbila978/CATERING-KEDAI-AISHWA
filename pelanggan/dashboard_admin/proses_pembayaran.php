<?php
session_start();
include 'koneksi.php';

// Pastikan user adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location='login.php';</script>";
    exit();
}

// Ambil pembayaran_id dari POST atau GET
$pembayaran_id = $_POST['pembayaran_id'] ?? $_GET['pembayaran_id'] ?? null;

if (!$pembayaran_id) {
    echo "<script>alert('ID Pembayaran tidak ditemukan!'); window.history.back();</script>";
    exit();
}

// Ambil pesanan_id dari pembayaran
$query_pembayaran = "SELECT pesanan_id FROM pembayaran WHERE pembayaran_id = '$pembayaran_id'";
$result = mysqli_query($conn, $query_pembayaran);
$pembayaran = mysqli_fetch_assoc($result);

if (!$pembayaran) {
    echo "<script>alert('Pembayaran tidak ditemukan!'); window.history.back();</script>";
    exit();
}

$pesanan_id = $pembayaran['pesanan_id'];

// UPDATE STATUS PEMBAYARAN MENJADI "Selesai"
$update_query = "UPDATE pembayaran SET status_pembayaran = 'Selesai' WHERE pembayaran_id = '$pembayaran_id'";

if (mysqli_query($conn, $update_query)) {
    // UPDATE STATUS PESANAN JUGA
    mysqli_query($conn, "UPDATE pesanan SET status_pesanan = 'Dikonfirmasi' WHERE pesanan_id = '$pesanan_id'");
    
    echo "<script>alert('Pembayaran berhasil dikonfirmasi!'); window.location='manajemen_pesanan.php';</script>";
} else {
    echo "<script>alert('Gagal mengupdate pembayaran: " . mysqli_error($conn) . "'); window.history.back();</script>";
}
?>
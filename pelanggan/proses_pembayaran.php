<?php
include 'koneksi.php';

$pesanan_id = $_POST['pesanan_id'];
$metode     = $_POST['metode_pembayaran'];

// Update pembayaran
mysqli_query($conn, "UPDATE pembayaran 
SET metode_pembayaran='$metode',
    status_pembayaran='Sudah Bayar'
WHERE pesanan_id='$pesanan_id'");

// Update status pesanan
mysqli_query($conn, "UPDATE pesanan 
SET status_pesanan='Diproses'
WHERE pesanan_id='$pesanan_id'");

// Redirect ke status
header("Location: status.php?id=$pesanan_id");
exit;
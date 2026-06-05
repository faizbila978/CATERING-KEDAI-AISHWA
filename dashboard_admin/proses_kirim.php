<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pesanan_id'])) {
    $pesanan_id = mysqli_real_escape_string($conn, $_POST['pesanan_id']);

    // Update status_pesanan menjadi 'Dikirim'
    $query = "UPDATE pesanan SET status_pesanan = 'Dikirim' WHERE pesanan_id = '$pesanan_id'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Status pesanan berhasil diupdate menjadi DIKIRIM!'); window.location='manajemen_pesanan.php';</script>";
    } else {
        echo "<script>alert('Gagal update status!'); window.location='manajemen_pesanan.php';</script>";
    }
}
?>
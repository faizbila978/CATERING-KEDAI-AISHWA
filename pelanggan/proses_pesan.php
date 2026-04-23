<?php
include 'koneksi.php';
session_start();

// Validasi keranjang
if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    die("Keranjang kosong!");
}

// Ambil data
$user_id     = $_SESSION['user_id'];
$tgl_acara   = $_POST['tanggal_acara'] ?? null;
$waktu_acara = $_POST['waktu_acara'] ?? null;
$alamat      = $_POST['alamat'];
$no_hp       = $_POST['no_hp'];
$total_bayar = $_POST['total_harga'];

// 1. INSERT PESANAN
$query_pesan = "INSERT INTO pesanan 
(user_id, tanggal_pesan, tanggal_acara, waktu_acara, alamat, no_handphone, total_pesan, status_pesanan) 
VALUES 
('$user_id', CURDATE(), '$tgl_acara', '$waktu_acara', '$alamat', '$no_hp', '$total_bayar', 'Menunggu Pembayaran')";

if (mysqli_query($conn, $query_pesan)) {

    $id_pesanan_baru = mysqli_insert_id($conn);

    // 2. INSERT DETAIL PESANAN (loop keranjang)
    foreach ($_SESSION['keranjang'] as $menu_id => $qty) {

        $query_menu = mysqli_query($conn, "SELECT * FROM menu WHERE menu_id = '$menu_id'");
        $menu = mysqli_fetch_assoc($query_menu);

        if ($menu) {
            $harga = $menu['harga_satuan'];
            $total = $harga * $qty;

            mysqli_query($conn, "INSERT INTO detail_pesanan 
            (pesanan_id, menu_id, harga_satuan, jumlah_menu, total_detail_pesanan, status_detail_pesanan) 
            VALUES 
            ('$id_pesanan_baru', '$menu_id', '$harga', '$qty', '$total', 'Diproses')");
        }
    }

    // 3. INSERT PEMBAYARAN
    mysqli_query($conn, "INSERT INTO pembayaran 
    (pesanan_id, status_pembayaran, total_pembayaran) 
    VALUES 
    ('$id_pesanan_baru', 'Belum Bayar', '$total_bayar')");

    // 4. Kosongkan keranjang
    unset($_SESSION['keranjang']);

    echo "<script>alert('Pesanan berhasil!'); window.location='payment.php';</script>";

} else {
    echo "Gagal: " . mysqli_error($conn);
}
?>
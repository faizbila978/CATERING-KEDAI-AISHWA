<?php
include 'koneksi.php';
session_start();

// Validasi keranjang
if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    echo "<script>alert('Keranjang kosong!'); window.location='menu.php';</script>";
    exit();
}

// Ambil data
$user_id     = $_SESSION['user_id'];
$nama        = $_POST['nama'] ?? null;
$tgl_acara   = $_POST['tanggal_acara'] ?? null;
$waktu_acara = $_POST['waktu_acara'] ?? null;
$alamat      = $_POST['alamat'] ?? null;
$no_hp       = $_POST['no_hp'] ?? null;
$catatan     = $_POST['catatan'] ?? null;
$total_bayar = $_POST['total_harga'] ?? 0;

// Validasi input
if (!$nama || !$tgl_acara || !$waktu_acara || !$alamat || !$no_hp) {
    echo "<script>alert('Semua field harus diisi!'); window.history.back();</script>";
    exit();
}

// Escape input untuk keamanan
$nama = mysqli_real_escape_string($conn, $nama);
$alamat = mysqli_real_escape_string($conn, $alamat);
$no_hp = mysqli_real_escape_string($conn, $no_hp);
$catatan = mysqli_real_escape_string($conn, $catatan);

// 1. INSERT PESANAN
$query_pesan = "INSERT INTO pesanan 
(user_id, tanggal_pesan, tanggal_acara, waktu_acara, alamat, no_handphone, catatan, total_pesan, status_pesanan) 
VALUES 
('$user_id', CURDATE(), '$tgl_acara', '$waktu_acara', '$alamat', '$no_hp', '$catatan', '$total_bayar', 'Belum Konfirmasi')";

if (mysqli_query($conn, $query_pesan)) {

    $id_pesanan_baru = mysqli_insert_id($conn);

    // SET SESSION PESANAN ID - PENTING!
    $_SESSION['pesanan_id'] = $id_pesanan_baru;

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

    // 5. REDIRECT KE PAYMENT PAGE
    echo "<script>alert('Pesanan berhasil dibuat!'); window.location='payment.php';</script>";

} else {
    echo "<script>alert('Gagal: " . mysqli_error($conn) . "'); window.history.back();</script>";
}
?>
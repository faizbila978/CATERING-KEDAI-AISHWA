<?php
include 'koneksi.php';
session_start();

// 1. Ambil data dari Form atau Session Keranjang
$user_id       = $_SESSION['user_id']; // ID user yang sedang login
$tgl_acara     = $_POST['tanggal_acara'];
$waktu_acara   = $_POST['waktu_acara'];
$alamat        = $_POST['alamat'];
$no_hp         = $_POST['no_hp'];
$total_bayar   = $_POST['total_harga']; // Total dari hitungan keranjang

// 2. INSERT ke tabel PESANAN
$query_pesan = "INSERT INTO pesanan (user_id, tanggal_pesan, tanggal_acara, waktu_acara, alamat, no_handphone, total_pesan, status_pesanan) 
                VALUES ('$user_id', CURDATE(), '$tgl_acara', '$waktu_acara', '$alamat', '$no_hp', '$total_bayar', 'Menunggu Pembayaran')";

if (mysqli_query($koneksi, $query_pesan)) {
    // Ambil ID pesanan yang baru saja masuk
    $id_pesanan_baru = mysqli_insert_id($koneksi);

    // 3. INSERT ke DETAIL PESANAN (Contoh untuk 1 menu)
    // Jika ada banyak menu, biasanya pakai looping (foreach) dari keranjang
    $menu_id = $_POST['menu_id'];
    $harga   = $_POST['harga_satuan'];
    $qty     = $_POST['jumlah'];

    $query_detail = "INSERT INTO detail_pesanan (pesanan_id, menu_id, harga_satuan, jumlah_menu, total_detail_pesanan, status_detail_pesanan) 
                     VALUES ('$id_pesanan_baru', '$menu_id', '$harga', '$qty', ($harga * $qty), 'Diproses')";
    
    mysqli_query($koneksi, $query_detail);

    // 4. INSERT ke tabel PEMBAYARAN (Otomatis buat tagihan)
    $query_bayar = "INSERT INTO pembayaran (pesanan_id, status_pembayaran, total_pembayaran) 
                    VALUES ('$id_pesanan_baru', 'Belum Bayar', '$total_bayar')";
    
    mysqli_query($koneksi, $query_bayar);

    echo "Pesanan Berhasil! Data sudah masuk ke Database secara otomatis.";
} else {
    echo "Gagal menyimpan pesanan: " . mysqli_error($koneksi);
}
?>
<?php
session_start();
require_once 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk   = mysqli_real_escape_string($koneksi, $_POST['nama_produk']);
    $tanggal_acara = mysqli_real_escape_string($koneksi, $_POST['tanggal_acara']);
    $deskripsi     = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    
    // Konfigurasi Upload Berkas Bukti Komplain
    $nama_file = $_FILES['foto_produk']['name'];
    $tmp_name  = $_FILES['foto_produk']['tmp_name'];
    $ukuran    = $_FILES['foto_produk']['size'];
    
    $target_dir = "img/uploads/komplain/";
    
    // Membuat folder khusus komplain jika belum tersedia
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0775, true);
    }
    
    $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
    $nama_file_baru = "BUKTI_" . uniqid() . "." . $ext;
    $target_file = $target_dir . $nama_file_baru;

    // 1. Validasi Ekstensi Gambar
    $ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array(strtolower($ext), $ekstensi_diizinkan)) {
        $_SESSION['notif'] = [
            'status' => 'danger',
            'pesan' => 'Gagal mengirim komplain! Format bukti foto harus berupa JPG, JPEG, PNG, atau WEBP.'
        ];
        header("Location: index.php#isi-testimoni");
        exit();
    }

    // 2. Validasi Ukuran Berkas Bukti (Maks 2MB)
    if ($ukuran > 2 * 1024 * 1024) {
        $_SESSION['notif'] = [
            'status' => 'danger',
            'pesan' => 'Gagal mengirim komplain! Ukuran file bukti foto terlalu besar (Maksimal 2MB).'
        ];
        header("Location: index.php#isi-testimoni");
        exit();
    }

    // 3. Proses Upload dan Simpan Data
    if (move_uploaded_file($tmp_name, $target_file)) {
        $query = "INSERT INTO komplain (nama_produk, tanggal_acara, deskripsi, foto) 
                  VALUES ('$nama_produk', '$tanggal_acara', '$deskripsi', '$target_file')";
        
        if (mysqli_query($koneksi, $query)) {
            $_SESSION['notif'] = [
                'status' => 'success',
                'pesan' => 'Laporan komplain Anda berhasil terkirim. Tim manajemen Kedai Aishwa akan segera meninjau ulasan Anda.'
            ];
        } else {
            $_SESSION['notif'] = [
                'status' => 'danger',
                'pesan' => 'Gagal menyimpan laporan ke database: ' . mysqli_error($koneksi)
            ];
        }
    } else {
        $_SESSION['notif'] = [
            'status' => 'danger',
            'pesan' => 'Terjadi kesalahan sistem saat mengunggah foto bukti.'
        ];
    }

    header("Location: index.php#isi-testimoni");
    exit();
}
<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_produk = $_POST['nama_produk'];
    $rating = $_POST['rating'];
    $deskripsi = $_POST['deskripsi'];
    
    // Konfigurasi Pengunggahan Berkas Foto
    $nama_file = $_FILES['foto_produk']['name'];
    $tmp_name = $_FILES['foto_produk']['tmp_name'];
    $ukuran = $_FILES['foto_produk']['size'];
    
    $target_dir = "img/uploads/";
    
    // Membuat direktori penyimpanan secara otomatis jika belum tersedia
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0775, true);
    }
    
    // Pembuatan nama file unik agar tidak saling menimpa
    $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
    $nama_file_baru = "IMG_" . uniqid() . "." . $ext;
    $target_file = $target_dir . $nama_file_baru;

    // 1. Validasi Ekstensi Gambar
    $ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array(strtolower($ext), $ekstensi_diizinkan)) {
        $_SESSION['notif'] = [
            'status' => 'danger',
            'pesan' => 'Gagal mengirim ulasan! Format gambar harus berupa JPG, JPEG, PNG, atau WEBP.'
        ];
        header("Location: index.php#isi-testimoni");
        exit();
    }

    // 2. Validasi Batas Ukuran File (Maksimal 2 Megabyte)
    if ($ukuran > 2 * 1024 * 1024) {
        $_SESSION['notif'] = [
            'status' => 'danger',
            'pesan' => 'Gagal mengirim ulasan! Ukuran file foto terlalu besar (Maksimal 2MB).'
        ];
        header("Location: index.php#isi-testimoni");
        exit();
    }

    // 3. Proses Pemindahan File ke Direktori Utama
    if (move_uploaded_file($tmp_name, $target_file)) {
        
        // Inisialisasi session array jika data admin belum terbentuk
        if (!isset($_SESSION['data_testimoni'])) {
            $_SESSION['data_testimoni'] = [];
        }

        // Menyimpan data kiriman terbaru ke posisi paling atas (indeks awal)
        array_unshift($_SESSION['data_testimoni'], [
            'id' => uniqid(),
            'nama_produk' => $nama_produk,
            'rating' => (int)$rating,
            'deskripsi' => $deskripsi,
            'foto' => $target_file,
            'waktu_masuk' => date('d M Y, H:i')
        ]);

        // Menyusun pesan sukses untuk dikirim kembali
        $_SESSION['notif'] = [
            'status' => 'success',
            'pesan' => 'Terima kasih! Testimoni Anda berhasil dikirim dan telah masuk ke sistem Catering Kedai Aishwa.'
        ];
    } else {
        $_SESSION['notif'] = [
            'status' => 'danger',
            'pesan' => 'Terjadi kesalahan internal saat mengunggah foto. Silakan coba beberapa saat lagi.'
        ];
    }

    header("Location: index.php#isi-testimoni");
    exit();
}
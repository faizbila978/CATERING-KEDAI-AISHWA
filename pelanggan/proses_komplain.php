<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mengambil data inputan tanpa mysqli_real_escape_string (karena tidak ada $koneksi)
    $nama_produk   = $_POST['nama_produk'];
    $tanggal_acara = $_POST['tanggal_acara'];
    $deskripsi     = $_POST['deskripsi'];
    
    // Konfigurasi Pengunggahan Berkas Foto Bukti Komplain
    $nama_file = $_FILES['foto_produk']['name'];
    $tmp_name  = $_FILES['foto_produk']['tmp_name'];
    $ukuran    = $_FILES['foto_produk']['size'];
    
    $target_dir = "../img/uploads/komplain/";
    
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0775, true);
    }
    
    $ext = pathinfo($nama_file, PATHINFO_EXTENSION);
    $nama_file_baru = "BUKTI_" . uniqid() . "." . $ext;
    $target_file = $target_dir . $nama_file_baru;

    // Validasi Ekstensi Gambar
    $ekstensi_diizinkan = ['jpg', 'jpeg', 'png', 'webp'];
    if (!in_array(strtolower($ext), $ekstensi_diizinkan)) {
        $_SESSION['notif'] = [
            'status' => 'danger',
            'pesan' => 'Gagal mengirim komplain! Format gambar bukti harus berupa JPG, JPEG, PNG, atau WEBP.'
        ];
        header("Location: index.php#isi-testimoni");
        exit();
    }

    // Validasi Batas Ukuran File (Maksimal 2MB)
    if ($ukuran > 2 * 1024 * 1024) {
        $_SESSION['notif'] = [
            'status' => 'danger',
            'pesan' => 'Gagal mengirim komplain! Ukuran file foto terlalu besar (Maksimal 2MB).'
        ];
        header("Location: index.php#isi-testimoni");
        exit();
    }

    // Proses Pemindahan File & Simpan ke Session (Bukan Database)
    if (move_uploaded_file($tmp_name, $target_file)) {
        
        // Inisialisasi session array jika data komplain belum terbentuk
        if (!isset($_SESSION['data_komplain'])) {
            $_SESSION['data_komplain'] = [];
        }

        // Menyimpan data komplain ke dalam Session internal browser pelanggan
        array_unshift($_SESSION['data_komplain'], [
            'id' => uniqid(),
            'nama_produk' => $nama_produk,
            'tanggal_acara' => $tanggal_acara,
            'deskripsi' => $deskripsi,
            'foto' => $target_file,
            'waktu_masuk' => date('d M Y, H:i')
        ]);

        $_SESSION['notif'] = [
            'status' => 'success',
            'pesan' => 'Terima kasih. Laporan komplain Anda berhasil diproses dan terkirim ke sistem Catering Kedai Aishwa.'
        ];
        
    } else {
        $_SESSION['notif'] = [
            'status' => 'danger',
            'pesan' => 'Terjadi kesalahan internal saat mengunggah foto bukti. Silakan coba beberapa saat lagi.'
        ];
    }

    header("Location: index.php#isi-testimoni");
    exit();
}
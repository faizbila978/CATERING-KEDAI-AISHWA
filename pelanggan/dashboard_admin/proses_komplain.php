<?php
// 1. Wajib jalankan session di paling atas file
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Cek apakah form dikirim menggunakan metode POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['jenis_form']) && $_POST['jenis_form'] === 'komplain') {
    
    // Ambil data dari form komplain
    $nama_produk   = $_POST['nama_produk'];
    $tanggal_acara = $_POST['tanggal_acara'];
    $deskripsi     = $_POST['deskripsi'];
    $foto_produk   = $_FILES['foto_produk'];

    // --- PROSES VALIDASI & SIMPAN DATA / LOGIKANYA DI SINI ---
    // (Misalnya proses upload gambar dan simpan ke database)
    
    $proses_sukses = true; // Set true jika semua validasi & penyimpanan database berhasil

    if ($proses_sukses) {
        // 3. Set $_SESSION['notif'] dengan status 'success' agar berwarna hijau di Bootstrap
        $_SESSION['notif'] = [
            'status' => 'success',
            'pesan'  => '<strong>Berhasil!</strong> Komplain Anda telah kami terima. Tim Kedai Aishwa akan segera menindaklanjuti kendala Anda.'
        ];
    } else {
        // Set 'danger' jika terjadi kegagalan sistem
        $_SESSION['notif'] = [
            'status' => 'danger',
            'pesan'  => '<strong>Gagal!</strong> Terjadi kesalahan sistem saat mengirim komplain. Silakan coba lagi.'
        ];
    }

    // 4. Alihkan kembali (Redirect) ke halaman utama agar terhindar dari submit ganda (resubmission)
    // Sesuaikan 'index.php' dengan nama file landing page Anda saat ini
    header("Location: index.php#isi-testimoni"); 
    exit();
} else {
    // Jika diakses ilegal tanpa POST, lempar kembali ke halaman utama
    header("Location: index.php");
    exit();
}
?>
<?php
include "koneksi.php";

$id        = $_POST['id'];
$nama      = $_POST['nama'];
$harga     = $_POST['harga'];
$deskripsi = $_POST['deskripsi'];

$nama_file = $_FILES['gambar']['name'];
$tmp_file  = $_FILES['gambar']['tmp_name'];

// Cek apakah user mengunggah file baru atau tidak
if ($nama_file != "") {
    // 1. Ambil nama gambar lama dari database
    $query_lama = mysqli_query($conn, "SELECT gambar FROM menu WHERE menu_id='$id'");
    $data_lama  = mysqli_fetch_assoc($query_lama);
    
    // 2. Hapus file fisik gambar lama dari folder 'uploads' agar tidak penuh
    if (file_exists("uploads/" . $data_lama['gambar'])) {
        unlink("uploads/" . $data_lama['gambar']);
    }

    // 3. Pindahkan file baru ke folder uploads
    move_uploaded_file($tmp_file, "uploads/" . $nama_file);

    // 4. Update database dengan gambar baru
    mysqli_query($conn, "UPDATE menu SET 
        nama_menu    = '$nama',
        harga_satuan = '$harga',
        deskripsi    = '$deskripsi',
        gambar       = '$nama_file' 
        WHERE menu_id = '$id'");

} else {
    // Jika tidak ada gambar baru, update data selain gambar
    mysqli_query($conn, "UPDATE menu SET 
        nama_menu    = '$nama',
        harga_satuan = '$harga',
        deskripsi    = '$deskripsi' 
        WHERE menu_id = '$id'");
}

header("Location: manajemen_produk.php");
?>
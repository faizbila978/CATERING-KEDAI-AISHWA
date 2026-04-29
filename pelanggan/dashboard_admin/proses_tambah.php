<?php
include "koneksi.php";

// ambil data dari form
$nama       = $_POST['nama_produk'];
$harga      = $_POST['harga_produk'];
$deskripsi  = $_POST['deskripsi_produk'];
$kategori   = "nasi kotak"; // sementara (nanti bisa dropdown)

// =======================
// PROSES UPLOAD GAMBAR
// =======================
$gambar = $_FILES['foto_produk']['name'];
$tmp    = $_FILES['foto_produk']['tmp_name'];

// biar nama file tidak bentrok
$namaBaru = time() . "_" . $gambar;

// pindahkan ke folder img
move_uploaded_file($tmp, __DIR__ . "/../img/" . $namaBaru);

// =======================
// INSERT KE DATABASE
// =======================
$query = "INSERT INTO menu 
          (nama_menu, deskripsi, harga_satuan, gambar, kategori)
          VALUES 
          ('$nama', '$deskripsi', '$harga', '$namaBaru', '$kategori')";

if(mysqli_query($conn, $query)){
    echo "<script>
        alert('Produk berhasil ditambahkan!');
        window.location='manajemen_produk.php';
    </script>";
} else {
    echo "Gagal: " . mysqli_error($conn);
}
?>
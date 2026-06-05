<?php
include "koneksi.php";

// Ambil data dari form
$nama       = $_POST['nama_produk'];
$deskripsi  = $_POST['deskripsi_produk'];
$kategori_id = $_POST['kategori_id']; // Mengambil ID Kategori pilihan dari form

// Bersihkan format Rupiah (menghilangkan titik ribuan agar berupa angka murni)
$harga_mentah = $_POST['harga_produk'];
$harga        = preg_replace('/[^0-9]/', '', $harga_mentah);

// =======================
// PROSES UPLOAD GAMBAR
// =======================
$gambar = $_FILES['foto_produk']['name'];
$tmp    = $_FILES['foto_produk']['tmp_name'];

// Biar nama file tidak bentrok
$namaBaru = time() . "_" . $gambar;

// Pindahkan ke folder img
move_uploaded_file($tmp, __DIR__ . "/../img/" . $namaBaru);

// =======================
// INSERT KE DATABASE
// ======================
// Sekarang $kategori_id sudah dinamis sesuai dropdown yang dipilih
$query = "INSERT INTO menu 
          (nama_menu, deskripsi, harga_satuan, gambar, kategori_id)
          VALUES 
          ('$nama', '$deskripsi', '$harga', '$namaBaru', '$kategori_id')";

if(mysqli_query($conn, $query)){
    echo "<script>
        alert('Produk berhasil ditambahkan!');
        window.location='manajemen_produk.php';
    </script>";
} else {
    echo "Gagal: " . mysqli_error($conn);
}
?>
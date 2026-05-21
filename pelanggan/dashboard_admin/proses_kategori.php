<?php
include "koneksi.php";

$aksi = $_REQUEST['aksi'] ?? '';

if ($aksi == 'tambah') {
    $nama = mysqli_real_escape_string($conn, $_POST['nama_kategori']);
    
    // Validasi agar nama kategori tidak duplikat
    $cek = mysqli_query($conn, "SELECT * FROM kategori WHERE nama_kategori = '$nama'");
    if (mysqli_num_rows($cek) > 0) {
        echo "<script>alert('Gagal! Kategori sudah ada.'); history.back();</script>";
    } else {
        mysqli_query($conn, "INSERT INTO kategori (nama_kategori) VALUES ('$nama')");
        header("Location: kategori.php");
    }
} 

elseif ($aksi == 'hapus') {
    $id = $_GET['id'];
    mysqli_query($conn, "DELETE FROM kategori WHERE kategori_id = $id");
    header("Location: kategori.php");
}
?>
<?php
session_start();

$id = $_POST['id'];
$jumlah = (int)$_POST['jumlah'];

// validasi biar tidak 0 atau negatif
if ($jumlah < 1) {
    unset($_SESSION['keranjang'][$id]);
} else {
    $_SESSION['keranjang'][$id] = $jumlah;
}

header("Location: formulir.php");
exit();
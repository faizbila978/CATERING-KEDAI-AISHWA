<?php
session_start();
$id = $_GET['id'];
$aksi = $_GET['aksi'];

if ($aksi == 'tambah') {
    $_SESSION['keranjang'][$id]++;
} elseif ($aksi == 'kurang') {
    $_SESSION['keranjang'][$id]--;
    if ($_SESSION['keranjang'][$id] <= 0) {
        unset($_SESSION['keranjang'][$id]);
    }
}

header("Location: formulir.php");
exit();
?>
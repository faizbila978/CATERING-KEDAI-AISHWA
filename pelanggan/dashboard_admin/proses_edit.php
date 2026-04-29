<?php
include "koneksi.php";

mysqli_query($conn, "UPDATE menu SET
nama_menu='$_POST[nama]',
harga_satuan='$_POST[harga]',
deskripsi='$_POST[deskripsi]'
WHERE menu_id='$_POST[id]'");

header("Location: manajemen_produk.php");
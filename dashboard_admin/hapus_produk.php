<?php
include "koneksi.php";
$id = $_GET['id'];

mysqli_query($conn, "DELETE FROM menu WHERE menu_id='$id'");
header("Location: manajemen_produk.php");
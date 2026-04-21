<?php
// koneksi.php
$server   = "localhost";
$user     = "root";
$password = "";
$db_name  = "catering_kedai_aishwa";

// Perbaikan: variabel harus $user (sesuai yang didefinisikan di atas)
$conn = mysqli_connect($server, $user, $password, $db_name);

if (!$conn) {
    die("Gagal Terhubung dengan Database: " . mysqli_connect_error());
}
?>
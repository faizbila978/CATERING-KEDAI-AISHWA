<?php
include "koneksi.php";
$id = $_GET['id'];

$data = mysqli_query($conn, "SELECT * FROM menu WHERE menu_id='$id'");
$d = mysqli_fetch_assoc($data);
?>

<form action="proses_edit.php" method="POST">
<input type="hidden" name="id" value="<?= $d['menu_id'] ?>">
<input type="text" name="nama" value="<?= $d['nama_menu'] ?>">
<input type="number" name="harga" value="<?= $d['harga_satuan'] ?>">
<textarea name="deskripsi"><?= $d['deskripsi'] ?></textarea>
<button type="submit">Update</button>
</form>
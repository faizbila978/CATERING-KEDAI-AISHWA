<?php
include "koneksi.php";
$id = $_GET['id'];

$data = mysqli_query($conn, "SELECT * FROM menu WHERE menu_id='$id'");
$d = mysqli_fetch_assoc($data);
?>

<!-- PENTING: Tambahkan enctype agar form bisa mengirim file -->
<form action="proses_edit.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id" value="<?= $d['menu_id'] ?>">
    
    <label>Nama Menu:</label><br>
    <input type="text" name="nama" value="<?= $d['nama_menu'] ?>"><br><br>

    <label>Harga:</label><br>
    <input type="number" name="harga" value="<?= $d['harga_satuan'] ?>"><br><br>

    <label>Deskripsi:</label><br>
    <textarea name="deskripsi"><?= $d['deskripsi'] ?></textarea><br><br>

    <label>Gambar Saat Ini:</label><br>
    <!-- Menampilkan gambar lama -->
    <img src="uploads/<?= $d['gambar'] ?>" width="100"><br>
    <input type="file" name="gambar"><br>
    <small style="color: red;">*Kosongkan jika tidak ingin mengubah gambar</small><br><br>

    <button type="submit">Update</button>
</form>
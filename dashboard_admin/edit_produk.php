<?php
include "koneksi.php";
$id = $_GET['id'];

$data = mysqli_query($conn, "SELECT * FROM menu WHERE menu_id='$id'");
$d = mysqli_fetch_assoc($data);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Menu</title>
    <link rel="stylesheet" href="edit_produk.css">
</head>
<body>

<div class="form-container">
    <h2>Edit Menu Kuliner</h2>
    
    <form action="proses_edit.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $d['menu_id'] ?>">
        
        <div class="form-group">
            <label>Nama Menu:</label>
            <input type="text" name="nama" value="<?= $d['nama_menu'] ?>">
        </div>

        <div class="form-group">
            <label>Harga:</label>
            <input type="number" name="harga" value="<?= $d['harga_satuan'] ?>">
        </div>

        <div class="form-group">
            <label>Deskripsi:</label>
            <textarea name="deskripsi"><?= $d['deskripsi'] ?></textarea>
        </div>

        <div class="form-group">
            <label>Gambar Saat Ini:</label>
            <img src="uploads/<?= $d['gambar'] ?>" width="100" class="current-img">
            <input type="file" name="gambar">
            <span class="note">*Kosongkan jika tidak ingin mengubah gambar</span>
        </div>

        <button type="submit">Update Menu</button>
    </form>
</div>

</body>
</html>
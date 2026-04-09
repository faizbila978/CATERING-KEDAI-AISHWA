<?php
// ================= KONEKSI DATABASE =================
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_kedai_aishwa";

$conn = mysqli_connect($host, $user, $pass, $db);
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// ================= TAMBAH MENU =================
if (isset($_POST['tambah'])) {
    $nama = $_POST['nama_menu'];
    $deskripsi = $_POST['deskripsi'];
    $harga = $_POST['harga_satuan'];

    $gambar = $_FILES['gambar']['name'];
    $tmp = $_FILES['gambar']['tmp_name'];

    move_uploaded_file($tmp, "upload/" . $gambar);

    mysqli_query($conn, "INSERT INTO menu (nama_menu, deskripsi, harga_satuan, gambar)
    VALUES ('$nama','$deskripsi','$harga','$gambar')");
}

// ================= HAPUS MENU =================
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($conn, "DELETE FROM menu WHERE menu_id=$id");
}

// ================= AMBIL DATA =================
$data = mysqli_query($conn, "SELECT * FROM menu");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-orange-50 p-6">

<h1 class="text-2xl font-bold mb-4">Manajemen Menu</h1>

<!-- ================= FORM TAMBAH ================= -->
<form method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow mb-6">
    <input type="text" name="nama_menu" placeholder="Nama Menu" required class="border p-2 w-full mb-2">
    <textarea name="deskripsi" placeholder="Deskripsi" class="border p-2 w-full mb-2"></textarea>
    <input type="number" name="harga_satuan" placeholder="Harga" required class="border p-2 w-full mb-2">
    <input type="file" name="gambar" required class="mb-2">
    <button type="submit" name="tambah" class="bg-green-500 text-white px-4 py-2 rounded">
        Tambah Menu
    </button>
</form>

<!-- ================= TAMPIL DATA ================= -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
<?php while($row = mysqli_fetch_assoc($data)) { ?>
    <div class="bg-white p-4 rounded shadow">
        <img src="upload/<?php echo $row['gambar']; ?>" class="w-full h-40 object-cover rounded">
        <h2 class="font-bold mt-2"><?php echo $row['nama_menu']; ?></h2>
        <p class="text-sm"><?php echo $row['deskripsi']; ?></p>
        <p class="text-orange-600 font-bold">Rp <?php echo $row['harga_satuan']; ?></p>

        <a href="?hapus=<?php echo $row['menu_id']; ?>" 
           onclick="return confirm('Yakin hapus?')"
           class="bg-red-500 text-white px-3 py-1 rounded inline-block mt-2">
           Hapus
        </a>
    </div>
<?php } ?>
</div>

</body>
</html>
<?php
session_start();

// Koneksi ke Database
$host     = "localhost";
$username = "root";
$password = "";
$database = "kedai_aishwa";
$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Fitur Update Status Komplain oleh Admin
if (isset($_POST['update_status'])) {
    $id_komplain = $_POST['id_komplain'];
    $status_baru = $_POST['status'];
    
    $update_query = "UPDATE komplain SET status = '$status_baru' WHERE id = '$id_komplain'";
    if (mysqli_query($conn, $update_query)) {
        $msg = "<div class='alert alert-success'>Status komplain ID #$id_komplain berhasil diperbarui!</div>";
    } else {
        $msg = "<div class='alert alert-danger'>Gagal memperbarui status.</div>";
    }
}

// Ambil semua data komplain dari database (Urutkan dari yang terbaru)
$query_tampil = "SELECT * FROM komplain ORDER BY tanggal_masuk DESC";
$result = mysqli_query($conn, $query_tampil);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Admin | Kelola Komplain Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; }
        .admin-header { background-color: #212529; color: white; padding: 20px 0; mb-4: 30px; }
        .table-card { border-radius: 12px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .badge-pending { background-color: #ffc107; color: #000; }
        .badge-proses { background-color: #0dcaf0; color: #fff; }
        .badge-selesai { background-color: #198754; color: #fff; }
        .img-bukti { max-width: 100px; border-radius: 6px; cursor: pointer; transition: 0.2s; }
        .img-bukti:hover { transform: scale(1.1); }
    </style>
</head>
<body>

    <div class="admin-header mb-4">
        <div class="container d-flex justify-content-between align-items-center">
            <div>
                <h2 class="mb-0 fw-bold">Kedai Aishwa Admin Panel</h2>
                <small class="text-muted">Manajemen Komplain & Masukan Pelanggan</small>
            </div>
            <a href="index.php" class="btn btn-outline-light btn-sm">Lihat Website</a>
        </div>
    </div>

    <div class="container">
        <?php if(isset($msg)) echo $msg; ?>

        <div class="card table-card p-4">
            <h4 class="fw-bold mb-4">Daftar Komplain Masuk</h4>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Tanggal Masuk</th>
                            <th>Produk</th>
                            <th>Tanggal Acara</th>
                            <th>Deskripsi Masalah</th>
                            <th>Foto Bukti</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><strong>#<?php echo $row['id']; ?></strong></td>
                                    <td><small class="text-muted"><?php echo date('d M Y, H:i', strtotime($row['tanggal_masuk'])); ?></small></td>
                                    <td><span class="badge bg-secondary"><?php echo $row['nama_produk']; ?></span></td>
                                    <td><?php echo date('d/m/Y', strtotime($row['tanggal_acara'])); ?></td>
                                    <td><p class="mb-0 small" style="max-width: 250px;"><?php echo nl2br($row['deskripsi']); ?></p></td>
                                    <td>
                                        <a href="uploads/<?php echo $row['foto']; ?>" target="_blank">
                                            <img src="uploads/<?php echo $row['foto']; ?>" class="img-bukti" alt="Bukti Komplain">
                                        </a>
                                    </td>
                                    <td>
                                        <?php 
                                        $status = $row['status'];
                                        $badge_class = 'badge-pending';
                                        if($status == 'Diproses') $badge_class = 'badge-proses';
                                        if($status == 'Selesai') $badge_class = 'badge-selesai';
                                        ?>
                                        <span class="badge <?php echo $badge_class; ?> px-3 py-2 rounded-pill"><?php echo $status; ?></span>
                                    </td>
                                    <td>
                                        <!-- Form Update Status Singkat -->
                                        <form action="" method="POST" class="d-flex gap-1 align-items-center">
                                            <input type="hidden" name="id_komplain" value="<?php echo $row['id']; ?>">
                                            <select name="status" class="form-select form-select-sm" style="width: 110px;">
                                                <option value="Pending" <?php if($status == 'Pending') echo 'selected'; ?>>Pending</option>
                                                <option value="Diproses" <?php if($status == 'Diproses') echo 'selected'; ?>>Diproses</option>
                                                <option value="Selesai" <?php if($status == 'Selesai') echo 'selected'; ?>>Selesai</option>
                                            </select>
                                            <button type="submit" name="update_status" class="btn btn-primary btn-sm">Simpan</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="8" class="text-center py-4 text-muted">Belum ada komplain yang masuk.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
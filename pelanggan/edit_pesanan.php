<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
    header('Location: riwayat_pesanan.php');
    exit();
}

$pesanan_id = mysqli_real_escape_string($conn, $_GET['id']);
$user_id = $_SESSION['user_id'];

// Ambil data pesanan
$query = "SELECT * FROM pesanan WHERE pesanan_id = '$pesanan_id' AND user_id = '$user_id'";
$result = mysqli_query($conn, $query);
$pesanan = mysqli_fetch_assoc($result);

if (!$pesanan || $pesanan['status_pesanan'] == 'Dikirim' || $pesanan['status_pesanan'] == 'Diproses') {
    echo "<script>alert('Pesanan sudah tidak bisa diubah!'); window.location='status.php?id=$pesanan_id';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal_acara = mysqli_real_escape_string($conn, $_POST['tanggal_acara']);
    $waktu_acara = mysqli_real_escape_string($conn, $_POST['waktu_acara']);
    $alamat = mysqli_real_escape_string($conn, $_POST['alamat']);
    $no_handphone = mysqli_real_escape_string($conn, $_POST['no_handphone']);
    $catatan = mysqli_real_escape_string($conn, $_POST['catatan']);
    
    $update_query = "UPDATE pesanan SET 
                    tanggal_acara = '$tanggal_acara', 
                    waktu_acara = '$waktu_acara', 
                    alamat = '$alamat', 
                    no_handphone = '$no_handphone', 
                    catatan = '$catatan' 
                    WHERE pesanan_id = '$pesanan_id'";
                    
    if(mysqli_query($conn, $update_query)){
        echo "<script>alert('Data pesanan berhasil diperbarui!'); window.location='status.php?id=$pesanan_id';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui pesanan!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Pesanan - Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8f9fa; }
        .card-custom { border: none; border-radius: 16px; box-shadow: 0 4px 6px rgba(0,0,0,0.07); }
        .btn-pink { background-color: #ad2d5e; color: white; border-radius: 25px; padding: 12px; font-weight: 600; }
        .btn-pink:hover { background-color: #8a244b; color: white; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card card-custom p-4">
                <h4 class="fw-bold mb-4 text-center">Edit Detail Pesanan #ORD-<?php echo str_pad($pesanan_id, 3, '0', STR_PAD_LEFT); ?></h4>
                
                <form action="" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tanggal Acara</label>
                            <input type="date" name="tanggal_acara" class="form-control" value="<?php echo $pesanan['tanggal_acara']; ?>" required>
                        </div>
                        <div class="col-md-6 mt-3 mt-md-0">
                            <label class="form-label fw-bold">Waktu Acara</label>
                            <input type="time" name="waktu_acara" class="form-control" value="<?php echo $pesanan['waktu_acara']; ?>" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat Pengiriman</label>
                        <textarea name="alamat" class="form-control" rows="3" required><?php echo htmlspecialchars($pesanan['alamat']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">No. Handphone (WhatsApp)</label>
                        <input type="text" name="no_handphone" class="form-control" value="<?php echo htmlspecialchars($pesanan['no_handphone']); ?>" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Catatan Pesanan</label>
                        <textarea name="catatan" class="form-control" rows="2"><?php echo htmlspecialchars($pesanan['catatan']); ?></textarea>
                        <div class="form-text">Contoh: "Sambal dipisah ya", "Tumpeng harap tiba 1 jam sebelum acara"</div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-pink"><i class="bi bi-save me-2"></i> Simpan Perubahan</button>
                        <a href="status.php?id=<?php echo $pesanan_id; ?>" class="btn btn-light rounded-pill fw-bold">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
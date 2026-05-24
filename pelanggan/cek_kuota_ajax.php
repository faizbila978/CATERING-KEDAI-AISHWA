<?php
include 'koneksi.php';

$tanggal = isset($_GET['tanggal']) ? $_GET['tanggal'] : '';
$total_terpakai = 0;

if (!empty($tanggal)) {
    // Menghitung jumlah akumulasi porsi yang terkumpul dari seluruh pembeli pada tanggal acara tersebut
    // Menghubungkan tabel pesanan dan detail_pesanan berdasarkan pesanan_id
    // Silakan sesuaikan nama kolom jika berbeda (Contoh asumsi: tanggal_acara, pesanan_id, jumlah_menu)
    $query = "SELECT SUM(dp.jumlah_menu) as total 
              FROM pesanan p 
              JOIN detail_pesanan dp ON p.pesanan_id = dp.pesanan_id 
              WHERE p.tanggal_acara = '$tanggal'";
              
    $result = mysqli_query($conn, $query);
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $total_terpakai = $row['total'] ? intval($row['total']) : 0;
    }
}

// Kembalikan data dalam format JSON agar bisa dibaca oleh JavaScript di formulir.php
echo json_encode(['total_terpakai' => $total_terpakai]);
<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['user_id']) || !isset($_POST['submit_pelunasan'])) {
    header('Location: login.php');
    exit();
}

$pesanan_id = mysqli_real_escape_string($conn, $_POST['pesanan_id']);
$metode_pelunasan = mysqli_real_escape_string($conn, $_POST['metode_pelunasan']);

// Proses Upload Bukti Gambar
$nama_file = $_FILES['bukti_transfer']['name'];
$ukuran_file = $_FILES['bukti_transfer']['size'];
$error = $_FILES['bukti_transfer']['error'];
$tmp_name = $_FILES['bukti_transfer']['tmp_name'];

if ($error === 0) {
    $ekstensi_valid = ['jpg', 'jpeg', 'png'];
    $ekstensi_file = explode('.', $nama_file);
    $ekstensi_file = strtolower(end($ekstensi_file));

    if (in_array($ekstensi_file, $ekstensi_valid)) {
        // Generate nama file unik agar tidak bentrok
        $nama_file_baru = time() . '_pelunasan_' . $nama_file;
        $folder_tujuan = 'img/' . $nama_file_baru;

        if (move_uploaded_file($tmp_name, $folder_tujuan)) {
            // Jalankan query update data pembayaran ke database
            // Kolom status_pembayaran diubah menjadi 'Menunggu Konfirmasi' agar divalidasi admin
            $query = "UPDATE pembayaran SET 
                        metode_pembayaran = '$metode_pelunasan', 
                        tanggal_pembayaran = CURDATE(), 
                        waktu_pembayaran = CURTIME(),
                        status_pembayaran = 'Menunggu Konfirmasi',
                        bukti_pelunasan = '$nama_file_baru' 
                      WHERE pesanan_id = '$pesanan_id'";
            
            if (mysqli_query($conn, $query)) {
                echo "<script>
                        alert('Bukti pelunasan berhasil dikirim! Menunggu konfirmasi admin.');
                        window.location.href = 'riwayat_pesanan.php';
                      </script>";
            } else {
                echo "Gagal memperbarui database: " . mysqli_error($conn);
            }
        } else {
            echo "<script>alert('Gagal mengunggah file gambar.'); window.history.back();</script>";
        }
    } else {
        echo "<script>alert('Format file tidak didukung! Harus JPG/JPEG/PNG.'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Terjadi kesalahan saat upload berkas.'); window.history.back();</script>";
}
?>
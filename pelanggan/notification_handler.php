<?php
include 'koneksi.php'; // Hubungkan ke database Kedai Aishwa
require_once dirname(__FILE__) . '/midtrans-php/Midtrans.php';

\Midtrans\Config::$serverKey = 'YOUR_SERVER_KEY';
\Midtrans\Config::$isProduction = false;

$notif = new \Midtrans\Notification();

$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;

// Pisahkan Order ID jika tadi ditambahkan timestamp (misal: 40-1714800)
$real_order_id = explode('-', $order_id)[0];

if ($transaction == 'settlement') {
    // Pembayaran Berhasil: Update tabel pembayaran[cite: 2]
    mysqli_query($koneksi, "UPDATE pembayaran SET 
        status_pembayaran = 'Selesai', 
        metode_pembayaran = '$type',
        tanggal_pembayaran = CURDATE(),
        waktu_pembayaran = CURTIME()
        WHERE pesanan_id = '$real_order_id'");

    // Update status di tabel pesanan[cite: 2]
    mysqli_query($koneksi, "UPDATE pesanan SET 
        status_pesanan = 'Dikonfirmasi' 
        WHERE pesanan_id = '$real_order_id'");

} else if ($transaction == 'pending') {
    mysqli_query($koneksi, "UPDATE pembayaran SET status_pembayaran = 'Menunggu Pembayaran' WHERE pesanan_id = '$real_order_id'");

} else if ($transaction == 'expire' || $transaction == 'cancel') {
    mysqli_query($koneksi, "UPDATE pembayaran SET status_pembayaran = 'Gagal/Expired' WHERE pesanan_id = '$real_order_id'");
}
?>
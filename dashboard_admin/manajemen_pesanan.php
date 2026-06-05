<?php
session_start();
include 'koneksi.php';

// Pastikan user adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    exit();
}

// ================= PROSES UPDATE STATUS UTAMA & KONFIRMASI PEMBAYARAN =================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
    // 1. Aksi Update Status Pesanan Berurutan (Pending -> Diproses -> Dikirim -> Selesai)
    if ($_POST['action'] === 'update_status_next') {
        $id_pesanan_update = mysqli_real_escape_string($conn, $_POST['pesanan_id']);
        $status_baru = mysqli_real_escape_string($conn, $_POST['status_baru']);
        
        // Cek status saat ini di database
        $cek_status_skrg = mysqli_query($conn, "SELECT status_pesanan FROM pesanan WHERE pesanan_id = '$id_pesanan_update'");
        $data_status = mysqli_fetch_assoc($cek_status_skrg);
        $status_db_skrg = trim($data_status['status_pesanan'] ?? '');
        
        if ($status_db_skrg === 'Selesai' || $status_db_skrg === 'Dibatalkan') {
            echo "<script>alert('Gagal! Pesanan yang sudah Selesai atau Dibatalkan tidak dapat diubah lagi.'); window.location='manajemen_pesanan.php';</script>";
            exit();
        }
        
        $update_query = "UPDATE pesanan SET status_pesanan = '$status_baru' WHERE pesanan_id = '$id_pesanan_update'";
        mysqli_query($conn, $update_query);
    }
    
    // 2. AKSI: Konfirmasi DP (50%) -> Status Pesanan Otomatis Berubah ke 'Pending'
    if ($_POST['action'] === 'konfirmasi_dp') {
        $id_pesanan_update = mysqli_real_escape_string($conn, $_POST['pesanan_id']);
        $tgl_sekarang = date('Y-m-d');
        $waktu_sekarang = date('H:i:s');

        $cek_pesanan = mysqli_query($conn, "SELECT total_pesan FROM pesanan WHERE pesanan_id = '$id_pesanan_update'");
        $data_p = mysqli_fetch_assoc($cek_pesanan);
        $total_pesan = $data_p['total_pesan'] ?? 0;
        $jumlah_dp = $total_pesan * 0.5;

        $cek_bayar = mysqli_query($conn, "SELECT * FROM pembayaran WHERE pesanan_id = '$id_pesanan_update'");
        
        if (mysqli_num_rows($cek_bayar) > 0) {
            mysqli_query($conn, "UPDATE pembayaran SET 
                status_pembayaran = 'Diproses', 
                status_dp = 'DP DIKONFIRMASI',
                jumlah_dp = '$jumlah_dp',
                tanggal_pembayaran = '$tgl_sekarang',
                waktu_pembayaran = '$waktu_sekarang'
                WHERE pesanan_id = '$id_pesanan_update'");
        } else {
            mysqli_query($conn, "INSERT INTO pembayaran 
                (pesanan_id, metode_pembayaran, total_pembayaran, jumlah_dp, status_pembayaran, status_dp, tanggal_pembayaran, waktu_pembayaran) 
                VALUES 
                ('$id_pesanan_update', 'DP Transfer', '$total_pesan', '$jumlah_dp', 'Diproses', 'DP DIKONFIRMASI', '$tgl_sekarang', '$waktu_sekarang')");
        }
        
        // Mengubah status pesanan menjadi 'Pending' agar tombol interaktif bisa diklik
        mysqli_query($conn, "UPDATE pesanan SET status_pesanan = 'Pending' WHERE pesanan_id = '$id_pesanan_update'");
        
        echo "<script>alert('DP Berhasil dikonfirmasi! Status pesanan berubah menjadi PENDING.'); window.location='manajemen_pesanan.php';</script>";
        exit();
    }

    // 3. AKSI: Konfirmasi Pelunasan Full (100%) -> Status Pesanan Berubah ke 'Pending' jika baru masuk
    if ($_POST['action'] === 'konfirmasi_pelunasan') {
        $id_pesanan_update = mysqli_real_escape_string($conn, $_POST['pesanan_id']);
        $tgl_sekarang = date('Y-m-d');
        $waktu_sekarang = date('H:i:s');

        $cek_pesanan = mysqli_query($conn, "SELECT total_pesan, status_pesanan FROM pesanan WHERE pesanan_id = '$id_pesanan_update'");
        $data_p = mysqli_fetch_assoc($cek_pesanan);
        $total_pesan = $data_p['total_pesan'] ?? 0;
        $status_pesanan_skrg = trim($data_p['status_pesanan'] ?? '');

        $cek_bayar = mysqli_query($conn, "SELECT * FROM pembayaran WHERE pesanan_id = '$id_pesanan_update'");
        
        if (mysqli_num_rows($cek_bayar) > 0) {
            mysqli_query($conn, "UPDATE pembayaran SET 
                status_pembayaran = 'Selesai', 
                status_dp = 'Lunas',
                total_pembayaran = '$total_pesan',
                tanggal_pembayaran = '$tgl_sekarang',
                waktu_pembayaran = '$waktu_sekarang'
                WHERE pesanan_id = '$id_pesanan_update'");
        } else {
            mysqli_query($conn, "INSERT INTO pembayaran 
                (pesanan_id, metode_pembayaran, total_pembayaran, status_pembayaran, status_dp, tanggal_pembayaran, waktu_pembayaran) 
                VALUES 
                ('$id_pesanan_update', 'Transfer Full', '$total_pesan', 'Selesai', 'Lunas', '$tgl_sekarang', '$waktu_sekarang')");
        }
        
        // Jika pelunasan langsung di awal, ubah status pesanan menjadi 'Pending' dulu agar bisa mengikuti alur klik manual admin
        if ($status_pesanan_skrg === '' || $status_pesanan_skrg === 'MENUNGGU VERIFIKASI' || $status_pesanan_skrg === 'MENUNGGU BAYAR') {
            mysqli_query($conn, "UPDATE pesanan SET status_pesanan = 'Pending' WHERE pesanan_id = '$id_pesanan_update'");
            echo "<script>alert('Pembayaran Lunas! Status pesanan berubah menjadi PENDING.'); window.location='manajemen_pesanan.php';</script>";
        } else {
            echo "<script>alert('Pelunasan berhasil dikonfirmasi!'); window.location='manajemen_pesanan.php';</script>";
        }
        exit();
    }
    
    echo "<script>window.location='manajemen_pesanan.php';</script>";
    exit();
}

// ================= FUNGSI FILTER & PENCARIAN =================
$where_clauses = [];

if (!empty($_GET['search_id'])) {
    $search_id = mysqli_real_escape_string($conn, str_replace('#ORD-', '', $_GET['search_id']));
    $where_clauses[] = "p.pesanan_id = '$search_id'";
}
if (!empty($_GET['status_filter'])) {
    $st_filter = mysqli_real_escape_string($conn, $_GET['status_filter']);
    $where_clauses[] = "p.status_pesanan = '$st_filter'";
}
if (!empty($_GET['bulan_filter'])) {
    $bln_filter = mysqli_real_escape_string($conn, $_GET['bulan_filter']);
    $where_clauses[] = "MONTH(p.tanggal_acara) = '$bln_filter'";
}
if (!empty($_GET['tahun_filter'])) {
    $thn_filter = mysqli_real_escape_string($conn, $_GET['tahun_filter']);
    $where_clauses[] = "YEAR(p.tanggal_acara) = '$thn_filter'";
}

$where_sql = count($where_clauses) > 0 ? "WHERE " . implode(" AND ", $where_clauses) : "";

$query = "
    SELECT 
        p.pesanan_id, p.user_id, u.nama_lengkap, p.tanggal_pesan, p.tanggal_acara, p.waktu_acara,
        p.alamat, p.no_handphone, p.catatan, p.total_pesan, p.status_pesanan,
        pb.pembayaran_id, pb.metode_pembayaran, pb.total_pembayaran, pb.status_pembayaran, pb.status_dp, pb.jumlah_dp
    FROM pesanan p
    LEFT JOIN users u ON p.user_id = u.user_id
    LEFT JOIN pembayaran pb ON p.pesanan_id = pb.pesanan_id
    $where_sql
    ORDER BY 
        CASE WHEN p.status_pesanan = 'Dibatalkan' THEN 1 ELSE 0 END ASC, 
        p.pesanan_id DESC
";

$result = mysqli_query($conn, $query);
$pesanan_list = [];
while ($row = mysqli_fetch_assoc($result)) { $pesanan_list[] = $row; }

$nama_bulan = [
    1 => "Januari", 2 => "Februari", 3 => "Maret", 4 => "April", 5 => "Mei", 6 => "Juni",
    7 => "Juli", 8 => "Agustus", 9 => "September", 10 => "Oktober", 11 => "November", 12 => "Desember"
];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesanan - Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .status-badge { padding: 6px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; display: inline-block; text-align: center; width: 100%; max-width: 130px; }
        .payment-belum { background-color: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; }
        .payment-sudah { background-color: #DCFCE7; color: #166534; border: 1px solid #86EFAC; }
        .badge-dp { background-color: #FEF3C7; color: #92400E; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: bold; display: inline-block; border: 1px solid #FCD34D; }
        .badge-full { background-color: #DBEAFE; color: #1E40AF; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: bold; display: inline-block; border: 1px solid #BFDBFE; }
        .badge-none { background-color: #F3F4F6; color: #4B5563; padding: 4px 8px; border-radius: 6px; font-size: 11px; font-weight: bold; display: inline-block; border: 1px solid #D1D5DB; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex">
        <?php include('sidebar.php'); ?>

        <main class="flex-1 flex flex-col h-screen">
            <header class="bg-white shadow-sm border-b border-gray-200 p-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center"><i class="fas fa-shopping-cart text-pink-600 mr-3"></i> Manajemen Pesanan</h2>
                    <p class="text-gray-500 text-sm mt-1">Kelola pesanan dan konfirmasi pembayaran</p>
                </div>
                <div class="bg-gradient-to-r from-pink-500 to-pink-600 text-white px-5 py-2 rounded-full text-sm font-bold shadow-lg">
                    <i class="fas fa-bell mr-2"></i> <?php echo count($pesanan_list); ?> PESANAN
                </div>
            </header>

            <div class="flex-1 p-6 overflow-y-auto">
                <!-- FORM FILTER -->
                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 mb-6">
                    <form method="GET" action="manajemen_pesanan.php" class="flex flex-wrap items-end gap-4">
                        <div class="flex-1 min-w-[280px]">
                            <label class="block text-xs font-bold text-gray-500 mb-1">CARI ID PESANAN</label>
                            <input type="text" name="search_id" value="<?php echo isset($_GET['search_id']) ? htmlspecialchars($_GET['search_id']) : ''; ?>" placeholder="Contoh: 068" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-pink-500">
                        </div>
                        
                        <div class="w-36">
                            <label class="block text-xs font-bold text-gray-500 mb-1">STATUS</label>
                            <select name="status_filter" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:border-pink-500">
                                <option value="">Semua Status</option>
                                <option value="Pending" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Diproses" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Diproses') ? 'selected' : ''; ?>>Diproses</option>
                                <option value="Dikirim" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Dikirim') ? 'selected' : ''; ?>>Dikirim</option>
                                <option value="Selesai" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                                <option value="Dibatalkan" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Dibatalkan') ? 'selected' : ''; ?>>Dibatalkan</option>
                            </select>
                        </div>
                        
                        <div class="w-32">
                            <label class="block text-xs font-bold text-gray-500 mb-1">BULAN</label>
                            <select name="bulan_filter" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:border-pink-500">
                                <option value="">Bulan</option>
                                <?php foreach($nama_bulan as $num => $name): ?>
                                    <option value="<?php echo $num; ?>" <?php echo (isset($_GET['bulan_filter']) && $_GET['bulan_filter'] == $num) ? 'selected' : ''; ?>><?php echo $name; ?></option>
                                <?php ENDFOREACH; ?>
                            </select>
                        </div>
                        
                        <div class="w-24">
                            <label class="block text-xs font-bold text-gray-500 mb-1">TAHUN</label>
                            <select name="tahun_filter" class="w-full px-2 py-2 border border-gray-300 rounded-lg text-sm bg-white focus:outline-none focus:border-pink-500">
                                <option value="">Tahun</option>
                                <?php 
                                $tahun_skrg = date('Y');
                                for($i = $tahun_skrg - 2; $i <= $tahun_skrg + 2; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo (isset($_GET['tahun_filter']) && $_GET['tahun_filter'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                <?php ENDFOR; ?>
                            </select>
                        </div>
                        
                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium shadow-sm transition"><i class="fas fa-filter"></i> Tampilkan</button>
                            <a href="manajemen_pesanan.php" class="bg-gray-100 border border-gray-300 text-gray-700 px-3 py-2 rounded-lg text-sm font-medium shadow-sm transition text-center flex items-center justify-center">Reset</a>
                        </div>
                    </form>
                </div>

                <!-- TABEL DATA -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full table-auto min-w-[1200px]">
                            <thead class="bg-gradient-to-r from-pink-50 to-pink-100 border-b border-pink-200">
                                <tr>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-pink-900 uppercase">ID Pesanan</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-pink-900 uppercase">Pelanggan</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-pink-900 uppercase">Tgl Pesan</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-pink-900 uppercase">Tgl Acara</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-pink-900 uppercase">Total</th>
                                    <th class="px-4 py-4 text-center text-xs font-bold text-pink-900 uppercase">Tipe Bayar</th>
                                    <th class="px-4 py-4 text-center text-xs font-bold text-pink-900 uppercase">Status Bayar</th>
                                    <th class="px-5 py-4 text-center text-xs font-bold text-pink-900 uppercase">Status Pesanan</th>
                                    <th class="px-4 py-4 text-center text-xs font-bold text-pink-900 uppercase">Detail</th>
                                    <th class="px-4 py-4 text-center text-xs font-bold text-pink-900 uppercase">Batal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <?php if (!empty($pesanan_list)): ?>
                                    <?php foreach ($pesanan_list as $pesanan): 
                                        $detail_query = mysqli_query($conn, "SELECT dp.*, m.nama_menu, m.gambar FROM detail_pesanan dp LEFT JOIN menu m ON dp.menu_id = m.menu_id WHERE dp.pesanan_id = {$pesanan['pesanan_id']}");
                                        $details = [];
                                        while ($d = mysqli_fetch_assoc($detail_query)) { $details[] = $d; }
                                        
                                        $payment_status = strtoupper(trim($pesanan['status_pembayaran'] ?? ''));
                                        $status_dp       = strtoupper(trim($pesanan['status_dp'] ?? ''));
                                        $metode_db       = strtoupper(trim($pesanan['metode_pembayaran'] ?? ''));
                                        $status_db       = strtoupper(trim($pesanan['status_pesanan'] ?? ''));
                                        $catatan_db      = strtoupper(trim($pesanan['catatan'] ?? ''));
                                        
                                        // LOGIKA TIPE BAYAR
                                        $tipe_pembayaran = 'FULL (100%)'; 
                                        $badge_class     = 'badge-full'; 
                                        $is_dp           = false;
                                        
                                        if (strpos($metode_db, 'DP') !== false || $status_dp === 'MENUNGGU KONFIRMASI' || strpos($catatan_db, 'DP') !== false || $status_dp === 'DP DIKONFIRMASI') { 
                                            $tipe_pembayaran = 'DP (50%)'; 
                                            $badge_class     = 'badge-dp'; 
                                            $is_dp           = true;
                                        } elseif ($payment_status === '' && $status_dp === '') {
                                            $tipe_pembayaran = 'Belum Ada';
                                            $badge_class     = 'badge-none';
                                        }

                                        // LOGIKA KONDISI KOLOM STATUS BAYAR
                                        $bisa_konfirmasi_sekarang = false;
                                        if ($status_db === 'DIBATALKAN' || $status_db === 'SELESAI') {
                                            $text_status_bayar = ($status_db === 'SELESAI') ? 'Selesai' : 'Batal';
                                            $payment_badge_class = ($status_db === 'SELESAI') ? 'payment-sudah opacity-60 cursor-not-allowed' : 'payment-belum opacity-60 cursor-not-allowed';
                                        } else {
                                            if ($payment_status === 'SELESAI' || $status_dp === 'LUNAS') {
                                                $text_status_bayar = 'Selesai';
                                                $payment_badge_class = 'payment-sudah';
                                            } else if ($status_dp === 'DP DIKONFIRMASI') {
                                                $text_status_bayar = 'Konfirmasi Pelunasan';
                                                $payment_badge_class = 'bg-blue-100 text-blue-800 border-blue-300 cursor-pointer hover:bg-blue-200';
                                                $bisa_konfirmasi_sekarang = true;
                                                $action_type = 'konfirmasi_pelunasan';
                                            } else {
                                                if ($is_dp) {
                                                    $text_status_bayar = 'Konfirmasi DP';
                                                    $action_type = 'konfirmasi_dp';
                                                } else {
                                                    $text_status_bayar = 'Konfirmasi Pelunasan';
                                                    $action_type = 'konfirmasi_pelunasan';
                                                }
                                                $payment_badge_class = 'bg-amber-100 text-amber-800 border-amber-300 cursor-pointer hover:bg-amber-200';
                                                $bisa_konfirmasi_sekarang = true;
                                            }
                                        }

                                        // ================= LOGIKA FIX BARU: SIKLUS STATUS PESANAN (Menunggu Pembayaran -> Pending -> Proses -> Kirim -> Selesai) =================
                                        $next_status = ''; $btn_text = ''; $btn_class = ''; $confirm_msg = ''; $clickable = true;

                                        if ($status_db === 'DIBATALKAN') {
                                            $btn_text = 'Dibatalkan'; $btn_class = 'bg-red-100 text-red-700 border-red-300 cursor-not-allowed'; $clickable = false;
                                        } elseif ($status_db === 'SELESAI') {
                                            $btn_text = 'Selesai'; $btn_class = 'bg-green-100 text-green-700 border-green-300 cursor-not-allowed'; $clickable = false;
                                        } else {
                                            // JIKA BELUM ADA KONFIRMASI PEMBAYARAN APAPUN (Menampilkan "Menunggu Pembayaran" dan dikunci pasif)
                                            if ($status_db === '' || $status_db === 'MENUNGGU VERIFIKASI' || $status_db === 'MENUNGGU BAYAR') {
                                                $btn_text = 'Menunggu Pembayaran'; 
                                                $btn_class = 'bg-gray-100 text-gray-400 border-gray-200 cursor-not-allowed font-medium text-[11px]'; 
                                                $clickable = false;
                                            } 
                                            // JIKA ADMIN SUDAH KLIK TOMBOL BAYAR -> Status Pesanan berubah jadi PENDING (Tombol Aktif Berwarna Biru)
                                            elseif ($status_db === 'PENDING') {
                                                $btn_text = 'Pending'; $btn_class = 'bg-blue-500 text-white border-blue-600 hover:bg-blue-600';
                                                $next_status = 'Diproses'; $confirm_msg = 'Ubah status pesanan menjadi Diproses?';
                                            }
                                            // SIKLUS KLIK BERIKUTNYA: DIPROSES -> DIKIRIM
                                            elseif ($status_db === 'DIPROSES') {
                                                $btn_text = 'Proses'; $btn_class = 'bg-purple-500 text-white border-purple-600 hover:bg-purple-600';
                                                $next_status = 'Dikirim'; $confirm_msg = 'Ubah status pesanan menjadi Dikirim?';
                                            } 
                                            // SIKLUS KLIK BERIKUTNYA: DIKIRIM -> SELESAI
                                            elseif ($status_db === 'DIKIRIM') {
                                                $btn_text = 'Kirim'; $btn_class = 'bg-amber-500 text-white border-amber-600 hover:bg-amber-600';
                                                $next_status = 'Selesai'; $confirm_msg = 'Selesaikan pesanan ini? Status Selesai bersifat permanen.';
                                            }
                                        }
                                    ?>
                                    <tr class="hover:bg-pink-50 text-gray-700 transition">
                                        <td class="px-5 py-4 whitespace-nowrap font-mono text-sm font-bold">
                                            #ORD-<?php echo str_pad($pesanan['pesanan_id'], 3, '0', STR_PAD_LEFT); ?>
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900"><?php echo htmlspecialchars($pesanan['nama_lengkap'] ?? 'N/A'); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo htmlspecialchars($pesanan['no_handphone'] ?? ''); ?></div>
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap text-sm"><?php echo date('d/m/Y', strtotime($pesanan['tanggal_pesan'])); ?></td>
                                        <td class="px-5 py-4 whitespace-nowrap text-sm font-bold"><?php echo $pesanan['tanggal_acara'] != '0000-00-00' ? date('d/m/Y', strtotime($pesanan['tanggal_acara'])) : '-'; ?></td>
                                        <td class="px-5 py-4 whitespace-nowrap font-bold text-pink-600">Rp <?php echo number_format($pesanan['total_pesan'], 0, ',', '.'); ?></td>
                                        
                                        <td class="px-4 py-4 whitespace-nowrap text-center"><span class="<?php echo $badge_class; ?>"><?php echo $tipe_pembayaran; ?></span></td>
                                        
                                        <!-- KOLOM STATUS BAYAR -->
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <?php if ($bisa_konfirmasi_sekarang): ?>
                                                <button onclick="confirmAction(<?php echo $pesanan['pesanan_id']; ?>, '<?php echo $action_type; ?>', 'Konfirmasi pembayaran untuk pesanan #ORD-<?php echo str_pad($pesanan['pesanan_id'], 3, '0', STR_PAD_LEFT); ?>?')" class="status-badge <?php echo $payment_badge_class; ?> border text-xs shadow-sm" type="button">
                                                    <i class="fas fa-exclamation-circle mr-1"></i> <?php echo $text_status_bayar; ?>
                                                </button>
                                            <?php else: ?>
                                                <span class="status-badge <?php echo $payment_badge_class; ?> border text-xs"><?php echo $text_status_bayar; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <!-- KOLOM STATUS PESANAN -->
                                        <td class="px-5 py-4 whitespace-nowrap text-center">
                                            <?php if ($clickable): ?>
                                                <button onclick="confirmAction(<?php echo $pesanan['pesanan_id']; ?>, 'update_status_next', '<?php echo $confirm_msg; ?>', '<?php echo $next_status; ?>')" class="text-xs font-bold border rounded-lg px-2 py-1.5 w-full max-w-[140px] shadow-sm transition block text-center mx-auto uppercase <?php echo $btn_class; ?>"><?php echo $btn_text; ?></button>
                                            <?php else: ?>
                                                <span class="text-xs font-bold border rounded-lg px-2 py-1.5 w-full max-w-[140px] inline-block text-center mx-auto uppercase <?php echo $btn_class; ?>"><?php echo $btn_text; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <!-- KOLOM DETAIL -->
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded text-sm transition font-medium shadow-sm" onclick='showDetailModal(<?php echo json_encode($pesanan); ?>, <?php echo json_encode($details); ?>)'><i class="fas fa-eye"></i> Detail</button>
                                        </td>
                                        
                                        <!-- KOLOM BATAL -->
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <?php if ($status_db !== 'DIBATALKAN' && $status_db !== 'SELESAI'): ?>
                                                <button class="bg-red-500 hover:bg-red-600 text-white w-9 h-9 rounded flex items-center justify-center transition shadow-sm mx-auto" title="Batalkan Pesanan" onclick="confirmAction(<?php echo $pesanan['pesanan_id']; ?>, 'update_status_next', 'Apakah Anda yakin ingin membatalkan pesanan ini?', 'Dibatalkan')"><i class="fas fa-trash-alt"></i></button>
                                            <?php else: ?>
                                                <button class="bg-gray-200 text-gray-400 w-9 h-9 rounded flex items-center justify-center cursor-not-allowed mx-auto" disabled><i class="fas fa-trash-alt"></i></button>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="10" class="px-6 py-8 text-center text-gray-500"><i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i><p>Tidak ada data pesanan ditemukan.</p></td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- MODAL DETAIL (Dibiarkan tetap sama) -->
    <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-xl shadow-xl max-w-2xl w-full mx-4 overflow-hidden transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
            <div class="bg-gradient-to-r from-pink-500 to-pink-600 p-4 text-white flex justify-between items-center">
                <h3 class="text-lg font-bold flex items-center"><i class="fas fa-file-invoice mr-2"></i> Rincian Detail Pesanan <span id="modalOrderId" class="ml-2 font-mono bg-pink-700 px-2 py-0.5 rounded text-sm"></span></h3>
                <button onclick="closeDetailModal()" class="text-white hover:text-gray-200 focus:outline-none"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 overflow-y-auto space-y-4 text-sm flex-1">
                <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-lg border border-gray-200">
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase">Nama Pelanggan</p>
                        <p id="modalCustomerName" class="font-semibold text-gray-800 text-base"></p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs font-bold uppercase">No. Handphone</p>
                        <p id="modalCustomerPhone" class="text-gray-700"></p>
                    </div>
                    <div class="col-span-2">
                        <p class="text-gray-400 text-xs font-bold uppercase">Alamat Pengiriman / Acara</p>
                        <p id="modalAddress" class="text-gray-700"></p>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-gray-800 mb-2 flex items-center border-b pb-1 text-pink-600"><i class="fas fa-utensils mr-2"></i> Menu Yang Dipesan</h4>
                    <div id="modalMenuItems" class="space-y-2"></div>
                </div>
                <div class="bg-pink-50 p-4 rounded-lg border border-pink-100 flex justify-between items-center">
                    <span class="font-bold text-gray-700 text-base">TOTAL PEMBAYARAN:</span>
                    <span id="modalTotalPesan" class="font-mono font-black text-xl text-pink-600"></span>
                </div>
                <div>
                    <p class="text-gray-400 text-xs font-bold uppercase">Catatan Tambahan</p>
                    <p id="modalCatatan" class="text-gray-600 italic bg-gray-50 p-2.5 rounded border border-dashed mt-1"></p>
                </div>
            </div>
            <div class="bg-gray-100 p-4 flex justify-end border-t border-gray-200">
                <button onclick="closeDetailModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg text-sm font-medium shadow-sm transition">Tutup</button>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT LOGIC -->
    <script>
        function confirmAction(pesananId, actionType, pesanKonfirmasi, nextStatus = '') {
            if (confirm(pesanKonfirmasi)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'manajemen_pesanan.php';
                
                const inputs = { 'action': actionType, 'pesanan_id': pesananId };
                if (nextStatus !== '') { inputs['status_baru'] = nextStatus; }
                
                for (let key in inputs) {
                    const input = document.createElement('input');
                    input.type = 'hidden'; 
                    input.name = key; 
                    input.value = inputs[key];
                    form.appendChild(input);
                }
                document.body.appendChild(form);
                form.submit();
            }
        }

        function showDetailModal(pesanan, details) {
            document.getElementById('modalOrderId').innerText = '#ORD-' + String(pesanan.pesanan_id).padStart(3, '0');
            document.getElementById('modalCustomerName').innerText = pesanan.nama_lengkap || 'N/A';
            document.getElementById('modalCustomerPhone').innerText = pesanan.no_handphone || '-';
            document.getElementById('modalAddress').innerText = pesanan.alamat || '-';
            document.getElementById('modalCatatan').innerText = pesanan.catatan || 'Tidak ada catatan tambahan.';
            
            const totalFormatted = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(pesanan.total_pesan);
            document.getElementById('modalTotalPesan').innerText = totalFormatted;

            const menuContainer = document.getElementById('modalMenuItems');
            menuContainer.innerHTML = '';

            if(details.length === 0) {
                menuContainer.innerHTML = '<p class="text-gray-500 italic">Tidak ada rincian item.</p>';
            } else {
                details.forEach(item => {
                    const hargaMenu = new Intl.NumberFormat('id-ID').format(item.harga_satuan);
                    const subtotal = new Intl.NumberFormat('id-ID').format(item.harga_satuan * item.jumlah_menu);
                    
                    let tambahanHtml = '';
                    if(item.nama_tambahan) {
                        tambahanHtml = `<div class="text-xs text-amber-700 bg-amber-50 px-2 py-1 rounded inline-block mt-1">+ Tambahan: ${item.nama_tambahan} (${item.jumlah_tambahan}x)</div>`;
                    }

                    const div = document.createElement('div');
                    div.className = 'flex items-start justify-between border-b pb-2 text-gray-700';
                    div.innerHTML = `
                        <div>
                            <p class="font-semibold text-gray-800">${item.nama_menu}</p>
                            <p class="text-xs text-gray-500">${item.jumlah_menu} x Rp ${hargaMenu}</p>
                            ${tambahanHtml}
                        </div>
                        <span class="font-mono font-bold text-gray-900">Rp ${subtotal}</span>
                    `;
                    menuContainer.appendChild(div);
                });
            }

            const modal = document.getElementById('detailModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                modal.firstElementChild.classList.remove('scale-95');
            }, 20);
        }

        function closeDetailModal() {
            const modal = document.getElementById('detailModal');
            modal.classList.add('opacity-0');
            modal.firstElementChild.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>
</body>
</html>
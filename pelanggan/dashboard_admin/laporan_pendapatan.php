<?php
include('../koneksi.php');

if (!isset($koneksi) && isset($conn)) { $koneksi = $conn; }

// Tangkap filter bulan, tahun, dan status
$bulan_pilihan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun_pilihan = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$status_filter = isset($_GET['status_pembayaran']) ? $_GET['status_pembayaran'] : 'Selesai';

// 1. Ambil Total Pendapatan berdasarkan filter
$query_total = mysqli_query($koneksi, "SELECT SUM(total_pembayaran) as total FROM pembayaran 
    WHERE status_pembayaran = '$status_filter' 
    AND MONTH(tanggal_pembayaran) = '$bulan_pilihan' 
    AND YEAR(tanggal_pembayaran) = '$tahun_pilihan'");
$row_total = mysqli_fetch_assoc($query_total);
$total_pendapatan = $row_total['total'] ?? 0;

// 2. Ambil Jumlah Transaksi berdasarkan filter
$query_pesanan_count = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pembayaran 
    WHERE status_pembayaran = '$status_filter' 
    AND MONTH(tanggal_pembayaran) = '$bulan_pilihan' 
    AND YEAR(tanggal_pembayaran) = '$tahun_pilihan'");
$row_pesanan_count = mysqli_fetch_assoc($query_pesanan_count);
$jumlah_pesanan = $row_pesanan_count['jml'] ?? 0;

$rata_rata = ($jumlah_pesanan > 0) ? ($total_pendapatan / $jumlah_pesanan) : 0;

// 3. Ambil Data Riwayat Transaksi (Menampilkan metode_pembayaran dan status_pesanan)
$query_history = mysqli_query($koneksi, "SELECT p.tanggal_pembayaran, p.pembayaran_id, p.total_pembayaran, p.metode_pembayaran, p.status_pembayaran, ps.status_pesanan 
    FROM pembayaran p 
    JOIN pesanan ps ON p.pesanan_id = ps.pesanan_id 
    WHERE p.status_pembayaran = '$status_filter' 
    AND MONTH(p.tanggal_pembayaran) = '$bulan_pilihan' 
    AND YEAR(p.tanggal_pembayaran) = '$tahun_pilihan'
    ORDER BY p.tanggal_pembayaran DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .status-oval {
            border-radius: 9999px;
            padding: 4px 14px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-block;
            border: 1px solid;
            min-width: 100px;
            text-align: center;
        }
    </style>
</head>
<body class="bg-[#D1D5DB] font-sans flex">

    <?php include('sidebar.php'); ?>

    <main class="flex-1 h-screen overflow-y-auto">
        <header class="bg-white shadow-sm p-6 mb-8 flex justify-between items-center border-b border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-[#1e293b] flex items-center">
                    <i class="fas fa-file-invoice-dollar mr-3 text-pink-600"></i> Laporan Pendapatan
                </h1>
                <p class="text-slate-500 text-xs mt-1">Kelola laporan pendapatan dan riwayat transaksi</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-pink-600 text-white px-5 py-2 rounded-full text-xs font-bold shadow-lg uppercase tracking-widest">
                    <i class="fas fa-receipt mr-2"></i> <?= $jumlah_pesanan ?> Transaksi
                </div>
                <button onclick="window.print()" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 shadow-md transition">
                    <i class="fas fa-print mr-2"></i> Cetak Laporan
                </button>
            </div>
        </header>

        <div class="px-10 pb-10">
            <div class="bg-white p-6 rounded-2xl shadow-sm mb-10 border border-slate-100">
                <form method="GET" class="flex flex-wrap gap-4 items-end">
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Bulan</label>
                        <select name="bulan" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg block w-40 p-2.5">
                            <?php
                            $bulan_nama = ['01'=>'Januari','02'=>'Februari','03'=>'Maret','04'=>'April','05'=>'Mei','06'=>'Juni','07'=>'Juli','08'=>'Agustus','09'=>'September','10'=>'Oktober','11'=>'November','12'=>'Desember'];
                            foreach ($bulan_nama as $m => $nama) {
                                echo "<option value='$m' ".($m == $bulan_pilihan ? 'selected' : '').">$nama</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Tahun</label>
                        <select name="tahun" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg block w-32 p-2.5">
                            <?php
                            $tahun_now = date('Y');
                            for ($y = $tahun_now; $y >= $tahun_now - 5; $y--) {
                                echo "<option value='$y' ".($y == $tahun_pilihan ? 'selected' : '').">$y</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-blue-700 shadow-md">
                        <i class="fas fa-search mr-2"></i> Tampilkan
                    </button>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <p class="text-slate-500 text-xs font-bold uppercase">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold text-green-600 mt-2">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h3>
                    <p class="text-[10px] text-green-500 mt-1 font-bold">Data Real-time</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <p class="text-slate-500 text-xs font-bold uppercase">Transaksi Selesai</p>
                    <h3 class="text-2xl font-bold text-[#1e293b] mt-2"><?= $jumlah_pesanan ?> Transaksi</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Pembayaran Terverifikasi</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <p class="text-slate-500 text-xs font-bold uppercase">Rata-rata Pendapatan</p>
                    <h3 class="text-2xl font-bold text-blue-600 mt-2">Rp <?= number_format($rata_rata, 0, ',', '.') ?></h3>
                    <p class="text-[10px] text-slate-400 mt-1">Per transaksi</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <div class="p-6 border-b border-slate-100 bg-gray-50/50">
                    <h2 class="font-bold text-lg text-slate-800">Transaksi</h2>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4 text-center">Tanggal Bayar</th>
                            <th class="px-6 py-4 text-center">ID Bayar</th>
                            <th class="px-6 py-4 text-center">Tipe Bayar</th>
                            <th class="px-6 py-4 text-center">Status Bayar</th>
                            <th class="px-6 py-4 text-center">Status Pesanan</th>
                            <th class="px-6 py-4 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                        <?php if (mysqli_num_rows($query_history) > 0) : ?>
                            <?php while($row = mysqli_fetch_assoc($query_history)) : ?>
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 text-center"><?= date('d/m/Y', strtotime($row['tanggal_pembayaran'])) ?></td>
                                <td class="px-6 py-4 text-center font-mono text-xs font-bold">#PAY-<?= $row['pembayaran_id'] ?></td>
                                <td class="px-6 py-4 text-center">
                                    <span class="status-oval border-blue-400 text-blue-600 bg-blue-50">
                                        <?= htmlspecialchars($row['metode_pembayaran']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="status-oval border-green-500 text-green-600 bg-green-50">
                                        <?= htmlspecialchars($row['status_pembayaran']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="px-3 py-1 border border-gray-200 text-gray-500 rounded text-[10px] font-bold">
                                        <?= htmlspecialchars($row['status_pesanan'] ?: 'Proses') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right font-bold text-slate-900">
                                    Rp <?= number_format($row['total_pembayaran'], 0, ',', '.') ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        <?php else : ?>
                            <tr><td colspan="6" class="px-6 py-10 text-center text-slate-400 italic">Data transaksi tidak ditemukan.</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
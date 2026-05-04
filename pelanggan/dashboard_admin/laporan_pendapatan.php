<?php
include('../koneksi.php');

if (!isset($koneksi) && isset($conn)) { $koneksi = $conn; }

// Tangkap filter bulan dan tahun (default ke bulan & tahun sekarang jika belum dipilih)
$bulan_pilihan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun_pilihan = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

// 1. Ambil Total Pendapatan berdasarkan filter
$query_total = mysqli_query($koneksi, "SELECT SUM(total_pembayaran) as total FROM pembayaran 
    WHERE status_pembayaran = 'Selesai' 
    AND MONTH(tanggal_pembayaran) = '$bulan_pilihan' 
    AND YEAR(tanggal_pembayaran) = '$tahun_pilihan'");
$row_total = mysqli_fetch_assoc($query_total);
$total_pendapatan = $row_total['total'] ?? 0;

// 2. Ambil Jumlah Transaksi Selesai berdasarkan filter
$query_pesanan = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pembayaran 
    WHERE status_pembayaran = 'Selesai' 
    AND MONTH(tanggal_pembayaran) = '$bulan_pilihan' 
    AND YEAR(tanggal_pembayaran) = '$tahun_pilihan'");
$row_pesanan = mysqli_fetch_assoc($query_pesanan);
$jumlah_pesanan = $row_pesanan['jml'] ?? 0;

// 3. Rata-rata
$rata_rata = ($jumlah_pesanan > 0) ? ($total_pendapatan / $jumlah_pesanan) : 0;

// 4. Ambil Data Riwayat Transaksi berdasarkan filter
$query_history = mysqli_query($koneksi, "SELECT p.tanggal_pembayaran, p.pembayaran_id, ps.catatan, p.total_pembayaran 
    FROM pembayaran p 
    JOIN pesanan ps ON p.pesanan_id = ps.pesanan_id 
    WHERE p.status_pembayaran = 'Selesai' 
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
</head>
<body class="bg-[#D1D5DB] font-sans flex">

    <?php include('sidebar.php'); ?>

    <main class="flex-1 p-10 h-screen overflow-y-auto">
        <header class="mb-10 flex justify-between items-center">
            <!-- Form Filter Bulan & Tahun -->
<div class="bg-white p-6 rounded-2xl shadow-sm mb-10 border border-slate-100">
    <form method="GET" class="flex flex-wrap gap-4 items-end">
        <div>
            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Bulan</label>
            <select name="bulan" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-40 p-2.5">
                <?php
                $bulan_nama = [
                    '01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April',
                    '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus',
                    '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
                ];
                foreach ($bulan_nama as $m => $nama) {
                    $selected = ($m == $bulan_pilihan) ? 'selected' : '';
                    echo "<option value='$m' $selected>$nama</option>";
                }
                ?>
            </select>
        </div>
        <div>
            <label class="block text-xs font-bold text-slate-400 uppercase mb-2">Tahun</label>
            <select name="tahun" class="bg-slate-50 border border-slate-200 text-slate-700 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-32 p-2.5">
                <?php
                $tahun_sekarang = date('Y');
                for ($y = $tahun_sekarang; $y >= $tahun_sekarang - 5; $y--) {
                    $selected = ($y == $tahun_pilihan) ? 'selected' : '';
                    echo "<option value='$y' $selected>$y</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-blue-700 transition shadow-md">
            <i class="fas fa-search mr-2"></i> Tampilkan
        </button>
        <a href="laporan_pendapatan.php" class="bg-slate-100 text-slate-500 px-6 py-2.5 rounded-lg text-sm font-semibold hover:bg-slate-200 transition">
            Reset
        </a>
    </form>
</div>
            <h1 class="text-3xl font-bold text-[#1e293b]">Laporan Pendapatan</h1>
            <div class="flex gap-4">
                <button onclick="window.print()" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 shadow-md">
                    <i class="fas fa-print mr-2"></i> Cetak Laporan
                </button>
            </div>
        </header>

        <!-- Statistik Utama -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <p class="text-slate-500 text-sm font-medium uppercase">Total Pendapatan</p>
                <h3 class="text-2xl font-bold text-green-600 mt-2">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h3>
                <p class="text-[10px] text-green-500 mt-1 font-bold">Data Real-time</p>
            </div>
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <p class="text-slate-500 text-sm font-medium uppercase">Transaksi Selesai</p>
                <h3 class="text-2xl font-bold text-[#1e293b] mt-2"><?= $jumlah_pesanan ?> Transaksi</h3>
                <p class="text-[10px] text-slate-400 mt-1">Pembayaran Terverifikasi</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                <p class="text-slate-500 text-sm font-medium uppercase">Rata-rata Pendapatan</p>
                <h3 class="text-2xl font-bold text-blue-600 mt-2">Rp <?= number_format($rata_rata, 0, ',', '.') ?></h3>
                <p class="text-[10px] text-slate-400 mt-1">Per transaksi</p>
            </div>
        </div>

        <!-- Tabel Riwayat -->
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="p-6 border-b border-slate-100">
                <h2 class="font-bold text-lg text-slate-800">10 Transaksi Terakhir</h2>
            </div>
            <table class="w-full text-left">
                <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold">
                    <tr>
                        <th class="px-6 py-4">Tanggal Bayar</th>
                        <th class="px-6 py-4">ID Bayar</th>
                        <th class="px-6 py-4">Catatan Pesanan</th>
                        <th class="px-6 py-4 text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                    <?php if (mysqli_num_rows($query_history) > 0) : ?>
                        <?php while($row = mysqli_fetch_assoc($query_history)) : ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4"><?= date('d/m/Y', strtotime($row['tanggal_pembayaran'])) ?></td>
                            <td class="px-6 py-4 font-mono text-xs">#PAY-<?= $row['pembayaran_id'] ?></td>
                            <td class="px-6 py-4 italic text-xs"><?= htmlspecialchars($row['catatan'] ?: '-') ?></td>
                            <td class="px-6 py-4 text-right font-bold text-slate-900">
                                Rp <?= number_format($row['total_pembayaran'], 0, ',', '.') ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else : ?>
                        <tr>
                            <td colspan="4" class="px-6 py-10 text-center text-slate-400">Belum ada data pembayaran 'Selesai'.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
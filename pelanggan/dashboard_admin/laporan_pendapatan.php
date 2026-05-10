<?php
include('../koneksi.php');

if (!isset($koneksi) && isset($conn)) { $koneksi = $conn; }

$bulan_pilihan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun_pilihan = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$status_filter = isset($_GET['status_pembayaran']) ? $_GET['status_pembayaran'] : 'Selesai';

$bulan_nama = [
    '01'=>'Semua Bulan', '02'=>'Januari', '03'=>'Februari', '04'=>'Maret', '05'=>'April', '06'=>'Mei',
    '07'=>'Juni', '08'=>'Juli', '09'=>'Agustus', '10'=>'September', '11'=>'Oktober', '12'=>'November', '13'=>'Desember'
];

// Logika Filter WHERE
if ($bulan_pilihan == '01') {
    $where_query = "AND YEAR(tanggal_pembayaran) = '$tahun_pilihan'";
} else {
    // Penyesuaian index karena Januari dimulai dari 02 di array $bulan_nama
    $bulan_sql = intval($bulan_pilihan) - 1;
    $where_query = "AND MONTH(tanggal_pembayaran) = '$bulan_sql' AND YEAR(tanggal_pembayaran) = '$tahun_pilihan'";
}

// 1. Query Statistik Utama
$query_total = mysqli_query($koneksi, "SELECT SUM(total_pembayaran) as total FROM pembayaran 
    WHERE status_pembayaran = '$status_filter' $where_query");
$row_total = mysqli_fetch_assoc($query_total);
$total_pendapatan = $row_total['total'] ?? 0;

$query_pesanan_count = mysqli_query($koneksi, "SELECT COUNT(*) as jml FROM pembayaran 
    WHERE status_pembayaran = '$status_filter' $where_query");
$row_pesanan_count = mysqli_fetch_assoc($query_pesanan_count);
$jumlah_pesanan = $row_pesanan_count['jml'] ?? 0;

$rata_rata = ($jumlah_pesanan > 0) ? ($total_pendapatan / $jumlah_pesanan) : 0;

// 2. Data untuk Diagram (Jan - Des)
$labels_bulan_chart = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
$data_bulanan = array_fill(0, 12, 0);

$query_chart = mysqli_query($koneksi, "SELECT MONTH(tanggal_pembayaran) as bln, SUM(total_pembayaran) as total 
    FROM pembayaran WHERE status_pembayaran = 'Selesai' AND YEAR(tanggal_pembayaran) = '$tahun_pilihan'
    GROUP BY MONTH(tanggal_pembayaran) ORDER BY bln ASC");

while($r = mysqli_fetch_assoc($query_chart)){
    $index = (int)$r['bln'] - 1; 
    if($index >= 0 && $index < 12) {
        $data_bulanan[$index] = $r['total'];
    }
}

// 3. Query History Tabel
$query_history = mysqli_query($koneksi, "SELECT p.tanggal_pembayaran, p.pembayaran_id, p.total_pembayaran, p.metode_pembayaran, p.status_pembayaran, ps.status_pesanan 
    FROM pembayaran p JOIN pesanan ps ON p.pesanan_id = ps.pesanan_id 
    WHERE p.status_pembayaran = '$status_filter' $where_query
    ORDER BY p.tanggal_pembayaran DESC");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan - Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .status-oval { border-radius: 9999px; padding: 4px 12px; font-size: 10px; font-weight: 700; text-transform: uppercase; border: 1px solid; min-width: 90px; text-align: center; display: inline-block; }
    </style>
</head>
<body class="bg-[#D1D5DB] font-sans flex">
    <?php include('sidebar.php'); ?>

    <main class="flex-1 h-screen overflow-y-auto">
        <header class="bg-white shadow-sm p-6 mb-8 flex justify-between items-center border-b">
            <div>
                <h1 class="text-2xl font-bold text-[#1e293b] flex items-center">
                    <i class="fas fa-chart-line mr-3 text-pink-600"></i> Laporan Pendapatan
                </h1>
                <p class="text-slate-500 text-xs mt-1">Analisis pemasukan Kedai Aishwa</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="bg-pink-600 text-white px-5 py-2 rounded-full text-xs font-bold uppercase tracking-widest">
                    <i class="fas fa-receipt mr-2"></i> <?= $jumlah_pesanan ?> Transaksi
                </div>
                <button onclick="window.print()" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 shadow-md">
                    <i class="fas fa-print mr-2"></i> Cetak
                </button>
            </div>
        </header>

        <div class="px-10 pb-10">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500">
                    <p class="text-slate-400 text-[10px] font-bold uppercase">Total Pendapatan (<?= $bulan_nama[$bulan_pilihan] ?>)</p>
                    <h3 class="text-xl font-bold text-green-600">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h3>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-blue-500">
                    <p class="text-slate-400 text-[10px] font-bold uppercase">Rata-rata</p>
                    <h3 class="text-xl font-bold text-blue-600">Rp <?= number_format($rata_rata, 0, ',', '.') ?></h3>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm flex items-center justify-between border">
                   <form method="GET" class="flex gap-2 w-full">
                        <select name="bulan" class="text-xs border rounded p-2 flex-1">
                            <?php foreach ($bulan_nama as $m => $nama) echo "<option value='$m' ".($m == $bulan_pilihan ? 'selected' : '').">$nama</option>"; ?>
                        </select>
                        <select name="tahun" class="text-xs border rounded p-2 flex-1">
                            <?php $y_now = date('Y'); for($i=$y_now; $i>=$y_now-3; $i--) echo "<option value='$i' ".($i == $tahun_pilihan ? 'selected' : '').">$i</option>"; ?>
                        </select>
                        <button type="submit" class="bg-blue-600 text-white text-[10px] px-4 py-1 rounded font-bold">FILTER</button>
                   </form>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 bg-white rounded-2xl shadow-xl overflow-hidden border">
                    <div class="p-5 border-b bg-gray-50/50 flex justify-between items-center">
                        <h2 class="font-bold text-slate-800">Riwayat Transaksi</h2>
                        <span class="text-[10px] text-slate-400 font-bold uppercase"><?= $bulan_nama[$bulan_pilihan] ?> <?= $tahun_pilihan ?></span>
                    </div>
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold">
                            <tr>
                                <th class="px-6 py-4 text-center">Tanggal</th>
                                <th class="px-6 py-4 text-center">ID Bayar</th>
                                <th class="px-6 py-4 text-center">Tipe</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y text-xs text-slate-600">
                            <?php if(mysqli_num_rows($query_history) > 0) : ?>
                                <?php while($row = mysqli_fetch_assoc($query_history)) : ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 text-center"><?= date('d/m/Y', strtotime($row['tanggal_pembayaran'])) ?></td>
                                    <td class="px-6 py-4 text-center font-mono font-bold">#PAY-<?= $row['pembayaran_id'] ?></td>
                                    <td class="px-6 py-4 text-center"><span class="text-blue-600 font-bold"><?= $row['metode_pembayaran'] ?></span></td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="status-oval border-green-500 text-green-600 bg-green-50 text-[9px]"><?= $row['status_pembayaran'] ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-slate-900">Rp <?= number_format($row['total_pembayaran'], 0, ',', '.') ?></td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr><td colspan="5" class="px-6 py-10 text-center text-slate-400 italic">Tidak ada data transaksi.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-xl border flex flex-col">
                    <h2 class="font-bold text-slate-800 mb-6 italic underline decoration-pink-500">Tren Pendapatan Bulanan</h2>
                    <div class="flex-1 flex items-center">
                        <canvas id="incomeChart"></canvas>
                    </div>
                    <div class="mt-6 p-4 bg-slate-50 rounded-lg">
                        <p class="text-[10px] text-slate-500 leading-relaxed italic">
                            * Grafik menampilkan perbandingan total pendapatan dari Januari sampai Desember pada tahun <strong><?= $tahun_pilihan ?></strong>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        const ctx = document.getElementById('incomeChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= json_encode($labels_bulan_chart) ?>,
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: <?= json_encode($data_bulanan) ?>,
                    backgroundColor: 'rgba(219, 39, 119, 0.1)',
                    borderColor: 'rgb(219, 39, 119)',
                    borderWidth: 3,
                    pointBackgroundColor: 'rgb(219, 39, 119)',
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { 
                        beginAtZero: true, 
                        ticks: { 
                            font: { size: 9 },
                            callback: function(value) { return 'Rp ' + value.toLocaleString(); }
                        } 
                    },
                    x: { ticks: { font: { size: 8 } } }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
</body>
</html>
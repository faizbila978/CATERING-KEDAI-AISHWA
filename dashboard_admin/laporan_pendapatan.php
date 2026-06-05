<?php
include('../koneksi.php');

if (!isset($koneksi) && isset($conn)) { $koneksi = $conn; }

// --- LOGIKA PROSES STATUS ---
if (isset($_GET['action']) && $_GET['action'] == 'update_status' && isset($_GET['id'])) {
    $id_bayar = $_GET['id'];
    $tgl_sekarang = date('Y-m-d');
    $waktu_sekarang = date('H:i:s');

    // Update status menjadi Selesai
    $update = mysqli_query($koneksi, "UPDATE pembayaran SET 
        status_pembayaran = 'Selesai', 
        status_dp = 'Lunas',
        tanggal_pembayaran = '$tgl_sekarang',
        waktu_pembayaran = '$waktu_sekarang'
        WHERE pembayaran_id = '$id_bayar'");

    if ($update) {
        echo "<script>alert('Status Berhasil Diperbarui!'); window.location.href='laporan_pendapatan.php';</script>";
    }
}

// 1. Ambil Parameter Filter
$bulan_pilihan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');
$tahun_pilihan = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');

$bulan_nama = [
    'all' => 'Semua Bulan', '01' => 'Januari', '02' => 'Februari', '03' => 'Maret',
    '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli',
    '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'
];

// 2. Query untuk Statistik Box (Menggunakan COALESCE agar jika total_pembayaran masih kosong/0, diambil dari total_pesan yang asli)
$where_stats = ($bulan_pilihan == 'all') 
    ? "AND YEAR(COALESCE(p.tanggal_pembayaran, ps.tanggal_pesan)) = '$tahun_pilihan'" 
    : "AND MONTH(COALESCE(p.tanggal_pembayaran, ps.tanggal_pesan)) = '$bulan_pilihan' AND YEAR(COALESCE(p.tanggal_pembayaran, ps.tanggal_pesan)) = '$tahun_pilihan'";

$query_stats = mysqli_query($koneksi, "SELECT SUM(COALESCE(NULLIF(p.total_pembayaran, 0), ps.total_pesan)) as total, COUNT(p.pembayaran_id) as jml 
    FROM pembayaran p 
    JOIN pesanan ps ON p.pesanan_id = ps.pesanan_id
    WHERE p.status_pembayaran = 'Selesai' $where_stats");
$res_stats = mysqli_fetch_assoc($query_stats);

$total_pendapatan = $res_stats['total'] ?? 0;
$jumlah_transaksi = $res_stats['jml'] ?? 0;
$rata_rata = ($jumlah_transaksi > 0) ? ($total_pendapatan / $jumlah_transaksi) : 0;

// 3. Query Chart (Disinkronkan dengan total nominal pesanan yang valid)
$data_chart = array_fill(0, 12, 0);
$query_chart = mysqli_query($koneksi, "SELECT MONTH(COALESCE(p.tanggal_pembayaran, ps.tanggal_pesan)) as bln, SUM(COALESCE(NULLIF(p.total_pembayaran, 0), ps.total_pesan)) as total 
    FROM pembayaran p 
    JOIN pesanan ps ON p.pesanan_id = ps.pesanan_id
    WHERE p.status_pembayaran = 'Selesai' AND YEAR(COALESCE(p.tanggal_pembayaran, ps.tanggal_pesan)) = '$tahun_pilihan'
    GROUP BY MONTH(COALESCE(p.tanggal_pembayaran, ps.tanggal_pesan))");

while($rc = mysqli_fetch_assoc($query_chart)){
    $data_chart[(int)$rc['bln'] - 1] = (float)$rc['total'];
}

// 4. Query Riwayat Transaksi (Menampilkan data pesanan secara utuh)
$query_history = mysqli_query($koneksi, "SELECT p.*, ps.total_pesan, ps.tanggal_acara, ps.alamat, ps.tanggal_pesan 
    FROM pembayaran p 
    JOIN pesanan ps ON p.pesanan_id = ps.pesanan_id 
    WHERE p.status_pembayaran = 'Selesai' $where_stats
    ORDER BY COALESCE(p.tanggal_pembayaran, ps.tanggal_pesan) DESC"); 
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Pendapatan - Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
                    <i class="fas fa-receipt mr-2"></i> <?= $jumlah_transaksi ?> Transaksi Selesai
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
                        <h2 class="font-bold text-slate-800">Riwayat Transaksi (Selesai)</h2>
                        <span class="text-[10px] text-slate-400 font-bold uppercase"><?= $bulan_nama[$bulan_pilihan] ?> <?= $tahun_pilihan ?></span>
                    </div>
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold">
                            <tr>
                                <th class="px-6 py-4 text-center">Tgl Bayar</th>
                                <th class="px-6 py-4">Detail Pesanan</th>
                                <th class="px-6 py-4 text-center">Metode</th>
                                <th class="px-6 py-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y text-xs text-slate-600">
                            <?php if(mysqli_num_rows($query_history) > 0) : ?>
                                <?php while($row = mysqli_fetch_assoc($query_history)) : 
                                    $tanggal_final = !empty($row['tanggal_pembayaran']) ? $row['tanggal_pembayaran'] : $row['tanggal_pesan'];
                                    $metode_final = !empty($row['metode_pembayaran']) ? strtoupper($row['metode_pembayaran']) : 'TRANSFER BANK';
                                    
                                    // Ambil nilai nominal yang valid (prioritas total_pembayaran, jika 0 atau null gunakan total_pesan)
                                    $nominal_tampil = ($row['total_pembayaran'] > 0) ? $row['total_pembayaran'] : $row['total_pesan'];
                                ?>
                                <tr class="hover:bg-slate-50 transition">
                                    <td class="px-6 py-4 text-center"><?= date('d/m/Y', strtotime($tanggal_final)) ?></td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold">#PAY-<?= $row['pembayaran_id'] ?> (ORD-<?= $row['pesanan_id'] ?>)</div>
                                        <div class="text-[10px] text-slate-400"><?= substr($row['alamat'], 0, 45) ?>...</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="px-2 py-1 bg-gray-100 rounded uppercase font-semibold text-[9px]"><?= $metode_final ?></span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-green-600">
                                        Rp <?= number_format($nominal_tampil, 0, ',', '.') ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr><td colspan="4" class="px-6 py-10 text-center text-slate-400 italic">Tidak ada data transaksi selesai ditemukan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-xl border flex flex-col">
                    <h2 class="font-bold text-slate-800 mb-6 italic underline decoration-pink-500">Tren Pendapatan Bulanan</h2>
                    <div class="flex-1 flex items-center">
                        <canvas id="incomeChart" style="height: 280px; min-height: 280px;"></canvas>
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
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: <?= json_encode($data_chart) ?>,
                    backgroundColor: 'rgba(219, 39, 119, 0.1)',
                    borderColor: 'rgb(219, 39, 119)',
                    borderWidth: 3,
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
                        ticks: { callback: function(value) { return 'Rp ' + value.toLocaleString(); } } 
                    }
                }
            }
        });
    </script>
</body>
</html>
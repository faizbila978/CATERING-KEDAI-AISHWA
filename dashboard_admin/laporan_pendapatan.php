<?php
session_start(); // Wajib dijalankan di awal untuk membaca data session yang aktif
include('../koneksi.php');

if (!isset($koneksi) && isset($conn)) { $koneksi = $conn; }

// Proteksi Halaman: Jika belum login atau role-nya bukan admin, tendang kembali ke login.php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../../login.php?status=wajib_login");
    exit();
}

// --- LOGIKA PROSES STATUS ---
if (isset($_GET['action']) && $_GET['action'] == 'update_status' && isset($_GET['id'])) {
    $id_bayar = $_GET['id'];
    $tgl_sekarang = date('Y-m-d');
    $waktu_sekarang = date('H:i:s');

    // Update status menjadi Selesai
    $update = mysqli_query($koneksi, "UPDATE pembayaran SET 
        status_pembayaran = 'Selesai', 
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

// 2. Query untuk Statistik Box (Hanya Selesai)
$where_stats = ($bulan_pilihan == 'all') 
    ? "AND YEAR(p.tanggal_pembayaran) = '$tahun_pilihan'" 
    : "AND MONTH(p.tanggal_pembayaran) = '$bulan_pilihan' AND YEAR(p.tanggal_pembayaran) = '$tahun_pilihan'";

$query_stats = mysqli_query($koneksi, "SELECT SUM(p.total_pembayaran) as total, COUNT(p.pembayaran_id) as jml 
    FROM pembayaran p 
    WHERE p.status_pembayaran = 'Selesai' $where_stats");
$res_stats = mysqli_fetch_assoc($query_stats);

$total_pendapatan = $res_stats['total'] ?? 0;
$jumlah_transaksi = $res_stats['jml'] ?? 0;
$rata_rata = ($jumlah_transaksi > 0) ? ($total_pendapatan / $jumlah_transaksi) : 0;

// 3. Query Chart
$data_chart = array_fill(0, 12, 0);
$query_chart = mysqli_query($koneksi, "SELECT MONTH(tanggal_pembayaran) as bln, SUM(total_pembayaran) as total 
    FROM pembayaran 
    WHERE status_pembayaran = 'Selesai' AND YEAR(tanggal_pembayaran) = '$tahun_pilihan'
    GROUP BY MONTH(tanggal_pembayaran)");

while($rc = mysqli_fetch_assoc($query_chart)){
    $data_chart[(int)$rc['bln'] - 1] = (float)$rc['total'];
}

// 4. Query Riwayat Transaksi
$query_history = mysqli_query($koneksi, "SELECT p.*, ps.tanggal_acara, ps.alamat, ps.no_handphone, u.nama_lengkap 
    FROM pembayaran p 
    JOIN pesanan ps ON p.pesanan_id = ps.pesanan_id 
    JOIN users u ON ps.user_id = u.user_id
    WHERE p.status_pembayaran = 'Selesai' $where_stats
    ORDER BY p.tanggal_pembayaran DESC"); 

// --- 5. LOGIKA DOWNLOAD EXCEL ---
if (isset($_GET['download']) && $_GET['download'] == 'excel') {
    header("Content-Type: application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=Rekap_Penjualan_Aishwa_" . $bulan_nama[$bulan_pilihan] . "_" . $tahun_pilihan . ".xls");
    header("Pragma: no-cache");
    header("Expires: 0");

    echo "<h3>REKAP LAPORAN PENJUALAN KEDAI AISHWA</h3>";
    echo "<p>Periode: " . $bulan_nama[$bulan_pilihan] . " " . $tahun_pilihan . "</p>";
    echo "<table border='1'>
            <thead>
                <tr style='background-color: #db2777; color: white; font-weight: bold;'>
                    <th>No</th>
                    <th>ID Pembayaran</th>
                    <th>Tanggal Bayar</th>
                    <th>Nama Pelanggan</th>
                    <th>No. HP</th>
                    <th>Metode Pembayaran</th>
                    <th>Tanggal Acara</th>
                    <th>Alamat Pengiriman</th>
                    <th>Total Pemasukan</th>
                </tr>
            </thead>
            <tbody>";
    
    $no = 1;
    if (mysqli_num_rows($query_history) > 0) {
        while ($row = mysqli_fetch_assoc($query_history)) {
            echo "<tr>
                    <td align='center'>".$no++."</td>
                    <td>#PAY-".$row['pembayaran_id']."</td>
                    <td>".date('d/m/Y', strtotime($row['tanggal_pembayaran']))."</td>
                    <td>".$row['nama_lengkap']."</td>
                    <td>'".$row['no_handphone']."</td>
                    <td align='center'>".strtoupper($row['metode_pembayaran'])."</td>
                    <td>".date('d/m/Y', strtotime($row['tanggal_acara']))."</td>
                    <td>".$row['alamat']."</td>
                    <td align='right'>".$row['total_pembayaran']."</td>
                  </tr>";
        }
        echo "<tr style='font-weight: bold; background-color: #f3f4f6;'>
                <td colspan='8' align='right'>TOTAL PENDAPATAN:</td>
                <td align='right'>".$total_pendapatan."</td>
              </tr>";
    } else {
        echo "<tr><td colspan='9' align='center'>Tidak ada data transaksi selesai.</td></tr>";
    }
    echo "</tbody></table>";
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <!-- Viewport tag wajib untuk responsive mobile device -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-[#f1f5f9] font-sans flex flex-col md:flex-row min-h-screen">
    
    <!-- Catatan: Di file sidebar.php Anda, pastikan juga sudah mendukung responsive (bisa disembunyikan / hamburger menu di mobile) -->
    <?php include('sidebar.php'); ?>

    <main class="flex-1 h-screen overflow-y-auto">
        <!-- Header: Flex-col pada mobile, flex-row pada desktop -->
        <header class="bg-white shadow-sm p-4 md:p-6 mb-4 md:mb-8 flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center border-b border-slate-200">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-[#1e293b] flex items-center">
                    <i class="fas fa-chart-line mr-3 text-pink-600"></i> Laporan Pendapatan
                </h1>
                <p class="text-slate-500 text-xs mt-1">Analisis pemasukan Kedai Aishwa</p>
            </div>
            <div class="flex flex-wrap items-center gap-2 md:gap-4 w-full sm:w-auto">
                <div class="bg-pink-600 text-white px-4 py-2 rounded-full text-[11px] md:text-xs font-bold uppercase tracking-widest shadow-sm">
                    <i class="fas fa-receipt mr-1.5"></i> <?= $jumlah_transaksi ?> Selesai
                </div>
                <a href="laporan_pendapatan.php?bulan=<?= $bulan_pilihan ?>&tahun=<?= $tahun_pilihan ?>&download=excel" class="bg-green-600 text-white px-4 py-2 rounded-lg text-xs md:text-sm font-semibold hover:bg-green-700 shadow-sm flex items-center transition w-full sm:w-auto justify-center">
                    <i class="fas fa-file-excel mr-2"></i> Download Excel
                </a>
            </div>
        </header>

        <!-- Container utama: Padding mengecil di mobile (px-4) dan melebar di desktop (md:px-10) -->
        <div class="px-4 md:px-10 pb-10 space-y-6 md:space-y-8">
            
            <!-- Grid Statistik Utama: 1 kolom di HP, 3 kolom di desktop -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border-l-4 border-green-500 hover:shadow-md transition">
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Total Pendapatan (<?= $bulan_nama[$bulan_pilihan] ?>)</p>
                    <h3 class="text-xl md:text-2xl font-bold text-green-600 mt-1">Rp <?= number_format($total_pendapatan, 0, ',', '.') ?></h3>
                </div>
                <div class="bg-white p-5 md:p-6 rounded-2xl shadow-sm border-l-4 border-blue-500 hover:shadow-md transition">
                    <p class="text-slate-400 text-[10px] font-bold uppercase tracking-wider">Rata-rata Pendapatan</p>
                    <h3 class="text-xl md:text-2xl font-bold text-blue-600 mt-1">Rp <?= number_format($rata_rata, 0, ',', '.') ?></h3>
                </div>
                <div class="bg-white p-4 md:p-5 rounded-2xl shadow-sm flex items-center justify-between border border-slate-100 hover:shadow-md transition">
                   <form method="GET" class="flex flex-wrap sm:flex-nowrap gap-2 w-full items-center">
                        <select name="bulan" class="text-xs border border-slate-200 bg-slate-50 rounded-lg p-2.5 flex-1 w-full sm:w-auto font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php foreach ($bulan_nama as $m => $nama) echo "<option value='$m' ".($m == $bulan_pilihan ? 'selected' : '').">$nama</option>"; ?>
                        </select>
                        <select name="tahun" class="text-xs border border-slate-200 bg-slate-50 rounded-lg p-2.5 flex-1 w-full sm:w-auto font-medium text-slate-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <?php $y_now = date('Y'); for($i=$y_now; $i>=$y_now-3; $i--) echo "<option value='$i' ".($i == $tahun_pilihan ? 'selected' : '').">$i</option>"; ?>
                        </select>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-[11px] px-4 py-2.5 rounded-lg font-bold transition shadow-sm tracking-wider uppercase w-full sm:w-auto">FILTER</button>
                   </form>
                </div>
            </div>

            <!-- Riwayat Transaksi -->
            <div class="bg-white rounded-2xl shadow-md overflow-hidden border border-slate-200 flex flex-col">
                <div class="p-4 md:p-5 border-b bg-gray-50/70 flex justify-between items-center">
                    <h2 class="font-bold text-sm md:text-base text-slate-800 flex items-center gap-2">
                        <i class="fas fa-history text-pink-600"></i> Riwayat Transaksi
                    </h2>
                    <span class="text-[9px] md:text-[10px] bg-slate-200 text-slate-600 px-2 py-1 rounded font-bold uppercase tracking-wider"><?= $bulan_nama[$bulan_pilihan] ?> <?= $tahun_pilihan ?></span>
                </div>
                
                <!-- Pembungkus Tabel: Ditambahkan overflow-x-auto agar tabel bisa di-swipe ke kanan-kiri di HP tanpa merusak layout luar -->
                <div class="overflow-x-auto overflow-y-auto max-h-[380px] custom-scrollbar w-full">
                    <table class="w-full text-left border-collapse min-w-[600px]"> <!-- min-w menjaga agar kolom tidak gepeng di mobile -->
                        <thead class="bg-slate-50 text-slate-500 text-[11px] uppercase font-bold sticky top-0 border-b shadow-sm z-10 bg-slate-50">
                            <tr>
                                <th class="px-4 md:px-6 py-4 text-center">Tgl Bayar</th>
                                <th class="px-4 md:px-6 py-4">Detail Pesanan</th>
                                <th class="px-4 md:px-6 py-4 text-center">Metode</th>
                                <th class="px-4 md:px-6 py-4 text-right">Total</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                            <?php 
                            mysqli_data_seek($query_history, 0);
                            if(mysqli_num_rows($query_history) > 0) : 
                            ?>
                                <?php while($row = mysqli_fetch_assoc($query_history)) : ?>
                                <tr class="hover:bg-slate-50/80 transition">
                                    <td class="px-4 md:px-6 py-4 text-center font-medium text-slate-500"><?= date('d/m/Y', strtotime($row['tanggal_pembayaran'])) ?></td>
                                    <td class="px-4 md:px-6 py-4">
                                        <div class="font-bold text-slate-800">#PAY-<?= $row['pembayaran_id'] ?> - <span class="text-pink-600 font-semibold"><?= htmlspecialchars($row['nama_lengkap']) ?></span></div>
                                        <div class="text-[10px] text-slate-400 mt-0.5 max-w-[250px] md:max-w-none truncate"><?= htmlspecialchars($row['alamat']) ?></div>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 text-center">
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-700 rounded-md uppercase font-bold text-[9px] border border-slate-200/60"><?= $row['metode_pembayaran'] ?></span>
                                    </td>
                                    <td class="px-4 md:px-6 py-4 text-right font-bold text-slate-900 text-sm">
                                        Rp <?= number_format($row['total_pembayaran'], 0, ',', '.') ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else : ?>
                                <tr><td colspan="4" class="px-6 py-12 text-center text-slate-400 italic bg-slate-50/30">Tidak ada data transaksi selesai ditemukan.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Bagian Bawah: Grafik Penjualan -->
            <div class="bg-white p-4 md:p-6 rounded-2xl shadow-md border border-slate-200 flex flex-col">
                <div class="mb-4">
                    <h2 class="font-bold text-slate-800 flex items-center gap-2 text-base">
                        <i class="fas fa-chart-area text-pink-600"></i> Tren Pendapatan Bulanan
                    </h2>
                    <p class="text-xs text-slate-400 mt-0.5">Visualisasi performa tahun ke-<?= $tahun_pilihan ?></p>
                </div>
                <!-- h-64 di mobile, h-80 di desktop agar ukuran grafik proporsional -->
                <div class="h-64 md:h-80 w-full relative">
                    <canvas id="incomeChart"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script>
        const ctx = document.getElementById('incomeChart').getContext('2d');
        
        const gradientBg = ctx.createLinearGradient(0, 0, 0, 300);
        gradientBg.addColorStop(0, 'rgba(219, 39, 119, 0.35)');
        gradientBg.addColorStop(1, 'rgba(219, 39, 119, 0.00)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: <?= json_encode($data_chart) ?>,
                    backgroundColor: gradientBg,
                    borderColor: 'rgb(219, 39, 119)',
                    borderWidth: 2.5,
                    pointBackgroundColor: 'rgb(219, 39, 119)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 1.5,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    tension: 0.38,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        grid: { color: 'rgba(226, 232, 240, 0.6)' },
                        ticks: { 
                            font: { size: 9, weight: '500' },
                            // Format ringkas untuk mobile jika angka terlalu panjang (Contoh: Rp 1.5M / Rp 500rb)
                            callback: function(value) { 
                                if (value >= 1e6) {
                                    return 'Rp ' + (value / 1e6).toFixed(1) + 'jt';
                                } else if (value >= 1e3) {
                                    return 'Rp ' + (value / 1e3).toFixed(0) + 'rb';
                                }
                                return 'Rp ' + value;
                            } 
                        } 
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 9, weight: '500' } }
                    }
                }
            }
        });
    </script>
</body>
</html>
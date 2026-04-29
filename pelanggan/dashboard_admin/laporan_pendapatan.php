<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="laporan pendapatan.css">
</head>
<body class="bg-slate-50 font-sans flex">

    <!-- Memanggil Sidebar Modular -->
    <?php include('sidebar.php'); ?>

    <main class="flex-1 p-10 h-screen overflow-y-auto">
        <header class="mb-10 flex justify-between items-center">
            <h1 id="pageHeader" class="text-3xl font-bold text-[#1e293b]">Laporan Pendapatan</h1>
            <div class="flex gap-4">
                <button class="bg-white border border-gray-300 px-4 py-2 rounded-lg text-sm font-semibold text-gray-600 hover:bg-gray-50">
                    <i class="fas fa-calendar-alt mr-2"></i> Filter Tanggal
                </button>
                <button class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700 shadow-md">
                    <i class="fas fa-file-excel mr-2"></i> Ekspor Excel
                </button>
            </div>
        </header>

        <div id="dynamicContent" class="fade-in">
            <!-- Grid Ringkasan (Statistik) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <p class="text-slate-500 text-sm font-medium uppercase tracking-wider">Total Pendapatan</p>
                    <h3 class="text-2xl font-bold text-green-600 mt-2">Rp 12.500.000</h3>
                    <p class="text-[10px] text-green-500 mt-1 font-bold"><i class="fas fa-arrow-up"></i> +15% dari bulan lalu</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <p class="text-slate-500 text-sm font-medium uppercase tracking-wider">Pesanan Selesai</p>
                    <h3 class="text-2xl font-bold text-[#1e293b] mt-2">145 Pesanan</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Total pesanan bulan April</p>
                </div>
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
                    <p class="text-slate-500 text-sm font-medium uppercase tracking-wider">Rata-rata per Pesanan</p>
                    <h3 class="text-2xl font-bold text-blue-600 mt-2">Rp 86.200</h3>
                    <p class="text-[10px] text-slate-400 mt-1">Berdasarkan total transaksi</p>
                </div>
            </div>

            <!-- Tabel Riwayat Pendapatan -->
            <div class="bg-white rounded-2xl shadow-xl shadow-slate-200 overflow-hidden border border-slate-50">
                <div class="p-6 border-b border-slate-100 flex justify-between items-center">
                    <h2 class="font-bold text-lg text-slate-800">Riwayat Transaksi Terakhir</h2>
                </div>
                <table class="w-full text-left">
                    <thead class="bg-slate-50 text-slate-400 text-[10px] uppercase font-bold">
                        <tr>
                            <th class="px-6 py-4">Tanggal</th>
                            <th class="px-6 py-4">ID Transaksi</th>
                            <th class="px-6 py-4">Produk Terjual</th>
                            <th class="px-6 py-4 text-right">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-600">
                        <!-- Contoh Baris Data -->
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">29 April 2026</td>
                            <td class="px-6 py-4 font-mono text-xs">#TX-99812</td>
                            <td class="px-6 py-4">Paket Aqiqah A (5), Snack Box (20)</td>
                            <td class="px-6 py-4 text-right font-bold text-slate-900">Rp 1.250.000</td>
                        </tr>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4">28 April 2026</td>
                            <td class="px-6 py-4 font-mono text-xs">#TX-99811</td>
                            <td class="px-6 py-4">Nasi Bento Karakter (30)</td>
                            <td class="px-6 py-4 text-right font-bold text-slate-900">Rp 750.000</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="laporan pendapatan.js"></script>
</body>
</html>
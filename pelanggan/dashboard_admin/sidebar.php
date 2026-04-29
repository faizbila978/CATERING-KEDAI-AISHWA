<!-- sidebar.php -->
<aside class="w-64 bg-orange-700 text-white flex flex-col shadow-xl flex-shrink-0 h-screen sticky top-0">
    <div class="p-6 text-center border-b border-orange-600">
        <div class="bg-white rounded-full w-20 h-20 mx-auto mb-3 flex items-center justify-center overflow-hidden border-2 border-white shadow-lg">
            <img src="logo catering.jpeg" alt="Logo" class="w-full h-full object-cover">
        </div>
        <h1 class="text-lg font-bold uppercase tracking-wider leading-tight">Catering Kedai Aishwa</h1>
        <p class="text-[10px] text-orange-200 tracking-widest uppercase font-semibold">Karangampel</p>
    </div>

    <nav class="flex-1 mt-6 px-4 space-y-2">
        <a href="manajemen_produk.php" class="flex items-center p-3 rounded-lg hover:bg-orange-600 transition group">
            <i class="fas fa-th-large mr-3 w-5 text-orange-300 group-hover:text-white"></i>
            <span class="font-medium">Manajemen Produk</span>
        </a>
        <a href="manajemen_pesanan.php" class="flex items-center p-3 rounded-lg hover:bg-orange-600 transition group">
            <i class="fas fa-shopping-cart mr-3 w-5 text-orange-300 group-hover:text-white"></i>
            <span>Manajemen Pesanan</span>
        </a>
        <a href="laporan_pendapatan.php" class="flex items-center p-3 rounded-lg hover:bg-orange-600 transition group">
            <i class="fas fa-chart-line mr-3 w-5 text-orange-300 group-hover:text-white"></i>
            <span>Laporan Pendapatan</span>
        </a>
    </nav>

    <div class="p-4 border-t border-orange-600">
        <a href="../logout.php" class="w-full flex items-center justify-center p-2 rounded-md bg-red-500 hover:bg-red-600 transition shadow-lg text-sm font-bold text-white">
    <i class="fas fa-sign-out-alt mr-2"></i> Keluar
</a>
    </div>
</aside>
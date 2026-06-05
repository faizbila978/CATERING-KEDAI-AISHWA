<div class="bg-orange-700 text-white p-4 flex justify-between items-center md:hidden w-full sticky top-0 z-50 shadow-md">
    <div class="flex items-center gap-2">
        <h1 class="text-sm font-bold uppercase tracking-wider">Kedai Aishwa</h1>
        <span class="text-[9px] bg-orange-600 px-1.5 py-0.5 rounded font-bold uppercase tracking-widest text-orange-200">Admin</span>
    </div>
    <button id="mobile-sidebar-toggle" class="text-white hover:text-orange-200 focus:outline-none p-1.5 rounded-lg bg-orange-600/50 transition">
        <i class="fas fa-bars text-xl" id="toggle-icon"></i>
    </button>
</div>

<div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 z-40 hidden md:hidden transition-opacity duration-300 opacity-0"></div>

<aside id="main-sidebar" class="fixed md:sticky top-0 left-0 w-64 bg-orange-700 text-white flex flex-col shadow-xl h-screen z-40 
    -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out flex-shrink-0">
    
    <div class="p-6 text-center border-b border-orange-600 flex-shrink-0 relative">
        <h1 class="text-lg font-bold uppercase tracking-wider leading-tight">Catering Kedai Aishwa</h1>
        <p class="text-[10px] text-orange-200 tracking-widest uppercase font-semibold">Karangampel</p>
    </div>

    <nav class="flex-1 mt-6 px-4 space-y-2 overflow-y-auto custom-scrollbar">
        <a href="manajemen_produk.php" class="flex items-center p-3 rounded-lg hover:bg-orange-600 transition group">
            <i class="fas fa-th-large mr-3 w-5 text-orange-300 group-hover:text-white"></i>
            <span class="font-medium">Manajemen Produk</span>
        </a>
        
        <a href="kategori.php" class="flex items-center p-3 rounded-lg hover:bg-orange-600 transition group">
            <i class="fas fa-tags mr-3 w-5 text-orange-300 group-hover:text-white"></i>
            <span class="font-medium">Manajemen Kategori</span>
        </a>

        <a href="admin_testimoni.php" class="flex items-center p-3 rounded-lg hover:bg-orange-600 transition group">
            <i class="fas fa-star-half-stroke mr-3 w-5 text-orange-300 group-hover:text-white"></i>
            <span class="font-medium">Testimoni Pelanggan</span>
        </a>
        
        <a href="admin_komplain.php" class="flex items-center p-3 rounded-lg hover:bg-orange-600 transition group">
            <i class="fas fa-triangle-exclamation mr-3 w-5 text-orange-300 group-hover:text-white"></i>
            <span class="font-medium">Komplain Pelanggan</span>
        </a>
        
        <a href="manajemen_pesanan.php" class="flex items-center p-3 rounded-lg hover:bg-orange-600 transition group">
            <i class="fas fa-shopping-cart mr-3 w-5 text-orange-300 group-hover:text-white"></i>
            <span class="font-medium">Manajemen Pesanan</span>
        </a>
        
        <a href="laporan_pendapatan.php" class="flex items-center p-3 rounded-lg hover:bg-orange-600 transition group">
            <i class="fas fa-chart-line mr-3 w-5 text-orange-300 group-hover:text-white"></i>
            <span class="font-medium">Laporan Pendapatan</span>
        </a>

        <a href="pengaturan_pesanan.php" class="flex items-center p-3 rounded-lg hover:bg-orange-600 transition group border-t border-orange-500 mt-2 pt-4">
            <i class="fas fa-sliders mr-3 w-5 text-orange-300 group-hover:text-white"></i>
            <span class="font-medium">Pengaturan Batasan</span>
        </a>
        
        <a href="pengaturan_web.php" class="flex items-center p-3 rounded-lg hover:bg-orange-600 transition group">
            <i class="fas fa-cog mr-3 w-5 text-orange-300 group-hover:text-white"></i>
            <span class="font-medium">Pengaturan Halaman Utama</span>
        </a>
    </nav>

    <div class="p-4 border-t border-orange-600 flex-shrink-0">
        <a href="logout.php" class="w-full flex items-center justify-center p-2 rounded-md bg-red-500 hover:bg-red-600 transition shadow-lg text-sm font-bold text-white">
            <i class="fas fa-sign-out-alt mr-2"></i> Keluar
        </a>
    </div>
</aside>

<style>
    .custom-scrollbar::-webkit-scrollbar {
        width: 5px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.2);
        border-radius: 10px;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover {
        background: rgba(255, 255, 255, 0.4);
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const toggleBtn = document.getElementById('mobile-sidebar-toggle');
        const sidebar = document.getElementById('main-sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const toggleIcon = document.getElementById('toggle-icon');

        function toggleSidebar() {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            
            if (isHidden) {
                // Buka Sidebar
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
                setTimeout(() => backdrop.classList.add('opacity-100'), 10);
                toggleIcon.classList.remove('fa-bars');
                toggleIcon.classList.add('fa-times');
            } else {
                // Tutup Sidebar
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.remove('opacity-100');
                setTimeout(() => backdrop.classList.add('hidden'), 300);
                toggleIcon.classList.remove('fa-times');
                toggleIcon.classList.add('fa-bars');
            }
        }

        // Event listener saat tombol hamburger atau backdrop diklik
        toggleBtn.addEventListener('click', toggleSidebar);
        backdrop.addEventListener('click', toggleSidebar);
    });
</script>
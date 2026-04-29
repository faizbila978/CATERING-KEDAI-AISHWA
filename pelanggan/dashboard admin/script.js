function showWelcome() {
    const mainArea = document.getElementById('mainContentArea');
    const content = document.getElementById('dynamicContent');
    
    document.getElementById('pageTitle').innerText = 'Beranda Admin';
    
    // Kembalikan background catering saat ke Beranda
    mainArea.classList.add('welcome-background');

    content.innerHTML = `
        <div class="text-center py-20 fade-in glass-card p-12 rounded-3xl shadow-xl border border-white/20">
            <h2 class="text-4xl font-extrabold text-gray-800 drop-shadow-md">Halo, Admin!</h2>
            <p class="text-gray-700 mt-4 text-lg font-medium">Silakan pilih menu di samping kiri untuk mengelola data katering.</p>
        </div>
    `;
}

function changeContent(page) {
    const mainArea = document.getElementById('mainContentArea');
    const content = document.getElementById('dynamicContent');
    
    document.getElementById('pageTitle').innerText = 'Manajemen ' + page;
    
    // Hapus background catering saat masuk ke menu manajemen agar tidak mengganggu data
    mainArea.classList.remove('welcome-background');
    
    if (page === 'Produk') {
        content.innerHTML = `
            <div class="fade-in w-full">
                <div class="flex justify-end mb-6">
                    <button class="bg-green-600 hover:bg-green-700 text-white px-5 py-2.5 rounded-lg flex items-center shadow-lg transition transform active:scale-95">
                        <i class="fas fa-plus mr-2"></i> Tambah Produk
                    </button>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <div class="bg-white rounded-2xl shadow-md overflow-hidden border">
                        <img src="image_ed127f.jpg" class="w-full h-48 object-cover">
                        <div class="p-5">
                            <h3 class="text-lg font-bold">Nasi Kotak Ayam Geprek</h3>
                            <p class="text-orange-600 font-bold">Rp 20.000</p>
                            <div class="flex gap-2 mt-4">
                                <button class="flex-1 bg-yellow-400 py-2 rounded-lg text-xs font-bold">EDIT</button>
                                <button class="flex-1 bg-red-50 text-red-600 py-2 rounded-lg text-xs font-bold">HAPUS</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;
    } else {
        content.innerHTML = `
            <div class="text-center py-20 fade-in glass-card p-12 rounded-3xl shadow-xl border border-white/20">
                <h2 class="text-2xl font-bold text-gray-800 italic">Halaman ${page} sedang dalam pengembangan...</h2>
            </div>
        `;
    }
}

function logout() {
    if (confirm("Apakah Anda yakin ingin keluar?")) {
        // Karena tidak ada login, logout akan me-refresh halaman ke kondisi awal
        location.reload(); 
    }
}
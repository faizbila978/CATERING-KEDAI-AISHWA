<<<<<<< HEAD
// 1. DATA (Menggunakan 'let' agar bisa diedit dan dihapus)
let menuUtama = [
=======
// DATA MENU UTAMA
const menuUtama = [
>>>>>>> 3a1efd830ba3fb9abca181edf61f95eb35d888c8
    { id: "MNU-101", n: "Paket Ayam Goreng", p: "20.000", d: "Nasi, ayam (dada/paha), sambel, lalapan, aqua gelas.", img: "WhatsApp Image 2026-02-21 at 21.48.58.jpeg" },
    { id: "MNU-102", n: "Paket Ayam Bakar", p: "20.000", d: "Nasi, ayam (dada/paha), sambel, lalapan, aqua gelas.", img: "WhatsApp Image 2026-02-21 at 22.03.08.jpeg" },
    { id: "MNU-103", n: "Paket Ayam Geprek", p: "20.000", d: "Nasi, ayam (dada/paha), mie goreng jawa, sambel, lalapan, aqua gelas.", img: "WhatsApp Image 2026-02-21 at 22.07.53.jpeg" },
    { id: "MNU-104", n: "Rice Bowl", p: "10.000", d: "Nasi, ayam krispi, sayur/kentang, sambal (geprek, matah, tomat).", img: "WhatsApp Image 2026-02-16 at 22.03.25.jpeg" },
    { id: "MNU-105", n: "Tumpeng Nusantara", p: "200.000", d: "Nasi kuning, telor balado, mie goreng jawa, orek, urap, sambal.", img: "WhatsApp_Image_2026-02-11_at_08.53.28-removebg-preview.png" },
    { id: "MNU-106", n: "Tumpeng Nusantara Spesial", p: "250.000", d: "Nasi kuning, ayam bakar/goreng, orek, mie goreng jawa, urap, sambal.", img: "tumpeng.png" },
    { id: "MNU-107", n: "Tumpeng Nusantara Premium", p: "300.000", d: "Nasi kuning, ayam bakar/goreng, telor balado, mie goreng jawa, orek, urap, sambal.", img: "Gemini_Generated_Image_vgy9v9vgy9v9vgy9.png" }
];

<<<<<<< HEAD
let tambahan = [
=======
// DATA MENU TAMBAHAN
const tambahan = [
>>>>>>> 3a1efd830ba3fb9abca181edf61f95eb35d888c8
    { id: "TMB-201", n: "Tempe Tahu", p: "2.000", img: "Tempe tahu goreng.jpeg" }, 
    { id: "TMB-202", n: "Puding", p: "2.000", img: "puding.jpeg" },
    { id: "TMB-203", n: "Putu Ayu", p: "2.000", img: "Resep Putu Ayu Enak Dan Lembut Ala Ncc oleh BunnaBintang.jpeg" }, 
    { id: "TMB-204", n: "Bolu Kukus", p: "2.000", img: "WhatsApp Image 2026-03-06 at 13.55.39.jpeg" },
    { id: "TMB-205", n: "Pisang", p: "2.000", img: "Banana Bread Muffin Tops – Oh She Glows.jpeg" }, 
    { id: "TMB-206", n: "Jeruk", p: "2.000", img: "Buah buahan.jpeg" },
    { id: "TMB-207", n: "Rengginang", p: "1.000", img: "download (8).jpeg" }, 
    { id: "TMB-208", n: "Bolu Jadul", p: "40.000", img: "WhatsApp Image 2026-02-21 at 21.47.17.jpeg" },
    { id: "TMB-209", n: "Bolu Meses Keju", p: "50.000", img: "WhatsApp Image 2026-02-21 at 21.45.35.jpeg" }, 
    { id: "TMB-210", n: "Brownies Keju", p: "35.000", img: "WhatsApp Image 2026-02-21 at 21.43.57.jpeg" }
];

// 2. FUNGSI UNTUK MERENDER KARTU PRODUK
function renderProducts() {
    const mainContainer = document.getElementById('container-menu-utama');
    const extraContainer = document.getElementById('container-menu-tambahan');

    if (mainContainer) {
        mainContainer.innerHTML = menuUtama.map(item => createCard(item, 'orange')).join('');
    }
    if (extraContainer) {
        extraContainer.innerHTML = tambahan.map(item => createCard(item, 'blue')).join('');
    }
}

// 3. FUNGSI TEMPLATE KARTU
function createCard(item, themeColor) {
    const isMain = themeColor === 'orange';
    const textClass = isMain ? 'text-orange-600' : 'text-blue-600';
    const desc = item.d ? `<p class="text-sm text-gray-500 line-clamp-3 mb-5 flex-1 leading-relaxed italic border-l-2 border-orange-100 pl-2">${item.d}</p>` : '<div class="mb-5"></div>';

    return `
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 flex flex-col hover:shadow-xl transition-all duration-300 group">
            <div class="relative h-48 overflow-hidden rounded-t-2xl">
                <div class="absolute top-2 left-2 bg-black/70 text-white text-[10px] px-2 py-1 rounded font-mono z-10">${item.id}</div>
                <img src="${item.img}" alt="${item.n}" onerror="this.src='https://via.placeholder.com/500x500?text=Foto+Produk'" class="w-full h-full object-cover">
            </div>
            <div class="p-5 flex flex-col flex-1">
                <h4 class="font-bold text-gray-800 text-lg leading-tight mb-1 truncate">${item.n}</h4>
                <div class="${textClass} font-bold text-xl mb-2">Rp ${item.p}</div>
                ${desc}
                <div class="grid grid-cols-2 gap-2 mt-auto">
                    <button onclick="editProduk('${item.id}')" 
                        class="bg-blue-100 text-blue-600 py-2 rounded-xl text-xs font-bold border border-blue-200 hover:bg-blue-600 hover:text-white transition-all">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                    <button onclick="hapusProduk('${item.id}')" 
                        class="bg-red-100 text-red-600 py-2 rounded-xl text-xs font-bold border border-red-200 hover:bg-red-600 hover:text-white transition-all">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    `;
}

// 4. FUNGSI EDIT PRODUK
window.editProduk = function(id) {
    let produk = menuUtama.find(item => item.id === id) || tambahan.find(item => item.id === id);
    
    if (produk) {
        const namaBaru = prompt("Edit Nama Produk:", produk.n);
        const hargaBaru = prompt("Edit Harga (Contoh: 25.000):", produk.p);
        
        if (namaBaru !== null && hargaBaru !== null) {
            produk.n = namaBaru;
            produk.p = hargaBaru;

            if (produk.d) {
                const descBaru = prompt("Edit Deskripsi:", produk.d);
                if (descBaru !== null) produk.d = descBaru;
            }

            renderProducts();
            alert("Produk berhasil diperbarui!");
        }
    }
};

// 5. FUNGSI HAPUS PRODUK
window.hapusProduk = function(id) {
    if (confirm("Apakah Anda yakin ingin menghapus produk ini?")) {
        menuUtama = menuUtama.filter(item => item.id !== id);
        tambahan = tambahan.filter(item => item.id !== id);
        renderProducts();
    }
};

// 6. JALANKAN SAAT HALAMAN DIMUAT
document.addEventListener('DOMContentLoaded', renderProducts);
<<<<<<< HEAD
// DATA MENU UTAMA
const menuUtama = [
    { id: "MNU-101", n: "Paket Ayam Goreng", p: "20.000,00", d: "Nasi, ayam (dada/paha), sambel, lalapan, aqua gelas.", img: "WhatsApp Image 2026-02-21 at 21.48.58.jpeg" },
    { id: "MNU-102", n: "Paket Ayam Bakar", p: "20.000,00", d: "Nasi, ayam (dada/paha), sambel, lalapan, aqua gelas.", img: "WhatsApp Image 2026-02-21 at 22.03.08.jpeg" },
    { id: "MNU-103", n: "Paket Ayam Geprek", p: "20.000,00", d: "Nasi, ayam (dada/paha), mie goreng jawa, sambel, lalapan, aqua gelas.", img: "WhatsApp Image 2026-02-21 at 22.07.53.jpeg" },
    { id: "MNU-104", n: "Rice Bowl", p: "10.000,00", d: "Nasi, ayam krispi, sayur/kentang, sambal (geprek, matah, tomat).", img: "WhatsApp Image 2026-02-16 at 22.03.25.jpeg" },
    { id: "MNU-105", n: "Tumpeng Nusantara", p: "200.000,00", d: "Nasi kuning, telor balado, mie goreng jawa, orek, urap, sambal.", img: "Gemini_Generated_Image_sfm989sfm989sfm9.png" },
    { id: "MNU-106", n: "Tumpeng Nusantara Spesial", p: "250.000,00", d: "Nasi kuning, ayam bakar/goreng, orek, mie goreng jawa, urap, sambal.", img: "Gemini_Generated_Image_erm4mderm4mderm4.png" },
    { id: "MNU-107", n: "Tumpeng Nusantara Premium", p: "300.000,00", d: "Nasi kuning, ayam bakar/goreng, telor balado, mie goreng jawa, orek, urap, sambal.", img: "Gemini_Generated_Image_vgy9v9vgy9v9vgy9.png" }
];

// DATA MENU TAMBAHAN
const tambahan = [
    { id: "TMB-201", n: "Tempe Tahu", p: "2.000,00", img: "Tempe tahu goreng.jpeg" }, 
    { id: "TMB-202", n: "Puding", p: "2.000,00", img: "puding.jpeg" },
    { id: "TMB-203", n: "Putu Ayu", p: "2.000,00", img: "Resep Putu Ayu Enak Dan Lembut Ala Ncc oleh BunnaBintang.jpeg" }, 
    { id: "TMB-204", n: "Bolu Kukus", p: "2.000,00", img: "WhatsApp Image 2026-03-06 at 13.55.39.jpeg" },
    { id: "TMB-205", n: "Pisang", p: "2.000,00", img: "Banana Bread Muffin Tops – Oh She Glows.jpeg" }, 
    { id: "TMB-206", n: "Jeruk", p: "2.000,00", img: "Buah buahan.jpeg" },
    { id: "TMB-207", n: "Rengginang", p: "1.000,00", img: "download (8).jpeg" }, 
    { id: "TMB-208", n: "Bolu Jadul", p: "40.000,00", img: "WhatsApp Image 2026-02-21 at 21.47.17.jpeg" },
    { id: "TMB-209", n: "Bolu Meses Keju", p: "50.000,00", img: "WhatsApp Image 2026-02-21 at 21.45.35.jpeg" }, 
    { id: "TMB-210", n: "Brownies Keju", p: "35.000,00", img: "WhatsApp Image 2026-02-21 at 21.43.57.jpeg" }
];

// FUNGSI UNTUK MERENDER KARTU PRODUK
function renderProducts() {
    const mainContainer = document.getElementById('container-menu-utama');
    const extraContainer = document.getElementById('container-menu-tambahan');

    // Render Menu Utama
    mainContainer.innerHTML = menuUtama.map(item => createCard(item, 'orange')).join('');

    // Render Menu Tambahan
    extraContainer.innerHTML = tambahan.map(item => createCard(item, 'blue')).join('');
}

// FUNGSI TEMPLATE KARTU
function createCard(item, themeColor) {
    const isMain = themeColor === 'orange';
    const textClass = isMain ? 'text-orange-600' : 'text-blue-600';
    const btnClass = isMain ? 'bg-red-50 text-red-600 border-red-100 hover:bg-red-100' : 'bg-blue-50 text-blue-600 border-blue-100 hover:bg-blue-100';
    const desc = item.d ? `<p class="text-sm text-gray-500 line-clamp-3 mb-5 flex-1 leading-relaxed italic border-l-2 border-orange-100 pl-2">${item.d}</p>` : '<div class="mb-5"></div>';

    return `
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 flex flex-col hover:shadow-xl transition-all duration-300 group">
            <div class="img-container relative">
                <div class="absolute top-2 left-2 bg-black/70 text-white text-[10px] px-2 py-1 rounded font-mono z-10">${item.id}</div>
                <img src="${item.img}" alt="${item.n}" onerror="this.src='https://via.placeholder.com/500x500?text=Foto+Produk'" class="w-full h-full object-cover product-card-img">
            </div>
            <div class="p-5 flex flex-col flex-1">
                <h4 class="font-bold text-gray-800 text-lg leading-tight mb-1 truncate">${item.n}</h4>
                <div class="${textClass} font-bold text-xl mb-2">Rp ${item.p}</div>
                ${desc}
                <div class="grid grid-cols-2 gap-2 mt-auto">
                    <button class="bg-yellow-400 hover:bg-yellow-500 text-white py-2 rounded-xl text-xs font-bold transition flex items-center justify-center">
                        <i class="fas fa-edit mr-1"></i> Edit
                    </button>
                    <button class="${btnClass} py-2 rounded-xl text-xs font-bold transition flex items-center justify-center border">
                        <i class="fas fa-trash-alt mr-1"></i> Hapus
                    </button>
                </div>
            </div>
        </div>
    `;
}

// Jalankan fungsi saat halaman dimuat
document.addEventListener('DOMContentLoaded', renderProducts);
=======
// Data Awal sesuai Gambar 3 (Excel)
let dataPesanan = [
    { dtl: "DTL-401", ord: "ORD-301", mnu: "MNU-101", hrgS: "20.000,00", jmlM: 100, tmbId: "TMB-208", hrgT: "50.000,00", jmlT: 10, tgl: "4/2/2026", wkt: "12:45", total: "2.500.000,00", status: "Selesai" },
    { dtl: "DTL-402", ord: "ORD-302", mnu: "MNU-104", hrgS: "10.000,00", jmlM: 120, tmbId: "-", hrgT: "-", jmlT: "-", tgl: "8/2/2026", wkt: "15:00", total: "1.200.000,00", status: "Selesai" },
    { dtl: "DTL-403", ord: "ORD-303", mnu: "MNU-101", hrgS: "20.000,00", jmlM: 75, tmbId: "-", hrgT: "-", jmlT: "-", tgl: "14/2/2026", wkt: "15:00", total: "1.500.000,00", status: "Selesai" },
    { dtl: "DTL-405", ord: "ORD-305", mnu: "MNU-102", hrgS: "20.000,00", jmlM: 90, tmbId: "TMB-201", hrgT: "2.000,00", jmlT: 90, tgl: "24/2/2026", wkt: "18:00", total: "2.000.000,00", status: "Dikirim" },
    { dtl: "DTL-404", ord: "ORD-304", mnu: "MNU-103", hrgS: "20.000,00", jmlM: 125, tmbId: "TMB-203", hrgT: "2.000,00", jmlT: 125, tgl: "25/2/2026", wkt: "13:17", total: "3.000.000,00", status: "Diproses" }
];

function checkLogin() {
    document.getElementById('loginSection').classList.add('hidden');
    document.getElementById('dashboardSection').classList.remove('hidden');
    changeContent('Pesanan');
    // Mulai otomatisasi setiap 15 detik
    setInterval(tambahPesananOtomatis, 15000);
}

function logout() { location.reload(); }

function changeContent(menu) {
    const container = document.getElementById('dynamicContent');
    document.getElementById('pageTitle').innerText = "Manajemen " + menu;
    if (menu === 'Pesanan') { renderTable(); } 
    else { container.innerHTML = `<div class="bg-white p-10 rounded-xl text-center text-gray-400">Halaman ${menu} dalam pengembangan...</div>`; }
}

function renderTable() {
    const container = document.getElementById('dynamicContent');
    let html = `<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-x-auto">
        <table class="order-table">
            <thead><tr>
                <th>Detail ID</th><th>Pesanan ID</th><th>Menu ID</th><th>Harga S</th><th>Jumlah M</th>
                <th>Tambahan ID</th><th>Harga T</th><th>Jumlah T</th><th>Tanggal A</th><th>Waktu A</th><th>Total</th><th>Status</th>
            </tr></thead><tbody>`;

    [...dataPesanan].reverse().forEach((item) => {
        let rowClass = item.status === "Diproses" ? "row-red" : (item.status === "Dikirim" ? "row-green" : "");
        let badgeClass = item.status === "Diproses" ? "badge-red" : (item.status === "Dikirim" ? "badge-green" : "badge-gray");

        html += `<tr class="${rowClass}">
            <td class="font-bold">${item.dtl}</td><td>${item.ord}</td><td>${item.mnu}</td><td>${item.hrgS}</td><td>${item.jmlM}</td>
            <td>${item.tmbId}</td><td>${item.hrgT}</td><td>${item.jmlT}</td><td>${item.tgl}</td><td>${item.wkt}</td>
            <td class="font-bold">Rp ${item.total}</td>
            <td><span onclick="updateStatus('${item.dtl}')" class="status-badge ${badgeClass}">${item.status} ${item.status !== 'Selesai' ? '➜' : ''}</span></td>
        </tr>`;
    });
    container.innerHTML = html + `</tbody></table></div>`;
}

function updateStatus(dtlId) {
    const item = dataPesanan.find(p => p.dtl === dtlId);
    if (item.status === "Diproses") { if(confirm("Kirim Pesanan?")) item.status = "Dikirim"; }
    else if (item.status === "Dikirim") { if(confirm("Selesaikan Pesanan?")) item.status = "Selesai"; }
    renderTable();
}

function tambahPesananOtomatis() {
    const idRand = Math.floor(Math.random() * 900) + 500;
    const listTambahan = [
        { id: "TMB-201", harga: "2.000,00" },
        { id: "TMB-203", harga: "2.000,00" },
        { id: "TMB-208", harga: "50.000,00" }
    ];
    const acak = listTambahan[Math.floor(Math.random() * listTambahan.length)];
    const jml = Math.floor(Math.random() * 100) + 20;

    dataPesanan.push({
        dtl: `DTL-${idRand}`, ord: `ORD-${idRand - 100}`, mnu: "MNU-102", hrgS: "20.000,00", jmlM: jml,
        tmbId: acak.id, hrgT: acak.harga, jmlT: jml, tgl: "06/03/2026", wkt: "15:45", total: "1.500.000,00", status: "Diproses"
    });

    const notif = document.getElementById('notifArea');
    notif.classList.remove('hidden');
    setTimeout(() => notif.classList.add('hidden'), 3000);
    if (document.getElementById('pageTitle').innerText.includes("Pesanan")) renderTable();
}
>>>>>>> 7c3ff9341555292636faf4a902d2a34fea3a743c

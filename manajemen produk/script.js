// Data Awal Pesanan
let dataPesanan = [
    { dtl: "DTL-401", ord: "ORD-301", mnu: "MNU-101", hrgS: "20.000,00", jmlM: 100, tmbId: "TMB-208", hrgT: "50.000,00", jmlT: 10, tgl: "4/2/2026", wkt: "12:45", total: "2.500.000,00", status: "Selesai" },
    { dtl: "DTL-402", ord: "ORD-302", mnu: "MNU-104", hrgS: "10.000,00", jmlM: 120, tmbId: "-", hrgT: "-", jmlT: "-", tgl: "8/2/2026", wkt: "15:00", total: "1.200.000,00", status: "Selesai" },
    { dtl: "DTL-403", ord: "ORD-303", mnu: "MNU-101", hrgS: "20.000,00", jmlM: 75, tmbId: "-", hrgT: "-", jmlT: "-", tgl: "14/2/2026", wkt: "15:00", total: "1.500.000,00", status: "Selesai" },
    { dtl: "DTL-405", ord: "ORD-305", mnu: "MNU-102", hrgS: "20.000,00", jmlM: 90, tmbId: "TMB-201", hrgT: "2.000,00", jmlT: 90, tgl: "24/2/2026", wkt: "18:00", total: "2.000.000,00", status: "Dikirim" },
    { dtl: "DTL-404", ord: "ORD-304", mnu: "MNU-103", hrgS: "20.000,00", jmlM: 125, tmbId: "TMB-203", hrgT: "2.000,00", jmlT: 125, tgl: "25/2/2026", wkt: "13:17", total: "3.000.000,00", status: "Diproses" }
];

// Inisialisasi Aplikasi
window.onload = function() {
    changeContent('Pesanan');
    // Jalankan penambahan pesanan otomatis setiap 15 detik
    setInterval(tambahPesananOtomatis, 15000);
};

// Fungsi pindah halaman/menu
function changeContent(menu) {
    const container = document.getElementById('dynamicContent');
    document.getElementById('pageTitle').innerText = "Manajemen " + menu;
    
    // Reset status aktif di sidebar
    document.querySelectorAll('.nav-link').forEach(btn => btn.classList.remove('active'));
    
    if(menu === 'Pesanan') {
        document.getElementById('btn-pesanan').classList.add('active');
        renderTable();
    } else {
        const btnId = 'btn-' + menu.toLowerCase();
        if(document.getElementById(btnId)) document.getElementById(btnId).classList.add('active');
        container.innerHTML = `<div class="bg-white p-10 rounded-xl text-center text-gray-400">Halaman ${menu} dalam pengembangan...</div>`;
    }
}

// Fungsi merender tabel pesanan
function renderTable() {
    const container = document.getElementById('dynamicContent');
    let html = `
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-x-auto">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Detail ID</th><th>Pesanan ID</th><th>Menu ID</th><th>Harga S</th><th>Jumlah M</th>
                        <th>Tambahan ID</th><th>Harga T</th><th>Jumlah T</th><th>Tanggal A</th><th>Waktu A</th><th>Total</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>`;

    // Render data terbaru di paling atas
    [...dataPesanan].reverse().forEach((item) => {
        let rowClass = item.status === "Diproses" ? "row-red" : (item.status === "Dikirim" ? "row-green" : "");
        let badgeClass = item.status === "Diproses" ? "badge-red" : (item.status === "Dikirim" ? "badge-green" : "badge-gray");

        html += `
            <tr class="${rowClass}">
                <td class="font-bold">${item.dtl}</td>
                <td>${item.ord}</td>
                <td>${item.mnu}</td>
                <td>${item.hrgS}</td>
                <td>${item.jmlM}</td>
                <td>${item.tmbId}</td>
                <td>${item.hrgT}</td>
                <td>${item.jmlT}</td>
                <td>${item.tgl}</td>
                <td>${item.wkt}</td>
                <td class="font-bold">Rp ${item.total}</td>
                <td>
                    <span onclick="updateStatus('${item.dtl}')" class="status-badge ${badgeClass}">
                        ${item.status} ${item.status !== 'Selesai' ? '➜' : ''}
                    </span>
                </td>
            </tr>`;
    });
    
    container.innerHTML = html + `</tbody></table></div>`;
}

// Fungsi mengubah status pesanan
function updateStatus(dtlId) {
    const item = dataPesanan.find(p => p.dtl === dtlId);
    if (!item) return;

    if (item.status === "Diproses") { 
        if(confirm("Kirim Pesanan ini?")) item.status = "Dikirim"; 
    } else if (item.status === "Dikirim") { 
        if(confirm("Selesaikan Pesanan ini?")) item.status = "Selesai"; 
    }
    renderTable();
}

// Simulasi pesanan baru masuk
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
        dtl: `DTL-${idRand}`, 
        ord: `ORD-${idRand - 100}`, 
        mnu: "MNU-102", 
        hrgS: "20.000,00", 
        jmlM: jml,
        tmbId: acak.id, 
        hrgT: acak.harga, 
        jmlT: jml, 
        tgl: "11/03/2026", 
        wkt: "12:45", 
        total: "1.500.000,00", 
        status: "Diproses"
    });

    // Munculkan notifikasi kedip
    const notif = document.getElementById('notifArea');
    if(notif) {
        notif.classList.remove('hidden');
        setTimeout(() => notif.classList.add('hidden'), 4000);
    }

    // Refresh tabel jika user sedang di halaman Pesanan
    if (document.getElementById('pageTitle').innerText.includes("Pesanan")) {
        renderTable();
    }
}
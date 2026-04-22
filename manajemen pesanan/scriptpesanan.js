/**
 * DATA PESANAN
 */
let dataPesanan = [
    { dtl: "DTL-401", ord: "ORD-301", mnu: "MNU-101", hrgS: "20.000,00", jmlM: 100, tmbId: "TMB-208", hrgT: "5.000,00", jmlT: 10, tgl: "19/4/2026", wkt: "12:45", total: "2.050.000,00", status: "Selesai" },
    { dtl: "DTL-403", ord: "ORD-303", mnu: "MNU-101", hrgS: "20.000,00", jmlM: 75, tmbId: "TMB-001", hrgT: "2.000,00", jmlT: 75, tgl: "19/4/2026", wkt: "15:00", total: "1.650.000,00", status: "Diproses" },
    { dtl: "DTL-405", ord: "ORD-305", mnu: "MNU-102", hrgS: "20.000,00", jmlM: 90, tmbId: "TMB-102", hrgT: "3.000,00", jmlT: 10, tgl: "20/4/2026", wkt: "18:00", total: "1.830.000,00", status: "Dikirim" },
    { dtl: "DTL-404", ord: "ORD-304", mnu: "MNU-103", hrgS: "20.000,00", jmlM: 125, tmbId: "TMB-203", hrgT: "1.500,00", jmlT: 100, tgl: "25/4/2026", wkt: "13:17", total: "2.650.000,00", status: "Diproses" }
];

let searchTerm = "";
let filterStatus = "Semua";
let filterBulan = "Semua";
let filterTahun = "Semua";

window.onload = () => {
    changeContent('Pesanan');
    setInterval(tambahPesananOtomatis, 30000);
};

function changeContent(menu) {
    document.getElementById('pageTitle').innerText = "Manajemen " + menu;
    document.querySelectorAll('.nav-link').forEach(btn => btn.classList.remove('active'));
    
    const container = document.getElementById('dynamicContent');

    if(menu === 'Pesanan') {
        document.getElementById('btn-pesanan').classList.add('active');
        // Reset container agar bingkai tabel dibuat ulang saat pindah menu
        container.innerHTML = ""; 
        renderTable();
    } else {
        const btnId = 'btn-' + menu.toLowerCase();
        if(document.getElementById(btnId)) document.getElementById(btnId).classList.add('active');
        container.innerHTML = `
            <div class="bg-white p-10 rounded-xl text-center text-gray-400">
                <i class="fas fa-tools mb-4 text-4xl block"></i>
                Halaman ${menu} dalam pengembangan...
            </div>`;
    }
}

/**
 * FILTER HANDLERS
 */
function handleSearch(e) { 
    searchTerm = e.target.value.toLowerCase(); 
    renderTableBodyOnly(); 
}
function handleFilterStatus(e) { filterStatus = e.target.value; renderTableBodyOnly(); }
function handleFilterBulan(e) { filterBulan = e.target.value; renderTableBodyOnly(); }
function handleFilterTahun(e) { filterTahun = e.target.value; renderTableBodyOnly(); }

/**
 * MERENDER BINGKAI TABEL & FILTER (Hanya sekali)
 */
function renderTable() {
    const container = document.getElementById('dynamicContent');
    
    // Jika bingkai sudah ada, jangan timpa (agar input tidak reset)
    if (document.getElementById('orderTableBody')) return;

    let html = `
        <div class="flex flex-col lg:flex-row gap-3 mb-4">
            <div class="relative flex-1">
                <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                <input type="text" id="searchInput" oninput="handleSearch(event)" placeholder="Cari ID Detail atau Pesanan..." 
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-orange-500 text-xs">
            </div>
            
            <div class="flex flex-wrap gap-2">
                <select onchange="handleFilterStatus(event)" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 text-xs">
                    <option value="Semua">Semua Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Diproses">Diproses</option>
                    <option value="Dikirim">Dikirim</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Dibatalkan">Dibatalkan</option>
                </select>

                <select onchange="handleFilterBulan(event)" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 text-xs">
                    <option value="Semua">Semua Bulan</option>
                    ${["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"].map((m, i) => 
                        `<option value="${i+1}">${m}</option>`
                    ).join('')}
                </select>

                <select onchange="handleFilterTahun(event)" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-orange-500 text-xs">
                    <option value="Semua">Semua Tahun</option>
                    <option value="2025">2025</option>
                    <option value="2026">2026</option>
                </select>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-x-auto">
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Detail ID</th><th>Pesanan ID</th><th>Menu ID</th><th>Harga S</th><th>Jumlah M</th>
                        <th>Tambahan ID</th><th>Harga T</th><th>Jumlah T</th><th>Tanggal A</th><th>Waktu A</th><th>Total</th><th>Status</th><th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="orderTableBody"></tbody>
            </table>
        </div>`;
    
    container.innerHTML = html;
    renderTableBodyOnly(); // Isi datanya
}

/**
 * MERENDER ISI DATA SAJA
 */
function renderTableBodyOnly() {
    const tbody = document.getElementById('orderTableBody');
    if (!tbody) return;

    const now = new Date();
    now.setHours(0,0,0,0);

    const getVisualStatus = (item) => {
        const [d, m, y] = item.tgl.split('/');
        const tglP = new Date(y, m - 1, d);
        const selisih = (tglP - now) / (1000 * 60 * 60 * 24);
        if (item.status === "Diproses" && selisih > 1) return "Pending";
        return item.status;
    };

    const statusPriority = { "Pending": 1, "Diproses": 2, "Dikirim": 3, "Selesai": 4, "Dibatalkan": 5 };

    let filtered = dataPesanan.filter(item => {
        const [d, m, y] = item.tgl.split('/');
        const vStatus = getVisualStatus(item);
        const matchSearch = item.ord.toLowerCase().includes(searchTerm) || item.dtl.toLowerCase().includes(searchTerm);
        const matchStatus = filterStatus === "Semua" || vStatus === filterStatus;
        const matchBulan = filterBulan === "Semua" || m == filterBulan;
        const matchTahun = filterTahun === "Semua" || y == filterTahun;
        return matchSearch && matchStatus && matchBulan && matchTahun;
    });

    filtered.sort((a, b) => {
        const pA = statusPriority[getVisualStatus(a)];
        const pB = statusPriority[getVisualStatus(b)];
        if (pA !== pB) return pA - pB;
        return new Date(b.tgl.split('/').reverse().join('-')) - new Date(a.tgl.split('/').reverse().join('-'));
    });

    let html = "";
    filtered.forEach(item => {
        const [d, m, y] = item.tgl.split('/');
        const tglP = new Date(y, m - 1, d);
        const selisih = (tglP - now) / (1000 * 60 * 60 * 24);

        let vStatus = getVisualStatus(item);
        let badgeClass = "";
        let rowClass = "";

        if (vStatus === "Pending") {
            badgeClass = "badge-orange";
            rowClass = "row-pending";
        } else {
            badgeClass = item.status === "Diproses" ? "badge-red" : (item.status === "Dikirim" ? "badge-blue" : (item.status === "Selesai" ? "badge-green" : "badge-gray"));
            if (item.status === "Diproses" && selisih <= 1) rowClass = "row-urgent";
            if (item.status === "Dikirim") rowClass = "row-shipping";
        }

        html += `
            <tr class="${rowClass}">
                <td class="font-bold">${item.dtl}</td>
                <td>${item.ord}</td>
                <td>${item.mnu}</td>
                <td>${item.hrgS}</td>
                <td>${item.jmlM}</td>
                <td><span class="bg-blue-50 text-blue-600 px-2 py-1 rounded border border-blue-100 font-bold">${item.tmbId}</span></td>
                <td>${item.hrgT}</td>
                <td>${item.jmlT}</td>
                <td>${item.tgl}</td>
                <td>${item.wkt}</td>
                <td class="font-bold">Rp ${item.total}</td>
                <td><span onclick="updateStatus('${item.dtl}')" class="status-badge ${badgeClass}">${vStatus}</span></td>
                <td>
                    ${(vStatus === 'Pending' || vStatus === 'Diproses') ? 
                    `<button onclick="batalkanPesanan('${item.dtl}')" class="text-red-500 hover:scale-125 transition"><i class="fas fa-trash"></i></button>` : '-'}
                </td>
            </tr>`;
    });

    tbody.innerHTML = html;
}

/**
 * LOGIKA STATUS & BATAL (Sama seperti sebelumnya)
 */
function updateStatus(dtlId) {
    const item = dataPesanan.find(p => p.dtl === dtlId);
    if (!item || item.status === "Selesai" || item.status === "Dibatalkan") return;

    const now = new Date();
    const todayStr = `${now.getDate()}/${now.getMonth() + 1}/${now.getFullYear()}`;
    const [d, m, y] = item.tgl.split('/');
    const tglP = new Date(y, m - 1, d);
    now.setHours(0,0,0,0);
    const selisih = (tglP - now) / (1000 * 60 * 60 * 24);

    if (item.status === "Diproses" && selisih > 1) {
        if(confirm("Pesanan Pending. Proses sekarang? (Tanggal diubah ke hari ini)")) {
            item.tgl = todayStr;
            renderTableBodyOnly();
        }
    } else if (item.status === "Diproses") {
        if(confirm("Ubah ke DIKIRIM?")) { item.status = "Dikirim"; renderTableBodyOnly(); }
    } else if (item.status === "Dikirim") {
        if(confirm("Tandai SELESAI?")) { item.status = "Selesai"; renderTableBodyOnly(); }
    }
}

function batalkanPesanan(dtlId) {
    const item = dataPesanan.find(p => p.dtl === dtlId);
    if(confirm("Batalkan pesanan ini?")) {
        item.status = "Dibatalkan";
        renderTableBodyOnly();
    }
}

function tambahPesananOtomatis() {
    const id = Math.floor(Math.random() * 900) + 100;
    const now = new Date();
    const fut = new Date(now); fut.setDate(now.getDate() + 3);
    const dateStr = `${fut.getDate()}/${fut.getMonth() + 1}/${fut.getFullYear()}`;

    dataPesanan.push({
        dtl: `DTL-${id}`, ord: `ORD-${id}`, mnu: "MNU-102", hrgS: "25.000,00", jmlM: 20,
        tmbId: "TMB-111", hrgT: "1.000,00", jmlT: 10, tgl: dateStr, wkt: "10:00", total: "510.000,00", status: "Diproses"
    });

    const notif = document.getElementById('notifArea');
    if(notif) {
        notif.classList.remove('hidden');
        setTimeout(() => notif.classList.add('hidden'), 5000);
    }
    renderTableBodyOnly();
}
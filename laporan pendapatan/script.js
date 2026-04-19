/** --- DATABASE & STATE --- **/
let dataPembayaran = [
    { dtl: "PAY-501", ord: "ORD-301", mnu: "Cash", tgl: "2/1/2026", total: 2500000, status: "Lunas" },
    { dtl: "PAY-502", ord: "ORD-302", mnu: "Transfer", tgl: "5/2/2026", total: 4200000, status: "Lunas" },
    { dtl: "PAY-503", ord: "ORD-303", mnu: "Transfer", tgl: "12/3/2026", total: 1500000, status: "Lunas" },
    { dtl: "PAY-504", ord: "ORD-304", mnu: "Cash", tgl: "21/3/2026", total: 3000000, status: "DP" },
    { dtl: "PAY-405", ord: "ORD-305", mnu: "Dana", tgl: "10/4/2026", total: 2000000, status: "Menunggu" }
];

let currentMenu = "Laporan"; // Menu default
let filterBulan = "Semua";
let filterTahun = "2026";

const namaBulanFull = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
const namaBulanShort = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];

/** --- FUNGSI FORMATTING --- **/
const formatRP = (v) => "Rp " + v.toLocaleString('id-ID') + ",00";
const formatJuta = (v) => (v / 1000000).toFixed(1) + " Jt";

/** --- LOGIKA CORE & NAVIGASI --- **/
window.onload = () => {
    initFilter();
    changeMenu('Laporan'); // Inisialisasi tampilan pertama
    setInterval(prosesPembayaranOtomatis, 30000); // Update data otomatis tiap 30 detik
};

function initFilter() {
    const select = document.getElementById('filterBulan');
    if (select) {
        namaBulanFull.forEach((bulan, i) => {
            let opt = document.createElement('option');
            opt.value = i + 1;
            opt.innerHTML = bulan;
            select.appendChild(opt);
        });
    }
}

function changeMenu(menu) {
    currentMenu = menu;
    const header = document.getElementById('pageHeader');
    const container = document.getElementById('dynamicContent');
    const filterSec = document.getElementById('filterSection');

    // Update Judul Header
    header.innerText = (menu === 'Laporan') ? "Laporan Pendapatan" : "Manajemen " + menu;

    // Tampilkan filter hanya di menu Laporan
    if (filterSec) filterSec.style.display = (menu === 'Laporan') ? 'flex' : 'none';

    // Update Active State di Sidebar
    document.querySelectorAll('.nav-link').forEach(btn => {
        btn.classList.remove('active');
        if (btn.innerText.includes(menu)) btn.classList.add('active');
    });

    renderContent(container);
}

function handleFilterChange() {
    filterBulan = document.getElementById('filterBulan').value;
    filterTahun = document.getElementById('filterTahun').value;
    const container = document.getElementById('dynamicContent');
    renderContent(container);
}

/** --- RENDERER KONTEN --- **/
function renderContent(container) {
    if (currentMenu === 'Produk') {
        container.innerHTML = `
            <div class="bg-white p-10 rounded-[2.5rem] text-center border border-dashed border-slate-200">
                <i class="fas fa-box-open text-5xl text-slate-200 mb-4"></i>
                <p class="text-slate-500 font-medium">Modul Manajemen Produk sedang dikembangkan.</p>
            </div>`;
    } else if (currentMenu === 'Pelanggan') {
        container.innerHTML = `
            <div class="bg-white p-10 rounded-[2.5rem] text-center border border-dashed border-slate-200">
                <i class="fas fa-users text-5xl text-slate-200 mb-4"></i>
                <p class="text-slate-500 font-medium">Modul Manajemen Pelanggan sedang dikembangkan.</p>
            </div>`;
    } else {
        renderDashboard(container);
    }
}

/** --- LOGIKA LAPORAN (DASHBOARD) --- **/
function renderDashboard(container) {
    const dataFiltered = dataPembayaran.filter(item => {
        const parts = item.tgl.split('/');
        const m = parseInt(parts[1]);
        const y = parts[2];
        return (filterBulan === "Semua" || m === parseInt(filterBulan)) && (filterTahun === "Semua" || y === filterTahun);
    });

    const totalOmzet = dataFiltered.reduce((acc, curr) => acc + curr.total, 0);
    const jumlahTransaksi = dataFiltered.length;
    const totalPorsi = jumlahTransaksi * 50; 
    const jumlahDP = dataFiltered.filter(item => item.status === "DP").length;

    container.innerHTML = `
        <div class="fade-in">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div class="bg-white p-6 rounded-3xl shadow-sm border-l-4 border-blue-500">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Total Omzet</p>
                    <p class="text-xl font-black text-gray-800">${formatRP(totalOmzet)}</p>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border-l-4 border-red-500">
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">Bahan Baku (45%)</p>
                    <p class="text-xl font-black text-red-600">-${formatRP(totalOmzet * 0.45)}</p>
                </div>
                <div class="bg-white p-6 rounded-3xl shadow-sm border-l-4 border-green-500 bg-green-50">
                    <p class="text-[10px] font-bold text-green-700 uppercase tracking-widest">Laba Bersih</p>
                    <p class="text-xl font-black text-green-700">${formatRP(totalOmzet * 0.55 * 0.9)}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                <div class="bg-white p-4 rounded-2xl border border-gray-100 flex items-center shadow-sm">
                    <div class="w-10 h-10 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center mr-3"><i class="fas fa-box text-xs"></i></div>
                    <div><p class="text-[10px] text-gray-400 font-bold uppercase">Total Pesanan</p><p class="text-sm font-bold">${totalPorsi} Porsi</p></div>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-gray-100 flex items-center shadow-sm">
                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mr-3"><i class="fas fa-receipt text-xs"></i></div>
                    <div><p class="text-[10px] text-gray-400 font-bold uppercase">Jumlah Transaksi</p><p class="text-sm font-bold">${jumlahTransaksi} Transaksi</p></div>
                </div>
                <div class="bg-white p-4 rounded-2xl border border-blue-200 flex items-center shadow-sm">
                    <div class="w-10 h-10 bg-blue-600 text-white rounded-full flex items-center justify-center mr-3"><i class="fas fa-hand-holding-usd text-xs"></i></div>
                    <div><p class="text-[10px] text-gray-400 font-bold uppercase">Jumlah DP</p><p class="text-sm font-bold text-blue-600">${jumlahDP} DP</p></div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                    <h3 class="font-bold mb-8 text-[10px] text-gray-400 uppercase tracking-widest"><i class="fas fa-chart-bar mr-2 text-orange-500"></i> Tren Pendapatan</h3>
                    <div class="chart-container">
                        ${generateChartBars()}
                    </div>
                </div>

                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 overflow-x-auto">
                    <h3 class="font-bold mb-5 text-[10px] text-gray-400 uppercase tracking-widest"><i class="fas fa-list mr-2 text-orange-500"></i> Rincian Data (${dataFiltered.length})</h3>
                    <table class="transaction-table">
                        <thead><tr><th>ID</th><th>Metode</th><th>Tanggal</th><th>Total</th><th>Status</th></tr></thead>
                        <tbody>${renderTableRows(dataFiltered)}</tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
}

function generateChartBars() {
    let bulanan = Array(12).fill(0);
    dataPembayaran.forEach(item => {
        const parts = item.tgl.split('/');
        const m = parseInt(parts[1]) - 1;
        bulanan[m] += item.total;
    });

    const maxVal = Math.max(...bulanan) || 1;
    return bulanan.map((val, i) => {
        const height = (val / maxVal) * 100;
        return `
            <div class="chart-column">
                <span class="chart-value">${val > 0 ? formatJuta(val) : ''}</span>
                <div class="chart-bar" style="height: ${Math.max(height, 5)}%; opacity: ${val > 0 ? 1 : 0.2}"></div>
                <span class="chart-label">${namaBulanShort[i]}</span>
            </div>
        `;
    }).join('');
}

function renderTableRows(data) {
    if (data.length === 0) return `<tr><td colspan="5" class="text-center py-10 text-gray-300">Data Tidak Ditemukan</td></tr>`;
    return data.map(item => {
        let s = item.status === "Lunas" ? "text-lunas" : (item.status === "DP" ? "text-dp" : "text-menunggu");
        let b = item.status === "Lunas" ? "badge-lunas" : (item.status === "DP" ? "badge-dp" : "badge-menunggu");
        return `
            <tr>
                <td class="font-bold ${s}">${item.dtl}</td>
                <td class="${s}">${item.mnu}</td>
                <td class="${s}">${item.tgl}</td>
                <td class="font-bold ${s}">${formatRP(item.total)}</td>
                <td><button onclick="updateStatus('${item.dtl}')" class="status-badge ${b}">${item.status}</button></td>
            </tr>`;
    }).join('');
}

/** --- FUNGSI AKSI --- **/
function updateStatus(dtl) {
    const idx = dataPembayaran.findIndex(i => i.dtl === dtl);
    if (idx !== -1) {
        const s = dataPembayaran[idx].status;
        if (s === "Menunggu") dataPembayaran[idx].status = "DP";
        else if (s === "DP") dataPembayaran[idx].status = "Lunas";
        
        const container = document.getElementById('dynamicContent');
        renderContent(container);
    }
}

function prosesPembayaranOtomatis() {
    const idBaru = "PAY-" + Math.floor(Math.random() * 900 + 100);
    const dataBaru = {
        dtl: idBaru, 
        ord: "ORD-" + Math.floor(Math.random() * 900 + 100),
        mnu: ["QRIS", "Cash", "Transfer"][Math.floor(Math.random() * 3)],
        tgl: new Date().toLocaleDateString('id-ID'),
        total: 50000 * (Math.floor(Math.random() * 15) + 5),
        status: "Menunggu"
    };
    dataPembayaran.unshift(dataBaru);
    
    // Hanya re-render jika sedang di menu Laporan
    if (currentMenu === 'Laporan') {
        const container = document.getElementById('dynamicContent');
        renderContent(container);
    }
}

function handleLogout() {
    if(confirm("Yakin ingin keluar dari sistem Admin?")) {
        window.location.reload();
    }
}
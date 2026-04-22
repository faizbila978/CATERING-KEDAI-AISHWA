let dataPembayaran = [
    { id: "INV-9001", metode: "Transfer", tgl: "22/4/2026", total: 2500000, status: "Lunas" },
    { id: "INV-9002", metode: "Cash", tgl: "22/4/2026", total: 1500000, status: "DP" },
    { id: "INV-9003", metode: "QRIS", tgl: "22/4/2026", total: 800000, status: "Menunggu" }
];

const namaBulan = ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"];

window.onload = () => {
    const selBulan = document.getElementById('filterBulan');
    selBulan.innerHTML = `<option value="Semua">Semua Bulan</option>` + 
        namaBulan.map((b, i) => `<option value="${i+1}">${b}</option>`).join('');
    renderDashboard();
    setInterval(tambahTransaksiOtomatis, 30000); // Pesanan masuk setiap 30 detik
};

function renderDashboard() {
    const bulan = document.getElementById('filterBulan').value;
    const tahun = document.getElementById('filterTahun').value;
    const container = document.getElementById('dynamicContent');
    
    const filtered = dataPembayaran.filter(d => {
        const p = d.tgl.split('/');
        return (bulan === "Semua" || p[1] == bulan) && (tahun === "Semua" || p[2] == tahun);
    });

    const omzet = filtered.reduce((a, b) => a + b.total, 0);
    const bahan = omzet * 0.45;
    const laba = (omzet - bahan) * 0.95;
    const porsi = filtered.length * 50;
    const nota = filtered.length;
    const dp = filtered.filter(x => x.status === "DP").length;

    container.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="metric-card border-blue-600"><p class="card-label">Total Omzet</p><p class="card-value text-blue-600">Rp ${omzet.toLocaleString('id-ID')}</p></div>
            <div class="metric-card border-red-500"><p class="card-label">Bahan Baku (45%)</p><p class="card-value text-red-600">Rp ${bahan.toLocaleString('id-ID')}</p></div>
            <div class="metric-card border-green-600 bg-green-50"><p class="card-label">Laba Bersih</p><p class="card-value text-green-700">Rp ${laba.toLocaleString('id-ID')}</p></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="metric-card border-indigo-500"><p class="card-label">Total Porsi</p><p class="card-value">${porsi.toLocaleString()} Porsi</p></div>
            <div class="metric-card border-orange-500"><p class="card-label">Total Transaksi</p><p class="card-value">${nota} Nota</p></div>
            <div class="metric-card border-cyan-500"><p class="card-label">Status DP</p><p class="card-value text-cyan-600">${dp} Nota</p></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase mb-4">Tren Pendapatan ${tahun}</h3>
                <div class="chart-container">${generateBars(tahun)}</div>
            </div>
            <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
                <h3 class="text-[10px] font-bold text-slate-400 uppercase mb-4">Daftar Transaksi</h3>
                <div class="table-scroll">
                    <table class="w-full text-[11px] text-left">
                        <thead class="sticky top-0 bg-slate-100 shadow-sm">
                            <tr class="text-slate-500"><th class="p-3">ID</th><th class="p-3">METODE</th><th class="p-3">TGL</th><th class="p-3">TOTAL</th><th class="p-3">STATUS</th></tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">${renderRows(filtered)}</tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
}

function gantiStatus(id) {
    const idx = dataPembayaran.findIndex(d => d.id === id);
    const s = dataPembayaran[idx].status;
    if (s === "Lunas") return; // Kunci status lunas
    dataPembayaran[idx].status = s === "Menunggu" ? "DP" : (s === "DP" ? "Lunas" : "Menunggu");
    renderDashboard();
}

function renderRows(data) {
    return data.map(x => `
        <tr class="hover:bg-slate-50 transition border-b border-slate-50">
            <td class="p-3 font-bold text-orange-600">${x.id}</td>
            <td class="p-3 text-slate-500">${x.metode}</td>
            <td class="p-3 text-slate-400">${x.tgl}</td>
            <td class="p-3 font-bold text-slate-700">Rp ${x.total.toLocaleString()}</td>
            <td class="p-3"><span onclick="gantiStatus('${x.id}')" class="badge badge-${x.status.toLowerCase()}">${x.status}</span></td>
        </tr>`).join('');
}

function tambahTransaksiOtomatis() {
    const mtds = ["Transfer", "Cash", "QRIS", "Dana", "ShopeePay"];
    const id = "INV-" + Math.floor(Math.random() * 9000 + 1000);
    const tot = Math.floor(Math.random() * 10 + 1) * 200000;
    const tgl = new Date().toLocaleDateString('id-ID');

    dataPembayaran.unshift({ id: id, metode: mtds[Math.floor(Math.random()*mtds.length)], tgl: tgl, total: tot, status: "Menunggu" });

    const container = document.getElementById('notification-container');
    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.innerHTML = `<i class="fas fa-shopping-bag text-green-500 mt-1"></i> <div><b>Pesanan Masuk!</b><br>${id} - Rp ${tot.toLocaleString()}</div>`;
    container.appendChild(toast);
    setTimeout(() => toast.remove(), 5000);

    renderDashboard();
}

function generateBars(thn) {
    let html = '';
    const maxScale = 15000000;
    for(let i=1; i<=12; i++) {
        const sum = dataPembayaran.filter(d => d.tgl.split('/')[1] == i && d.tgl.split('/')[2] == thn).reduce((a,b)=>a+b.total,0);
        const h = sum > 0 ? (sum/maxScale * 100) : 5;
        html += `<div class="chart-bar-group"><div class="chart-bar" style="height: ${h}%"></div><span class="chart-month-label">${namaBulan[i-1]}</span></div>`;
    }
    return html;
}
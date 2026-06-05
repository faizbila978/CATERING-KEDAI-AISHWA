<?php
session_start();
include '../koneksi.php';

// Proteksi Halaman: Jika belum login atau bukan admin, tendang ke login.php
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location:login.php?status=wajib_login");
    exit();
}

// PROSES 1: Update Status Alur Transaksi Pesanan (Sequential)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status_next') {
    $id_pesanan_update = mysqli_real_escape_string($conn, $_POST['pesanan_id']);
    $status_baru = mysqli_real_escape_string($conn, $_POST['status_baru']);
    
    $update_query = "UPDATE pesanan SET status_pesanan = '$status_baru' WHERE pesanan_id = '$id_pesanan_update'";
    mysqli_query($conn, $update_query);
    
    echo "<script>window.location='manajemen_pesanan.php';</script>";
    exit();
}

// PROSES 2: Konfirmasi Pelunasan Pembayaran oleh Admin
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'konfirmasi_lunas') {
    $id_pesanan_pay = mysqli_real_escape_string($conn, $_POST['pesanan_id']);
    
    $pay_query = "UPDATE pembayaran SET status_pembayaran = 'Selesai' WHERE pesanan_id = '$id_pesanan_pay'";
    mysqli_query($conn, $pay_query);
    
    echo "<script>alert('Pembayaran Sisa/Pelunasan Berhasil Dikonfirmasi Lunas!'); window.location='manajemen_pesanan.php';</script>";
    exit();
}

// ================= FUNGSI FILTER & PENCARIAN =================
$where_clauses = [];

if (!empty($_GET['search_id'])) {
    $search_id = mysqli_real_escape_string($conn, str_replace('#ORD-', '', $_GET['search_id']));
    $where_clauses[] = "p.pesanan_id = '$search_id'";
}

if (!empty($_GET['bulan'])) {
    $bulan = mysqli_real_escape_string($conn, $_GET['bulan']);
    $where_clauses[] = "MONTH(p.tanggal_pesan) = '$bulan'";
}

if (!empty($_GET['tahun'])) {
    $tahun = mysqli_real_escape_string($conn, $_GET['tahun']);
    $where_clauses[] = "YEAR(p.tanggal_pesan) = '$tahun'";
}

if (!empty($_GET['status_filter'])) {
    $st_filter = mysqli_real_escape_string($conn, $_GET['status_filter']);
    $where_clauses[] = "p.status_pesanan = '$st_filter'";
}

$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where_clauses);
}
// =============================================================

$query = "
    SELECT 
        p.pesanan_id,
        p.user_id,
        u.nama_lengkap,
        p.tanggal_pesan,
        p.tanggal_acara,
        p.waktu_acara,
        p.alamat,
        p.no_handphone,
        p.catatan,
        p.total_pesan,
        p.status_pesanan,
        pb.pembayaran_id,
        pb.metode_pembayaran,
        pb.total_pembayaran,
        pb.status_pembayaran,
        pb.status_dp,
        pb.jumlah_dp
    FROM pesanan p
    LEFT JOIN users u ON p.user_id = u.user_id
    LEFT JOIN pembayaran pb ON p.pesanan_id = pb.pesanan_id
    $where_sql
    ORDER BY p.pesanan_id DESC
";

$result = mysqli_query($conn, $query);
$pesanan_list = [];
while ($row = mysqli_fetch_assoc($result)) {
    $pesanan_list[] = $row;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesanan - Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        .status-badge { 
            padding: 4px 10px; 
            border-radius: 20px; 
            font-size: 11px; 
            font-weight: bold; 
            display: inline-block;
            white-space: nowrap;
        }
        .payment-belum { background-color: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; }
        .payment-dp { background-color: #FEF3C7; color: #92400E; border: 1px solid #FCD34D; }
        .payment-konfirmasi { background-color: #E0F2FE; color: #0369A1; border: 1px solid #7DD3FC; animation: pulse 2s infinite; }
        .payment-sudah { background-color: #DCFCE7; color: #166534; border: 1px solid #86EFAC; }
        
        .badge-dp { background-color: #FCD34D; color: #78350F; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; display: inline-block; white-space: nowrap; }
        .badge-full { background-color: #10B981; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; display: inline-block; white-space: nowrap; }
        
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 50; }
        .modal.active { display: flex; align-items: center; justify-content: center; }
        .modal-content { background: white; border-radius: 12px; max-height: 90vh; overflow-y: auto; box-shadow: 0 10px 40px rgba(0,0,0,0.3); width: 92%; max-width: 650px; }

        /* Custom scrollbar tipis untuk container tabel */
        .custom-table-scroll::-webkit-scrollbar { height: 6px; }
        .custom-table-scroll::-webkit-scrollbar-track { background: #f1f5f9; }
        .custom-table-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    </style>
</head>
<body class="bg-gray-100 min-h-screen">

    <!-- Diubah ke flex-col untuk HP, md:flex-row untuk Desktop agar sidebar menyatu dengan pas -->
    <div class="flex flex-col md:flex-row min-h-screen">
        <?php include('sidebar.php'); ?>

        <main class="flex-1 flex flex-col h-screen overflow-y-auto">
            <!-- Header Responsif -->
            <header class="bg-white shadow-sm border-b border-gray-200 p-4 md:p-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-shopping-cart text-pink-600 mr-3"></i> Manajemen Pesanan
                    </h2>
                    <p class="text-gray-500 text-xs md:text-sm mt-0.5">Kelola pesanan dan konfirmasi pembayaran</p>
                </div>
                <div class="bg-gradient-to-r from-pink-500 to-pink-600 text-white px-4 py-1.5 rounded-full text-xs md:text-sm font-bold shadow-md w-full sm:w-auto text-center">
                    <i class="fas fa-bell mr-2"></i> <?php echo count($pesanan_list); ?> PESANAN
                </div>
            </header>

            <!-- Container Konten Utama -->
            <div class="flex-1 p-4 md:p-6 space-y-6">
                
                <!-- Area Filter Form Responsif -->
                <div class="bg-white p-4 md:p-5 rounded-xl shadow-sm border border-gray-200">
                    <form method="GET" action="manajemen_pesanan.php" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end">
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 mb-1 tracking-wider">CARI ID PESANAN</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400 text-xs"></i>
                                </div>
                                <input type="text" name="search_id" value="<?php echo isset($_GET['search_id']) ? htmlspecialchars($_GET['search_id']) : ''; ?>" placeholder="Contoh: 068" class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition bg-gray-50/50">
                            </div>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 mb-1 tracking-wider">STATUS</label>
                            <select name="status_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 bg-white">
                                <option value="">Semua Status</option>
                                <option value="Menunggu Bayar">Menunggu</option>
                                <option value="Menunggu Verifikasi">Menunggu Verifikasi</option>
                                <option value="Pending">Pending</option>
                                <option value="Diproses">Proses</option>
                                <option value="Dikirim">Kirim</option>
                                <option value="Selesai">Selesai</option>
                                <option value="Dibatalkan">Batal</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-bold text-gray-500 mb-1 tracking-wider">BULAN</label>
                            <select name="bulan" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 bg-white">
                                <option value="">Semua Bulan</option>
                                <?php
                                $bulan_array = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
                                foreach ($bulan_array as $num => $name) {
                                    echo "<option value='$num'>$name</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-xs font-semibold transition shadow-sm w-full h-[34px] tracking-wide uppercase">
                                <i class="fas fa-filter mr-1.5"></i> Tampilkan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Pembungkus Tabel Utama dengan Fitur Horizontal Scroll Aman -->
                <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
                    <div class="overflow-x-auto custom-table-scroll w-full">
                        <table class="w-full text-left border-collapse min-w-[900px]">
                            <thead class="bg-gradient-to-r from-pink-50 to-pink-100 border-b border-pink-200 text-pink-900 text-xs font-bold uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3.5 text-center">ID Pesanan</th>
                                    <th class="px-5 py-3.5">Pelanggan</th>
                                    <th class="px-4 py-3.5 text-center">Tgl Acara</th>
                                    <th class="px-5 py-3.5">Total Kontrak</th>
                                    <th class="px-4 py-3.5 text-center">Tipe Skema</th>
                                    <th class="px-4 py-3.5 text-center">Status Bayar</th>
                                    <th class="px-5 py-3.5 text-center">Status Alur Katering</th>
                                    <th class="px-4 py-3.5 text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 text-xs text-gray-600">
                                <?php if (!empty($pesanan_list)): ?>
                                    <?php foreach ($pesanan_list as $pesanan): 
                                        $detail_query = mysqli_query($conn, "
                                            SELECT dp.*, m.nama_menu, m.gambar, t.nama_tambahan
                                            FROM detail_pesanan dp
                                            LEFT JOIN menu m ON dp.menu_id = m.menu_id
                                            LEFT JOIN tambahan t ON dp.tambahan_id = t.tambahan_id
                                            WHERE dp.pesanan_id = {$pesanan['pesanan_id']}
                                        ");
                                        $details = [];
                                        while ($d = mysqli_fetch_assoc($detail_query)) { $details[] = $d; }
                                        
                                        $payment_status = $pesanan['status_pembayaran'] ?? 'Belum Bayar';
                                        $status_dp = $pesanan['status_dp'] ?? 'Belum Bayar';
                                        
                                        $tipe_pembayaran = 'Full (100%)';
                                        $badge_skema_class = 'badge-full';
                                        if (strpos(strtolower($pesanan['metode_pembayaran'] ?? ''), '-dp') !== false || $status_dp == 'Selesai' || !empty($pesanan['jumlah_dp'])) {
                                            $tipe_pembayaran = 'DP (50%)';
                                            $badge_skema_class = 'badge-dp';
                                        }

                                        if ($payment_status === 'Selesai') {
                                            $ui_status_bayar = "<span class='status-badge payment-sudah'><i class='fas fa-check-circle mr-1'></i> Lunas Total</span>";
                                        } elseif ($payment_status === 'Menunggu Konfirmasi') {
                                            $ui_status_bayar = "<span class='status-badge payment-konfirmasi'><i class='fas fa-clock mr-1'></i> Verifikasi Sisa</span>";
                                        } elseif ($status_dp === 'Selesai') {
                                            $ui_status_bayar = "<span class='status-badge payment-dp'><i class='fas fa-cookie mr-1'></i> Baru Bayar DP</span>";
                                        } else {
                                            $ui_status_bayar = "<span class='status-badge payment-belum'>Belum Bayar</span>";
                                        }

                                        $status_db = $pesanan['status_pesanan'];
                                        $next_status = ''; $btn_text = ''; $btn_class = ''; $confirm_msg = ''; $clickable = true;

                                        if ($status_db === 'Dibatalkan') {
                                            $btn_text = 'Dibatalkan'; $btn_class = 'bg-red-100 text-red-700 border-red-300 cursor-not-allowed'; $clickable = false;
                                        } elseif (empty($status_db) || $status_db === 'Menunggu Bayar' || $status_db === 'Pending' || $status_db === 'Menunggu Verifikasi') {
                                            $btn_text = 'Konfirmasi Pesanan'; $btn_class = 'bg-blue-100 text-blue-800 border-blue-300 hover:bg-blue-200';
                                            $next_status = 'Diproses'; $confirm_msg = 'Konfirmasi bahwa pesanan telah masuk & ubah status ke DIPROSES (Masuk Dapur)?';
                                        } elseif ($status_db === 'Diproses') {
                                            $btn_text = 'Diproses (Kirim)'; $btn_class = 'bg-blue-100 text-blue-800 border-blue-300 hover:bg-blue-200';
                                            $next_status = 'Dikirim'; $confirm_msg = 'Katering siap diantar? Ubah status ke DIKIRIM?';
                                        } elseif ($status_db === 'Dikirim') {
                                            $btn_text = 'Dikirim (Selesai)'; $btn_class = 'bg-orange-100 text-orange-800 border-orange-300 hover:bg-orange-200';
                                            $next_status = 'Selesai'; $confirm_msg = 'Acara selesai & Katering sukses diterima? Ubah status ke SELESAI?';
                                        } elseif ($status_db === 'Selesai') {
                                            $btn_text = 'Selesai'; $btn_class = 'bg-green-100 text-green-800 border-green-300 cursor-not-allowed'; $clickable = false;
                                        }
                                    ?>
                                    <tr class="hover:bg-pink-50/50 bg-white transition">
                                        <td class="px-4 py-4 font-mono font-bold text-gray-700 text-center">#ORD-<?php echo str_pad($pesanan['pesanan_id'], 3, '0', STR_PAD_LEFT); ?></td>
                                        <td class="px-5 py-4">
                                            <div class="font-semibold text-gray-900"><?php echo htmlspecialchars($pesanan['nama_lengkap'] ?? 'N/A'); ?></div>
                                            <div class="text-[11px] text-gray-500 mt-0.5"><?php echo htmlspecialchars($pesanan['no_handphone'] ?? ''); ?></div>
                                        </td>
                                        <td class="px-4 py-4 text-center font-medium"><?php echo date('d/m/Y', strtotime($pesanan['tanggal_acara'])); ?></td>
                                        <td class="px-5 py-4 font-bold text-pink-600">Rp <?php echo number_format($pesanan['total_pesan'], 0, ',', '.'); ?></td>
                                        <td class="px-4 py-4 text-center"><span class="<?php echo $badge_skema_class; ?>"><?php echo $tipe_pembayaran; ?></span></td>
                                        <td class="px-4 py-4 text-center"><?php echo $ui_status_bayar; ?></td>
                                        <td class="px-5 py-4 text-center">
                                            <?php if ($clickable): ?>
                                                <button onclick="confirmNextStatus(<?php echo $pesanan['pesanan_id']; ?>, '<?php echo $next_status; ?>', '<?php echo $confirm_msg; ?>')" class='text-[11px] font-bold border rounded-lg px-3 py-1.5 w-full shadow-sm transition max-w-[160px] inline-block <?php echo $btn_class; ?>'>
                                                    <?php echo $btn_text; ?>
                                                </button>
                                            <?php else: ?>
                                                <span class='text-[11px] font-bold border rounded-lg px-3 py-1.5 w-full max-w-[160px] inline-block <?php echo $btn_class; ?>'><?php echo $btn_text; ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="px-4 py-4 text-center">
                                            <div class="flex flex-col gap-1.5 max-w-[110px] mx-auto">
                                                <button class="bg-blue-500 hover:bg-blue-600 text-white px-2.5 py-1.5 rounded-md font-medium transition shadow-sm" 
                                                        onclick="showDetailModal(<?php echo htmlspecialchars(json_encode($pesanan)); ?>, <?php echo htmlspecialchars(json_encode($details)); ?>)">
                                                    <i class="fas fa-eye mr-1"></i> Detail
                                                </button>

                                                <?php if ($payment_status === 'Menunggu Konfirmasi'): ?>
                                                    <button onclick="konfirmasiLunas(<?php echo $pesanan['pesanan_id']; ?>)" class="bg-green-600 hover:bg-green-700 text-white px-2.5 py-1.5 rounded-md font-bold transition shadow-md animate-bounce">
                                                        <i class="fas fa-check mr-1"></i> ACC
                                                    </button>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="8" class="px-6 py-12 text-center text-gray-400 italic">Tidak ada data pesanan ditemukan.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Detail Responsif -->
    <div id="detailModal" class="modal px-4">
        <div class="modal-content p-5 md:p-6">
            <div class="flex justify-between items-center mb-5 pb-3 border-b border-gray-200">
                <h3 class="text-lg md:text-xl font-bold text-gray-800" id="modalTitle">Detail Pesanan</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600 text-2xl transition"><i class="fas fa-times"></i></button>
            </div>
            <!-- Grid Modal: 1 kolom di HP, 2 kolom di tablet/PC -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-5">
                <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                    <h4 class="font-bold text-xs uppercase text-gray-500 mb-2 tracking-wider"><i class="fas fa-user mr-1.5 text-pink-500"></i>Informasi Pelanggan</h4>
                    <div class="space-y-1.5 text-xs text-gray-600">
                        <p>Nama: <span class="font-semibold text-gray-900" id="modalNama"></span></p>
                        <p>No. HP: <span class="font-semibold text-gray-900" id="modalNoHp"></span></p>
                        <p>Alamat: <span class="font-medium text-gray-800 block mt-0.5" id="modalAlamat"></span></p>
                        <p class="pt-1 border-t border-dashed border-gray-200 mt-2">Catatan: <span class="font-bold text-amber-600 block" id="modalCatatan"></span></p>
                    </div>
                </div>
                <div class="bg-gray-50 p-3.5 rounded-xl border border-gray-100">
                    <h4 class="font-bold text-xs uppercase text-gray-500 mb-2 tracking-wider"><i class="fas fa-wallet mr-1.5 text-pink-500"></i>Rincian Finansial</h4>
                    <div class="space-y-1.5 text-xs text-gray-600">
                        <p>Total Kontrak: <span class="font-bold text-pink-600 text-sm" id="modalTotal"></span></p>
                        <p>Uang DP: <span class="font-semibold text-orange-600" id="modalDP"></span></p>
                        <div class="pt-1.5 border-t border-gray-200 mt-2 flex justify-between items-center">
                            <span class="font-medium">Sisa Tagihan:</span>
                            <span class="font-bold text-red-600 text-sm" id="modalSisa"></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <h4 class="font-bold text-xs uppercase text-gray-500 mb-2 tracking-wider border-t pt-4"><i class="fas fa-utensils mr-1.5 text-pink-500"></i>Menu Katering Ditargetkan</h4>
            <div id="itemsContainer" class="space-y-2.5 max-h-[180px] overflow-y-auto pr-1"></div>
            
            <div class="flex justify-end gap-2 bg-gray-50 p-4 -mx-5 md:-mx-6 -mb-5 md:-mb-6 mt-6 rounded-b-xl border-t border-gray-100">
                <button onclick="closeDetailModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg text-xs font-semibold transition uppercase tracking-wider">Tutup</button>
            </div>
        </div>
    </div>

    <!-- Script Alur Interaksi Form Jquery/Vanilla -->
    <script>
        function confirmNextStatus(pesananId, nextStatus, pesanKonfirmasi) {
            if (confirm(pesanKonfirmasi)) {
                const form = document.createElement('form');
                form.method = 'POST'; form.action = 'manajemen_pesanan.php';
                const inputs = { 'action': 'update_status_next', 'pesanan_id': pesananId, 'status_baru': nextStatus };
                for (let key in inputs) {
                    const input = document.createElement('input'); input.type = 'hidden'; input.name = key; input.value = inputs[key];
                    form.appendChild(input);
                }
                document.body.appendChild(form); form.submit();
            }
        }

        function konfirmasiLunas(pesananId) {
            if (confirm('Apakah Anda sudah mengecek mutasi masuk bank dan memvalidasi uang pelunasan dari pelanggan ini?')) {
                const form = document.createElement('form');
                form.method = 'POST'; form.action = 'manajemen_pesanan.php';
                const inputs = { 'action': 'konfirmasi_lunas', 'pesanan_id': pesananId };
                for (let key in inputs) {
                    const input = document.createElement('input'); input.type = 'hidden'; input.name = key; input.value = inputs[key];
                    form.appendChild(input);
                }
                document.body.appendChild(form); form.submit();
            }
        }

        function showDetailModal(pesanan, details) {
            document.getElementById('modalTitle').textContent = `Detail Pesanan #ORD-${String(pesanan.pesanan_id).padStart(3, '0')}`;
            document.getElementById('modalNama').textContent = pesanan.nama_lengkap || 'N/A';
            document.getElementById('modalNoHp').textContent = pesanan.no_handphone || '-';
            document.getElementById('modalAlamat').textContent = pesanan.alamat || '-';
            document.getElementById('modalCatatan').textContent = pesanan.catatan || 'Tidak ada catatan';
            
            let total = parseInt(pesanan.total_pesan) || 0;
            let dp = parseInt(pesanan.jumlah_dp) || 0;
            let sisa = total - dp;
            if(pesanan.status_pembayaran === 'Selesai') { sisa = 0; }

            document.getElementById('modalTotal').textContent = 'Rp ' + total.toLocaleString('id-ID');
            document.getElementById('modalDP').textContent = 'Rp ' + dp.toLocaleString('id-ID') + (pesanan.status_dp === 'Selesai' ? ' (Paid)' : ' (Unpaid)');
            document.getElementById('modalSisa').textContent = 'Rp ' + sisa.toLocaleString('id-ID');

            let itemsHTML = '';
            details.forEach(item => {
                const img = item.gambar ? '../img/' + item.gambar : 'https://via.placeholder.com/200x150';
                itemsHTML += `
                    <div class="flex items-center gap-3 bg-gray-50 p-2.5 rounded-lg border border-gray-200">
                        <img src="${img}" class="w-10 h-10 object-cover rounded shadow-sm">
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-gray-800 text-xs truncate">${item.nama_menu}</p>
                            <p class="text-[11px] text-gray-500">${item.jumlah_menu} porsi x Rp ${parseInt(item.harga_satuan).toLocaleString('id-ID')}</p>
                        </div>
                    </div>`;
            });
            document.getElementById('itemsContainer').innerHTML = itemsHTML;
            document.getElementById('detailModal').classList.add('active');
        }

        function closeDetailModal() { document.getElementById('detailModal').classList.remove('active'); }
    </script>
</body>
</html>
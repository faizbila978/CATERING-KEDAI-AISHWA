<?php
session_start();
include 'koneksi.php';

// Pastikan user adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    exit();
}

// Proses Update Status Pesanan Berurutan (Sequential)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_status_next') {
    $id_pesanan_update = mysqli_real_escape_string($conn, $_POST['pesanan_id']);
    $status_baru = mysqli_real_escape_string($conn, $_POST['status_baru']);
    
    $update_query = "UPDATE pesanan SET status_pesanan = '$status_baru' WHERE pesanan_id = '$id_pesanan_update'";
    mysqli_query($conn, $update_query);
    
    // Refresh halaman agar data terbaru tampil
    echo "<script>window.location='manajemen_pesanan.php';</script>";
    exit();
}

// ================= FUNGSI FILTER & PENCARIAN =================
$where_clauses = [];

// Filter Pencarian ID Pesanan
if (!empty($_GET['search_id'])) {
    $search_id = mysqli_real_escape_string($conn, str_replace('#ORD-', '', $_GET['search_id']));
    $where_clauses[] = "p.pesanan_id = '$search_id'";
}

// Filter Bulan
if (!empty($_GET['bulan'])) {
    $bulan = mysqli_real_escape_string($conn, $_GET['bulan']);
    $where_clauses[] = "MONTH(p.tanggal_pesan) = '$bulan'";
}

// Filter Tahun
if (!empty($_GET['tahun'])) {
    $tahun = mysqli_real_escape_string($conn, $_GET['tahun']);
    $where_clauses[] = "YEAR(p.tanggal_pesan) = '$tahun'";
}

// Filter Status (TAMBAHAN AREA HIJAU)
if (!empty($_GET['status_filter'])) {
    $st_filter = mysqli_real_escape_string($conn, $_GET['status_filter']);
    $where_clauses[] = "p.status_pesanan = '$st_filter'";
}

// Gabungkan klausa WHERE jika ada filter yang diisi
$where_sql = "";
if (count($where_clauses) > 0) {
    $where_sql = "WHERE " . implode(" AND ", $where_clauses);
}
// =============================================================

// Ambil semua pesanan dengan detail + filter
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
            padding: 6px 12px; 
            border-radius: 20px; 
            font-size: 12px; 
            font-weight: bold; 
            display: inline-block;
        }
        .payment-belum { background-color: #FEE2E2; color: #991B1B; border: 1px solid #FCA5A5; }
        .payment-sudah { background-color: #DCFCE7; color: #166534; border: 1px solid #86EFAC; }
        .badge-dp { background-color: #FCD34D; color: #78350F; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .badge-full { background-color: #10B981; color: white; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: bold; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 50; }
        .modal.active { display: flex; align-items: center; justify-content: center; }
        .modal-content { background: white; border-radius: 12px; max-height: 90vh; overflow-y: auto; box-shadow: 0 10px 40px rgba(0,0,0,0.3); width: 90%; max-width: 700px; }
        .menu-item-image { width: 100%; height: 150px; object-fit: cover; border-radius: 8px; }
    </style>
</head>
<body class="bg-gray-100">

    <div class="flex">
        <?php include('sidebar.php'); ?>

        <main class="flex-1 flex flex-col h-screen">
            <header class="bg-white shadow-sm border-b border-gray-200 p-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-shopping-cart text-pink-600 mr-3"></i> Manajemen Pesanan
                    </h2>
                    <p class="text-gray-500 text-sm mt-1">Kelola pesanan dan konfirmasi pembayaran</p>
                </div>
                <div class="bg-gradient-to-r from-pink-500 to-pink-600 text-white px-5 py-2 rounded-full text-sm font-bold shadow-lg">
                    <i class="fas fa-bell mr-2"></i> <?php echo count($pesanan_list); ?> PESANAN
                </div>
            </header>

            <div class="flex-1 p-6 overflow-y-auto">

                <div class="bg-white p-5 rounded-lg shadow-sm border border-gray-200 mb-6 flex flex-wrap items-center justify-between gap-4">
                    <form method="GET" action="manajemen_pesanan.php" class="flex flex-wrap items-end gap-4 w-full">
                        
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-xs font-bold text-gray-500 mb-1">CARI ID PESANAN</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-search text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" name="search_id" value="<?php echo isset($_GET['search_id']) ? htmlspecialchars($_GET['search_id']) : ''; ?>" placeholder="Contoh: 068 atau #ORD-068" class="w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 transition">
                            </div>
                        </div>

                        <div class="w-40">
                            <label class="block text-xs font-bold text-gray-500 mb-1">STATUS</label>
                            <select name="status_filter" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 bg-white">
                                <option value="">Semua Status</option>
                                <option value="Menunggu Bayar" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Menunggu Bayar') ? 'selected' : ''; ?>>Menunggu</option>
                                <option value="Pending" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                                <option value="Diproses" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Diproses') ? 'selected' : ''; ?>>Proses</option>
                                <option value="Dikirim" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Dikirim') ? 'selected' : ''; ?>>Kirim</option>
                                <option value="Selesai" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Selesai') ? 'selected' : ''; ?>>Selesai</option>
                                <option value="Dibatalkan" <?php echo (isset($_GET['status_filter']) && $_GET['status_filter'] == 'Dibatalkan') ? 'selected' : ''; ?>>Batal</option>
                            </select>
                        </div>

                        <div class="w-32">
                            <label class="block text-xs font-bold text-gray-500 mb-1">BULAN</label>
                            <select name="bulan" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 bg-white">
                                <option value="">Bulan</option>
                                <?php
                                $bulan_array = ['01' => 'Januari', '02' => 'Februari', '03' => 'Maret', '04' => 'April', '05' => 'Mei', '06' => 'Juni', '07' => 'Juli', '08' => 'Agustus', '09' => 'September', '10' => 'Oktober', '11' => 'November', '12' => 'Desember'];
                                $selected_bulan = $_GET['bulan'] ?? '';
                                foreach ($bulan_array as $num => $name) {
                                    $selected = ($selected_bulan == $num) ? 'selected' : '';
                                    echo "<option value='$num' $selected>$name</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="w-32">
                            <label class="block text-xs font-bold text-gray-500 mb-1">TAHUN</label>
                            <select name="tahun" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:border-pink-500 focus:ring-1 focus:ring-pink-500 bg-white">
                                <option value="">Tahun</option>
                                <?php
                                $tahun_sekarang = date('Y');
                                $selected_tahun = $_GET['tahun'] ?? '';
                                for ($i = $tahun_sekarang; $i >= 2024; $i--) {
                                    $selected = ($selected_tahun == $i) ? 'selected' : '';
                                    echo "<option value='$i' $selected>$i</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                                <i class="fas fa-filter mr-1"></i> Tampilkan
                            </button>
                            <a href="manajemen_pesanan.php" class="bg-gray-100 hover:bg-gray-200 text-gray-700 border border-gray-300 px-5 py-2 rounded-lg text-sm font-medium transition shadow-sm text-center">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>

                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-pink-50 to-pink-100 border-b border-pink-200">
                                <tr>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-pink-900 uppercase tracking-wider">ID Pesanan</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-pink-900 uppercase tracking-wider">Pelanggan</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-pink-900 uppercase tracking-wider">Tgl Pesan</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-pink-900 uppercase tracking-wider">Tgl Acara</th>
                                    <th class="px-5 py-4 text-left text-xs font-bold text-pink-900 uppercase tracking-wider">Total</th>
                                    <th class="px-4 py-4 text-center text-xs font-bold text-pink-900 uppercase tracking-wider">Tipe Bayar</th>
                                    <th class="px-4 py-4 text-center text-xs font-bold text-pink-900 uppercase tracking-wider">Status Bayar</th>
                                    <th class="px-5 py-4 text-center text-xs font-bold text-pink-900 uppercase tracking-wider">Status Pesanan</th>
                                    <th class="px-4 py-4 text-center text-xs font-bold text-pink-900 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
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
                                        $is_paid = ($payment_status === 'Selesai' || $status_dp === 'Selesai');
                                        
                                        $tipe_pembayaran = 'Belum Ada';
                                        $badge_class = '';
                                        if ($status_dp == 'Selesai') { $tipe_pembayaran = 'DP (50%)'; $badge_class = 'badge-dp'; }
                                        elseif ($payment_status == 'Selesai') { $tipe_pembayaran = 'FULL (100%)'; $badge_class = 'badge-full'; }

                                        // ================= LOGIKA STATUS PESANAN (REVISI KONFIRMASI) =================
                                        $status_db = $pesanan['status_pesanan'];
                                        $next_status = '';
                                        $btn_text = '';
                                        $btn_class = '';
                                        $confirm_msg = '';
                                        $clickable = true;

                                        if (!$is_paid) {
                                            if ($status_db === 'Dibatalkan') {
                                                $btn_text = 'Dibatalkan';
                                                $btn_class = 'bg-red-100 text-red-700 border-red-300 cursor-not-allowed opacity-75';
                                                $clickable = false;
                                            } else {
                                                // REVISI: Bisa klik untuk konfirmasi pembayaran manual ke Pending
                                                $btn_text = 'Menunggu Bayar';
                                                $btn_class = 'bg-gray-100 text-gray-700 border-gray-300 hover:bg-yellow-100 hover:text-yellow-700 hover:border-yellow-400';
                                                $next_status = 'Pending';
                                                $confirm_msg = 'Konfirmasi pembayaran telah diterima? Status akan berubah menjadi PENDING.';
                                            }
                                        } else {
                                            // JIKA SUDAH DIBAYAR (Alur Admin Sesuai Instruksi)
                                            if (empty($status_db) || $status_db === 'Menunggu Bayar' || $status_db === 'Pending') {
                                                $btn_text = 'Pending';
                                                $btn_class = 'bg-yellow-100 text-yellow-800 border-yellow-300 hover:bg-blue-100 hover:text-blue-700 hover:border-blue-400';
                                                $next_status = 'Diproses';
                                                $confirm_msg = 'Pembayaran valid. Ubah status menjadi PROSES?';
                                            } elseif ($status_db === 'Diproses') {
                                                $btn_text = 'Proses';
                                                $btn_class = 'bg-blue-100 text-blue-800 border-blue-300 hover:bg-orange-100 hover:text-orange-700 hover:border-orange-400';
                                                $next_status = 'Dikirim';
                                                $confirm_msg = 'Pesanan sudah siap? Ubah status menjadi KIRIM?';
                                            } elseif ($status_db === 'Dikirim') {
                                                $btn_text = 'Kirim';
                                                $btn_class = 'bg-orange-100 text-orange-800 border-orange-300 hover:bg-green-100 hover:text-green-700 hover:border-green-400';
                                                $next_status = 'Selesai';
                                                $confirm_msg = 'Pesanan sudah diterima? Ubah status menjadi SELESAI?';
                                            } elseif ($status_db === 'Selesai') {
                                                $btn_text = 'Selesai';
                                                $btn_class = 'bg-green-100 text-green-800 border-green-300 cursor-not-allowed';
                                                $clickable = false;
                                            }
                                        }

                                        if ($clickable) {
                                            $ui_status_pesanan = "
                                            <button onclick=\"confirmNextStatus({$pesanan['pesanan_id']}, '$next_status', '$confirm_msg')\" 
                                                    class='text-xs font-bold border rounded px-3 py-1.5 w-full max-w-[120px] shadow-sm transition duration-200 $btn_class'>
                                                $btn_text
                                            </button>";
                                        } else {
                                            $ui_status_pesanan = "
                                            <span class='text-xs font-bold border rounded px-3 py-1.5 w-full max-w-[120px] inline-block $btn_class'>
                                                $btn_text
                                            </span>";
                                        }
                                    ?>
                                    <tr class="hover:bg-pink-50 transition duration-200 border-l-4 border-transparent hover:border-pink-500 bg-white">
                                        <td class="px-5 py-4 whitespace-nowrap font-mono text-sm font-bold text-gray-700">#ORD-<?php echo str_pad($pesanan['pesanan_id'], 3, '0', STR_PAD_LEFT); ?></td>
                                        <td class="px-5 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900"><?php echo htmlspecialchars($pesanan['nama_lengkap'] ?? 'N/A'); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo htmlspecialchars($pesanan['no_handphone'] ?? ''); ?></div>
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo date('d/m/Y', strtotime($pesanan['tanggal_pesan'])); ?></td>
                                        <td class="px-5 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo $pesanan['tanggal_acara'] != '0000-00-00' ? date('d/m/Y', strtotime($pesanan['tanggal_acara'])) : '-'; ?></td>
                                        <td class="px-5 py-4 whitespace-nowrap font-bold text-pink-600">Rp <?php echo number_format($pesanan['total_pesan'], 0, ',', '.'); ?></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center"><span class="<?php echo $badge_class; ?>"><?php echo $tipe_pembayaran; ?></span></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <span class="status-badge <?php echo $is_paid ? 'payment-sudah' : 'payment-belum'; ?>">
                                                <?php echo ($status_dp == 'Selesai') ? 'DP Dibayar' : (($payment_status === 'Selesai') ? 'Selesai' : 'Belum Bayar'); ?>
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 whitespace-nowrap text-center"><?php echo $ui_status_pesanan; ?></td>
                                        <td class="px-4 py-4 whitespace-nowrap text-center">
                                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded text-sm transition duration-200 font-medium shadow-sm" 
                                                    onclick="showDetailModal(<?php echo htmlspecialchars(json_encode($pesanan)); ?>, <?php echo htmlspecialchars(json_encode($details)); ?>)">
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                                            <i class="fas fa-box-open text-4xl mb-3 text-gray-300"></i>
                                            <p>Tidak ada data pesanan ditemukan.</p>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div id="detailModal" class="modal">
        <div class="modal-content p-6">
            <div class="flex justify-between items-center mb-6 pb-4 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-800" id="modalTitle">Detail Pesanan</h3>
                <button onclick="closeDetailModal()" class="text-gray-500 hover:text-gray-700 text-2xl"><i class="fas fa-times"></i></button>
            </div>
            <div class="grid grid-cols-2 gap-6 mb-6">
                <div>
                    <h4 class="font-bold text-gray-700 mb-3">Informasi Pelanggan</h4>
                    <div class="space-y-2 text-sm">
                        <div><span class="text-gray-600">Nama:</span> <span class="font-medium" id="modalNama"></span></div>
                        <div><span class="text-gray-600">No. HP:</span> <span class="font-medium" id="modalNoHp"></span></div>
                        <div><span class="text-gray-600">Alamat:</span> <span class="font-medium text-xs" id="modalAlamat"></span></div>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-gray-700 mb-3">Informasi Acara</h4>
                    <div class="space-y-2 text-sm">
                        <div><span class="text-gray-600">Tanggal:</span> <span class="font-medium" id="modalTglAcara"></span></div>
                        <div><span class="text-gray-600">Total:</span> <span class="font-bold text-pink-600" id="modalTotal"></span></div>
                        <div><span class="text-gray-600">Status:</span> <span id="modalStatusPembayaran"></span></div>
                    </div>
                </div>
            </div>
            <h4 class="font-bold text-gray-700 mb-3 border-t pt-6">Item Pesanan</h4>
            <div id="itemsContainer" class="space-y-4 mb-6"></div>
            <div class="bg-gray-100 -m-6 mt-6 px-6 py-4 flex justify-end">
                <button onclick="closeDetailModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition font-medium">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        function confirmNextStatus(pesananId, nextStatus, pesanKonfirmasi) {
            if (confirm(pesanKonfirmasi)) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'manajemen_pesanan.php';
                
                const inputs = {
                    'action': 'update_status_next',
                    'pesanan_id': pesananId,
                    'status_baru': nextStatus
                };

                for (let key in inputs) {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = key;
                    input.value = inputs[key];
                    form.appendChild(input);
                }
                
                document.body.appendChild(form);
                form.submit();
            }
        }

        function showDetailModal(pesanan, details) {
            document.getElementById('modalTitle').textContent = `Detail Pesanan #ORD-${String(pesanan.pesanan_id).padStart(3, '0')}`;
            document.getElementById('modalNama').textContent = pesanan.nama_lengkap || 'N/A';
            document.getElementById('modalNoHp').textContent = pesanan.no_handphone || '-';
            document.getElementById('modalAlamat').textContent = pesanan.alamat || '-';
            document.getElementById('modalTglAcara').textContent = pesanan.tanggal_acara !== '0000-00-00' ? new Date(pesanan.tanggal_acara).toLocaleDateString('id-ID') : '-';
            document.getElementById('modalTotal').textContent = 'Rp ' + parseInt(pesanan.total_pesan).toLocaleString('id-ID');
            
            const is_paid = (pesanan.status_pembayaran === 'Selesai' || pesanan.status_dp === 'Selesai');
            document.getElementById('modalStatusPembayaran').innerHTML = is_paid 
                ? `<span class="status-badge payment-sudah">Lunas</span>`
                : `<span class="status-badge payment-belum">Belum Bayar</span>`;

            let itemsHTML = '';
            details.forEach(item => {
                const img = item.gambar ? 'img/' + item.gambar : 'https://via.placeholder.com/200x150';
                itemsHTML += `
                    <div class="flex items-center gap-4 bg-gray-50 p-3 rounded-lg border border-gray-200">
                        <img src="${img}" class="w-16 h-16 object-cover rounded">
                        <div class="flex-1">
                            <p class="font-bold text-gray-800">${item.nama_menu}</p>
                            <p class="text-xs text-gray-500">${item.jumlah_menu} x Rp ${parseInt(item.harga_satuan).toLocaleString('id-ID')}</p>
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
<?php
session_start();
include 'koneksi.php';

// Pastikan user adalah admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    echo "<script>alert('Akses ditolak!'); window.location='login.php';</script>";
    exit();
}

// Ambil semua pesanan dengan detail
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
    ORDER BY p.pesanan_id DESC
";

$result = mysqli_query($conn, $query);
$pesanan_list = [];

while ($row = mysqli_fetch_assoc($result)) {
    $pesanan_list[] = $row;
}
$payment_status = $pesanan['status_pembayaran'] ?? 'Belum Bayar';
    $status_dp = $pesanan['status_dp'] ?? 'Belum Bayar';
    $jumlah_dp = (int)($pesanan['jumlah_dp'] ?? 0); // Ambil data jumlah_dp
    
    // LOGIKA OTOMATIS:
    // Jika jumlah_dp lebih dari 0, maka otomatis Tipe adalah DP
    if ($jumlah_dp > 0) { 
        $tipe_pembayaran = 'DP (50%)'; 
        $badge_class = 'badge-dp'; 
    } else { 
        // Jika tidak ada DP, maka tipenya FULL
        $tipe_pembayaran = 'FULL (100%)'; 
        $badge_class = 'badge-full'; 
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
        .status-pending { background-color: #FEF3C7; color: #92400E; }
        .status-confirmed { background-color: #DBEAFE; color: #0C4A6E; }
        .status-completed { background-color: #DCFCE7; color: #166534; }
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

        <main class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm border-b border-gray-200 p-6 flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <i class="fas fa-shopping-cart text-orange-600 mr-3"></i> Manajemen Pesanan
                    </h2>
                    <p class="text-gray-500 text-sm mt-1">Kelola pesanan dan konfirmasi pembayaran</p>
                </div>
                <div class="bg-gradient-to-r from-red-500 to-red-600 text-white px-5 py-2 rounded-full text-sm font-bold animate-pulse shadow-lg">
                    <i class="fas fa-bell mr-2"></i> <?php echo count($pesanan_list); ?> PESANAN
                </div>
            </header>

            <div class="flex-1 p-6 overflow-y-auto">
                <div class="bg-white rounded-lg shadow-md overflow-hidden border border-gray-200">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-orange-50 to-orange-100 border-b border-orange-200">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-900 uppercase tracking-wider">ID Pesanan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-900 uppercase tracking-wider">Nama Pelanggan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-900 uppercase tracking-wider">Tgl Pesan</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-900 uppercase tracking-wider">Tgl Acara</th>
                                    <th class="px-6 py-4 text-left text-xs font-bold text-orange-900 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-orange-900 uppercase tracking-wider">Tipe Pembayaran</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-orange-900 uppercase tracking-wider">Status Pembayaran</th>
                                    <th class="px-6 py-4 text-center text-xs font-bold text-orange-900 uppercase tracking-wider">Aksi</th>
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
                                        
                                        $tipe_pembayaran = 'Belum Ada';
                                        $badge_class = '';
                                        if ($status_dp == 'Selesai') { $tipe_pembayaran = 'DP (50%)'; $badge_class = 'badge-dp'; }
                                        elseif ($payment_status == 'Selesai') { $tipe_pembayaran = 'FULL (100%)'; $badge_class = 'badge-full'; }
                                    ?>
                                    <tr class="hover:bg-orange-50 transition duration-200 border-l-4 border-transparent hover:border-orange-500">
                                        <td class="px-6 py-4 whitespace-nowrap font-mono text-sm font-bold text-gray-700">#ORD-<?php echo str_pad($pesanan['pesanan_id'], 3, '0', STR_PAD_LEFT); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="font-medium text-gray-900"><?php echo htmlspecialchars($pesanan['nama_lengkap'] ?? 'N/A'); ?></div>
                                            <div class="text-xs text-gray-500"><?php echo htmlspecialchars($pesanan['no_handphone'] ?? ''); ?></div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo date('d/m/Y', strtotime($pesanan['tanggal_pesan'])); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600"><?php echo $pesanan['tanggal_acara'] != '0000-00-00' ? date('d/m/Y', strtotime($pesanan['tanggal_acara'])) : '-'; ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap font-bold text-orange-600">Rp <?php echo number_format($pesanan['total_pesan'], 0, ',', '.'); ?></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center"><span class="<?php echo $badge_class; ?>"><?php echo $tipe_pembayaran; ?></span></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <span class="status-badge <?php echo ($payment_status === 'Selesai' || $status_dp === 'Selesai') ? 'payment-sudah' : 'payment-belum'; ?>">
                                                <?php echo ($status_dp == 'Selesai') ? 'DP Dibayar' : (($payment_status === 'Selesai') ? 'Selesai' : 'Belum Bayar'); ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-center">
                                            <button class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm transition duration-200 font-medium" 
                                                    onclick="showDetailModal(<?php echo htmlspecialchars(json_encode($pesanan)); ?>, <?php echo htmlspecialchars(json_encode($details)); ?>)">
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Detail Pesanan -->
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
                        <div><span class="text-gray-600">Catatan:</span> <span class="font-medium text-xs" id="modalCatatan"></span></div>
                    </div>
                </div>
                <div>
                    <h4 class="font-bold text-gray-700 mb-3">Informasi Acara & Pembayaran</h4>
                    <div class="space-y-2 text-sm">
                        <div><span class="text-gray-600">Tanggal Acara:</span> <span class="font-medium" id="modalTglAcara"></span></div>
                        <div><span class="text-gray-600">Waktu Acara:</span> <span class="font-medium" id="modalWaktuAcara"></span></div>
                        <div><span class="text-gray-600">Total Pesanan:</span> <span class="font-bold text-orange-600" id="modalTotal"></span></div>
                        <div><span class="text-gray-600">Metode Pembayaran:</span> <span class="font-medium text-blue-600" id="modalMetodePembayaran"></span></div>
                        <div><span class="text-gray-600">Tipe Pembayaran:</span> <span class="font-medium" id="modalTipePembayaran"></span></div>
                        <div><span class="text-gray-600">Status Pembayaran:</span> <span class="font-medium" id="modalStatusPembayaran"></span></div>
                    </div>
                </div>
            </div>

            <h4 class="font-bold text-gray-700 mb-3 border-t pt-6">Detail Item Pesanan</h4>
            <div id="itemsContainer" class="space-y-4 mb-6"></div>

            <div class="bg-gray-100 -m-6 mt-6 px-6 py-4 flex justify-between gap-3">
                <button id="confirmPaymentBtn" onclick="confirmPaymentFromModal()" class="bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition duration-200 font-medium">
                    <i class="fas fa-check-circle me-2"></i> Konfirmasi Pembayaran
                </button>
                <button onclick="closeDetailModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg transition duration-200 font-medium">Tutup</button>
            </div>
        </div>
    </div>

    <script>
        let currentPembayaranId = null;
        let currentPesananId = null; // Tambahkan ini
        let currentPaymentStatus = null;

        function showDetailModal(pesanan, details) {
            // 1. Mengisi Informasi Dasar
            document.getElementById('modalTitle').textContent = `Detail Pesanan #ORD-${String(pesanan.pesanan_id).padStart(3, '0')}`;
            document.getElementById('modalNama').textContent = pesanan.nama_lengkap || 'N/A';
            document.getElementById('modalNoHp').textContent = pesanan.no_handphone || '-';
            document.getElementById('modalAlamat').textContent = pesanan.alamat || '-';
            document.getElementById('modalCatatan').textContent = pesanan.catatan || '-';
            document.getElementById('modalTglAcara').textContent = pesanan.tanggal_acara !== '0000-00-00' ? new Date(pesanan.tanggal_acara).toLocaleDateString('id-ID') : '-';
            document.getElementById('modalWaktuAcara').textContent = pesanan.waktu_acara !== '00:00:00' ? pesanan.waktu_acara : '-';
            document.getElementById('modalTotal').textContent = 'Rp ' + parseInt(pesanan.total_pesan).toLocaleString('id-ID');
            
            // 2. Simpan ID untuk keperluan proses (konfirmasi/kirim)
            currentPembayaranId = pesanan.pembayaran_id;
            currentPesananId = pesanan.pesanan_id;

            // 3. LOGIKA OTOMATIS TIPE PEMBAYARAN (Deteksi dari nominal DP)
            let tipePembayaranHTML = '';
            const jmlDp = parseInt(pesanan.jumlah_dp) || 0;

            if (jmlDp > 0) {
                tipePembayaranHTML = `<span class="badge-dp">DP (50%)</span> <span class="text-xs text-gray-500 ml-1">(Bayar: Rp ${jmlDp.toLocaleString('id-ID')})</span>`;
            } else {
                tipePembayaranHTML = `<span class="badge-full">LUNAS / FULL (100%)</span>`;
            }
            document.getElementById('modalTipePembayaran').innerHTML = tipePembayaranHTML;

            // 4. Status Pembayaran Visual
            const paymentStatus = pesanan.status_pembayaran;
            const statusDp = pesanan.status_dp;
            
            document.getElementById('modalStatusPembayaran').innerHTML = (paymentStatus === 'Selesai' || statusDp === 'Selesai')
                ? `<span class="status-badge payment-sudah">Sudah Dibayar</span>`
                : `<span class="status-badge payment-belum">Belum Bayar</span>`;

            // 5. LOGIKA TOMBOL AKSI (Otomatis ganti teks tanpa pilih-pilih lagi)
            const confirmBtn = document.getElementById('confirmPaymentBtn');
            if (paymentStatus !== 'Selesai' && statusDp !== 'Selesai') {
                // Jika belum bayar, tombol berfungsi untuk KONFIRMASI UANG MASUK
                confirmBtn.innerHTML = '<i class="fas fa-check-circle me-2"></i> Konfirmasi Pembayaran Diterima';
                confirmBtn.className = "bg-green-500 hover:bg-green-600 text-white px-6 py-2 rounded-lg transition font-medium";
                confirmBtn.style.display = 'block';
                confirmBtn.setAttribute('data-aksi', 'konfirmasi_bayar');
            } else if (pesanan.status_pesanan !== 'Dikirim') {
                // Jika sudah bayar tapi belum dikirim, tombol berubah jadi KIRIM
                confirmBtn.innerHTML = '<i class="fas fa-truck me-2"></i> Kirim Pesanan Sekarang';
                confirmBtn.className = "bg-orange-500 hover:bg-orange-600 text-white px-6 py-2 rounded-lg transition font-medium";
                confirmBtn.style.display = 'block';
                confirmBtn.setAttribute('data-aksi', 'kirim_pesanan');
            } else {
                // Jika sudah beres semua, sembunyikan tombol
                confirmBtn.style.display = 'none';
            }

            // 6. Menampilkan Item Menu (Looping)
            let itemsHTML = '';
            details.forEach(item => {
                const imagePath = item.gambar ? 'img/' + item.gambar : 'https://via.placeholder.com/200x150?text=No+Image';
                itemsHTML += `
                    <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <img src="${imagePath}" class="menu-item-image mb-3" onerror="this.src='https://via.placeholder.com/200x150?text=No+Image'">
                        <div class="text-sm">
                            <span class="text-gray-600 font-medium">Menu:</span> <span class="font-bold block text-gray-900">${item.nama_menu || 'N/A'}</span>
                            <div class="grid grid-cols-3 gap-4 mt-2">
                                <div><span class="text-gray-600">Harga:</span><span class="block">Rp ${parseInt(item.harga_satuan).toLocaleString('id-ID')}</span></div>
                                <div><span class="text-gray-600">Jumlah:</span><span class="block">${item.jumlah_menu}x</span></div>
                                <div><span class="text-gray-600">Subtotal:</span><span class="block font-bold text-orange-600">Rp ${parseInt(item.harga_satuan * item.jumlah_menu).toLocaleString('id-ID')}</span></div>
                            </div>
                        </div>
                    </div>`;
            });
            document.getElementById('itemsContainer').innerHTML = itemsHTML || '<div class="text-center text-gray-500 py-4">Tidak ada item</div>';
            document.getElementById('detailModal').classList.add('active');
        }

        function closeDetailModal() { document.getElementById('detailModal').classList.remove('active'); }

        // PERBAIKAN FUNGSI SUBMIT
        function confirmPaymentFromModal() {
            const confirmBtn = document.getElementById('confirmPaymentBtn');
            const aksi = confirmBtn.getAttribute('data-aksi');

            if (aksi === 'konfirmasi_bayar') {
                if (confirm('Konfirmasi bahwa pembayaran sudah diterima?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'proses_pembayaran.php';
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'pembayaran_id';
                    input.value = currentPembayaranId;
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            } else if (aksi === 'kirim_pesanan') {
                if (confirm('Apakah pesanan sudah siap dan ingin dikirim sekarang?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = 'proses_kirim.php';
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'pesanan_id';
                    input.value = currentPesananId;
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
            }
        }

        document.getElementById('detailModal').addEventListener('click', function(e) { if (e.target === this) closeDetailModal(); });
    </script>
</body>
</html>
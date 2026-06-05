<?php
session_start();
include('../koneksi.php'); // Sesuaikan path koneksi database Anda

// 🔐 Proteksi admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    // PERBAIKAN: Jalur dimundurkan ke folder utama tempat login.php berada
    header("Location: ../../login.php?status=wajib_login");
    exit();
}



// 🗑️ PROSES HAPUS DATA KOMPLAIN
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $komplain_id = mysqli_real_escape_string($conn, $_GET['id']);
    
    // Menjalankan perintah hapus data berdasarkan id komplain
    $delete_query = "DELETE FROM komplain WHERE id = '$komplain_id'"; 
    
    if (mysqli_query($conn, $delete_query)) {
        echo "<script>
                alert('Data komplain berhasil dihapus secara permanen!');
                window.location.href = 'admin_komplain.php';
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Gagal menghapus data komplain: " . mysqli_error($conn) . "');
                window.location.href = 'admin_komplain.php';
              </script>";
        exit();
    }
}

// Ambil data komplain terbaru dari database
$query = "SELECT * FROM komplain ORDER BY waktu_masuk DESC";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Kedai Aishwa | Komplain Pelanggan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="font-sans text-gray-800 flex h-screen overflow-hidden bg-gray-100">

    <?php include('sidebar.php'); ?>

    <main class="flex-1 overflow-y-auto bg-orange-50 flex flex-col">
        
        <header class="bg-white/90 backdrop-blur-sm shadow-sm p-4 px-8 flex justify-between items-center">
            <div>
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">Pusat Komplain Pelanggan</h1>
                <p class="text-xs text-gray-400 mt-0.5">Pantau dan kelola keluhan serta feedback dari pengguna</p>
            </div>
        </header>

        <div class="p-8 flex-1">
            <div class="max-w-6xl mx-auto">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                            <i class="fa-solid fa-list-check text-orange-500"></i> Daftar Komplain Masuk
                        </h2>
                        <span class="bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1.5 rounded-full">
                            Total: <?php echo mysqli_num_rows($result); ?> Data
                        </span>
                    </div>

                    <?php if (mysqli_num_rows($result) == 0): ?>
                        <div class="text-center py-12 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            <i class="fa-regular fa-folder-open text-4xl text-gray-300 mb-3"></i>
                            <p class="text-gray-500 text-sm font-medium">Belum ada data komplain yang masuk dari pelanggan.</p>
                        </div>
                    <?php else: ?>
                        <div class="overflow-hidden border border-gray-100 rounded-xl">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 text-gray-500 text-xs font-bold uppercase border-b border-gray-100">
                                        <th class="p-4 pl-6 w-48">Identitas Pelanggan</th>
                                        <th class="p-4">Isi Keluhan / Komplain</th>
                                        <th class="p-4 w-40">Waktu Masuk</th>
                                        <th class="p-4 text-center w-24">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50 text-sm bg-white">
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <?php 
                                            // LOGIKA PENYELAMAT: Cek ketersediaan nama kolom database agar tidak memicu Undefined Key Warning
                                            $display_nama = isset($row['nama']) ? $row['nama'] : (isset($row['nama_lengkap']) ? $row['nama_lengkap'] : 'Pelanggan Kedai Aishwa');
                                            $display_email = isset($row['email']) ? $row['email'] : '-';
                                            $display_deskripsi = isset($row['deskripsi']) ? $row['deskripsi'] : (isset($row['isi_komplain']) ? $row['isi_komplain'] : '');
                                        ?>
                                        <tr class="hover:bg-gray-50/80 transition duration-150">
                                            <td class="p-4 pl-6 align-top">
                                                <div class="font-bold text-gray-900"><?php echo htmlspecialchars($display_nama); ?></div>
                                                <div class="text-xs text-gray-400 mt-1 flex items-center gap-1">
                                                    <i class="fa-regular fa-envelope text-[10px]"></i>
                                                    <?php echo htmlspecialchars($display_email); ?>
                                                </div>
                                            </td>
                                            <td class="p-4 align-top">
                                                <p class="text-gray-600 bg-gray-50 p-3 rounded-xl border border-gray-100 leading-relaxed text-xs">
                                                    <?php echo htmlspecialchars($display_deskripsi); ?>
                                                </p>
                                            </td>
                                            <td class="p-4 align-top text-gray-500 font-medium">
                                                <div class="text-gray-800 text-xs">
                                                    <i class="fa-regular fa-clock me-1 text-gray-400"></i>
                                                    <?php echo date('d M Y', strtotime($row['waktu_masuk'])); ?>
                                                </div>
                                                <div class="text-[11px] text-gray-400 mt-0.5 ps-4">
                                                    <?php echo date('H:i', strtotime($row['waktu_masuk'])); ?> WIB
                                                </div>
                                            </td>
                                            <td class="p-4 align-top text-center">
                                                <a href="admin_komplain.php?action=delete&id=<?php echo $row['id']; ?>" 
                                                   onclick="return confirm('Hapus data komplain ini?');" 
                                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 hover:text-red-700 transition duration-150" 
                                                   title="Hapus Komplain">
                                                    <i class="fas fa-trash text-xs"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>

</body>
</html>
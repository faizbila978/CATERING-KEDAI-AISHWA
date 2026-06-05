<?php
session_start();
include '../koneksi.php';

// ====================================================================
// FITUR BARU: MENGEMBALIKAN PESANAN JIKA USER KEMBALI DARI PEMBAYARAN
// ====================================================================
if (isset($_GET['pesanan_id'])) {
    $pesanan_id = $_GET['pesanan_id'];
    
    $query_detail = mysqli_query($conn, "SELECT * FROM detail_pesanan WHERE pesanan_id = '$pesanan_id'");
    if ($query_detail && mysqli_num_rows($query_detail) > 0) {
        $_SESSION['keranjang'] = []; 
        while ($row = mysqli_fetch_assoc($query_detail)) {
            $_SESSION['keranjang'][$row['menu_id']] = $row['jumlah_menu']; 
        }
    }
    
    mysqli_query($conn, "DELETE FROM pembayaran WHERE pesanan_id = '$pesanan_id'");
    mysqli_query($conn, "DELETE FROM detail_pesanan WHERE pesanan_id = '$pesanan_id'");
    mysqli_query($conn, "DELETE FROM pesanan WHERE pesanan_id = '$pesanan_id'");
    
    unset($_SESSION['pesanan_id']);
}
// ====================================================================

if (!isset($_SESSION['keranjang']) || empty($_SESSION['keranjang'])) {
    echo "<script>alert('Pilih menu dulu ya!'); window.location='menu.php';</script>";
    exit();
}

// --------------------------------------------------------------------
// AMBIL BATASAN DINAMIS DARI DATABASE (PENGATURAN ADMIN)
// --------------------------------------------------------------------
$query_setting = mysqli_query($conn, "SELECT * FROM pengaturan_sistem WHERE id = 1");
$setting = mysqli_fetch_assoc($query_setting);

// minimal_porsi di sini sekarang berfungsi sebagai BATAS MAKSIMAL TAMPUNGAN DAPUR PER HARI (misal: 700)
$max_porsi_sistem = isset($setting['minimal_porsi']) ? intval($setting['minimal_porsi']) : 700;
$batas_hari_sistem = isset($setting['batasan_hari']) ? intval($setting['batasan_hari']) : 2;

// Hitung tanggal minimal untuk atribut 'min' pada tag input HTML
$tanggal_minimal_html = date('Y-m-d', strtotime("+$batas_hari_sistem days"));

$total_bayar = 0;
$total_porsi_keranjang = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Pesanan - Kedai Aishwa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="formulir.css">
    <style>
        .alert-info-custom {
            background-color: #E0F2FE;
            border-color: #0284C7;
            color: #0C4A6E;
            border-left: 4px solid #0284C7;
        }
        .processing-indicator {
            display: none;
            text-align: center;
            margin: 20px 0;
        }
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #ad2d5e;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 10px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .text-pink {
            color: #ad2d5e;
        }
        .btn-pink {
            background-color: #ad2d5e;
            color: white;
            border: none;
        }
        .btn-pink:hover {
            background-color: #8a244b;
            color: white;
        }
        .card-custom {
            border: 1px solid #e0e0e0;
            border-radius: 16px;
        }
    </style>
</head>
<body>

<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <a href="menu.php" class="btn btn-outline-dark rounded-pill px-4">
            <i class="bi bi-arrow-left me-2"></i>Kembali
        </a>
        <a href="menu_tambahan.php" class="btn btn-pink rounded-pill px-4 shadow">
            <i class="bi bi-plus-lg me-2"></i>Tambah Menu
        </a>
    </div>

    <div class="row g-4">

        <div class="col-lg-7">
            <div class="card card-custom shadow-sm p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Detail Pesanan</h5>

                <?php 
                foreach ($_SESSION['keranjang'] as $id => $jumlah): 
                    $query = mysqli_query($conn, "SELECT * FROM menu WHERE menu_id = '$id'");
                    $menu = mysqli_fetch_assoc($query);

                    if ($menu):
                        $subtotal = $menu['harga_satuan'] * $jumlah;
                        $total_bayar += $subtotal;
                        $total_porsi_keranjang += $jumlah;
                ?>
                <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                    
                    <img src="../img/<?php echo $menu['gambar']; ?>" 
                         class="rounded-3 shadow-sm me-3" 
                         style="width: 80px; height: 80px; object-fit: cover;">

                    <div class="flex-grow-1">
                        <h6 class="fw-bold mb-1"><?php echo $menu['nama_menu']; ?></h6>
                        <p class="text-pink fw-bold mb-0">
                            Rp <?php echo number_format($menu['harga_satuan'], 0, ',', '.'); ?>
                        </p>
                    </div>

                    <form action="keranjang_update.php" method="POST" class="d-flex align-items-center bg-light rounded-pill px-2">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">

                        <button type="button" class="btn btn-sm fw-bold text-pink" onclick="kurangiItem(this)">-</button>

                        <input type="number" 
                               name="jumlah" 
                               value="<?php echo $jumlah; ?>" 
                               min="0"
                               class="form-control form-control-sm border-0 bg-transparent text-center mx-1" 
                               style="width: 45px; font-weight: bold;"
                               title="Isi 0 untuk hapus item">

                        <button type="button" class="btn btn-sm fw-bold text-pink" onclick="tambahItem(this)">+</button>
                    </form>
                </div>
                <?php endif; endforeach; ?>

            </div>
        </div>

        <div class="col-lg-5">
            <div class="card card-custom shadow-sm p-4">
                <h5 class="fw-bold mb-4 border-bottom pb-2">Informasi Pengiriman</h5>

                <div class="alert alert-info-custom mb-4 rounded-3" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Petunjuk Kuota:</strong> Batas pemesanan minimal **H+<?php echo $batas_hari_sistem; ?>** dari hari ini. Dapur kami memiliki kapasitas kuota maksimal **<?php echo number_format($max_porsi_sistem, 0, ',', '.'); ?> porsi** per hari acara untuk menjaga kualitas rasa.
                </div>

                <form id="pesananForm" action="proses_pesan.php" method="POST">
                    <input type="hidden" name="total_harga" value="<?php echo $total_bayar; ?>">
                    <input type="hidden" id="total_porsi_keranjang" value="<?php echo $total_porsi_keranjang; ?>">

                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">NAMA PENERIMA</label>
                        <input type="text" name="nama" class="form-control rounded-3" required>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">NOMOR HANDPHONE</label>
                        <input type="text" name="no_hp" class="form-control rounded-3" placeholder="Contoh: 08123456789" required>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">TANGGAL ACARA</label>
                        <input type="date" id="tanggal_acara" name="tanggal_acara" 
                               min="<?php echo $tanggal_minimal_html; ?>" 
                               class="form-control rounded-3" onchange="cekKuotaTanggal(this)" required>
                        <div id="kuotaFeedback" class="form-text fw-bold mt-1" style="display:none;"></div>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">WAKTU ACARA</label>
                        <input type="time" name="waktu_acara" class="form-control rounded-3" required>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">DETAIL ALAMAT</label>
                        <textarea name="alamat" class="form-control rounded-3" placeholder="Contoh: Jl Mawar No 10, dekat masjid" required rows="2"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="small fw-bold text-muted mb-1">CATATAN PESANAN (OPSIONAL)</label>
                        <textarea name="catatan" class="form-control rounded-3" rows="2" placeholder="Contoh: Sambal dipisah ya"></textarea>
                    </div>

                    <div class="bg-light p-3 rounded-4 mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-bold text-muted">Total Pembayaran</span>
                            <h4 class="fw-bold text-pink mb-0">
                                Rp <?php echo number_format($total_bayar, 0, ',', '.'); ?>
                            </h4>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2 pt-2 border-top border-200">
                            <span class="small fw-bold text-muted">Porsi Keranjang Anda</span>
                            <span class="badge bg-secondary rounded-pill px-3 py-2 fw-bold">
                                <?php echo $total_porsi_keranjang; ?> Porsi
                            </span>
                        </div>
                    </div>

                    <div class="processing-indicator" id="processingIndicator">
                        <div class="spinner"></div>
                        <p class="text-muted small">Memproses pesanan Anda...</p>
                    </div>

                    <button type="submit" class="btn btn-pink w-100 py-3 rounded-pill fw-bold shadow-sm" id="submitBtn">
                        <i class="bi bi-check-circle me-2"></i> KONFIRMASI SEKARANG
                    </button>
                </form>
            </div>
        </div>

    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bundle.min.js"></script>

<script>
const MAX_PORSI_SISTEM = <?php echo $max_porsi_sistem; ?>;
const BATAS_HARI_SISTEM = <?php echo $batas_hari_sistem; ?>;
let kuotaTersediaSisa = MAX_PORSI_SISTEM; // Default awal bebas

function cekKuotaTanggal(input) {
    if (!input.value) return;

    let tglPilihan = new Date(input.value);
    let hariIni = new Date();
    hariIni.setHours(0, 0, 0, 0);
    tglPilihan.setHours(0, 0, 0, 0);
    
    let selisihWaktu = tglPilihan.getTime() - hariIni.getTime();
    let selisihHari = Math.ceil(selisihWaktu / (1000 * 3600 * 24));

    // 1. Validasi H-* biasa
    if (selisihHari < BATAS_HARI_SISTEM) {
        alert("Pemesanan tidak boleh H-" + BATAS_HARI_SISTEM + "! Silakan pilih tanggal minimal " + BATAS_HARI_SISTEM + " hari dari sekarang.");
        input.value = ""; 
        document.getElementById('kuotaFeedback').style.display = 'none';
        return;
    }

    // 2. AJAX Check Kuota Produksi Berdasarkan Tanggal yang Dipilih
    let tglString = input.value;
    let feedbackDiv = document.getElementById('kuotaFeedback');
    
    fetch('cek_kuota_ajax.php?tanggal=' + tglString)
        .then(response => response.json())
        .then(data => {
            let porsiTerpakai = parseInt(data.total_terpakai);
            kuotaTersediaSisa = MAX_PORSI_SISTEM - porsiTerpakai;
            let porsiKeranjang = parseInt(document.getElementById('total_porsi_keranjang').value);

            feedbackDiv.style.display = 'block';
            
            if (kuotaTersediaSisa <= 0) {
                feedbackDiv.className = "form-text fw-bold mt-1 text-danger";
                feedbackDiv.innerHTML = `<i class="bi bi-x-circle-fill"></i> Kuota Tanggal Ini Habis! (Terpakai: ${porsiTerpakai}/${MAX_PORSI_SISTEM} porsi).`;
            } else if (porsiKeranjang > kuotaTersediaSisa) {
                feedbackDiv.className = "form-text fw-bold mt-1 text-warning";
                feedbackDiv.innerHTML = `<i class="bi bi-exclamation-triangle-fill"></i> Slot sisa ${kuotaTersediaSisa} porsi. Keranjang Anda (${porsiKeranjang} porsi) melebihi batas harian!`;
            } else {
                feedbackDiv.className = "form-text fw-bold mt-1 text-success";
                feedbackDiv.innerHTML = `<i class="bi bi-check-circle-fill"></i> Aman! Sisa kuota produksi hari ini: ${kuotaTersediaSisa} porsi.`;
            }
        })
        .catch(err => {
            console.error("Gagal memuat kuota harian", err);
        });
}

function kurangiItem(btn) {
    let input = btn.nextElementSibling;
    let jumlah = parseInt(input.value);
    if (jumlah <= 1) {
        if (confirm("Item akan dihapus dari keranjang. Lanjutkan?")) {
            input.value = 0;
            input.form.submit();
        }
    } else {
        input.stepDown();
        input.form.submit();
    }
}

function tambahItem(btn) {
    let input = btn.previousElementSibling;
    input.stepUp();
    input.form.submit();
}

// --- SESSION STORAGE DRAFT INPUT ---
const formFields = document.querySelectorAll('#pesananForm input, #pesananForm textarea');
formFields.forEach(field => {
    field.addEventListener('input', function() {
        if (this.name) sessionStorage.setItem('draft_pesanan_' + this.name, this.value);
    });
});

window.addEventListener('DOMContentLoaded', function() {
    formFields.forEach(field => {
        if (field.name) {
            let savedValue = sessionStorage.getItem('draft_pesanan_' + field.name);
            if (savedValue !== null && field.type !== 'hidden') field.value = savedValue;
        }
    });
    // Triger cek kuota jika tanggal sudah terisi dari draft
    let tglInput = document.getElementById('tanggal_acara');
    if(tglInput.value) cekKuotaTanggal(tglInput);
});

document.getElementById('pesananForm').addEventListener('keydown', function(e) {
    if (e.key === 'Enter' && e.target.tagName !== 'TEXTAREA') e.preventDefault(); 
});

// --- VALIDASI SUBMIT ---
document.getElementById('pesananForm').addEventListener('submit', function(e) {
    let tglInput = document.getElementById('tanggal_acara');
    let porsiKeranjang = parseInt(document.getElementById('total_porsi_keranjang').value);
    
    if (!tglInput.value) {
        e.preventDefault();
        alert("Silakan pilih tanggal acara terlebih dahulu.");
        return false;
    }

    // Validasi final jika porsi melampaui sisa kuota dapur harian
    if (porsiKeranjang > kuotaTersediaSisa) {
        e.preventDefault();
        if (kuotaTersediaSisa <= 0) {
            alert("Maaf, pendaftaran pesanan ditutup untuk tanggal tersebut karena kuota porsi produksi katering sudah penuh.");
        } else {
            alert("Gagal Konfirmasi! Kuota harian sisa " + kuotaTersediaSisa + " porsi lagi. Pesanan Anda sebanyak " + porsiKeranjang + " porsi terlalu banyak untuk hari tersebut.");
        }
        return false;
    }

    document.getElementById('processingIndicator').style.display = 'block';
    document.getElementById('submitBtn').disabled = true;
});
</script>

</body>
</html>
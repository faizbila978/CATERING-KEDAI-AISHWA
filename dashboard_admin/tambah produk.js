// Mengambil elemen-elemen yang dibutuhkan
const dropArea = document.getElementById('drop-area');
const fileInput = document.getElementById('file-input');
const btnTambah = document.querySelector('.btn-tambah');
const btnHapus = document.querySelector('.btn-hapus');

// Elemen Input Form
const inputNama = document.querySelector('input[placeholder="Masukan Nama Produk"]');
const inputHarga = document.querySelector('input[placeholder="Masukan Harga Produk"]');
const inputDeskripsi = document.querySelector('textarea[placeholder="Masukan Deskripsi Produk"]');

// --- 1. LOGIKA UNGGAH FOTO ---

// Klik area kotak untuk buka file manager
dropArea.addEventListener('click', () => fileInput.click());

// Menangani file yang dipilih
fileInput.addEventListener('change', function() {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function() {
            // Mengganti isi drop-area dengan pratinjau foto
            dropArea.innerHTML = `
                <img src="${reader.result}" class="w-full h-full object-cover rounded-lg" id="preview-img">
            `;
        }
        reader.readAsDataURL(file);
    }
});

// --- 2. LOGIKA TOMBOL TAMBAH (HIJAU) ---

btnTambah.addEventListener('click', () => {
    const data = {
        nama: inputNama.value,
        harga: inputHarga.value,
        deskripsi: inputDeskripsi.value,
        foto: document.getElementById('preview-img') ? "Foto Terpilih" : "Belum ada foto"
    };

    // Validasi sederhana
    if (!data.nama || !data.harga) {
        alert("Mohon isi minimal Nama dan Harga Produk!");
        return;
    }

    console.log("Data Produk Berhasil Ditambahkan:", data);
    alert("Produk " + data.nama + " berhasil disimpan!");

    // Opsional: Reset form setelah berhasil tambah
    // resetForm();
});

// --- 3. LOGIKA TOMBOL HAPUS (MERAH) ---

btnHapus.addEventListener('click', () => {
    if (confirm("Apakah Anda yakin ingin menghapus semua inputan?")) {
        resetForm();
    }
});

// Fungsi untuk mengosongkan kembali form
function resetForm() {
    inputNama.value = "";
    inputHarga.value = "";
    inputDeskripsi.value = "";
    fileInput.value = "";
    // Kembalikan tampilan kotak upload ke semula
    dropArea.innerHTML = `
        <i class="fas fa-camera text-7xl text-gray-500 mb-4"></i>
        <p class="text-gray-700 font-bold text-lg">Masukan Photo Produk</p>
    `;
}
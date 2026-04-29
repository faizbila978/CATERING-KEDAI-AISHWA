<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="tambah produk menu.css">
</head>
<body class="font-sans flex">

    <!-- Memanggil Sidebar Modular -->
    <?php include('sidebar.php'); ?>

    <main class="flex-1 p-12 overflow-y-auto bg-[#fdf2e9] h-screen">
        
        <div class="flex justify-between items-center mb-10">
            <h2 class="text-3xl font-bold text-[#1e293b]">Tambah Produk Menu</h2>
            <button class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition" onclick="history.back()">
                Kembali
            </button>
        </div>

        <!-- Form Utama untuk Mengirim Data -->
        <form id="formProduk" action="proses_tambah.php" method="POST" enctype="multipart/form-data">
            <div class="bg-white p-10 rounded-3xl shadow-xl border border-orange-100">
                <div class="flex flex-col lg:flex-row gap-12">
                    
                    <!-- Area Upload Foto -->
                    <div class="w-full lg:w-1/2">
                        <div class="border-4 border-dashed border-gray-300 rounded-3xl p-12 text-center cursor-pointer hover:bg-orange-50 transition-all group h-full flex flex-col justify-center items-center" id="drop-area">
                            <div id="preview-container" class="flex flex-col items-center">
                                <i class="fas fa-camera text-7xl text-gray-400 group-hover:text-orange-500 mb-4 transition"></i>
                                <p class="text-gray-500 font-bold text-lg">Masukan Photo Produk</p>
                            </div>
                            <input type="file" name="foto_produk" id="file-input" hidden accept="image/*">
                        </div>
                    </div>

                    <!-- Input Data Produk -->
                    <div class="w-full lg:w-1/2 flex flex-col gap-8 justify-center">
                        <div class="relative">
                            <input type="text" name="nama_produk" id="inputNama" placeholder="Masukan Nama Produk" class="w-full border-b-2 border-gray-300 p-3 text-lg focus:border-orange-600 outline-none transition bg-transparent" required>
                        </div>
                        <div class="relative">
                            <input type="number" name="harga_produk" id="inputHarga" placeholder="Masukan Harga Produk" class="w-full border-b-2 border-gray-300 p-3 text-lg focus:border-orange-600 outline-none transition bg-transparent" required>
                        </div>
                        <div class="relative">
                            <textarea name="deskripsi_produk" id="inputDeskripsi" placeholder="Masukan Deskripsi Produk" class="w-full border-b-2 border-gray-300 p-3 h-32 resize-none focus:border-orange-600 outline-none transition bg-transparent"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="flex gap-8 mt-12">
                <button type="submit" class="flex-1 bg-green-600 text-white py-4 rounded-2xl font-black text-xl shadow-lg hover:bg-green-700 hover:-translate-y-1 transition-all uppercase tracking-wider">
                    <i class="fas fa-save mr-2"></i> Tambah Produk
                </button>
                <button type="button" id="btnReset" class="w-1/3 bg-red-500 text-white py-4 rounded-2xl font-bold text-xl shadow-lg hover:bg-red-600 hover:-translate-y-1 transition-all uppercase">
                    <i class="fas fa-trash-alt mr-2"></i> Hapus
                </button>
            </div>
        </form>

    </main>

    <script>
        // Logika JavaScript dari Source 10 yang diadaptasi
        const dropArea = document.getElementById('drop-area');
        const fileInput = document.getElementById('file-input');
        const previewContainer = document.getElementById('preview-container');
        const btnReset = document.getElementById('btnReset');

        // Klik area untuk upload
        dropArea.addEventListener('click', () => fileInput.click());

        // Preview Foto saat dipilih
        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function() {
                    previewContainer.innerHTML = `
                        <img src="${reader.result}" class="max-h-64 rounded-lg shadow-md border-2 border-orange-500 mb-2">
                        <p class="text-orange-600 font-bold text-sm">Foto Berhasil Dipilih</p>
                    `;
                }
                reader.readAsDataURL(file);
            }
        });

        // Tombol Reset Form
        btnReset.addEventListener('click', () => {
            if (confirm("Kosongkan semua inputan?")) {
                document.getElementById('formProduk').reset();
                previewContainer.innerHTML = `
                    <i class="fas fa-camera text-7xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500 font-bold text-lg">Masukan Photo Produk</p>
                `;
            }
        });
    </script>
</body>
</html>
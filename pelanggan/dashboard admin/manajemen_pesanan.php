<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Pesanan - Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="manajemen pesanan.css">
</head>
<body class="font-sans text-sm bg-stone-100 flex">

    <!-- Memanggil Sidebar Modular -->
    <?php include('sidebar.php'); ?>

    <main class="flex-1 overflow-hidden flex flex-col h-screen">
        <!-- Header dari Source 3 -->
        <header class="bg-white p-5 shadow-sm flex justify-between items-center border-b border-gray-200">
            <h2 id="pageTitle" class="text-base font-bold text-gray-800 flex items-center">
                <i class="fas fa-shopping-cart text-orange-600 mr-2"></i> Manajemen Pesanan
            </h2>
            <div id="notifArea" class="bg-orange-500 text-white px-3 py-1 rounded-full text-[10px] animate-pulse">
                PESANAN BARU MASUK!
            </div>
        </header>

        <!-- Area Konten Dinamis -->
        <div id="dynamicContent" class="p-6 overflow-auto bg-[#fffbf5] h-full">
            
            <div class="bg-white rounded-xl shadow-md overflow-hidden border border-orange-100">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-orange-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-orange-700 uppercase">ID Pesanan</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-orange-700 uppercase">Nama Pelanggan</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-orange-700 uppercase">Produk</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-orange-700 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-orange-700 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-orange-700 uppercase">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <!-- Contoh Data Statis (Nanti akan diganti dengan Loop PHP dari Database) -->
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">#ORD-001</td>
                            <td class="px-6 py-4 whitespace-nowrap">Budi Santoso</td>
                            <td class="px-6 py-4 whitespace-nowrap">Paket Nasi Kuning (20 Box)</td>
                            <td class="px-6 py-4 whitespace-nowrap font-bold text-orange-600">Rp 500.000</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 text-[10px] font-bold bg-yellow-100 text-yellow-700 rounded-full uppercase">Pending</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                                <button class="text-blue-600 hover:text-blue-900"><i class="fas fa-eye"></i> Detail</button>
                                <button class="text-green-600 hover:text-green-900"><i class="fas fa-check"></i> Proses</button>
                            </td>
                        </tr>
                        
                        <!-- Area untuk Loop PHP Database -->
                        <?php
                        /* 
                        Contoh cara mengambil data dari DB:
                        $query = mysqli_query($conn, "SELECT * FROM pesanan");
                        while($row = mysqli_fetch_array($query)){
                            echo "<tr>... data ...</tr>";
                        }
                        */
                        ?>
                    </tbody>
                </table>
            </div>

        </div>
    </main>

    <script src="manajemen pesanan.js"></script>
</body>
</html>
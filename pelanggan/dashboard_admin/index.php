<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Kedai Aishwa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="font-sans text-gray-800 flex">

    <?php include('sidebar.php'); ?>

    <main class="flex-1 overflow-y-auto bg-orange-50">
        <header class="bg-white/90 backdrop-blur-sm shadow-sm p-4 px-8 flex justify-between items-center sticky top-0 z-10 border-b border-gray-200">
            <h2 class="text-2xl font-bold text-gray-700">Beranda Admin</h2>
        </header>

        <div class="p-8 h-full flex items-center justify-center">
            <div class="text-center py-20 fade-in bg-white p-12 rounded-3xl shadow-xl border border-gray-100">
                <h2 class="text-4xl font-extrabold text-gray-800">Halo, Admin!</h2>
                <p class="text-gray-700 mt-4 text-lg font-medium">Silakan pilih menu di samping kiri untuk mengelola data katering.</p>
            </div>
        </div>
    </main>

</body>
</html>
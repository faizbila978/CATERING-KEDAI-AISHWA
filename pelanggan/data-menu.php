<?php
<<<<<<< HEAD
// 1. Data Menu (Gunakan Angka Murni untuk harga)
$menus = [
    [
        "id" => 1,
        "nama" => "Nasi Kotak Ayam Bakar",
        "deskripsi" => "Nasi, Ayam Bakar, Lalapan, Sambal.",
        "harga" => 20000,
=======
// 1. Data Menu (Disimpan dalam Array agar mudah dikelola)
$menus = [
    [
        "nama" => "Nasi Kotak Ayam Bakar",
        "deskripsi" => "Nasi, Ayam Bakar, Lalapan, Sambal.",
        "harga" => "35rb",
>>>>>>> 9c32336805fc3f254a23636c348bef075027d3ba
        "gambar" => "ayambakar.png",
        "kategori" => "nasi"
    ],
    [
<<<<<<< HEAD
        "id" => 2,
        "nama" => "Nasi Ayam Krispy",
        "deskripsi" => "Nasi, Ayam krispy, Lalapan, Sambal.",
        "harga" => 45000,
=======
        "nama" => "Nasi Ayam Krispy",
        "deskripsi" => "Nasi, Ayam krispy, Lalapan, Sambal.",
        "harga" => "45rb",
>>>>>>> 9c32336805fc3f254a23636c348bef075027d3ba
        "gambar" => "ayamkrispy.png",
        "kategori" => "nasi"
    ],
    [
<<<<<<< HEAD
        "id" => 3,
        "nama" => "Beef Teriyaki Bowl",
        "deskripsi" => "Daging sapi slice dengan saus premium.",
        "harga" => 45000,
=======
        "nama" => "Beef Teriyaki Bowl",
        "deskripsi" => "Daging sapi slice dengan saus premium.",
        "harga" => "45rb",
>>>>>>> 9c32336805fc3f254a23636c348bef075027d3ba
        "gambar" => "kentang.png",
        "kategori" => "ricebowl"
    ],
    [
<<<<<<< HEAD
        "id" => 4,
        "nama" => "nasi tumpeng nusantara",
        "deskripsi" => "Nasi kuning, Orek Tempe, Ayam, urap, smabal.",
        "harga" => 30000,
=======
        "nama" => "Nasi Tumpeng Mini",
        "deskripsi" => "Nasi kuning, Orek Tempe, Ayam, Serundeng.",
        "harga" => "35rb",
>>>>>>> 9c32336805fc3f254a23636c348bef075027d3ba
        "gambar" => "tumpeng.png",
        "kategori" => "nasi"
    ],
    [
<<<<<<< HEAD
        "id" => 5,
        "nama" => "Chicken Katsu Bowl",
        "deskripsi" => "Ayam katsu renyah dengan salad segar.",
        "harga" => 30000,
=======
        "nama" => "Chicken Katsu Bowl",
        "deskripsi" => "Ayam katsu renyah dengan salad segar.",
        "harga" => "30rb",
>>>>>>> 9c32336805fc3f254a23636c348bef075027d3ba
        "gambar" => "img/chicken-bowl.jpg",
        "kategori" => "ricebowl"
    ],
    [
<<<<<<< HEAD
        "id" => 6,
        "nama" => "Box Jajanan Pasar",
        "deskripsi" => "Isi 3 macam kue tradisional pilihan.",
        "harga" => 15000,
=======
        "nama" => "Box Jajanan Pasar",
        "deskripsi" => "Isi 3 macam kue tradisional pilihan.",
        "harga" => "15rb",
>>>>>>> 9c32336805fc3f254a23636c348bef075027d3ba
        "gambar" => "img/snack.jpg",
        "kategori" => "snack"
    ]
];

// Fungsi bantuan untuk memfilter menu berdasarkan kategori
function filterMenu($menu_list, $category) {
    if ($category == 'all') return $menu_list;
    return array_filter($menu_list, function($item) use ($category) {
        return $item['kategori'] == $category;
    });
<<<<<<< HEAD
}
?>
=======
}
>>>>>>> 9c32336805fc3f254a23636c348bef075027d3ba

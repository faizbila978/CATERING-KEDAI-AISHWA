<?php
// 1. Data Menu (Gunakan Angka Murni untuk harga)
$menus = [
    [
        "id" => 1,
        "nama" => "Nasi Kotak Ayam Bakar",
        "deskripsi" => "Nasi, Ayam Bakar, Lalapan, Sambal.",
        "harga" => 35000,
        "gambar" => "ayambakar.png",
        "kategori" => "nasi"
    ],
    [
        "id" => 2,
        "nama" => "Nasi Ayam Krispy",
        "deskripsi" => "Nasi, Ayam krispy, Lalapan, Sambal.",
        "harga" => 45000,
        "gambar" => "ayamkrispy.png",
        "kategori" => "nasi"
    ],
    [
        "id" => 3,
        "nama" => "Beef Teriyaki Bowl",
        "deskripsi" => "Daging sapi slice dengan saus premium.",
        "harga" => 45000,
        "gambar" => "kentang.png",
        "kategori" => "ricebowl"
    ],
    [
        "id" => 4,
        "nama" => "Nasi Tumpeng Mini",
        "deskripsi" => "Nasi kuning, Orek Tempe, Ayam, Serundeng.",
        "harga" => 35000,
        "gambar" => "tumpeng.png",
        "kategori" => "nasi"
    ],
    [
        "id" => 5,
        "nama" => "Chicken Katsu Bowl",
        "deskripsi" => "Ayam katsu renyah dengan salad segar.",
        "harga" => 30000,
        "gambar" => "img/chicken-bowl.jpg",
        "kategori" => "ricebowl"
    ],
    [
        "id" => 6,
        "nama" => "Box Jajanan Pasar",
        "deskripsi" => "Isi 3 macam kue tradisional pilihan.",
        "harga" => 15000,
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
}
?>
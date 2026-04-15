<?php
// 1. Data Menu (Disimpan dalam Array agar mudah dikelola)
$menus = [
    [
        "nama" => "Nasi Kotak Ayam Bakar",
        "deskripsi" => "Nasi, Ayam Bakar, Lalapan, Sambal.",
        "harga" => "35rb",
        "gambar" => "ayambakar.png",
        "kategori" => "nasi"
    ],
    [
        "nama" => "Nasi Ayam Krispy",
        "deskripsi" => "Nasi, Ayam krispy, Lalapan, Sambal.",
        "harga" => "45rb",
        "gambar" => "ayamkrispy.png",
        "kategori" => "nasi"
    ],
    [
        "nama" => "Beef Teriyaki Bowl",
        "deskripsi" => "Daging sapi slice dengan saus premium.",
        "harga" => "45rb",
        "gambar" => "kentang.png",
        "kategori" => "ricebowl"
    ],
    [
        "nama" => "Nasi Tumpeng Mini",
        "deskripsi" => "Nasi kuning, Orek Tempe, Ayam, Serundeng.",
        "harga" => "35rb",
        "gambar" => "tumpeng.png",
        "kategori" => "nasi"
    ],
    [
        "nama" => "Chicken Katsu Bowl",
        "deskripsi" => "Ayam katsu renyah dengan salad segar.",
        "harga" => "30rb",
        "gambar" => "img/chicken-bowl.jpg",
        "kategori" => "ricebowl"
    ],
    [
        "nama" => "Box Jajanan Pasar",
        "deskripsi" => "Isi 3 macam kue tradisional pilihan.",
        "harga" => "15rb",
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
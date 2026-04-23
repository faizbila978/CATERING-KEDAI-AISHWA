<?php
// data-payment.php

// Data Ringkasan Pesanan (Menggunakan Rupiah)
$order_item = "Paket Private Chef Tasting";
$price = 240000; // Harga dasar dalam Rupiah
$service_fee_percent = 10;
$service_fee = ($service_fee_percent / 100) * $price;
$total_price = $price + $service_fee;

// Daftar E-Wallet
$ewallets = [
    [
        'id' => 'gopay', 
        'name' => 'GoPay', 
        'desc' => 'Pembayaran instan & cashback gila-gilaan', 
        'icon' => 'bi-wallet2'
    ],
    [
        'id' => 'ovo', 
        'name' => 'OVO', 
        'desc' => 'Transaksi cepat dan aman', 
        'icon' => 'bi-phone'
    ],
    [
        'id' => 'dana', 
        'name' => 'DANA', 
        'desc' => 'Dompet digital untuk semua transaksi', 
        'icon' => 'bi-qr-code'
    ]
];

// Daftar Bank
$banks = [
    ['id' => 'bca', 'name' => 'BCA Mobile'],
    ['id' => 'bri', 'name' => 'BRImo'],
    ['id' => 'mandiri', 'name' => 'Livin\' by Mandiri'],
    ['id' => 'bni', 'name' => 'BNI Mobile Banking']
];
?>
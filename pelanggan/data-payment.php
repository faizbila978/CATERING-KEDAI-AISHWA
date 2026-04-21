<?php
// data-payment.php

// Data Ringkasan Pesanan
$order_item = "Private Chef Tasting";
$price = 240.00;
$service_fee_percent = 10;
$service_fee = ($service_fee_percent / 100) * $price;
$total_price = $price + $service_fee;

// Daftar E-Wallet
$ewallets = [
    ['id' => 'gopay', 'name' => 'GoPay', 'desc' => 'Instant payment & rewards', 'icon' => 'bi-wallet2'],
    ['id' => 'ovo', 'name' => 'OVO', 'desc' => 'Fast and secure transaction', 'icon' => 'bi-phone']
];

// Daftar Bank
$banks = [
    ['id' => 'bca', 'name' => 'Bca Mobile'],
    ['id' => 'bri', 'name' => 'Bri Mobile'],
    ['id' => 'mandiri', 'name' => 'Mandiri Online']
];
?>
<?php
// Panggil file utama library yang tadi kamu download
require_once dirname(__FILE__) . '/midtrans-php/Midtrans.php';

// Masukkan Server Key & Client Key dari Dashboard Midtrans kamu
\Midtrans\Config::$serverKey = 'MASUKKAN_SERVER_KEY_KAMU';
\Midtrans\Config::$clientKey = 'MASUKKAN_CLIENT_KEY_KAMU';

// Set ke false karena kita masih tahap belajar/testing (Sandbox)
\Midtrans\Config::$isProduction = false; 
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = true;
?>
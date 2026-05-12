<?php
require_once 'vendor/autoload.php'; // Pastikan sudah install google api client via composer

// Pastikan isinya seperti ini (Ganti dengan kode asli kamu)
// Contoh di google_auth.php
$clientID = 'ISI_NANTI_DI_SERVER'; 
$clientSecret = 'ISI_NANTI_DI_SERVER';
$redirectUri = 'http://localhost/CATERING-KEDAI-AISHWA/CATERING-KEDAI-AISHWA/pelanggan/google_callback.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);
$client->addScope("email");
$client->addScope("profile");

header("Location: " . $client->createAuthUrl());
exit;
?>
<?php
session_start();
require_once 'vendor/autoload.php';

// Koneksi ke database catering
$conn = mysqli_connect("localhost", "root", "", "catering_kedai_aishwa"); 

// GANTI DENGAN DATA DARI GOOGLE 

$redirectUri = 'http://localhost/CATERING-KEDAI-AISHWA/CATERING-KEDAI-AISHWA/pelanggan/google_callback.php';

$client = new Google_Client();
$client->setClientId($clientID);
$client->setClientSecret($clientSecret);
$client->setRedirectUri($redirectUri);

if (isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    
    // Cek apakah ada error saat mengambil token
    if (isset($token['error'])) {
        die("Error mengambil token: " . $token['error_description']);
    }

    $client->setAccessToken($token['access_token']);

    // Ambil data profil dari Google
    $google_oauth = new Google_Service_Oauth2($client);
    $google_account_info = $google_oauth->userinfo->get();
    
    $email = $google_account_info->email;
    $nama = $google_account_info->name;

    // Cek apakah email sudah ada di database
    $query = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $data = mysqli_fetch_assoc($query);

    if ($data) {
        // Jika user sudah ada, set session
        $_SESSION['user_id'] = $data['user_id'];
        $_SESSION['nama'] = $data['nama_lengkap'];
        $_SESSION['role'] = $data['role'];
        $_SESSION['user_email'] = $data['email'];
    } else {
        // Jika belum ada, daftar otomatis (password diisi acak karena login via Google)
        $password_random = bin2hex(random_bytes(8)); 
        $insert = mysqli_query($conn, "INSERT INTO users (nama_lengkap, email, password, role) 
                                       VALUES ('$nama', '$email', '$password_random', 'user')");
        
        if ($insert) {
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            $_SESSION['nama'] = $nama;
            $_SESSION['role'] = 'user';
            $_SESSION['user_email'] = $email;
        }
    }

    // Arahkan ke halaman menu setelah sukses
    header("Location: menu.php");
    exit;
}
?>
<?php
session_start();

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Sederhananya kita anggap login berhasil jika form terisi
    if (!empty($email) && !empty($password)) {
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = "Pelanggan Aishwa"; // Contoh nama statis
        
        // Flow: Setelah login sukses, arahkan ke menu
        header("Location: menu.php");
        exit();
    } else {
        header("Location: login.php?error=empty");
        exit();
    }
}
?>
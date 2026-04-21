<?php
session_start(); // Wajib dipanggil dulu agar sistem tahu session mana yang mau dihapus

// Hapus semua data yang tersimpan di session (nama, email, role, dll)
session_unset();

// Hancurkan session secara permanen dari server
session_destroy();

// Tendang balik ke halaman login sesuai skenario
echo "<script>
    alert('Log out berhasil. Sampai jumpa lagi!');
    window.location='index.php';
</script>";
exit();
?>
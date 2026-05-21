<?php
session_start();

// Hapus semua session data
session_unset();

// Destroy session
session_destroy();

// Redirect ke login page
header('Location: login.php');
exit;
?>
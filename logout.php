<?php 
// mengaktifkan session php
session_start();

// menghapus semua session
session_destroy();
setcookie('login_token', '', time() - 3600);
// mengalihkan halaman ke halaman login
header("location:sign-out.php");
?>
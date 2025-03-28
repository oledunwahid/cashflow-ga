<?php 
session_start();

if (isset($_GET['lang'])) {
    $_SESSION['lang'] = $_GET['lang'];
}

// Atur bahasa default jika belum diset
$language = $_SESSION['lang'] ?? 'indonesia';

// Load file bahasa sesuai pilihan
$lang = include "languages/$language.php";


$_SESSION['url-dituju'] = $_SERVER['REQUEST_URI'];

// Cek login token dari cookie jika ada
if (isset($_COOKIE['login_token'])) {
   $token = $_COOKIE['login_token'];
}

// Cek session idnik
if (!isset($_SESSION['idnik'])) {
   $_SESSION['Messages'] = 'Please login to continue.';
   $_SESSION['Icon'] = 'warning';
   header("location:login.php");
   exit;
}

// Cek role pengguna
if (isset($_SESSION['role'])) {
   $userRoles = $_SESSION['role'];
   $hasValidRole = false;
   
   // Cek apakah user memiliki role 1 atau 2
   foreach ($userRoles as $role) {
       if ($role == 1 || $role == 2) {
           $hasValidRole = true;
           break;
       }
   }
   
   // Jika tidak memiliki role yang valid, logout dan redirect ke login
   if (!$hasValidRole) {
       // Debug log
       error_log('Invalid role detected. User roles: ' . implode(',', $userRoles));
       
       // Hapus semua session
       session_regenerate_id(true);
       $_SESSION = array();
       
       // Hapus cookie jika ada
       if (isset($_COOKIE['login_token'])) {
           setcookie('login_token', '', time() - 3600, '/');
       }
       if (isset($_COOKIE['username'])) {
           setcookie('username', '', time() - 3600, '/');
       }
       
       // Set pesan error
       $_SESSION['Messages'] = 'You do not have permission to access this system.';
       $_SESSION['Icon'] = 'error';
       
       // Debug log
       error_log('Session after cleanup - Messages: ' . $_SESSION['Messages']);
       error_log('Session after cleanup - Icon: ' . $_SESSION['Icon']);
       
       // Pastikan session ditulis sebelum redirect
       session_write_close();
       
       // Redirect dengan pesan error
       header("location:login.php");
       exit;
   }
} else {
   // Debug log
   error_log('No roles found in session');
   
   // Hapus semua session
   session_regenerate_id(true);
   $_SESSION = array();
   
   // Hapus cookie jika ada
   if (isset($_COOKIE['login_token'])) {
       setcookie('login_token', '', time() - 3600, '/');
   }
   if (isset($_COOKIE['username'])) {
       setcookie('username', '', time() - 3600, '/');
   }
   
   // Set pesan error
   $_SESSION['Messages'] = 'You do not have permission to access this system.';
   $_SESSION['Icon'] = 'error';
   
   // Debug log
   error_log('Session after cleanup (no roles) - Messages: ' . $_SESSION['Messages']);
   error_log('Session after cleanup (no roles) - Icon: ' . $_SESSION['Icon']);
   
   // Pastikan session ditulis sebelum redirect
   session_write_close();
   
   // Redirect dengan pesan error
   header("location:login.php");
   exit;
}

// Jika sampai di sini, berarti user memiliki role yang valid
// Debug log
error_log('Valid role confirmed. User roles: ' . implode(',', $userRoles));
?>
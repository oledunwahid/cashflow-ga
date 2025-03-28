<?php
require_once("../koneksi.php");

if (isset($_GET['idnik'])) {
    $idnik = $_GET['idnik'];
    
    // Hapus record dari database
    $query = "DELETE FROM user_roles WHERE idnik = '$idnik'";
    $result = mysqli_query($koneksi, $query);
    
    session_start();
    if ($result) {
        $_SESSION["Messages"] = 'User Role Berhasil Di Hapus';
        $_SESSION["Icon"] = 'success';
    } else {
        $_SESSION["Messages"] = 'User Role Gagal Di Hapus: ' . mysqli_error($koneksi);
        $_SESSION["Icon"] = 'error';
    }
    
    header('Location: ../index.php?page=UserRoles');
    exit();
} else {
    session_start();
    $_SESSION["Messages"] = 'ID NIK tidak ditemukan';
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=UserRoles');
    exit();
}
?>
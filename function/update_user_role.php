<?php
require_once("../koneksi.php");

if (isset($_POST['update-user-role'])) {
    $idnik = $_POST['idnik'];
    $id_role = $_POST['id_role'];

    // Query untuk update data user role
    $query = "UPDATE user_roles SET 
                id_role = '$id_role'
              WHERE idnik = '$idnik'";
    
    // Eksekusi query
    $kondisi = mysqli_query($koneksi, $query);
    
    // Cek jika query berhasil
    if ($kondisi) {
        session_start();
        $_SESSION["Messages"] = 'Data User Role Berhasil Di Update';
        $_SESSION["Icon"] = 'success';
        header('Location: ../index.php?page=UserRoles');
        exit();
    } else {
        session_start();
        $_SESSION["Messages"] = 'Data User Role Gagal Di Update';
        $_SESSION["Icon"] = 'error';
        header('Location: ../index.php?page=UserRoles');
        exit();
    }
} else {
    header('Location: ../index.php?page=Dashboard');
    exit();
}
?>
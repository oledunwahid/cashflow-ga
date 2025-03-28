<?php
require_once("../koneksi.php");

if (isset($_POST['add-user-role'])) {
    $idnik = $_POST['idnik'];
    $id_role = $_POST['id_role'];

    // Prepare the INSERT query
    $query = "INSERT INTO user_roles (idnik, id_role) 
              VALUES ('$idnik', '$id_role')";
    
    $result = mysqli_query($koneksi, $query);
    
    session_start();
    if ($result) {
        $_SESSION["Messages"] = 'Data User Role Berhasil Di Input';
        $_SESSION["Icon"] = 'success';
    } else {
        $_SESSION["Messages"] = 'Data User Role Gagal Di Input: ' . mysqli_error($koneksi);
        $_SESSION["Icon"] = 'error';
    }
    
    header('Location: ../index.php?page=UserRoles');
    exit();
} else {
    session_start();
    $_SESSION["Messages"] = 'Form tidak terkirim dengan benar';
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=UserRoles');
    exit();
}
?>
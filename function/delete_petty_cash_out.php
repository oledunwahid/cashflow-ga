<?php
require_once("../koneksi.php");

if (isset($_GET["id_pc_out"])) {
    $id_pc_out = $_GET["id_pc_out"];

    // Change from DELETE to UPDATE to implement soft delete
    $query = "UPDATE petty_cash_out SET is_deleted = 1 WHERE id_pc_out = '$id_pc_out'";

    // Execute the query
    $result = mysqli_query($koneksi, $query);

    // Check if query was successful
    if ($result) {
        session_start();
        $_SESSION["Messages"] = 'Data berhasil dihapus';
        $_SESSION["Icon"] = 'success';
    } else {
        session_start();
        $_SESSION["Messages"] = 'Data gagal dihapus';
        $_SESSION["Icon"] = 'error';
    }

    header('Location: ../index.php?page=PettyCashOut');
    exit();
} else {
    header('Location: ../index.php?page=Dashboard');
    exit();
}

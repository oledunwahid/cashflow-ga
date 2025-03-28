<?php
require_once("../koneksi.php");

if (isset($_GET['id_pc_in'])) {
    $id_pc_in = $_GET['id_pc_in'];

    // Query to delete petty cash in data from database
    $query = "UPDATE petty_cash_in SET is_deleted = 1 WHERE id_pc_in = '$id_pc_in'";

    // Execute query
    $kondisi = mysqli_query($koneksi, $query);

    // Check if query was successful
    if ($kondisi) {
        session_start();
        $_SESSION["Messages"] = 'Data Petty Cash In Berhasil Dihapus';
        $_SESSION["Icon"] = 'success';
        header('Location: ../index.php?page=PettyCashIn');
        exit();
    } else {
        session_start();
        $_SESSION["Messages"] = 'Data Petty Cash In Gagal Dihapus';
        $_SESSION["Icon"] = 'error';
        header('Location: ../index.php?page=PettyCashIn');
        exit();
    }
} else {
    header('Location: ../index.php?page=Dashboard');
    exit();
}

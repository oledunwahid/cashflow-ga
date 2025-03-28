<?php
require_once("../koneksi.php");
if (isset($_POST["update-petty-cash-in"])) {
    // Data from form
    $id_pc_in = $_POST["id_pc_in"];
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST["deskripsi"]);
    $date = $_POST["date"];
    $harga = $_POST["harga_clean"] ? $_POST["harga_clean"] : $_POST["harga"]; // Use clean value if available
    $company = $_POST["company"];
    
    // Query to update petty cash in data in database
    $query = "UPDATE petty_cash_in 
              SET deskripsi = '$deskripsi', 
                  date = '$date', 
                  harga = '$harga', 
                  company = '$company' 
              WHERE id_pc_in = '$id_pc_in'";
    
    // Execute query
    $kondisi = mysqli_query($koneksi, $query);
    
    // Check if query was successful
    if ($kondisi) {
        session_start();
        $_SESSION["Messages"] = 'Data Petty Cash In Berhasil Diupdate';
        $_SESSION["Icon"] = 'success';
        header('Location: ../index.php?page=PettyCashIn');
        exit();
    } else {
        session_start();
        $_SESSION["Messages"] = 'Data Petty Cash In Gagal Diupdate';
        $_SESSION["Icon"] = 'error';
        header('Location: ../index.php?page=PettyCashIn');
        exit();
    }
} else {
    header('Location: ../index.php?page=Dashboard');
    exit();
}
?>
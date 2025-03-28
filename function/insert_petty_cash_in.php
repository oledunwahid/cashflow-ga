<?php
require_once("../koneksi.php");
if (isset($_POST["add-petty-cash-in"])) {
    // Data from form
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST["deskripsi"]);
    $date = $_POST["date"];
    $harga = $_POST["harga_clean"] ? $_POST["harga_clean"] : $_POST["harga"]; // Use clean value if available
    $company =$_POST["company"];
    
    // Query to insert petty cash in data into database
    $query = "INSERT INTO petty_cash_in (deskripsi, date, harga, company) 
              VALUES ('$deskripsi', '$date', '$harga', '$company')";
    
    // Execute query
    $kondisi = mysqli_query($koneksi, $query);
    
    // Check if query was successful
    if ($kondisi) {
        session_start();
        $_SESSION["Messages"] = 'Data Petty Cash In Berhasil Di Input';
        $_SESSION["Icon"] = 'success';
        header('Location: ../index.php?page=PettyCashIn');
        exit();
    } else {
        session_start();
        $_SESSION["Messages"] = 'Data Petty Cash In Gagal Di Input';
        $_SESSION["Icon"] = 'error';
        header('Location: ../index.php?page=PettyCashIn');
        exit();
    }
} else {
    header('Location: ../index.php?page=Dashboard');
    exit();
}
?>
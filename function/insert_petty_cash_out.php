<?php
require_once("../koneksi.php");
if (isset($_POST["add-petty-cash"])) {
    // Data from form
    $deskripsi = $_POST["deskripsi"];
    $date = $_POST["date"];
    $harga = $_POST["harga_clean"] ? $_POST["harga_clean"] : $_POST["harga"]; // Use clean value if available
    $company = $_POST["company"];
    $kategori = $_POST["kategori"];
    $status = 'Unsettled';
	$request = $_POST["request"];
    
    // Query to insert petty cash data into database
    // Note: We're not setting id_pt as it's auto-increment
    $query = "INSERT INTO petty_cash_out (deskripsi, date, harga, company, kategori, status, request) 
              VALUES ('$deskripsi', '$date', '$harga', '$company', '$kategori', '$status', '$request')";
    
    // Execute query
    $kondisi = mysqli_query($koneksi, $query);
    
    // Check if query was successful
    if ($kondisi) {
        session_start();
        $_SESSION["Messages"] = 'Data Petty Cash Berhasil Di Input';
        $_SESSION["Icon"] = 'success';
        header('Location: ../index.php?page=PettyCashOut');
        exit();
    } else {
        session_start();
        $_SESSION["Messages"] = 'Data Petty Cash Gagal Di Input';
        $_SESSION["Icon"] = 'error';
        header('Location: ../index.php?page=PettyCashOut');
        exit();
    }
} else {
    header('Location: ../index.php?page=Dashboard');
    exit();
}
?>
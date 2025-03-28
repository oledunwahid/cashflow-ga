<?php
require_once("../koneksi.php");
if (isset($_POST["add-ca"])) {
    // Data dari form
    // Note: id_ca will be auto-incremented by the database
    $deskripsi = $_POST["deskripsi"]; 
    $kategori = $_POST["kategori"]; 
    $request = $_POST["request"]; 
    $date_request = $_POST["date_request"];
    
    // Clean jumlah_request to ensure it has no formatting characters
    $jumlah_request = preg_replace('/[^\d]/', '', $_POST["jumlah_request"]);
    
    // Set default values
    $jumlah_actual = 0;
    $date_settlement = null;
    $status = "Pending";
    $bukti_pengembalian = null;
    
    // Query untuk memasukkan data cash advance ke dalam database
    $query = "INSERT INTO cash_advance (deskripsi, kategori, request, date_request, jumlah_request, jumlah_actual, date_settlement, status, bukti_pengembalian) 
              VALUES ('$deskripsi', '$kategori', '$request', '$date_request', '$jumlah_request', '$jumlah_actual', NULL, '$status', '$bukti_pengembalian')";
    
    // Eksekusi query
    $kondisi = mysqli_query($koneksi, $query);
    
    // Cek jika query berhasil
    if ($kondisi) {
        session_start();
        $_SESSION["Messages"] = 'Cash Advance Request Successfully Submitted';
        $_SESSION["Icon"] = 'success';
        header('Location: ../index.php?page=CashAdvance');
        exit();
    } else {
        session_start();
        $_SESSION["Messages"] = 'Failed to Submit Cash Advance Request';
        $_SESSION["Icon"] = 'error';
        header('Location: ../index.php?page=CashAdvance');
        exit();
    }
} else {
    header('Location: ../index.php?page=Dashboard');
    exit();
}
?>
<?php
require_once("../koneksi.php");
if (isset($_POST["update-ca"])) {
    $id_ca = $_POST["id_ca"];
    $deskripsi = $_POST["deskripsi"];
    $kategori = $_POST["kategori"];
    $request = $_POST["request"];
    $date_request = $_POST["date_request"];
    
    // Clean jumlah_request to ensure it has no formatting characters
    $jumlah_request = preg_replace('/[^\d]/', '', $_POST["jumlah_request"]);
    
    $status = $_POST["status"];
    
    $query = "UPDATE cash_advance 
              SET deskripsi='$deskripsi', 
                  kategori='$kategori', 
                  request='$request', 
                  date_request='$date_request', 
                  jumlah_request='$jumlah_request', 
                  status='$status'
              WHERE id_ca='$id_ca'";
    
    $result = mysqli_query($koneksi, $query);
    if ($result) {
        session_start();
        $_SESSION["Messages"] = 'Cash Advance data successfully updated';
        $_SESSION["Icon"] = 'success';
    } else {
        session_start();
        $_SESSION["Messages"] = 'Failed to update Cash Advance data';
        $_SESSION["Icon"] = 'error';
    }
    header('Location: ../index.php?page=CashAdvance');
    exit();
} else {
    header('Location: ../index.php?page=Dashboard');
    exit();
}
?>
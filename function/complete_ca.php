<?php
require_once("../koneksi.php");

if (isset($_GET["id_ca"])) {
    $id_ca = $_GET["id_ca"];
    
    // Update the cash advance record status to Done
    $query = "UPDATE cash_advance SET status='Done' WHERE id_ca='$id_ca'";
    $result = mysqli_query($koneksi, $query);
    
    session_start();
    if ($result) {
        $_SESSION["Messages"] = 'Cash Advance has been marked as completed';
        $_SESSION["Icon"] = 'success';
    } else {
        $_SESSION["Messages"] = 'Failed to complete Cash Advance: ' . mysqli_error($koneksi);
        $_SESSION["Icon"] = 'error';
    }
    
    header('Location: ../index.php?page=CashAdvance');
    exit();
} else {
    header('Location: ../index.php?page=Dashboard');
    exit();
}
?>
<?php
require_once("../koneksi.php");

if (isset($_GET["id_ca"])) {
    $id_ca = $_GET["id_ca"];
    
    // Check if file exists before deleting
    $query_check = "SELECT bukti_pengembalian FROM cash_advance WHERE id_ca='$id_ca'";
    $result_check = mysqli_query($koneksi, $query_check);
    
    if ($result_check && mysqli_num_rows($result_check) > 0) {
        $row = mysqli_fetch_assoc($result_check);
        $bukti_file = $row['bukti_pengembalian'];
        
        // If there's a file attached, delete it
        if ($bukti_file && file_exists("../uploads/bukti/" . $bukti_file)) {
            unlink("../uploads/bukti/" . $bukti_file);
        }
    }
    
    // Delete the cash advance record
    $query = "DELETE FROM cash_advance WHERE id_ca='$id_ca'";
    $result = mysqli_query($koneksi, $query);
    
    session_start();
    if ($result) {
        $_SESSION["Messages"] = 'Cash Advance successfully deleted';
        $_SESSION["Icon"] = 'success';
    } else {
        $_SESSION["Messages"] = 'Failed to delete Cash Advance';
        $_SESSION["Icon"] = 'error';
    }
    
    header('Location: ../index.php?page=CashAdvance');
    exit();
} else {
    header('Location: ../index.php?page=Dashboard');
    exit();
}
?>
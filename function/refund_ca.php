<?php
require_once("../koneksi.php");
if (isset($_POST["refund-ca"])) {
    $id_ca = $_POST["id_ca"];
    
    // Clean refund_amount to ensure it has no formatting characters
    $refund_amount = preg_replace('/[^\d]/', '', $_POST["refund_amount"]);
    
    $refund_date = $_POST["refund_date"];
    $refund_notes = $_POST["refund_notes"];
    
    // Handle file upload if a file was submitted
    if (isset($_FILES['bukti_refund']) && $_FILES['bukti_refund']['error'] == 0) {
        $upload_dir = "../file/bukti/";
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Get file extension
        $file_extension = pathinfo($_FILES['bukti_refund']['name'], PATHINFO_EXTENSION);
        
        // Generate a unique filename
        $new_filename = "refund_" . $id_ca . "_" . date("YmdHis") . "." . $file_extension;
        $target_file = $upload_dir . $new_filename;
        
        // Move uploaded file to destination
        if (move_uploaded_file($_FILES['bukti_refund']['tmp_name'], $target_file)) {
            $bukti_pengembalian = $new_filename;
        } else {
            session_start();
            $_SESSION["Messages"] = 'Failed to upload refund proof file';
            $_SESSION["Icon"] = 'error';
            header('Location: ../index.php?page=CashAdvance');
            exit();
        }
    } else {
        // No file uploaded
        $bukti_pengembalian = "";
    }
    
    // Update the cash advance record with refund information
    $query = "UPDATE cash_advance 
              SET status='Refund',
                  refund_date='$refund_date',
                  refund_notes='$refund_notes'";
                  
    // Add bukti_pengembalian to query if a file was uploaded
    if (!empty($bukti_pengembalian)) {
        $query .= ", bukti_pengembalian='$bukti_pengembalian'";
    }
    
    $query .= " WHERE id_ca='$id_ca'";
    
    // Debug - log the query
    file_put_contents("../debug_log.txt", "Refund Query: $query\n", FILE_APPEND);
    
    $result = mysqli_query($koneksi, $query);
    
    session_start();
    if ($result) {
        $_SESSION["Messages"] = 'Cash Advance refund has been successfully processed';
        $_SESSION["Icon"] = 'success';
    } else {
        $_SESSION["Messages"] = 'Failed to process refund: ' . mysqli_error($koneksi);
        $_SESSION["Icon"] = 'error';
        // Log the error
        file_put_contents("../debug_log.txt", "Refund Error: " . mysqli_error($koneksi) . "\n", FILE_APPEND);
    }
    
    header('Location: ../index.php?page=CashAdvance');
    exit();
} else {
    header('Location: ../index.php?page=Dashboard');
    exit();
}
?>
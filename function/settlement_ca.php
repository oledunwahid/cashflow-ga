<?php
require_once("../koneksi.php");
if (isset($_POST["settlement-ca"])) {
    $id_ca = $_POST["id_ca"];
    
    // Clean jumlah_actual to ensure it has no formatting characters
    $jumlah_actual = preg_replace('/[^\d]/', '', $_POST["jumlah_actual"]);
    
    $date_settlement = $_POST["date_settlement"];
    $bukti_nota = ""; // Variable for the receipt file
    
    // Handle file upload if a file was submitted
    if (isset($_FILES['bukti_pengeluaran']) && $_FILES['bukti_pengeluaran']['error'] == 0) {
        $upload_dir = "../file/bukti_nota/";
        
        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        
        // Get file extension
        $file_extension = pathinfo($_FILES['bukti_pengeluaran']['name'], PATHINFO_EXTENSION);
        
        // Generate a unique filename
        $new_filename = "nota_" . $id_ca . "_" . date("YmdHis") . "." . $file_extension;
        $target_file = $upload_dir . $new_filename;
        
        // Move uploaded file to destination
        if (move_uploaded_file($_FILES['bukti_pengeluaran']['tmp_name'], $target_file)) {
            $bukti_nota = $new_filename;
        } else {
            session_start();
            $_SESSION["Messages"] = 'Failed to upload receipt file';
            $_SESSION["Icon"] = 'error';
            header('Location: ../index.php?page=CashAdvance');
            exit();
        }
    }
    
    // Check the difference between request and actual amounts
    $check_query = "SELECT jumlah_request FROM cash_advance WHERE id_ca='$id_ca'";
    $check_result = mysqli_query($koneksi, $check_query);
    $row = mysqli_fetch_assoc($check_result);
    $jumlah_request = $row['jumlah_request'];
    
    $selisih = $jumlah_request - $jumlah_actual;
    
    // Always set to "Settled" after settlement is done
    $new_status = "Settled";
    
    // Update the cash advance record with settlement information
    $query = "UPDATE cash_advance 
              SET jumlah_actual='$jumlah_actual', 
                  date_settlement='$date_settlement',
                  status='$new_status'";
                  
    // Add bukti_nota to query only if a file was uploaded
    if (!empty($bukti_nota)) {
        $query .= ", bukti_nota='$bukti_nota'";
    }
    
    $query .= " WHERE id_ca='$id_ca'";
    
    // Debug log
    file_put_contents("../debug_log.txt", "Settlement Query: $query\n", FILE_APPEND);
    
    $result = mysqli_query($koneksi, $query);
    
    session_start();
    if ($result) {
        $_SESSION["Messages"] = 'Cash Advance has been successfully settled';
        $_SESSION["Icon"] = 'success';
    } else {
        $_SESSION["Messages"] = 'Failed to settle Cash Advance: ' . mysqli_error($koneksi);
        $_SESSION["Icon"] = 'error';
        // Log the error
        file_put_contents("../debug_log.txt", "Settlement Error: " . mysqli_error($koneksi) . "\n", FILE_APPEND);
    }
    
    header('Location: ../index.php?page=CashAdvance');
    exit();
} else {
    header('Location: ../index.php?page=Dashboard');
    exit();
}
?>
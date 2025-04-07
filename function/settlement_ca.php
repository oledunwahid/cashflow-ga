<?php
require_once("../koneksi.php");
if (isset($_POST["settlement-ca"])) {
    $id_ca = $_POST["id_ca"];

    // Clean jumlah_actual to ensure it has no formatting characters
    $jumlah_actual = preg_replace('/[^\d]/', '', $_POST["jumlah_actual"]);

    $date_settlement = $_POST["date_settlement"];
    $bukti_nota = ""; // Variable for the receipt file

    // Generate shorter settlement ID for tracking
    // Format: [Kategori][YearMonth][ID-CA]
    // Get category from database
    $cat_query = "SELECT kategori FROM cash_advance WHERE id_ca=?";
    $cat_stmt = mysqli_prepare($koneksi, $cat_query);
    mysqli_stmt_bind_param($cat_stmt, "i", $id_ca);
    mysqli_stmt_execute($cat_stmt);
    $cat_result = mysqli_stmt_get_result($cat_stmt);
    $cat_row = mysqli_fetch_assoc($cat_result);

    // Get category code (first 3 letters)
    $category_code = substr(strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $cat_row['kategori'])), 0, 3);
    if (empty($category_code)) $category_code = "STL";

    // Get current year and month (2 digits each)
    $current_year = date("y");
    $current_month = date("m");

    // Generate settlement ID
    $settlement_id = $category_code . $current_year . $current_month . $id_ca;

    // Log the settlement ID creation
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - Generated settlement ID: $settlement_id for CA ID: $id_ca\n", FILE_APPEND);

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
            header('Location: /index.php?page=CashAdvance');
            exit();
        }
    }

    // Check the difference between request and actual amounts
    $check_query = "SELECT jumlah_request, status FROM cash_advance WHERE id_ca=?";
    $stmt = mysqli_prepare($koneksi, $check_query);
    mysqli_stmt_bind_param($stmt, "i", $id_ca);
    mysqli_stmt_execute($stmt);
    $check_result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($check_result);
    $jumlah_request = $row['jumlah_request'];
    $current_status = $row['status'];

    $selisih = $jumlah_request - $jumlah_actual;

    // Pastikan status tidak berubah dari Approved saat pertama kali settlement
    // Status akan diubah saat settlement selesai di complete_settlement.php

    // Update the cash advance record with settlement information
    $query = "UPDATE cash_advance 
              SET jumlah_actual=?, 
                  date_settlement=?,
                  id_settlement=?";

    // Add bukti_nota to query only if a file was uploaded
    if (!empty($bukti_nota)) {
        $query .= ", bukti_nota=?";
    }

    $query .= " WHERE id_ca=?";

    // Debug log
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - Settlement Query: $query\n", FILE_APPEND);

    $stmt = mysqli_prepare($koneksi, $query);

    if (!empty($bukti_nota)) {
        mysqli_stmt_bind_param($stmt, "isssi", $jumlah_actual, $date_settlement, $settlement_id, $bukti_nota, $id_ca);
    } else {
        mysqli_stmt_bind_param($stmt, "issi", $jumlah_actual, $date_settlement, $settlement_id, $id_ca);
    }

    $result = mysqli_stmt_execute($stmt);

    session_start();
    if ($result) {
        $_SESSION["Messages"] = 'Cash Advance settlement details have been saved. Please add your expense details to complete the settlement.';
        $_SESSION["Icon"] = 'success';

        // Redirect to the settlement details page instead of the main cash advance page
        header('Location: ../index.php?page=SettlementDetails&id_ca=' . $id_ca . '&id_settlement=' . $settlement_id);
        exit();
    } else {
        $_SESSION["Messages"] = 'Failed to settle Cash Advance: ' . mysqli_error($koneksi);
        $_SESSION["Icon"] = 'error';
        // Log the error
        file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - Settlement Error: " . mysqli_error($koneksi) . "\n", FILE_APPEND);
        header('Location: ../index.php?page=CashAdvance');
        exit();
    }
} else {
    header('Location: ../index.php?page=Dashboard');
    exit();
}

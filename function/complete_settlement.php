<?php
// complete_settlement.php
require_once("../koneksi.php");
session_start();

// Log semua request
error_log("COMPLETE SETTLEMENT REQUEST: " . print_r($_POST, true));
file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - REQUEST DATA: " . print_r($_POST, true) . "\n", FILE_APPEND);

// Cek metode request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION["Messages"] = 'Method tidak valid. Gunakan POST.';
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=CashAdvance');
    exit();
}

// Validasi parameter
if (empty($_POST['id_ca']) || empty($_POST['id_settlement'])) {
    $_SESSION["Messages"] = 'Parameter tidak lengkap. ID CA dan Settlement ID diperlukan.';
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=CashAdvance');
    exit();
}

// Data parameter
$id_ca = $_POST['id_ca'];
$id_settlement = $_POST['id_settlement'];

// Log request data
file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - Processing settlement - CA ID: $id_ca, Settlement ID: $id_settlement\n", FILE_APPEND);

// 1. Hitung total detail yang aktif
$query_check = "SELECT SUM(total_price) as total_details FROM detailed_cash_advance 
                WHERE id_ca = ? AND id_settlement = ? AND is_deleted = 0";

$stmt_check = mysqli_prepare($koneksi, $query_check);

if (!$stmt_check) {
    $error = mysqli_error($koneksi);
    $_SESSION["Messages"] = 'Database error: ' . $error;
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=CashAdvance');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR preparing query: $error\n", FILE_APPEND);
    exit();
}

mysqli_stmt_bind_param($stmt_check, "is", $id_ca, $id_settlement);
$execute_result = mysqli_stmt_execute($stmt_check);

if (!$execute_result) {
    $error = mysqli_error($koneksi);
    $_SESSION["Messages"] = 'Database error: ' . $error;
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=CashAdvance');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR executing query: $error\n", FILE_APPEND);
    exit();
}

$result_check = mysqli_stmt_get_result($stmt_check);
$row_check = mysqli_fetch_assoc($result_check);

// 2. Ambil data cash advance
$query_ca = "SELECT jumlah_actual, status FROM cash_advance WHERE id_ca = ?";
$stmt_ca = mysqli_prepare($koneksi, $query_ca);

if (!$stmt_ca) {
    $error = mysqli_error($koneksi);
    $_SESSION["Messages"] = 'Database error: ' . $error;
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=CashAdvance');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR preparing CA query: $error\n", FILE_APPEND);
    exit();
}

mysqli_stmt_bind_param($stmt_ca, "i", $id_ca);
$execute_result = mysqli_stmt_execute($stmt_ca);

if (!$execute_result) {
    $error = mysqli_error($koneksi);
    $_SESSION["Messages"] = 'Database error: ' . $error;
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=CashAdvance');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR executing CA query: $error\n", FILE_APPEND);
    exit();
}

$result_ca = mysqli_stmt_get_result($stmt_ca);
$row_ca = mysqli_fetch_assoc($result_ca);

if (!$row_ca) {
    $_SESSION["Messages"] = 'Data cash advance tidak ditemukan';
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=CashAdvance');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR: Cash advance not found for ID: $id_ca\n", FILE_APPEND);
    exit();
}

$total_details = $row_check['total_details'] ?: 0;
$actual_amount = $row_ca['jumlah_actual'];

// Log values
file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - Total details: $total_details, Actual amount: $actual_amount\n", FILE_APPEND);

// Cek apakah ada detail
if ($total_details == 0) {
    $_SESSION["Messages"] = 'Anda harus menambahkan setidaknya satu item';
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=CashAdvance');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR: No settlement details found\n", FILE_APPEND);
    exit();
}

// Hitung selisih untuk menentukan apakah dana tersisa atau kurang
$difference = $actual_amount - $total_details;

// Tentukan status baru - Pertama ubah ke Settled, kemudian tentukan final status
$status = 'Settled'; // Default status saat ini

// STEP 1: Ubah status ke Settled
$query_update_settled = "UPDATE cash_advance SET status = ? WHERE id_ca = ?";
$stmt_update_settled = mysqli_prepare($koneksi, $query_update_settled);

if (!$stmt_update_settled) {
    $error = mysqli_error($koneksi);
    $_SESSION["Messages"] = 'Database error: ' . $error;
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=CashAdvance');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR preparing settled update query: $error\n", FILE_APPEND);
    exit();
}

mysqli_stmt_bind_param($stmt_update_settled, "si", $status, $id_ca);
$execute_result_settled = mysqli_stmt_execute($stmt_update_settled);

if (!$execute_result_settled) {
    $error = mysqli_error($koneksi);
    $_SESSION["Messages"] = 'Database error: ' . $error;
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=CashAdvance');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR executing settled update query: $error\n", FILE_APPEND);
    exit();
}

// Log settled status
file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - Updated status to Settled for CA ID: $id_ca\n", FILE_APPEND);

// Tentukan status final berdasarkan selisih
$final_status = 'Done'; // Default final status
$refund_note = '';

if ($difference > 0) {
    $final_status = 'Refund';
    $refund_note = "Refund amount: Rp " . number_format($difference, 0, ',', '.');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - Refund case - Amount: $difference\n", FILE_APPEND);
} elseif ($difference < 0) {
    $final_status = 'Done';
    $refund_note = "Exceeded budget by: Rp " . number_format(abs($difference), 0, ',', '.');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - Exceeded budget case - Amount: " . abs($difference) . "\n", FILE_APPEND);
}

// STEP 2: Jika perlu refund atau done, update lagi
if ($final_status !== 'Settled') {
    // Update status
    $query_update_final = "UPDATE cash_advance SET status = ?, refund_notes = ? WHERE id_ca = ?";
    $stmt_update_final = mysqli_prepare($koneksi, $query_update_final);

    if (!$stmt_update_final) {
        $error = mysqli_error($koneksi);
        $_SESSION["Messages"] = 'Database error: ' . $error;
        $_SESSION["Icon"] = 'error';
        header('Location: ../index.php?page=CashAdvance');
        file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR preparing final update query: $error\n", FILE_APPEND);
        exit();
    }

    mysqli_stmt_bind_param($stmt_update_final, "ssi", $final_status, $refund_note, $id_ca);
    $execute_result_final = mysqli_stmt_execute($stmt_update_final);

    if (!$execute_result_final) {
        $error = mysqli_error($koneksi);
        $_SESSION["Messages"] = 'Database error: ' . $error;
        $_SESSION["Icon"] = 'error';
        header('Location: ../index.php?page=CashAdvance');
        file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR executing final update query: $error\n", FILE_APPEND);
        exit();
    }

    // Log final status update
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - Updated status from Settled to $final_status for CA ID: $id_ca\n", FILE_APPEND);
    $status = $final_status; // Update status for message
}

// Log sukses
$message = "Settlement completed for Cash Advance ID: $id_ca, Settlement ID: $id_settlement, Total Details: Rp " . number_format($total_details, 0, ',', '.') . ", Final Status: $status";
file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - SUCCESS: $message\n", FILE_APPEND);

// Set success message and redirect
$_SESSION["Messages"] = 'Settlement berhasil diselesaikan dengan status ' . $status;
$_SESSION["Icon"] = 'success';
header('Location: ../index.php?page=CashAdvance');
exit();

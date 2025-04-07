<?php
// delete_settlement_detail.php
require_once("../koneksi.php");
session_start();

// Log semua request
error_log("DELETE SETTLEMENT DETAIL REQUEST: " . print_r($_POST, true));
file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - DELETE DETAIL REQUEST DATA: " . print_r($_POST, true) . "\n", FILE_APPEND);

// Cek metode request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION["Messages"] = 'Method tidak valid. Gunakan POST.';
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=CashAdvance');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR: Invalid request method\n", FILE_APPEND);
    exit();
}

// Validasi parameter
if (empty($_POST['id_detail'])) {
    $_SESSION["Messages"] = 'Parameter id_detail tidak ditemukan';
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=CashAdvance');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR: Missing id_detail parameter\n", FILE_APPEND);
    exit();
}

// Get the detail ID
$id_detail = intval($_POST['id_detail']);

// Validasi nilai id_detail
if ($id_detail <= 0) {
    $_SESSION["Messages"] = 'ID detail tidak valid';
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=CashAdvance');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR: Invalid id_detail value: $id_detail\n", FILE_APPEND);
    exit();
}

// Log request
file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - Processing delete detail ID: $id_detail\n", FILE_APPEND);

// Get associated information before deletion (for logging)
$query_info = "SELECT id_ca, id_settlement, item_name, total_price FROM detailed_cash_advance WHERE id_detail = ?";
$stmt_info = mysqli_prepare($koneksi, $query_info);

if (!$stmt_info) {
    $error = mysqli_error($koneksi);
    $_SESSION["Messages"] = 'Database error: ' . $error;
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=CashAdvance');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR preparing info query: $error\n", FILE_APPEND);
    exit();
}

mysqli_stmt_bind_param($stmt_info, "i", $id_detail);
$execute_result = mysqli_stmt_execute($stmt_info);

if (!$execute_result) {
    $error = mysqli_error($koneksi);
    $_SESSION["Messages"] = 'Database error: ' . $error;
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=CashAdvance');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR executing info query: $error\n", FILE_APPEND);
    exit();
}

$result_info = mysqli_stmt_get_result($stmt_info);
$row_info = mysqli_fetch_assoc($result_info);

if (!$row_info) {
    $_SESSION["Messages"] = 'Detail item tidak ditemukan';
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=CashAdvance');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR: Detail item not found for ID: $id_detail\n", FILE_APPEND);
    exit();
}

$id_ca = $row_info['id_ca'];
$id_settlement = $row_info['id_settlement'];
$item_name = $row_info['item_name'];
$total_price = $row_info['total_price'];

// Log item info
file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - Found item: CA ID=$id_ca, Settlement ID=$id_settlement, Item=$item_name, Price=$total_price\n", FILE_APPEND);

// Soft delete by setting is_deleted to 1
$query = "UPDATE detailed_cash_advance SET is_deleted = 1, deleted_date = NOW() WHERE id_detail = ?";
$stmt = mysqli_prepare($koneksi, $query);

if (!$stmt) {
    $error = mysqli_error($koneksi);
    $_SESSION["Messages"] = 'Database error: ' . $error;
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=SettlementDetails&id_ca=' . $id_ca . '&id_settlement=' . $id_settlement);
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR preparing delete query: $error\n", FILE_APPEND);
    exit();
}

mysqli_stmt_bind_param($stmt, "i", $id_detail);
$execute_result = mysqli_stmt_execute($stmt);

if ($execute_result) {
    // Log the deletion
    $log_message = "Deleted item from settlement - CA ID: $id_ca, Settlement ID: $id_settlement, Item: $item_name, Price: Rp " . number_format($total_price, 0, ',', '.');
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - SUCCESS: $log_message\n", FILE_APPEND);

    $_SESSION["Messages"] = 'Item berhasil dihapus';
    $_SESSION["Icon"] = 'success';
    header('Location: ../index.php?page=SettlementDetails&id_ca=' . $id_ca . '&id_settlement=' . $id_settlement);
    exit();
} else {
    // Log error
    $error = mysqli_error($koneksi);
    file_put_contents("../debug_log.txt", date('Y-m-d H:i:s') . " - ERROR executing delete query: $error\n", FILE_APPEND);

    $_SESSION["Messages"] = 'Gagal menghapus item: ' . $error;
    $_SESSION["Icon"] = 'error';
    header('Location: ../index.php?page=SettlementDetails&id_ca=' . $id_ca . '&id_settlement=' . $id_settlement);
    exit();
}

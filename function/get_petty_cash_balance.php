<?php
// Include database connection
require_once '../koneksi.php';

header('Content-Type: application/json');

try {
    // Query to get total from petty_cash_in
    // Added condition to filter out deleted records (if petty_cash_in also has is_deleted field)
    $query_in = "SELECT SUM(harga) as total_in FROM petty_cash_in WHERE (is_deleted = 0 OR is_deleted IS NULL)";
    $result_in = mysqli_query($koneksi, $query_in);

    if (!$result_in) {
        throw new Exception(mysqli_error($koneksi));
    }

    $row_in = mysqli_fetch_assoc($result_in);
    $total_in = $row_in['total_in'] ? $row_in['total_in'] : 0;

    // Query to get total from petty_cash_out
    // Added condition to filter out deleted records
    $query_out = "SELECT SUM(harga) as total_out FROM petty_cash_out WHERE (is_deleted = 0 OR is_deleted IS NULL)";
    $result_out = mysqli_query($koneksi, $query_out);

    if (!$result_out) {
        throw new Exception(mysqli_error($koneksi));
    }

    $row_out = mysqli_fetch_assoc($result_out);
    $total_out = $row_out['total_out'] ? $row_out['total_out'] : 0;

    // Calculate balance
    $balance = $total_in - $total_out;

    echo json_encode(['balance' => $balance, 'total_in' => $total_in, 'total_out' => $total_out]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

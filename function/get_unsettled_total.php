<?php
// Include database connection
require_once '../koneksi.php';

header('Content-Type: application/json');

try {
    // Query to get total harga from petty_cash_out where status is 'Unsettled'
    // Added condition to filter out deleted records
    $query = "SELECT SUM(harga) as total FROM petty_cash_out WHERE status = 'Unsettled' AND (is_deleted = 0 OR is_deleted IS NULL)";
    $result = mysqli_query($koneksi, $query);

    if (!$result) {
        throw new Exception(mysqli_error($koneksi));
    }

    $row = mysqli_fetch_assoc($result);
    $total = $row['total'] ? $row['total'] : 0;

    echo json_encode(['total' => $total]);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}

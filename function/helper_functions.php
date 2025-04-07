<?php
// Function to check if a Cash Advance has settlement in progress
function checkSettlementInProgress($id_ca, $koneksi)
{
    $query = "SELECT id_settlement, status FROM cash_advance WHERE id_ca = ? AND id_settlement IS NOT NULL";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_ca);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        if ($row['status'] == 'Approved' || $row['status'] == 'Pending') {
            return $row['id_settlement'];
        }
    }

    return false;
}

// Function to get Cash Advance details for the settlement page
function getCashAdvanceDetails($id_ca, $koneksi)
{
    $query = "SELECT ca.*, u.nama AS requestor_name 
              FROM cash_advance ca
              LEFT JOIN user u ON ca.request = u.idnik
              WHERE ca.id_ca = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "i", $id_ca);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}

// Function to get settlement details
function getSettlementDetails($id_ca, $id_settlement, $koneksi)
{
    $query = "SELECT * FROM detailed_cash_advance 
              WHERE id_ca = ? AND id_settlement = ? AND is_deleted = 0
              ORDER BY created_date ASC";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "is", $id_ca, $id_settlement);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $details = [];
    $total_details = 0;

    while ($row = mysqli_fetch_assoc($result)) {
        $details[] = $row;
        $total_details += $row['total_price'];
    }

    return ['details' => $details, 'total_details' => $total_details];
}

// Function to update the CA status
function updateCashAdvanceStatus($id_ca, $status, $koneksi)
{
    $query = "UPDATE cash_advance SET status = ? WHERE id_ca = ?";
    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "si", $status, $id_ca);
    $result = mysqli_stmt_execute($stmt);

    return $result;
}

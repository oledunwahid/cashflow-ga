<?php
// cashflow_dashboard_functions.php

/**
 * Get Cash Advance data for dashboard
 * 
 * @param mysqli $koneksi Database connection
 * @param string $start_date Start date in Y-m-d format (optional)
 * @param string $end_date End date in Y-m-d format (optional)
 * @return array Array of Cash Advance data
 */
function getCashAdvanceForDashboard($koneksi, $start_date = null, $end_date = null)
{
    // Default to current month if no dates provided
    if (is_null($start_date)) {
        $start_date = date('Y-m-01'); // First day of current month
    }

    if (is_null($end_date)) {
        $end_date = date('Y-m-t'); // Last day of current month
    }

    $query = "SELECT 
                ca.id_ca,
                ca.deskripsi,
                ca.kategori,
                ca.jumlah_request,
                ca.jumlah_actual,
                ca.date_request,
                ca.date_settlement,
                ca.status,
                ca.id_settlement,
                ca.refund_notes,
                u.nama AS requestor_name,
                (SELECT SUM(total_price) FROM detailed_cash_advance 
                 WHERE id_ca = ca.id_ca AND id_settlement = ca.id_settlement AND is_deleted = 0) as total_details
              FROM 
                cash_advance ca
              LEFT JOIN 
                user u ON ca.request = u.idnik
              WHERE 
                (ca.status = 'Settled' OR ca.status = 'Refund' OR ca.status = 'Done') 
                AND (ca.date_settlement BETWEEN ? AND ?)
              ORDER BY 
                ca.date_settlement DESC";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Add calculated properties
        $row['total_details'] = !is_null($row['total_details']) ? $row['total_details'] : 0;
        $row['difference'] = $row['jumlah_actual'] - $row['total_details'];

        // Format dates for display
        $row['formatted_request_date'] = date('d M Y', strtotime($row['date_request']));
        $row['formatted_settlement_date'] = date('d M Y', strtotime($row['date_settlement']));

        $data[] = $row;
    }

    return $data;
}

/**
 * Get Cash Advance summary statistics for dashboard
 * 
 * @param mysqli $koneksi Database connection
 * @param string $start_date Start date in Y-m-d format (optional)
 * @param string $end_date End date in Y-m-d format (optional)
 * @return array Array of summary statistics
 */
function getCashAdvanceSummary($koneksi, $start_date = null, $end_date = null)
{
    // Default to current month if no dates provided
    if (is_null($start_date)) {
        $start_date = date('Y-m-01'); // First day of current month
    }

    if (is_null($end_date)) {
        $end_date = date('Y-m-t'); // Last day of current month
    }

    $query = "SELECT 
                COUNT(*) as total_transactions,
                SUM(CASE WHEN status = 'Settled' THEN 1 ELSE 0 END) as settled_count,
                SUM(CASE WHEN status = 'Refund' THEN 1 ELSE 0 END) as refund_count,
                SUM(CASE WHEN status = 'Done' THEN 1 ELSE 0 END) as done_count,
                SUM(jumlah_request) as total_requested,
                SUM(jumlah_actual) as total_actual,
                SUM(COALESCE(jumlah_actual, 0) - 
                    COALESCE((SELECT SUM(total_price) FROM detailed_cash_advance 
                    WHERE id_ca = cash_advance.id_ca AND id_settlement = cash_advance.id_settlement AND is_deleted = 0), 0)
                ) as total_difference
              FROM 
                cash_advance
              WHERE 
                (status = 'Settled' OR status = 'Refund' OR status = 'Done') 
                AND (date_settlement BETWEEN ? AND ?)";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    return mysqli_fetch_assoc($result);
}

/**
 * Render Cash Advance summary widget for dashboard
 * 
 * @param mysqli $koneksi Database connection
 * @param string $start_date Start date in Y-m-d format (optional)
 * @param string $end_date End date in Y-m-d format (optional)
 * @return string HTML for the widget
 */
function renderCashAdvanceWidget($koneksi, $start_date = null, $end_date = null)
{
    $summary = getCashAdvanceSummary($koneksi, $start_date, $end_date);

    // Format numbers for display
    $total_requested = number_format($summary['total_requested'], 0, ',', '.');
    $total_actual = number_format($summary['total_actual'], 0, ',', '.');
    $total_difference = number_format($summary['total_difference'], 0, ',', '.');

    $period_text = "This Month";
    if ($start_date && $end_date) {
        $period_text = date('d M Y', strtotime($start_date)) . " - " . date('d M Y', strtotime($end_date));
    }

    // Buat HTML menggunakan string biasa daripada HEREDOC untuk menghindari error EncapsulatedAndWhitespace
    $html = '<div class="card card-enhanced">';
    $html .= '<div class="card-header bg-soft-primary">';
    $html .= '<h5 class="card-title">Cash Advance Summary (' . $period_text . ')</h5>';
    $html .= '</div>';
    $html .= '<div class="card-body">';
    $html .= '<div class="row">';

    // Total Transactions
    $html .= '<div class="col-md-3 mb-3">';
    $html .= '<div class="card bg-light">';
    $html .= '<div class="card-body text-center py-3">';
    $html .= '<h3 class="mb-0">' . $summary['total_transactions'] . '</h3>';
    $html .= '<p class="text-muted mb-0">Total Transaksi</p>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    // Total Requested
    $html .= '<div class="col-md-3 mb-3">';
    $html .= '<div class="card bg-light">';
    $html .= '<div class="card-body text-center py-3">';
    $html .= '<h3 class="mb-0">Rp ' . $total_requested . '</h3>';
    $html .= '<p class="text-muted mb-0">Total Permintaan</p>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    // Total Actual
    $html .= '<div class="col-md-3 mb-3">';
    $html .= '<div class="card bg-light">';
    $html .= '<div class="card-body text-center py-3">';
    $html .= '<h3 class="mb-0">Rp ' . $total_actual . '</h3>';
    $html .= '<p class="text-muted mb-0">Total Aktual</p>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    // Total Difference
    $totalDifferenceClass = $summary['total_difference'] > 0 ? 'text-success' : 'text-danger';
    $html .= '<div class="col-md-3 mb-3">';
    $html .= '<div class="card bg-light">';
    $html .= '<div class="card-body text-center py-3">';
    $html .= '<h3 class="mb-0 ' . $totalDifferenceClass . '">Rp ' . $total_difference . '</h3>';
    $html .= '<p class="text-muted mb-0">Total Selisih</p>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    $html .= '</div>';

    // Status counts
    $html .= '<div class="row mt-3">';

    // Settled
    $html .= '<div class="col-md-4">';
    $html .= '<div class="d-flex align-items-center">';
    $html .= '<div class="flex-shrink-0">';
    $html .= '<span class="badge bg-soft-success p-2"><i class="ri-checkbox-circle-line fs-5"></i></span>';
    $html .= '</div>';
    $html .= '<div class="flex-grow-1 ms-3">';
    $html .= '<h6 class="mb-0">Settled</h6>';
    $html .= '<small class="text-muted">' . $summary['settled_count'] . ' transaksi</small>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    // Refund
    $html .= '<div class="col-md-4">';
    $html .= '<div class="d-flex align-items-center">';
    $html .= '<div class="flex-shrink-0">';
    $html .= '<span class="badge bg-soft-warning p-2"><i class="ri-funds-line fs-5"></i></span>';
    $html .= '</div>';
    $html .= '<div class="flex-grow-1 ms-3">';
    $html .= '<h6 class="mb-0">Refund</h6>';
    $html .= '<small class="text-muted">' . $summary['refund_count'] . ' transaksi</small>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    // Done
    $html .= '<div class="col-md-4">';
    $html .= '<div class="d-flex align-items-center">';
    $html .= '<div class="flex-shrink-0">';
    $html .= '<span class="badge bg-soft-info p-2"><i class="ri-check-double-line fs-5"></i></span>';
    $html .= '</div>';
    $html .= '<div class="flex-grow-1 ms-3">';
    $html .= '<h6 class="mb-0">Done</h6>';
    $html .= '<small class="text-muted">' . $summary['done_count'] . ' transaksi</small>';
    $html .= '</div>';
    $html .= '</div>';
    $html .= '</div>';

    $html .= '</div>';
    $html .= '</div>';

    // Footer
    $html .= '<div class="card-footer text-end">';
    $html .= '<a href="index.php?page=CashAdvance" class="btn btn-primary btn-enhanced">';
    $html .= '<i class="ri-file-list-3-line me-1"></i> Lihat Semua';
    $html .= '</a>';
    $html .= '</div>';
    $html .= '</div>';

    return $html;
}

/**
 * Get category distribution of Cash Advance details
 * 
 * @param mysqli $koneksi Database connection
 * @param string $start_date Start date in Y-m-d format (optional)
 * @param string $end_date End date in Y-m-d format (optional)
 * @return array Array of category data
 */
function getCashAdvanceCategoryDistribution($koneksi, $start_date = null, $end_date = null)
{
    // Default to current month if no dates provided
    if (is_null($start_date)) {
        $start_date = date('Y-m-01'); // First day of current month
    }

    if (is_null($end_date)) {
        $end_date = date('Y-m-t'); // Last day of current month
    }

    $query = "SELECT 
                d.item_category,
                SUM(d.total_price) as total_amount,
                COUNT(d.id_detail) as item_count
              FROM 
                detailed_cash_advance d
              JOIN 
                cash_advance c ON d.id_ca = c.id_ca
              WHERE 
                c.date_settlement BETWEEN ? AND ?
                AND d.is_deleted = 0
              GROUP BY 
                d.item_category
              ORDER BY 
                total_amount DESC";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param($stmt, "ss", $start_date, $end_date);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
    }

    return $data;
}

<?php
// Include the database connection
include 'koneksi.php';
// Include cashflow dashboard functions
include 'function/cashflow_dashboard_functions.php';

// Get user information
$namalogin = isset($_SESSION['nama']) ? $_SESSION['nama'] : 'User';

// Calculate Cashflow metrics
// 1. Total Cash Advance Amount
$sql_total_ca = mysqli_query($koneksi, "SELECT SUM(jumlah_request) AS total_ca FROM cash_advance WHERE is_deleted = 0");
$data_total_ca = mysqli_fetch_assoc($sql_total_ca);
$total_ca_amount = $data_total_ca['total_ca'] ? number_format($data_total_ca['total_ca'], 0, ',', '.') : 0;

// 2. Pending Cash Advance Requests
$sql_pending_ca = mysqli_query($koneksi, "SELECT COUNT(*) AS pending_ca FROM cash_advance WHERE status = 'Pending' AND is_deleted = 0");
$data_pending_ca = mysqli_fetch_assoc($sql_pending_ca);
$pending_ca_count = $data_pending_ca['pending_ca'];

// 3. Total Petty Cash In
$sql_total_pc_in = mysqli_query($koneksi, "SELECT SUM(harga) AS total_pc_in FROM petty_cash_in WHERE is_deleted = 0");
$data_total_pc_in = mysqli_fetch_assoc($sql_total_pc_in);
$total_pc_in_amount = $data_total_pc_in['total_pc_in'] ? number_format($data_total_pc_in['total_pc_in'], 0, ',', '.') : 0;

// 4. Total Petty Cash Out
$sql_total_pc_out = mysqli_query($koneksi, "SELECT SUM(harga) AS total_pc_out FROM petty_cash_out WHERE is_deleted = 0");
$data_total_pc_out = mysqli_fetch_assoc($sql_total_pc_out);
$total_pc_out_amount = $data_total_pc_out['total_pc_out'] ? number_format($data_total_pc_out['total_pc_out'], 0, ',', '.') : 0;

// 5. Current Balance (Petty Cash In - Petty Cash Out)
$current_balance = ($data_total_pc_in['total_pc_in'] - $data_total_pc_out['total_pc_out']);
$formatted_balance = number_format($current_balance, 0, ',', '.');

// 6. Unsettled Cash Advances
$sql_unsettled_ca = mysqli_query($koneksi, "SELECT COUNT(*) AS unsettled_ca FROM cash_advance WHERE status = 'Approved' AND date_settlement IS NULL AND is_deleted = 0");
$data_unsettled_ca = mysqli_fetch_assoc($sql_unsettled_ca);
$unsettled_ca_count = $data_unsettled_ca['unsettled_ca'];

// 7. Get Monthly Cash Flow Data for Chart
$sql_monthly_cash_in = mysqli_query($koneksi, "
    SELECT 
        DATE_FORMAT(date, '%Y-%m') AS month,
        SUM(harga) AS total_in
    FROM 
        petty_cash_in
    WHERE 
        is_deleted = 0 AND
        date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY 
        DATE_FORMAT(date, '%Y-%m')
    ORDER BY 
        month
");

$sql_monthly_cash_out = mysqli_query($koneksi, "
    SELECT 
        DATE_FORMAT(date, '%Y-%m') AS month,
        SUM(harga) AS total_out
    FROM 
        petty_cash_out
    WHERE 
        is_deleted = 0 AND
        date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
    GROUP BY 
        DATE_FORMAT(date, '%Y-%m')
    ORDER BY 
        month
");

// Prepare data for the chart
$monthly_data = [];
while ($row = mysqli_fetch_assoc($sql_monthly_cash_in)) {
    $month = $row['month'];
    $monthly_data[$month]['cash_in'] = $row['total_in'];
    $monthly_data[$month]['cash_out'] = 0; // Initialize
}

while ($row = mysqli_fetch_assoc($sql_monthly_cash_out)) {
    $month = $row['month'];
    if (!isset($monthly_data[$month])) {
        $monthly_data[$month]['cash_in'] = 0;
    }
    $monthly_data[$month]['cash_out'] = $row['total_out'];
}

// Convert to JSON for the chart
$chart_data = [];
foreach ($monthly_data as $month => $data) {
    $chart_data[] = [
        'month' => date('M Y', strtotime($month . '-01')),
        'cashIn' => $data['cash_in'],
        'cashOut' => $data['cash_out'],
        'balance' => $data['cash_in'] - $data['cash_out']
    ];
}
$chart_json = json_encode($chart_data);

// 8. Recent Transactions
$sql_recent_transactions = mysqli_query($koneksi, "
    (SELECT 
        'Cash Advance' AS type,
        deskripsi,
        jumlah_request AS amount,
        date_request AS transaction_date,
        status
    FROM 
        cash_advance
    WHERE 
        is_deleted = 0)
    UNION
    (SELECT 
        'Petty Cash In' AS type,
        deskripsi,
        harga AS amount,
        date AS transaction_date,
        'Completed' AS status
    FROM 
        petty_cash_in
    WHERE 
        is_deleted = 0)
    UNION
    (SELECT 
        'Petty Cash Out' AS type,
        deskripsi,
        harga AS amount,
        date AS transaction_date,
        status
    FROM 
        petty_cash_out
    WHERE 
        is_deleted = 0)
    ORDER BY 
        transaction_date DESC
    LIMIT 10
");

// Mendapatkan data ringkasan Cash Advance menggunakan fungsi dari cashflow_dashboard_functions.php
$start_date = date('Y-m-01'); // First day of current month
$end_date = date('Y-m-t'); // Last day of current month
$ca_summary = getCashAdvanceSummary($koneksi, $start_date, $end_date);
$ca_category_distribution = getCashAdvanceCategoryDistribution($koneksi, $start_date, $end_date);
?>

<!-- Include DataTables and Styling -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>

<style>
    /* Enhanced styling for the entire page */
    body {
        background-color: #f5f7fb !important;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        transition: transform 0.3s, box-shadow 0.3s;
        overflow: hidden;
        border: none;
    }

    .card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        background-color: #fff;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
        padding: 1.25rem 1.5rem;
    }

    .card-body {
        padding: 1.5rem;
    }

    /* Improved stats cards */
    .mini-stats-wid {
        border-radius: 8px !important;
        overflow: hidden;
        transition: all 0.3s ease;
        border: none !important;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
        height: 100%;
    }

    .mini-stats-wid:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    .mini-stats-wid .card-body {
        padding: 1.25rem;
        display: flex;
        align-items: center;
    }

    .mini-stats-wid .avatar-sm {
        width: 3rem;
        height: 3rem;
        display: flex;
        justify-content: center;
        align-items: center;
        margin-left: 1.25rem;
    }

    .mini-stat-icon {
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
    }

    /* Better DataTables styling */
    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #e2e5e8;
        border-radius: 4px;
        padding: 5px 10px;
    }

    .dataTables_wrapper .dataTables_length select {
        border: 1px solid #e2e5e8;
        border-radius: 4px;
        padding: 5px;
    }

    .dataTables_wrapper .dataTables_info {
        padding-top: 1em;
    }

    /* Transaction table styling */
    .transaction-table th {
        background-color: #f5f7fb;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #e9ecef;
        padding: 10px 15px;
    }

    .transaction-table td {
        padding: 12px 15px;
        vertical-align: middle;
        border-top: 1px solid #f1f1f1;
    }

    .transaction-table tbody tr:hover {
        background-color: #f9fbfd;
    }

    /* Fix for scrolling on mobile */
    @media (max-width: 768px) {
        .dataTables_wrapper .dataTables_scrollBody {
            overflow-x: auto !important;
        }
    }

    /* Cash flow chart responsive styling */
    #cashflow_chart {
        min-height: 350px;
    }

    /* Category distribution chart styling */
    #category_distribution_chart {
        min-height: 300px;
    }
</style>

<div class="h-100">
    <div class="row mb-3 pb-1">
        <div class="col-12">
            <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                <div class="flex-grow-1">
                    <h4 class="fs-16 mb-1">Welcome, <?= $namalogin ?></h4>
                    <p class="text-muted mb-0">Cashflow Management Dashboard - Overview of all financial activities</p>
                </div>
                <div class="mt-3 mt-lg-0">
                    <form>
                        <div class="row g-3 mb-0 align-items-center">
                            <div class="col-auto">
                                <a href="index.php?page=CashAdvance" class="btn btn-soft-success">
                                    <i class="ri-add-circle-line align-middle me-1"></i> New Cash Advance
                                </a>
                            </div>
                            <div class="col-auto">
                                <a href="index.php?page=PettyCashIn" class="btn btn-soft-primary">
                                    <i class="ri-money-dollar-circle-line align-middle me-1"></i> Cash In
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Financial Summary Row -->
    <div class="row">
        <!-- Current Balance Card -->
        <div class="col-xl-4 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Current Balance</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-<?= ($current_balance >= 0) ? 'success' : 'danger' ?>">
                                Rp <?= $formatted_balance ?>
                            </h4>
                            <a href="index.php?page=PettyCashOut" class="text-decoration-underline">View Details</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-primary rounded fs-3">
                                <i class="ri-wallet-3-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Cash In -->
        <div class="col-xl-4 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Cash In</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-success">Rp <?= $total_pc_in_amount ?></h4>
                            <a href="index.php?page=PettyCashIn" class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-success rounded fs-3">
                                <i class="ri-arrow-down-circle-line text-success"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Cash Out -->
        <div class="col-xl-4 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Cash Out</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4 text-danger">Rp <?= $total_pc_out_amount ?></h4>
                            <a href="index.php?page=PettyCashOut" class="text-decoration-underline">View All</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-danger rounded fs-3">
                                <i class="ri-arrow-up-circle-line text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cash Advance Summary Widget from cashflow_dashboard_functions.php -->
    <?= renderCashAdvanceWidget($koneksi) ?>

    <!-- Cash Advance Summary Row (Original) -->
    <div class="row">
        <!-- Total Cash Advance -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Total Cash Advance</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4">Rp <?= $total_ca_amount ?></h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-info rounded fs-3">
                                <i class="ri-refund-2-line text-info"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Requests -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Pending Requests</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?= $pending_ca_count ?></h4>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-warning rounded fs-3">
                                <i class="ri-time-line text-warning"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Unsettled Cash Advances -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Unsettled Advances</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <h4 class="fs-22 fw-semibold ff-secondary mb-4"><?= $unsettled_ca_count ?></h4>
                            <a href="index.php?page=CashAdvance?filter=unsettled" class="text-decoration-underline">Settle Now</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-danger rounded fs-3">
                                <i class="ri-funds-box-line text-danger"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cash Advance Management -->
        <div class="col-xl-3 col-md-6">
            <div class="card card-animate">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-grow-1 overflow-hidden">
                            <p class="text-uppercase fw-medium text-muted text-truncate mb-0">Cash Advance Management</p>
                        </div>
                    </div>
                    <div class="d-flex align-items-end justify-content-between mt-4">
                        <div>
                            <a href="index.php?page=CashAdvance" class="btn btn-primary">View All Cash Advances</a>
                        </div>
                        <div class="avatar-sm flex-shrink-0">
                            <span class="avatar-title bg-soft-primary rounded fs-3">
                                <i class="ri-bank-card-line text-primary"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Cash Flow Chart -->
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Monthly Cash Flow</h4>
                </div>
                <div class="card-body">
                    <div id="cashflow_chart" class="apex-charts" dir="ltr" style="height: 350px;"></div>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header align-items-center d-flex">
                    <h4 class="card-title mb-0 flex-grow-1">Recent Transactions</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive table-card">
                        <table id="transactionsTable" class="table transaction-table table-borderless table-hover table-nowrap align-middle mb-0">
                            <thead class="table-light">
                                <tr class="text-muted">
                                    <th scope="col">Type</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = mysqli_fetch_assoc($sql_recent_transactions)) : ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php
                                                $icon_class = "";
                                                switch ($row['type']) {
                                                    case 'Cash Advance':
                                                        $icon_class = "ri-funds-line text-info";
                                                        break;
                                                    case 'Petty Cash In':
                                                        $icon_class = "ri-arrow-down-circle-line text-success";
                                                        break;
                                                    case 'Petty Cash Out':
                                                        $icon_class = "ri-arrow-up-circle-line text-danger";
                                                        break;
                                                }
                                                ?>
                                                <div class="flex-shrink-0 me-2">
                                                    <i class="<?= $icon_class ?>"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0"><?= $row['type'] ?></h6>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="text-truncate d-inline-block" style="max-width: 200px;"><?= $row['deskripsi'] ?></span>
                                        </td>
                                        <td><?= date('d M Y', strtotime($row['transaction_date'])) ?></td>
                                        <td>
                                            <h6 class="mb-0">Rp <?= number_format($row['amount'], 0, ',', '.') ?></h6>
                                        </td>
                                        <td>
                                            <?php
                                            $status_class = "";
                                            switch ($row['status']) {
                                                case 'Pending':
                                                    $status_class = "badge bg-warning-subtle text-warning";
                                                    break;
                                                case 'Approved':
                                                    $status_class = "badge bg-info-subtle text-info";
                                                    break;
                                                case 'Completed':
                                                case 'Settled':
                                                    $status_class = "badge bg-success-subtle text-success";
                                                    break;
                                                case 'Rejected':
                                                    $status_class = "badge bg-danger-subtle text-danger";
                                                    break;
                                                case 'Refund':
                                                    $status_class = "badge bg-primary-subtle text-primary";
                                                    break;
                                                case 'Done':
                                                    $status_class = "badge bg-dark-subtle text-dark";
                                                    break;
                                                default:
                                                    $status_class = "badge bg-secondary-subtle text-secondary";
                                            }
                                            ?>
                                            <span class="<?= $status_class ?>"><?= $row['status'] ?></span>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cash Advance Category Distribution Chart -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Cash Advance Expense Categories (<?= date('F Y') ?>)</h4>
                </div>
                <div class="card-body">
                    <div id="category_distribution_chart" class="apex-charts" dir="ltr"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ApexCharts JS -->
<script src="assets/libs/apexcharts/apexcharts.min.js"></script>

<!-- Initialize charts -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Cash Flow Chart
        var chartData = <?= $chart_json ?>;

        // Prepare category distribution data for chart
        var categoryData = <?= json_encode($ca_category_distribution) ?>;

        var options = {
            series: [{
                    name: 'Cash In',
                    type: 'column',
                    data: chartData.map(function(item) {
                        return item.cashIn;
                    })
                },
                {
                    name: 'Cash Out',
                    type: 'column',
                    data: chartData.map(function(item) {
                        return item.cashOut;
                    })
                },
                {
                    name: 'Balance',
                    type: 'line',
                    data: chartData.map(function(item) {
                        return item.balance;
                    })
                }
            ],
            chart: {
                height: 350,
                type: 'line',
                stacked: false,
                toolbar: {
                    show: false
                },
                fontFamily: '"Poppins", sans-serif',
                background: 'transparent'
            },
            stroke: {
                width: [0, 0, 3],
                curve: 'smooth'
            },
            plotOptions: {
                bar: {
                    columnWidth: '50%',
                    borderRadius: 3
                }
            },
            fill: {
                opacity: [0.85, 0.85, 1],
                gradient: {
                    inverseColors: false,
                    shade: 'light',
                    type: "vertical",
                    opacityFrom: 0.85,
                    opacityTo: 0.55,
                    stops: [0, 100, 100, 100]
                }
            },
            markers: {
                size: 4,
                strokeWidth: 0,
                hover: {
                    size: 6
                }
            },
            xaxis: {
                categories: chartData.map(function(item) {
                    return item.month;
                }),
                labels: {
                    style: {
                        fontSize: '12px',
                        fontFamily: '"Poppins", sans-serif'
                    }
                },
                axisBorder: {
                    show: false
                },
                axisTicks: {
                    show: false
                }
            },
            yaxis: {
                title: {
                    text: 'Amount (Rp)',
                    style: {
                        fontSize: '13px',
                        fontFamily: '"Poppins", sans-serif'
                    }
                },
                labels: {
                    formatter: function(val) {
                        return 'Rp ' + val.toLocaleString('id-ID');
                    },
                    style: {
                        fontSize: '12px',
                        fontFamily: '"Poppins", sans-serif'
                    }
                }
            },
            tooltip: {
                shared: true,
                intersect: false,
                y: {
                    formatter: function(y) {
                        if (typeof y !== "undefined") {
                            return "Rp " + y.toLocaleString('id-ID');
                        }
                        return y;
                    }
                },
                style: {
                    fontSize: '12px',
                    fontFamily: '"Poppins", sans-serif'
                }
            },
            colors: ['#0ab39c', '#f06548', '#405189'],
            grid: {
                borderColor: '#f1f1f1',
                padding: {
                    bottom: 15
                },
                strokeDashArray: 5
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                offsetY: -8,
                fontSize: '13px',
                fontFamily: '"Poppins", sans-serif',
                markers: {
                    width: 10,
                    height: 10,
                    radius: 6
                },
                itemMargin: {
                    horizontal: 10,
                    vertical: 0
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#cashflow_chart"), options);
        chart.render();

        // Initialize DataTable for recent transactions
        $('#transactionsTable').DataTable({
            responsive: true,
            dom: 'Bfrtip',
            buttons: [{
                extend: 'excel',
                text: '<i class="ri-file-excel-2-line me-1"></i> Export',
                className: 'btn btn-sm btn-soft-primary'
            }],
            pageLength: 5,
            lengthMenu: [
                [5, 10, 25, -1],
                [5, 10, 25, "All"]
            ],
            language: {
                paginate: {
                    previous: "<i class='ri-arrow-left-s-line'>",
                    next: "<i class='ri-arrow-right-s-line'>"
                }
            }
        });
    });
</script>

<!-- Include additional scripts -->
<script src="assets/libs/swiper/swiper-bundle.min.js"></script>
<script src="assets/js/pages/dashboard-cashflow.init.js"></script>
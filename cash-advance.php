<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.2.0/css/searchPanes.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/searchpanes/2.2.0/js/dataTables.searchPanes.min.js"></script>
<script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
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

    .stats-card-row {
        margin-bottom: 1.5rem;
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
    }

    .stats-card-container {
        flex: 1;
        min-width: 200px;
    }

    /* Style for clickable ID CA */
    .clickable-id {
        position: relative;
        cursor: pointer;
        color: #0d6efd;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .clickable-id:hover {
        color: #0a58ca;
        text-decoration: underline;
    }

    .clickable-id::after {
        content: '\f06e';
        font-family: 'remixicon';
        margin-left: 5px;
        font-size: 14px;
        opacity: 0.7;
    }

    /* Animation for modal transition */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
    }

    .modal.fade.show .modal-dialog {
        transform: none;
    }

    /* Style for modal detail sections */
    .detail-section {
        background-color: #f8f9fa;
        border-radius: 5px;
        padding: 15px;
        margin-bottom: 15px;
    }

    /* Style for action buttons in modal */
    #detail_action_buttons .btn {
        margin-right: 5px;
        margin-bottom: 5px;
    }

    /* Improved table styling */
    #caTable {
        border-collapse: separate;
        border-spacing: 0;
        width: 100%;
    }

    #caTable th {
        background-color: #f5f7fb;
        font-weight: 600;
        color: #495057;
        border-bottom: 2px solid #e9ecef;
        padding: 10px 15px;
    }

    #caTable td {
        padding: 12px 15px;
        vertical-align: middle;
        border-top: 1px solid #f1f1f1;
    }

    #caTable tbody tr:hover {
        background-color: #f9fbfd;
    }

    /* Better filter card */
    .filter-card {
        background-color: #fff;
        border-radius: 8px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .filter-label {
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: #495057;
    }

    /* Improved button styling */
    .action-btn {
        border-radius: 5px;
        padding: 6px 12px;
        font-weight: 500;
        transition: all 0.3s;
    }

    .btn-soft-info {
        background-color: rgba(13, 110, 253, 0.1);
        color: #0d6efd;
        border: none;
    }

    .btn-soft-info:hover {
        background-color: rgba(13, 110, 253, 0.2);
    }

    .btn-soft-success {
        background-color: rgba(25, 135, 84, 0.1);
        color: #198754;
        border: none;
    }

    .btn-soft-success:hover {
        background-color: rgba(25, 135, 84, 0.2);
    }

    .btn-soft-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
        border: none;
    }

    .btn-soft-danger:hover {
        background-color: rgba(220, 53, 69, 0.2);
    }

    .btn-soft-secondary {
        background-color: rgba(108, 117, 125, 0.1);
        color: #6c757d;
        border: none;
    }

    .btn-soft-secondary:hover {
        background-color: rgba(108, 117, 125, 0.2);
    }

    /* Badge styling */
    .badge {
        padding: 0.4em 0.6em;
        font-size: 0.75em;
        font-weight: 500;
        border-radius: 4px;
    }

    /* DataTables improvements */
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

    /* Fix for scrolling on mobile */
    @media (max-width: 768px) {
        .dataTables_wrapper .dataTables_scrollBody {
            overflow-x: auto !important;
        }

        .stats-card-container {
            min-width: 100%;
        }
    }
</style>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <div class="card-title mb-0 flex-grow-1">
                        <h5 class="mb-1">Cash Advance Management</h5>
                        <h6 class="text-muted fs-14 mb-0">Submit your cash advance requests here. Our finance team will process your request as soon as possible.</h6>
                    </div>
                    <div class="flex-shrink-0">
                        <button class="btn btn-primary add-btn" data-bs-toggle="modal" data-bs-target="#showModal">
                            <i class="ri-add-line align-bottom me-1"></i> Create Cash Advance
                        </button>
                    </div>
                </div>
            </div>

            <!-- Improved filter card design -->
            <div class="card-body pt-0 pb-2">
                <div class="filter-card">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="filter-label">Category</label>
                            <select id="categoryFilter" class="form-select">
                                <option value="">All Categories</option>
                                <?php
                                $categories = [];
                                $cat_query = "SELECT DISTINCT kategori FROM cash_advance ORDER BY kategori";
                                $cat_result = mysqli_query($koneksi, $cat_query);
                                while ($cat_row = mysqli_fetch_assoc($cat_result)) {
                                    echo '<option value="' . $cat_row['kategori'] . '">' . $cat_row['kategori'] . '</option>';
                                    $categories[] = $cat_row['kategori'];
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="filter-label">Start Date</label>
                            <input type="date" id="startDate" class="form-control" placeholder="Start Date">
                        </div>
                        <div class="col-md-3">
                            <label class="filter-label">End Date</label>
                            <input type="date" id="endDate" class="form-control" placeholder="End Date">
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button id="applyFilters" class="btn btn-primary me-2">
                                <i class="ri-filter-line me-1"></i> Apply Filters
                            </button>
                            <button id="resetFilters" class="btn btn-light">
                                <i class="ri-refresh-line me-1"></i> Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Improved summary cards layout -->
            <div class="card-body pt-0">
                <div class="stats-card-row">
                    <div class="stats-card-container">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-2">Total Requested</p>
                                    <h4 class="mb-0" id="totalRequested">Rp 0</h4>
                                </div>
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-primary">
                                    <span class="avatar-title">
                                        <i class="ri-arrow-up-circle-line fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="stats-card-container">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-2">Total Actual</p>
                                    <h4 class="mb-0" id="totalActual">Rp 0</h4>
                                </div>
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-success">
                                    <span class="avatar-title">
                                        <i class="ri-exchange-funds-fill fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="stats-card-container">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-2">Total Selisih (Settled)</p>
                                    <h4 class="mb-0" id="totalSelisih">Rp 0</h4>
                                </div>
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-warning">
                                    <span class="avatar-title">
                                        <i class="ri-compass-3-line fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="stats-card-container">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-2">Total Entries</p>
                                    <h4 class="mb-0" id="totalEntries">0</h4>
                                </div>
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-info">
                                    <span class="avatar-title">
                                        <i class="ri-file-list-3-line fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="stats-card-container">
                        <div class="card mini-stats-wid">
                            <div class="card-body">
                                <div class="flex-grow-1">
                                    <p class="text-muted fw-medium mb-2">Completed Entries</p>
                                    <h4 class="mb-0" id="doneEntries">0</h4>
                                </div>
                                <div class="mini-stat-icon avatar-sm rounded-circle bg-dark">
                                    <span class="avatar-title">
                                        <i class="ri-check-double-line fs-4"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-body pt-0">
                <div class="card">
                    <div class="card-body">
                        <table id="caTable" class="stripe row-border order-column" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ID CA</th>
                                    <th>Deskripsi Kebutuhan</th>
                                    <th>Kategori</th>
                                    <th>Request By</th>
                                    <th>Date Request</th>
                                    <th>Jumlah Request</th>
                                    <th>Jumlah Actual</th>
                                    <th>Selisih</th>
                                    <th>Date Settlement</th>
                                    <th>Bukti Nota</th>
                                    <th>Bukti Refund</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <?php
                            // Simplified query without JOIN
                            $sql = "SELECT 
                                id_ca, 
                                deskripsi,
                                kategori, 
                                request,
                                date_request,
                                jumlah_request,
                                jumlah_actual,
                                date_settlement,
                                status,
                                bukti_pengembalian,
                                bukti_nota,
                                refund_date,
                                refund_notes
                            FROM 
                                cash_advance";
                            $result = mysqli_query($koneksi, $sql);
                            if (!$result) {
                                die("Query failed: " . mysqli_error($koneksi));
                            }

                            while ($row = mysqli_fetch_assoc($result)) {
                                $selisih = $row['jumlah_request'] - $row['jumlah_actual'];
                            ?>
                                <tr>
                                    <td class="clickable-id" style="cursor: pointer; color: #0d6efd; text-decoration: underline;"><?= $row['id_ca']; ?></td>
                                    <td><?= $row['deskripsi'] ?></td>
                                    <td><?= $row['kategori'] ?></td>
                                    <!-- Simply display the request value -->
                                    <td><?= $row['request'] ?></td>
                                    <td><?= $row['date_request'] ?></td>
                                    <td class="jumlah-request" data-value="<?= $row['jumlah_request'] ?>">Rp <?= number_format($row['jumlah_request'], 0, ',', '.') ?></td>
                                    <td class="jumlah-actual" data-value="<?= $row['jumlah_actual'] ?>">Rp <?= number_format($row['jumlah_actual'], 0, ',', '.') ?></td>
                                    <td class="selisih" data-value="<?= $selisih ?>">
                                        <?php
                                        if ($selisih > 0) {
                                            echo 'Rp ' . number_format($selisih, 0, ',', '.');
                                            echo ' <span class="badge bg-primary">(Lebih)</span>';
                                        } elseif ($selisih < 0) {
                                            echo 'Rp ' . number_format(abs($selisih), 0, ',', '.');
                                            echo ' <span class="badge bg-warning">(Kurang)</span>';
                                        } else {
                                            echo '<span class="badge bg-success">Sesuai</span>';
                                        }
                                        ?>
                                    </td>
                                    <td><?= $row['date_settlement'] ?></td>

                                    <!-- Bukti Nota Column -->
                                    <td>
                                        <?php if (!empty($row['bukti_nota'])): ?>
                                            <a href="file/bukti_nota/<?= $row['bukti_nota'] ?>" class="btn btn-soft-info btn-sm" download>
                                                <i class="ri-download-2-line align-bottom"></i> Download
                                            </a>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted">No File</span>
                                        <?php endif; ?>
                                    </td>

                                    <!-- Bukti Refund Column -->
                                    <td>
                                        <?php if (!empty($row['bukti_pengembalian'])): ?>
                                            <a href="file/bukti/<?= $row['bukti_pengembalian'] ?>" class="btn btn-soft-success btn-sm" download>
                                                <i class="ri-download-2-line align-bottom"></i> Download
                                            </a>
                                        <?php else: ?>
                                            <span class="badge bg-light text-muted">No File</span>
                                        <?php endif; ?>
                                    </td>

                                    <td>
                                        <?php
                                        if ($row['status'] == 'Pending') {
                                            echo '<span class="badge bg-warning">Pending</span>';
                                        } elseif ($row['status'] == 'Approved') {
                                            echo '<span class="badge bg-success">Approved</span>';
                                        } elseif ($row['status'] == 'Rejected') {
                                            echo '<span class="badge bg-danger">Rejected</span>';
                                        } elseif ($row['status'] == 'Settled') {
                                            echo '<span class="badge bg-info">Settled</span>';
                                        } elseif ($row['status'] == 'Refund') {
                                            echo '<span class="badge bg-primary">Refund</span>';
                                        } elseif ($row['status'] == 'Done') {
                                            echo '<span class="badge bg-dark">Done</span>';
                                        } else {
                                            // Fallback for any other status
                                            echo '<span class="badge bg-secondary">' . $row['status'] . '</span>';
                                        }
                                        ?>
                                    </td>

                                    <!-- Action Buttons -->
                                    <td>
                                        <div class="d-flex gap-1">
                                            <button type="button" class="btn btn-soft-secondary btn-sm action-btn" onclick="openEditModalFromDetail({
                                                    id_ca: <?= $row['id_ca'] ?>,
                                                    deskripsi: '<?= addslashes($row['deskripsi']) ?>',
                                                    kategori: '<?= $row['kategori'] ?>',
                                                    request: '<?= $row['request'] ?>',
                                                    date_request: '<?= $row['date_request'] ?>',
                                                    jumlah_request: '<?= $row['jumlah_request'] ?>',
                                                    jumlah_actual: '<?= $row['jumlah_actual'] ?>',
                                                    date_settlement: '<?= $row['date_settlement'] ?>',
                                                    status: '<?= $row['status'] ?>',
                                                    bukti_nota: '<?= $row['bukti_nota'] ?>',
                                                    bukti_pengembalian: '<?= $row['bukti_pengembalian'] ?>',
                                                    refund_date: '<?= $row['refund_date'] ?>',
                                                    refund_notes: '<?= addslashes($row['refund_notes'] ?? '') ?>'
                                                })">
                                                <i class="ri-pencil-fill align-bottom text-muted"></i>
                                            </button>
                                            <button type="button" class="btn btn-soft-danger btn-sm action-btn"
                                                onclick="confirmDeleteFromDetail(<?= $row['id_ca'] ?>, '<?= addslashes($row['deskripsi']) ?>')">
                                                <i class="ri-delete-bin-5-line align-bottom text-muted"></i>
                                            </button>

                                            <?php
                                            // Show buttons based on status and selisih
                                            $selisih = $row['jumlah_request'] - $row['jumlah_actual'];

                                            // Settle button - only for Pending or Approved
                                            if ($row['status'] == 'Pending' || $row['status'] == 'Approved'):
                                            ?>
                                                <button type="button" class="btn btn-soft-info btn-sm action-btn" onclick="openSettlementModalFromDetail(<?= $row['id_ca'] ?>, '<?= addslashes($row['deskripsi']) ?>')">
                                                    <i class="ri-exchange-funds-fill align-bottom text-muted"></i>
                                                </button>
                                                <?php
                                            // For Settled status
                                            elseif ($row['status'] == 'Settled'):
                                                if ($selisih > 0):
                                                    // If there's a positive selisih, show the Pengembalian button
                                                ?>
                                                    <button type="button" class="btn btn-soft-primary btn-sm action-btn" onclick="openRefundModalFromDetail(<?= $row['id_ca'] ?>, '<?= addslashes($row['deskripsi']) ?>', <?= $selisih ?>)">
                                                        <i class="ri-refund-2-line align-bottom text-muted"></i>
                                                    </button>
                                                <?php
                                                else:
                                                    // If no selisih or negative selisih, show Complete button
                                                ?>
                                                    <button type="button" class="btn btn-soft-dark btn-sm action-btn" onclick="completeCAFromDetail(<?= $row['id_ca'] ?>)">
                                                        <i class="ri-checkbox-circle-line align-bottom text-muted"></i>
                                                    </button>
                                                <?php
                                                endif;
                                            endif;

                                            // For Refund status, show the Complete button
                                            if ($row['status'] == 'Refund'):
                                                ?>
                                                <button type="button" class="btn btn-soft-dark btn-sm action-btn" onclick="completeCAFromDetail(<?= $row['id_ca'] ?>)">
                                                    <i class="ri-checkbox-circle-line align-bottom text-muted"></i>
                                                </button>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Modal Detail Cash Advance -->
<div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-soft-primary">
                <h5 class="modal-title" id="detailModalLabel">Cash Advance Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <h5 id="detail_deskripsi" class="mb-1"></h5>
                                <div class="hstack gap-3">
                                    <div class="badge rounded-pill bg-soft-primary text-primary">
                                        ID: <span id="detail_id_ca"></span>
                                    </div>
                                    <div id="detail_status"></div>
                                    <div class="badge rounded-pill bg-soft-secondary text-secondary">
                                        <span id="detail_kategori"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="detail-section">
                            <h6 class="text-muted mb-3">Request Information</h6>
                            <div class="d-flex mb-2">
                                <div class="flex-shrink-0">
                                    <i class="ri-user-line text-muted fs-16 align-middle me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold">Request By:</span>
                                    <span id="detail_request_by_name"></span>
                                    <input type="hidden" id="detail_request_by" />
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="flex-shrink-0">
                                    <i class="ri-calendar-line text-muted fs-16 align-middle me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold">Date Request:</span>
                                    <span id="detail_date_request"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="flex-shrink-0">
                                    <i class="ri-calendar-check-line text-muted fs-16 align-middle me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold">Date Settlement:</span>
                                    <span id="detail_date_settlement"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="detail-section">
                            <h6 class="text-muted mb-3">Financial Details</h6>
                            <div class="d-flex mb-2">
                                <div class="flex-shrink-0">
                                    <i class="ri-money-dollar-circle-line text-muted fs-16 align-middle me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold">Jumlah Request:</span>
                                    <span id="detail_jumlah_request"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="flex-shrink-0">
                                    <i class="ri-money-dollar-box-line text-muted fs-16 align-middle me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold">Jumlah Actual:</span>
                                    <span id="detail_jumlah_actual"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="flex-shrink-0">
                                    <i class="ri-exchange-dollar-line text-muted fs-16 align-middle me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold">Selisih:</span>
                                    <span id="detail_selisih"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="detail-section">
                            <h6 class="text-muted mb-3">Supporting Documents</h6>
                            <div class="d-flex mb-2">
                                <div class="flex-shrink-0">
                                    <i class="ri-file-text-line text-muted fs-16 align-middle me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold">Bukti Nota:</span>
                                    <span id="detail_bukti_nota"></span>
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="flex-shrink-0">
                                    <i class="ri-file-text-line text-muted fs-16 align-middle me-2"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <span class="fw-semibold">Bukti Refund:</span>
                                    <span id="detail_bukti_refund"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer mt-3">
                <div id="detail_action_buttons" class="d-flex flex-wrap gap-2 me-auto">
                    <!-- Action buttons will be inserted here via JavaScript -->
                </div>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modified Create Cash Advance Modal with dropdown for Request By -->
<div class="modal fade zoomIn" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-soft-info">
                <h5 class="modal-title" id="exampleModalLabel">Create Cash Advance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="close-modal"></button>
            </div>
            <form action="function/insert_ca.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label hidden class="form-label">ID Cash Advance</label>
                                <input type="text" class="form-control" name="id_ca" hidden />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea class="form-control" name="deskripsi" rows="3" required></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select class="form-control" name="kategori" required>
                                    <option value="Operational">Operational</option>
                                    <option value="Marketing">Marketing</option>
                                    <option value="Travel">Travel</option>
                                    <option value="Project">Project</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Request By</label>
                                <select class="form-control" name="request" required>
                                    <option value="">-- Select Employee --</option>
                                    <?php
                                    // Query to fetch users from HRGA division
                                    $sql_users = "SELECT idnik, nama FROM user WHERE divisi = 'HRGA'";
                                    $result_users = mysqli_query($koneksi, $sql_users);
                                    if (!$result_users) {
                                        die("Query failed: " . mysqli_error($koneksi));
                                    }

                                    while ($user = mysqli_fetch_assoc($result_users)) {
                                        echo '<option value="' . $user['idnik'] . '">' . $user['nama'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date Request</label>
                                <input type="date" class="form-control" name="date_request" value="<?= date('Y-m-d') ?>" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Jumlah Request (Rp)</label>
                                <input type="text" class="form-control currency-input" name="jumlah_request" required />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add-ca">Submit Request</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Cash Advance -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-soft-warning">
                <h5 class="modal-title" id="editModalLabel">Edit Cash Advance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" action="function/update_ca.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_ca" id="edit_id_ca">

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea class="form-control" name="deskripsi" id="edit_deskripsi" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-control" name="kategori" id="edit_kategori" required>
                            <option value="Operational">Operational</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Travel">Travel</option>
                            <option value="Project">Project</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Request By</label>
                        <select class="form-control" name="request" id="edit_request" required>
                            <option value="">-- Select Employee --</option>
                            <?php
                            // Query to fetch users from HRGA division
                            $sql_users = "SELECT idnik, nama FROM user WHERE divisi = 'HRGA'";
                            $result_users = mysqli_query($koneksi, $sql_users);
                            if (!$result_users) {
                                die("Query failed: " . mysqli_error($koneksi));
                            }

                            while ($user = mysqli_fetch_assoc($result_users)) {
                                echo '<option value="' . $user['idnik'] . '">' . $user['nama'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date Request</label>
                        <input type="date" class="form-control" name="date_request" id="edit_date_request" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Request (Rp)</label>
                        <input type="text" class="form-control currency-input" name="jumlah_request" id="edit_jumlah_request" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-control" name="status" id="edit_status" required>
                            <option value="Pending">Pending</option>
                            <option value="Approved">Approved</option>
                            <option value="Rejected">Rejected</option>
                            <option value="Settled">Settled</option>
                            <option value="Refund">Refund</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" name="update-ca">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Document Viewer -->
<div class="modal fade" id="documentModal" tabindex="-1" aria-labelledby="documentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-soft-primary">
                <h5 class="modal-title" id="documentModalLabel">View Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <h6 id="document_description"></h6>
                    <p id="document_type_label" class="text-muted"></p>
                </div>

                <!-- Document preview container -->
                <div id="document_preview" class="mb-3 text-center">
                    <!-- Content will be dynamically inserted here -->
                </div>

                <!-- Loading indicator -->
                <div id="loading_indicator" class="text-center d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Loading document...</p>
                </div>

                <!-- Error message -->
                <div id="error_message" class="alert alert-danger d-none">
                    Unable to preview this document type. Please download it instead.
                </div>
            </div>
            <div class="modal-footer mt-3">
                <a href="#" id="download_link" class="btn btn-success" download>
                    <i class="ri-download-2-line align-bottom me-1"></i> Download
                </a>
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Enhanced Modal Settlement Cash Advance -->
<div class="modal fade" id="settlementModal" tabindex="-1" aria-labelledby="settlementModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-soft-info">
                <h5 class="modal-title" id="settlementModalLabel">Settlement Cash Advance</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="settlementForm" action="function/settlement_ca.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_ca" id="settlement_id_ca">
                    <div class="detail-section mb-3">
                        <h6 class="text-muted mb-2">Cash Advance Details</h6>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <p id="settlement_deskripsi" class="form-control-static"></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Actual (Rp)</label>
                        <input type="text" class="form-control currency-input" name="jumlah_actual" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date Settlement</label>
                        <input type="date" class="form-control" name="date_settlement" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bukti Pengeluaran</label>
                        <input type="file" class="form-control" name="bukti_pengeluaran" accept="image/*, .pdf">
                        <small class="text-muted">Upload receipt or supporting documents (JPG, PNG, PDF)</small>
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" name="settlement-ca">Submit Settlement</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Enhanced Modal Refund Cash Advance -->
<div class="modal fade" id="refundModal" tabindex="-1" aria-labelledby="refundModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-soft-primary">
                <h5 class="modal-title" id="refundModalLabel">Refund Dana</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="refundForm" action="function/refund_ca.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <input type="hidden" name="id_ca" id="refund_id_ca">
                    <div class="detail-section mb-3">
                        <h6 class="text-muted mb-2">Cash Advance Details</h6>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <p id="refund_deskripsi" class="form-control-static"></p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jumlah Selisih yang Harus Dikembalikan</label>
                        <div class="input-group">
                            <span class="input-group-text">Rp</span>
                            <input type="text" class="form-control currency-input" id="refund_amount" name="refund_amount">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tanggal Pengembalian</label>
                        <input type="date" class="form-control" name="refund_date" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bukti Transfer Pengembalian</label>
                        <input type="file" class="form-control" name="bukti_refund" accept="image/*, .pdf" required>
                        <small class="text-muted">Upload bukti transfer pengembalian dana (JPG, PNG, PDF)</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan Pengembalian</label>
                        <textarea class="form-control" name="refund_notes" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" name="refund-ca">Submit Refund</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Delete -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-3 bg-soft-danger">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mt-2">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px">
                    </lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4 class="mb-3">Are you sure?</h4>
                        <p class="text-muted mb-4" id="deleteMessage"></p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="deleteButton">
                        <i class="ri-delete-bin-5-line align-bottom"></i> Delete
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // DataTable initialization
    $(document).ready(function() {
        // Initialize the DataTable
        var table = $('#caTable').DataTable({
            scrollX: true,
            scrollCollapse: true,
            order: [],
            lengthMenu: [
                [10, 25, 50, 100, -1],
                ['10 rows', '25 rows', '50 rows', '100 rows', 'Show all']
            ],
            buttons: [
                'pageLength',
                {
                    extend: 'colvis',
                    text: 'Column View',
                },
                {
                    extend: 'excelHtml5',
                    title: 'Data Export Cash Advance',
                    text: 'Export Excel'
                }
            ],
            dom: 'Bfrtip',
            deferRender: true,
            columnDefs: [
                // Hide the specified columns (indexes start at 0)
                {
                    targets: [4, 7, 8, 9, 10, 12],
                    visible: false
                }
            ],
            initComplete: function() {
                // Update totals after table initialization
                updateTotals();

                // Count Done entries
                updateDoneEntries();
            }
        });

        // Make ID CA clickable
        $('#caTable tbody').on('click', 'td:first-child', function() {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            var rowData = row.data();
            openDetailModal(rowData, tr);
        });

        // Initialize currency formatting for all currency inputs
        initializeCurrencyInputs();

        // Custom filtering function
        $.fn.dataTable.ext.search.push(
            function(settings, searchData, index, rowData, counter) {
                // Get filter values
                var selectedCategory = $('#categoryFilter').val();
                var startDate = $('#startDate').val();
                var endDate = $('#endDate').val();

                // Get cell values from the table
                var row = table.row(index).node();
                var category = $(row).find('td:eq(2)').text(); // Kategori (3rd column)
                var dateRequest = $(row).find('td:eq(4)').text(); // Date Request (5th column)

                // Apply category filter
                if (selectedCategory && selectedCategory !== category) {
                    return false;
                }

                // Apply date filter
                if (startDate && endDate) {
                    var dateStart = new Date(startDate);
                    var dateEnd = new Date(endDate);
                    var dateParts = dateRequest.split('-');
                    // Convert to date object (assuming date format is yyyy-mm-dd)
                    var dateCompare = new Date(dateRequest);

                    // Add one day to the end date to include the end date in the range
                    dateEnd.setDate(dateEnd.getDate() + 1);

                    if (dateCompare < dateStart || dateCompare >= dateEnd) {
                        return false;
                    }
                }

                return true;
            }
        );

        // Apply filters button click
        $('#applyFilters').click(function() {
            table.draw();
            updateTotals();
            updateDoneEntries();
        });

        // Reset filters button click
        $('#resetFilters').click(function() {
            $('#categoryFilter').val('');
            $('#startDate').val('');
            $('#endDate').val('');
            table.draw();
            updateTotals();
            updateDoneEntries();
        });

        // Function to update totals
        function updateTotals() {
            var totalRequested = 0;
            var totalActual = 0;
            var totalSelisih = 0;
            var visibleRows = 0;
            var doneEntries = 0;

            // Loop through visible rows only
            $('#caTable tbody tr:visible').each(function() {
                // Get values from data attributes
                var jumlahRequest = parseFloat($(this).find('td.jumlah-request').attr('data-value')) || 0;
                var jumlahActual = parseFloat($(this).find('td.jumlah-actual').attr('data-value')) || 0;
                var selisih = parseFloat($(this).find('td.selisih').attr('data-value')) || 0;

                // Update totals
                totalRequested += jumlahRequest;
                totalActual += jumlahActual;
                totalSelisih += selisih;
                visibleRows++;
            });

            // Update the total display
            $('#totalRequested').text('Rp ' + formatNumber(totalRequested));
            $('#totalActual').text('Rp ' + formatNumber(totalActual));
            $('#totalSelisih').text('Rp ' + formatNumber(totalSelisih));
            $('#totalEntries').text(visibleRows);
        }

        // Function to update done entries count
        function updateDoneEntries() {
            var doneCount = 0;

            // Loop through visible rows only
            $('#caTable tbody tr:visible').each(function() {
                var status = $(this).find('td:nth-child(12)').text().trim();
                if (status === 'Done') {
                    doneCount++;
                }
            });

            // Update the done entries display
            $('#doneEntries').text(doneCount);
        }

        // Helper function to format numbers with commas
        function formatNumber(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Update totals when DataTable redraw event occurs
        table.on('draw.dt', function() {
            updateTotals();
            updateDoneEntries();
        });
    });

    // Initialize all currency inputs on the page
    function initializeCurrencyInputs() {
        // Get all elements with the class 'currency-input'
        const currencyInputs = document.querySelectorAll('.currency-input');

        // Add event listeners to each currency input
        currencyInputs.forEach(input => {
            // Format on input (as user types)
            input.addEventListener('input', function() {
                formatCurrencyInput(this);
            });

            // Format on focus out
            input.addEventListener('blur', function() {
                ensureFormattedValue(this);
            });

            // When focused, place cursor at the end
            input.addEventListener('focus', function() {
                // If the field isn't empty, place cursor at the end
                if (this.value) {
                    const valueLength = this.value.length;
                    this.setSelectionRange(valueLength, valueLength);
                }
            });

            // Initial formatting
            if (input.value) {
                formatCurrencyInput(input);
            }
        });

        // Handle form submissions
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // Get all currency inputs within this form
                const currencyInputs = this.querySelectorAll('.currency-input');

                // Unformat each input before submission
                currencyInputs.forEach(input => {
                    input.value = unformatCurrency(input.value);
                });
            });
        });
    }

    // Format a single currency input
    function formatCurrencyInput(input) {
        // Store cursor position
        const cursorPos = input.selectionStart;
        const originalLength = input.value.length;

        // Clean and format the number
        const unformattedValue = unformatCurrency(input.value);
        const formattedValue = formatNumberWithDots(unformattedValue);

        // Update input value
        input.value = formattedValue;

        // Calculate new cursor position
        const newLength = input.value.length;
        const posDiff = newLength - originalLength;

        // Restore cursor position
        if (document.activeElement === input) {
            const newPosition = Math.max(0, Math.min(cursorPos + posDiff, newLength));
            input.setSelectionRange(newPosition, newPosition);
        }
    }

    // Ensure value is properly formatted
    function ensureFormattedValue(input) {
        if (input.value) {
            // Make sure the value is clean first, then format it
            const cleanValue = unformatCurrency(input.value);
            input.value = formatNumberWithDots(cleanValue);
        }
    }

    // Format a number with thousand separators (dots)
    function formatNumberWithDots(num) {
        // Make sure we're working with a string
        num = String(num);

        // Remove existing non-numeric chars except decimal point
        num = num.replace(/[^\d.]/g, '');

        // Ensure only one decimal point
        const parts = num.split('.');
        num = parts[0] + (parts.length > 1 ? '.' + parts.slice(1).join('') : '');

        // Split integer and decimal parts
        const integerPart = parts[0];
        const decimalPart = parts.length > 1 ? '.' + parts[1] : '';

        // Add thousand separators to integer part
        const formattedInteger = integerPart.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        // Combine the formatted parts
        return formattedInteger + decimalPart;
    }

    // Clean a formatted number string (remove dots)
    function unformatCurrency(value) {
        // Make sure we're working with a string
        value = String(value || '');

        // Remove all thousand separators (dots)
        return value.replace(/\./g, '');
    }

    // Function to handle opening the detail modal
    function openDetailModal(rowData, tr) {
        // Get the values from the row data
        const id_ca = rowData[0];
        const deskripsi = rowData[1];
        const kategori = rowData[2];
        const requestBy = rowData[3];
        const dateRequest = rowData[4]; // hidden column
        const jumlahRequest = rowData[5];
        const jumlahActual = rowData[6];
        const selisih = rowData[7]; // hidden column
        const dateSettlement = rowData[8]; // hidden column
        const buktiNota = rowData[9]; // hidden column
        const buktiRefund = rowData[10]; // hidden column
        const status = rowData[11];
        const actions = rowData[12]; // hidden column with action buttons

        // Fill the modal with data
        document.getElementById('detail_id_ca').textContent = id_ca;
        document.getElementById('detail_deskripsi').textContent = deskripsi;
        document.getElementById('detail_kategori').textContent = kategori;
        document.getElementById('detail_request_by').value = requestBy; // Store the idnik as a hidden value
        document.getElementById('detail_request_by_name').textContent = requestBy; // This now shows the name from the table
        document.getElementById('detail_date_request').textContent = dateRequest;
        document.getElementById('detail_jumlah_request').textContent = jumlahRequest;
        document.getElementById('detail_jumlah_actual').textContent = jumlahActual || 'Not settled yet';
        document.getElementById('detail_selisih').innerHTML = selisih;
        document.getElementById('detail_date_settlement').textContent = dateSettlement || 'Not settled yet';
        document.getElementById('detail_status').innerHTML = status;

        // Handle the bukti nota and bukti refund links
        const buktiNotaContainer = document.getElementById('detail_bukti_nota');
        const buktiRefundContainer = document.getElementById('detail_bukti_refund');

        if (buktiNota && buktiNota.includes('download')) {
            buktiNotaContainer.innerHTML = buktiNota;
        } else {
            buktiNotaContainer.innerHTML = '<span class="badge bg-light text-muted">No File</span>';
        }

        if (buktiRefund && buktiRefund.includes('download')) {
            buktiRefundContainer.innerHTML = buktiRefund;
        } else {
            buktiRefundContainer.innerHTML = '<span class="badge bg-light text-muted">No File</span>';
        }

        // Extract action buttons and add them to the modal footer
        const actionButtons = document.getElementById('detail_action_buttons');

        // First, clear any existing buttons
        actionButtons.innerHTML = '';

        // If we have action buttons in the row data, add them to the modal
        if (actions) {
            // Create a temporary div to parse the HTML
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = actions;

            // Get all buttons from the parsed HTML
            const buttons = tempDiv.querySelectorAll('button, a');

            // Append each button to the action buttons container
            buttons.forEach(button => {
                // Add margin class to space buttons properly
                button.classList.add('me-2');
                actionButtons.appendChild(button);
            });
        }

        // Show the modal
        $('#detailModal').modal('show');
    }

    // Edit modal function
    function openEditModal(data) {
        // Fill modal inputs with data
        document.getElementById('edit_id_ca').value = data.id_ca;
        document.getElementById('edit_deskripsi').value = data.deskripsi;
        document.getElementById('edit_kategori').value = data.kategori;
        document.getElementById('edit_request').value = data.request;
        document.getElementById('edit_date_request').value = data.date_request;

        // Format currency value
        const jumlahRequestInput = document.getElementById('edit_jumlah_request');
        jumlahRequestInput.value = formatNumberWithDots(data.jumlah_request);

        document.getElementById('edit_status').value = data.status;

        // Show modal
        $('#editModal').modal('show');
    }

    // Settlement modal function
    function openSettlementModal(id_ca, deskripsi) {
        // Fill settlement modal
        document.getElementById('settlement_id_ca').value = id_ca;
        document.getElementById('settlement_deskripsi').textContent = deskripsi;

        // Clear and focus the jumlah_actual field
        const jumlahActualInput = document.querySelector('#settlementModal .currency-input');
        jumlahActualInput.value = '';

        // Show modal
        $('#settlementModal').modal('show');

        // Focus on the amount field after modal is shown
        $('#settlementModal').on('shown.bs.modal', function() {
            jumlahActualInput.focus();
        });
    }

    // Refund modal function
    function openRefundModal(id_ca, deskripsi, selisih) {
        // Fill refund modal
        document.getElementById('refund_id_ca').value = id_ca;
        document.getElementById('refund_deskripsi').textContent = deskripsi;

        // Format selisih amount with dots for readability
        const refundAmountInput = document.getElementById('refund_amount');
        refundAmountInput.value = formatNumberWithDots(selisih);

        // Show modal
        $('#refundModal').modal('show');

        // Focus on the refund amount field after modal is shown
        $('#refundModal').on('shown.bs.modal', function() {
            refundAmountInput.focus();
        });
    }

    // Helper functions for the action buttons
    function openEditModalFromDetail(data) {
        $('#detailModal').modal('hide');
        setTimeout(() => {
            openEditModal(data);
        }, 500);
    }

    function openSettlementModalFromDetail(id_ca, deskripsi) {
        $('#detailModal').modal('hide');
        setTimeout(() => {
            openSettlementModal(id_ca, deskripsi);
        }, 500);
    }

    function openRefundModalFromDetail(id_ca, deskripsi, selisih) {
        $('#detailModal').modal('hide');
        setTimeout(() => {
            openRefundModal(id_ca, deskripsi, selisih);
        }, 500);
    }

    function confirmDeleteFromDetail(id_ca, deskripsi) {
        $('#detailModal').modal('hide');
        setTimeout(() => {
            confirmDelete(id_ca, deskripsi);
        }, 500);
    }

    function completeCAFromDetail(id_ca) {
        $('#detailModal').modal('hide');
        if (confirm('Are you sure you want to mark this Cash Advance as completed?')) {
            window.location.href = 'function/complete_ca.php?id_ca=' + id_ca;
        }
    }

    function confirmDelete(id_ca, deskripsi) {
        // Set confirmation message
        document.getElementById('deleteMessage').innerHTML =
            'You are about to delete Cash Advance <strong>' + deskripsi + '</strong>.<br>' +
            'This action cannot be undone.';

        // Set the delete button href
        document.getElementById('deleteButton').href = 'function/delete_ca.php?id_ca=' + id_ca;

        // Show the modal
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    // Initialize when the document is ready
    document.addEventListener('DOMContentLoaded', function() {
        // Prevent double-click on delete
        const deleteBtn = document.getElementById('deleteButton');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function(event) {
                this.disabled = true;
                this.innerHTML = '<i class="ri-loader-4-line align-bottom"></i> Deleting...';
            });
        }

        // Add tooltip functionality to action buttons
        enableTooltips();

        // Initialize debugging for form submissions if needed
        setupFormDebugging();
    });

    // Enable tooltips for action buttons
    function enableTooltips() {
        // Add tooltips to action buttons
        const actionButtons = document.querySelectorAll('.action-btn');
        actionButtons.forEach(button => {
            const icon = button.querySelector('i');
            if (icon) {
                let tooltipText = '';

                if (icon.classList.contains('ri-pencil-fill')) {
                    tooltipText = 'Edit';
                    button.setAttribute('title', tooltipText);
                } else if (icon.classList.contains('ri-delete-bin-5-line')) {
                    tooltipText = 'Delete';
                    button.setAttribute('title', tooltipText);
                } else if (icon.classList.contains('ri-exchange-funds-fill')) {
                    tooltipText = 'Settle';
                    button.setAttribute('title', tooltipText);
                } else if (icon.classList.contains('ri-refund-2-line')) {
                    tooltipText = 'Refund';
                    button.setAttribute('title', tooltipText);
                } else if (icon.classList.contains('ri-checkbox-circle-line')) {
                    tooltipText = 'Complete';
                    button.setAttribute('title', tooltipText);
                }
            }
        });

        // Initialize tooltips if Bootstrap 5 is available
        if (typeof bootstrap !== 'undefined' && typeof bootstrap.Tooltip !== 'undefined') {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl, {
                    placement: 'top',
                    trigger: 'hover'
                });
            });
        }
    }

    // Setup debugging for form submissions
    function setupFormDebugging() {
        // Debug for refund form submission
        const refundForm = document.getElementById('refundForm');
        if (refundForm) {
            refundForm.addEventListener('submit', function(e) {
                console.log('Submitting refund form...');

                // Create FormData object to check what's being submitted
                const formData = new FormData(this);
                for (let [key, value] of formData.entries()) {
                    if (value instanceof File) {
                        console.log(`${key}: File name: ${value.name}, Size: ${value.size} bytes`);
                    } else {
                        console.log(`${key}: ${value}`);
                    }
                }

                // Show submission feedback
                showSubmissionFeedback('Refund form submitted');
            });
        }

        // Debug for settlement form submission
        const settlementForm = document.getElementById('settlementForm');
        if (settlementForm) {
            settlementForm.addEventListener('submit', function(e) {
                console.log('Submitting settlement form...');

                // Create FormData object to check what's being submitted
                const formData = new FormData(this);
                for (let [key, value] of formData.entries()) {
                    if (value instanceof File) {
                        console.log(`${key}: File name: ${value.name}, Size: ${value.size} bytes`);
                    } else {
                        console.log(`${key}: ${value}`);
                    }
                }

                // Show submission feedback
                showSubmissionFeedback('Settlement form submitted');
            });
        }
    }

    // Show submission feedback
    function showSubmissionFeedback(message) {
        const debugDiv = document.createElement('div');
        debugDiv.style.position = 'fixed';
        debugDiv.style.bottom = '10px';
        debugDiv.style.right = '10px';
        debugDiv.style.backgroundColor = 'rgba(0,0,0,0.7)';
        debugDiv.style.color = 'white';
        debugDiv.style.padding = '10px';
        debugDiv.style.borderRadius = '5px';
        debugDiv.style.zIndex = '9999';
        debugDiv.textContent = message + ' at ' + new Date().toLocaleTimeString();
        document.body.appendChild(debugDiv);

        // Auto-remove after 5 seconds
        setTimeout(() => {
            document.body.removeChild(debugDiv);
        }, 5000);
    }
</script>
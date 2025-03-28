<?php
// Pastikan user sudah login
if (!isset($_SESSION['idnik'])) {
    header("Location: index.php");
    exit();
}
?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.2.0/css/searchPanes.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/searchpanes/2.2.0/js/dataTables.searchPanes.min.js"></script>
<script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<div class="row">
    <div class="col-lg-12">
        <div class="card-header border-0">
            <div class="d-flex align-items-center">
                <div class="card-title mb-0 flex-grow-1">
                    <h5>Cash Out Export</h5>
                    <h6>Export petty cash data berdasarkan filter yang ditentukan.</h6>
                </div>
                <div class="flex-shrink-0">
                    <button id="printSelectedBtn" class="btn btn-success" disabled>
                        <i class="ri-printer-line align-bottom me-1"></i> Print Selected Data
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body border border-dashed border-end-0 border-start-0">
            <div class="row">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="filterKategori" class="form-label">Filter Kategori</label>
                        <select id="filterKategori" class="select2 form-control" multiple="multiple">
                            <?php
                            $queryKategori = "SELECT DISTINCT kategori FROM petty_cash_out WHERE (is_deleted = 0 OR is_deleted IS NULL)";
                            $resultKategori = mysqli_query($koneksi, $queryKategori);
                            while ($rowKategori = mysqli_fetch_assoc($resultKategori)) {
                                echo "<option value='" . htmlspecialchars($rowKategori['kategori']) . "'>" . htmlspecialchars($rowKategori['kategori']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="filterCompany" class="form-label">Filter Company</label>
                        <select id="filterCompany" class="select2 form-control" multiple="multiple">
                            <?php
                            $queryCompany = "SELECT DISTINCT company FROM petty_cash_out WHERE (is_deleted = 0 OR is_deleted IS NULL)";
                            $resultCompany = mysqli_query($koneksi, $queryCompany);
                            while ($rowCompany = mysqli_fetch_assoc($resultCompany)) {
                                echo "<option value='" . htmlspecialchars($rowCompany['company']) . "'>" . htmlspecialchars($rowCompany['company']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="filterSettlementId" class="form-label">Filter ID Settlement</label>
                        <select id="filterSettlementId" class="select2 form-control" multiple="multiple">
                            <?php
                            $querySettlement = "SELECT DISTINCT id_settled FROM petty_cash_out WHERE status = 'Settled' AND (is_deleted = 0 OR is_deleted IS NULL) AND id_settled IS NOT NULL";
                            $resultSettlement = mysqli_query($koneksi, $querySettlement);
                            while ($rowSettlement = mysqli_fetch_assoc($resultSettlement)) {
                                if (!empty($rowSettlement['id_settled'])) {
                                    echo "<option value='" . htmlspecialchars($rowSettlement['id_settled']) . "'>" . htmlspecialchars($rowSettlement['id_settled']) . "</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="startDate" class="form-label">Tanggal Mulai</label>
                        <input type="date" id="startDate" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="endDate" class="form-label">Tanggal Akhir</label>
                        <input type="date" id="endDate" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="dateType" class="form-label">Tipe Tanggal</label>
                        <select id="dateType" class="form-select">
                            <option value="date">Tanggal Transaksi</option>
                            <option value="date_settled">Tanggal Settlement</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filterRequestor" class="form-label">Filter Requestor</label>
                        <select id="filterRequestor" class="select2 form-control" multiple="multiple">
                            <?php
                            $queryRequestors = "SELECT DISTINCT u.idnik, u.nama 
                                               FROM petty_cash_out pco
                                               LEFT JOIN user u ON pco.request = u.idnik
                                               WHERE (pco.is_deleted = 0 OR pco.is_deleted IS NULL) 
                                               AND pco.status = 'Settled'
                                               AND u.nama IS NOT NULL
                                               ORDER BY u.nama";
                            $resultRequestors = mysqli_query($koneksi, $queryRequestors);
                            while ($rowRequestor = mysqli_fetch_assoc($resultRequestors)) {
                                echo "<option value='" . htmlspecialchars($rowRequestor['nama']) . "'>" . htmlspecialchars($rowRequestor['nama']) . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-12 d-flex justify-content-end">
                        <button id="resetFilter" class="btn btn-secondary me-2">Reset Filter</button>
                        <button id="applyFilter" class="btn btn-primary">Terapkan Filter</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-3">
                                        <h5 class="card-title mb-0 me-auto">Data Cash Out (Settled)</h5>
                                        <div class="d-flex align-items-center">
                                            <div class="card bg-light p-2 me-3">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2">Total Data:</span>
                                                    <span id="totalData" class="fw-bold">0</span>
                                                </div>
                                            </div>
                                            <div class="card bg-light p-2">
                                                <div class="d-flex align-items-center">
                                                    <span class="me-2">Total Selected:</span>
                                                    <span id="totalSelectedAmount" class="fw-bold">Rp 0</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <table id="cashOutTable" class="stripe row-border order-column" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="checkbox" id="checkAll">
                                                    </div>
                                                </th>
                                                <th>ID</th>
                                                <th>Deskripsi</th>
                                                <th>Kategori</th>
                                                <th>Tanggal</th>
                                                <th>Harga</th>
                                                <th>Company</th>
                                                <th>Request</th>
                                                <th>ID Settlement</th>
                                                <th>Tanggal Settled</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Query untuk mengambil data dengan status Settled
                                            $sql = "SELECT 
                                                pco.id_pc_out, 
                                                pco.deskripsi,
                                                pco.kategori,
                                                pco.date, 
                                                pco.harga,
                                                pco.company,
                                                u.nama AS requestor,
                                                pco.id_settled,
                                                pco.date_settled
                                            FROM 
                                                petty_cash_out pco
                                            LEFT JOIN user u ON pco.request = u.idnik
                                            WHERE pco.status = 'Settled' 
                                            AND (pco.is_deleted = 0 OR pco.is_deleted IS NULL)
                                            ORDER BY pco.date_settled DESC";

                                            $result = mysqli_query($koneksi, $sql);
                                            if (!$result) {
                                                die("Query failed: " . mysqli_error($koneksi));
                                            }

                                            $totalHarga = 0;
                                            $rowCount = 0;
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $totalHarga += $row['harga'];
                                                $rowCount++;
                                            ?>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input row-checkbox" type="checkbox"
                                                                value="<?= $row['id_pc_out'] ?>"
                                                                data-description="<?= htmlspecialchars($row['deskripsi']); ?>"
                                                                data-date="<?= htmlspecialchars($row['date']); ?>"
                                                                data-harga="<?= $row['harga']; ?>"
                                                                data-category="<?= htmlspecialchars($row['kategori']); ?>"
                                                                data-company="<?= htmlspecialchars($row['company']); ?>"
                                                                data-requestor="<?= htmlspecialchars($row['requestor']); ?>"
                                                                data-settlement-id="<?= htmlspecialchars($row['id_settled']); ?>"
                                                                data-settlement-date="<?= htmlspecialchars($row['date_settled']); ?>">
                                                        </div>
                                                    </td>
                                                    <td><?= htmlspecialchars($row['id_pc_out']); ?></td>
                                                    <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                                                    <td><?= htmlspecialchars($row['kategori']); ?></td>
                                                    <td><?= htmlspecialchars($row['date']); ?></td>
                                                    <td data-sort="<?= $row['harga']; ?>"><?= number_format($row['harga'], 0, ',', '.'); ?></td>
                                                    <td><?= htmlspecialchars($row['company']); ?></td>
                                                    <td><?= htmlspecialchars($row['requestor']); ?></td>
                                                    <td><?= htmlspecialchars($row['id_settled']); ?></td>
                                                    <td><?= htmlspecialchars($row['date_settled']); ?></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="5" style="text-align:right">Total:</th>
                                                <th id="totalHarga"><?= number_format($totalHarga, 0, ',', '.'); ?></th>
                                                <th colspan="4"></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript untuk fungsionalitas halaman -->
<script>
    $(document).ready(function() {
        // Inisialisasi Select2 untuk multiple select
        $('.select2').select2({
            placeholder: "Pilih opsi...",
            allowClear: true
        });

        // Set default date values (awal bulan sampai akhir bulan)
        function setDefaultDates() {
            var today = new Date();
            var firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            var lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);

            $('#startDate').val(firstDay.toISOString().split('T')[0]);
            $('#endDate').val(lastDay.toISOString().split('T')[0]);
        }

        setDefaultDates();

        // Tampilkan total data
        $('#totalData').text(<?= $rowCount ?>);

        // Format number dengan titik sebagai pemisah ribuan
        function formatNumber(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Inisialisasi DataTable
        var table = $('#cashOutTable').DataTable({
            responsive: true,
            scrollX: true,
            scrollCollapse: true,
            order: [
                [3, 'desc']
            ], // Sort by date
            lengthMenu: [
                [10, 25, 50, 100, -1],
                ['10 rows', '25 rows', '50 rows', '100 rows', 'Show all']
            ],
            buttons: [
                'pageLength',
                {
                    extend: 'colvis',
                    text: 'Column View',
                }
            ],
            dom: 'Bfrtip',
            columnDefs: [{
                    orderable: false,
                    targets: 0
                } // Disable sorting on checkbox column
            ]
        });

        // Handle "check all" checkbox
        $('#checkAll').on('click', function() {
            $('.row-checkbox').prop('checked', $(this).prop('checked'));
            updateSelectedTotal();
            updatePrintButtonState();
        });

        // Handle klik pada checkbox
        $(document).on('change', '.row-checkbox', function() {
            updateSelectedTotal();
            updatePrintButtonState();

            // Update checkAll status
            const totalCheckboxes = $('.row-checkbox').length;
            const checkedCheckboxes = $('.row-checkbox:checked').length;
            $('#checkAll').prop('checked', totalCheckboxes === checkedCheckboxes && totalCheckboxes > 0);
        });

        // Kalkulasi total data yang dicentang
        function updateSelectedTotal() {
            let totalAmount = 0;

            $('.row-checkbox:checked').each(function() {
                const harga = parseFloat($(this).data('harga'));
                totalAmount += harga;
            });

            // Update tampilan total yang dipilih
            $('#totalSelectedAmount').text('Rp ' + formatNumber(totalAmount));
        }

        // Update status tombol Print
        function updatePrintButtonState() {
            const checkedCount = $('.row-checkbox:checked').length;
            $('#printSelectedBtn').prop('disabled', checkedCount === 0);
        }

        // Reset filters
        $('#resetFilter').on('click', function() {
            $('#filterKategori').val(null).trigger('change');
            $('#filterCompany').val(null).trigger('change');
            $('#filterSettlementId').val(null).trigger('change');
            $('#filterRequestor').val(null).trigger('change');
            $('#dateType').val('date');
            setDefaultDates();

            // Remove all custom search functions
            while ($.fn.dataTable.ext.search.length > 0) {
                $.fn.dataTable.ext.search.pop();
            }

            // Clear all filters
            table.search('').columns().search('').draw();
        });

        // Apply filters
        $('#applyFilter').on('click', function() {
            var kategoriValues = $('#filterKategori').val();
            var companyValues = $('#filterCompany').val();
            var settlementValues = $('#filterSettlementId').val();
            var requestorValues = $('#filterRequestor').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();
            var dateType = $('#dateType').val();

            // Clear existing filters
            table.search('').columns().search('').draw();

            // Remove any existing date filter function
            while ($.fn.dataTable.ext.search.length > 0) {
                $.fn.dataTable.ext.search.pop();
            }

            // Apply kategori filter (column 3 is "Kategori")
            if (kategoriValues && kategoriValues.length > 0) {
                var kategoriFilter = kategoriValues.join('|');
                table.column(3).search(kategoriFilter, true, false);
            }

            // Apply company filter (column 6 is "Company")
            if (companyValues && companyValues.length > 0) {
                var companyFilter = companyValues.join('|');
                table.column(6).search(companyFilter, true, false);
            }

            // Apply settlement ID filter (column 8 is "ID Settlement")
            if (settlementValues && settlementValues.length > 0) {
                var settlementFilter = settlementValues.join('|');
                table.column(8).search(settlementFilter, true, false);
            }

            // Apply requestor filter (column 7 is "Requestor")
            if (requestorValues && requestorValues.length > 0) {
                var requestorFilter = requestorValues.join('|');
                table.column(7).search(requestorFilter, true, false);
            }

            // Apply date filter if dates are provided
            if (startDate || endDate) {
                var startDateObj = startDate ? new Date(startDate) : null;
                var endDateObj = endDate ? new Date(endDate) : null;

                // Determine which date column to filter (4 for transaction date, 9 for settled date)
                var dateColumnIndex = (dateType === 'date_settled') ? 9 : 4;

                // Add date filter function
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        var dateStr = data[dateColumnIndex];
                        if (!dateStr) return true;

                        var rowDate = new Date(dateStr);

                        // Check if date parsing was successful
                        if (isNaN(rowDate.getTime())) {
                            console.error('Failed to parse date:', dateStr);
                            return true;
                        }

                        // Filter based on date range
                        if (startDateObj && !endDateObj) {
                            return rowDate >= startDateObj;
                        }

                        if (!startDateObj && endDateObj) {
                            return rowDate <= endDateObj;
                        }

                        if (startDateObj && endDateObj) {
                            return rowDate >= startDateObj && rowDate <= endDateObj;
                        }

                        return true;
                    }
                );
            }

            // Apply all filters
            table.draw();

            // Uncheck all checkboxes after filtering
            $('#checkAll').prop('checked', false);
            $('.row-checkbox').prop('checked', false);
            updateSelectedTotal();
            updatePrintButtonState();
        });

        // Handle Print Selected button
        $('#printSelectedBtn').on('click', function() {
            // Collect selected items data
            var selectedItems = [];

            $('.row-checkbox:checked').each(function() {
                var $checkbox = $(this);

                selectedItems.push({
                    id: $checkbox.val(),
                    description: $checkbox.data('description'),
                    date: $checkbox.data('date'),
                    harga: $checkbox.data('harga'),
                    category: $checkbox.data('category'),
                    company: $checkbox.data('company'),
                    requestor: $checkbox.data('requestor'),
                    settlementId: $checkbox.data('settlement-id'),
                    settlementDate: $checkbox.data('settlement-date')
                });
            });

            // Store selected items in sessionStorage
            sessionStorage.setItem('selectedCashOutItems', JSON.stringify(selectedItems));

            // Open print view in a new tab/window
            window.open('index.php?page=CashOutPrint', '_blank');
        });
    });
</script>
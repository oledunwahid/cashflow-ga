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
                <div class="row mb-3 justify-content-end">
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
                                                <th>Harga</th>
                                                <th>Company</th>
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
pco.harga,
pco.company,
pco.id_settled,
pco.date_settled
FROM 
petty_cash_out pco
WHERE pco.status = 'Settled' 
AND (pco.is_deleted = 0 OR pco.is_deleted IS NULL)
ORDER BY pco.date_settled DESC";

                                            $result = mysqli_query($koneksi, $sql);
                                            if (!$result) {
                                                die("Query failed: " . mysqli_error($koneksi));
                                            }

                                            $totalHarga = 0;
                                            $rowCount = 0;

                                            // Simpan hasil query dalam array untuk digunakan dalam loop
                                            $rows = array();
                                            while ($row = mysqli_fetch_assoc($result)) {
                                                $rows[] = $row;
                                                $totalHarga += $row['harga'];
                                                $rowCount++;
                                            }

                                            // Kemudian gunakan $rows dalam loop foreach untuk menampilkan data
                                            foreach ($rows as $row) {
                                            ?>
                                                <tr>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input row-checkbox" type="checkbox"
                                                                value="<?= $row['id_pc_out'] ?>"
                                                                data-description="<?= htmlspecialchars($row['deskripsi']); ?>"
                                                                data-harga="<?= $row['harga']; ?>"
                                                                data-category="<?= htmlspecialchars($row['kategori']); ?>"
                                                                data-company="<?= htmlspecialchars($row['company']); ?>"
                                                                data-settlement-id="<?= htmlspecialchars($row['id_settled']); ?>"
                                                                data-settlement-date="<?= htmlspecialchars($row['date_settled']); ?>">
                                                        </div>
                                                    </td>
                                                    <td><?= htmlspecialchars($row['id_pc_out']); ?></td>
                                                    <td><?= htmlspecialchars($row['deskripsi']); ?></td>
                                                    <td><?= htmlspecialchars($row['kategori']); ?></td>
                                                    <td data-sort="<?= $row['harga']; ?>"><?= number_format($row['harga'], 0, ',', '.'); ?></td>
                                                    <td><?= htmlspecialchars($row['company']); ?></td>
                                                    <td><?= htmlspecialchars($row['id_settled']); ?></td>
                                                    <td><?= htmlspecialchars($row['date_settled']); ?></td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="4" style="text-align:right">Total:</th>
                                                <th id="totalHarga"><?= number_format($totalHarga, 0, ',', '.'); ?></th>
                                                <th colspan="3"></th>
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
                [7, 'desc']
            ], // Sort by settlement date
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

            // Clear existing filters
            table.search('').columns().search('').draw();

            // Remove any existing search functions
            while ($.fn.dataTable.ext.search.length > 0) {
                $.fn.dataTable.ext.search.pop();
            }

            // Apply kategori filter (column 3 is "Kategori")
            if (kategoriValues && kategoriValues.length > 0) {
                var kategoriFilter = kategoriValues.join('|');
                table.column(3).search(kategoriFilter, true, false);
            }

            // Apply company filter (column 5 is "Company")
            if (companyValues && companyValues.length > 0) {
                var companyFilter = companyValues.join('|');
                table.column(5).search(companyFilter, true, false);
            }

            // Apply settlement ID filter (column 6 is "ID Settlement")
            if (settlementValues && settlementValues.length > 0) {
                var settlementFilter = settlementValues.join('|');
                table.column(6).search(settlementFilter, true, false);
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
            // Disable tombol untuk mencegah klik ganda
            $(this).prop('disabled', true).html('<i class="ri-loader-4-line align-bottom me-1 spin"></i> Processing...');

            // Collect selected items data
            var selectedItems = [];

            $('.row-checkbox:checked').each(function() {
                var $checkbox = $(this);

                selectedItems.push({
                    id: $checkbox.val(),
                    description: $checkbox.data('description'),
                    harga: $checkbox.data('harga'),
                    category: $checkbox.data('category'),
                    company: $checkbox.data('company'),
                    settlementId: $checkbox.data('settlement-id'),
                    settlementDate: $checkbox.data('settlement-date')
                });
            });

            // Jika tidak ada item yang dipilih
            if (selectedItems.length === 0) {
                showAlert('warning', 'Warning', 'Please select at least one item to print.');
                $(this).prop('disabled', false).html('<i class="ri-printer-line align-bottom me-1"></i> Print Selected Data');
                return;
            }

            // Buat data reimbursement
            var reimbursementData = {
                items: selectedItems,
                company: selectedItems[0].company,
                reference: selectedItems[0].settlementId && selectedItems[0].settlementId.trim() !== '' ?
                    selectedItems[0].settlementId : 'REF-' + new Date().toISOString().slice(0, 10).replace(/-/g, ''),
                voucher: 'VC-' + new Date().toISOString().slice(0, 10).replace(/-/g, '') + '-' +
                    new Date().getHours().toString().padStart(2, '0') +
                    new Date().getMinutes().toString().padStart(2, '0')
            };

            // Simpan di sessionStorage sementara
            sessionStorage.setItem('selectedCashOutItems', JSON.stringify(selectedItems));

            // Simpan data reimbursement ke database melalui AJAX
            $.ajax({
                url: 'function/save_reimbursement.php',
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify(reimbursementData),
                success: function(response) {
                    try {
                        var result = typeof response === 'string' ? JSON.parse(response) : response;

                        if (result.status === 'success') {
                            // Simpan ID print di sessionStorage juga
                            sessionStorage.setItem('reimbursementData', JSON.stringify({
                                id_print: result.data.id_print,
                                print_reference: result.data.print_reference,
                                print_voucher: result.data.print_voucher
                            }));

                            // Buka halaman print dengan ID yang baru dibuat
                            window.open('index.php?page=CashOutPrint&id=' + result.data.id_print, '_blank');
                        } else {
                            showAlert('error', 'Error', result.message || 'Failed to save reimbursement data');
                        }
                    } catch (e) {
                        console.error('Error parsing response:', e, response);
                        showAlert('error', 'Error', 'An error occurred while processing the response');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', xhr.responseText, status, error);
                    showAlert('error', 'Error', 'Failed to save reimbursement data: ' + error);
                },
                complete: function() {
                    // Re-enable tombol
                    $('#printSelectedBtn').prop('disabled', false).html('<i class="ri-printer-line align-bottom me-1"></i> Print Selected Data');
                }
            });
        });

        // Fungsi untuk menampilkan alert
        function showAlert(type, title, message) {
            if (typeof Swal !== 'undefined') {
                // Jika SweetAlert2 tersedia
                Swal.fire({
                    icon: type,
                    title: title,
                    text: message
                });
            } else {
                // Fallback ke alert biasa
                alert(title + ': ' + message);
            }
        }
    });
</script>
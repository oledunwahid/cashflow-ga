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

<div class="row">
    <div class="col-lg-12">
        <div class="row mb-3">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Total Petty Cash (Unsettled)</h5>
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="card-text fs-3 fw-semibold mb-0" id="totalUnsettled">Rp 0</p>
                                <p class="text-muted mb-0">Total harga dengan status Unsettled</p>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="ri-alert-line text-warning fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Saldo Petty Cash</h5>
                        <div class="d-flex align-items-center">
                            <div class="flex-grow-1">
                                <p class="card-text fs-3 fw-semibold mb-0" id="totalBalance">Rp 0</p>
                                <p class="text-muted mb-0">Selisih petty cash in dan out</p>
                            </div>
                            <div class="flex-shrink-0">
                                <i class="ri-exchange-dollar-line text-primary fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="card-header border-0">
            <div class="d-flex align-items-center">
                <div class="card-title mb-0 flex-grow-1">
                    <h5>Petty Cash</h5>
                    <h6>Manage your petty cash transactions here.</h6>
                </div>
                <div class="flex-shrink-0 m-3">
                    <button class="btn btn-success add-btn" data-bs-toggle="modal" data-bs-target="#showModal">
                        <i class="ri-add-line align-bottom me-1"></i> Create Petty Cash
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body border border-dashed border-end-0 border-start-0">
            <div class="row">
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="filterKategori" class="form-label">Filter Kategori</label>
                        <select id="filterKategori" class="form-select">
                            <option value="">Semua Kategori</option>
                            <?php
                            $queryKategori = "SELECT DISTINCT kategori FROM petty_cash_out WHERE is_deleted = 0 OR is_deleted IS NULL";
                            $resultKategori = mysqli_query($koneksi, $queryKategori);
                            while ($rowKategori = mysqli_fetch_assoc($resultKategori)) {
                                echo "<option value='" . $rowKategori['kategori'] . "'>" . $rowKategori['kategori'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="filterStatus" class="form-label">Filter Status</label>
                        <select id="filterStatus" class="form-select">
                            <option value="">Semua Status</option>
                            <option value="Settled">Settled</option>
                            <option value="Unsettled">Unsettled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="startDate" class="form-label">Tanggal Mulai</label>
                        <input type="date" id="startDate" class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label for="endDate" class="form-label">Tanggal Akhir</label>
                        <input type="date" id="endDate" class="form-control">
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
                                        <h5 class="card-title mb-0 me-auto">Data Petty Cash</h5>
                                        <div class="d-flex align-items-center">
                                            <div class="me-3">
                                                <div class="card bg-light p-2">
                                                    <div class="d-flex align-items-center">
                                                        <span class="me-2">Total Terpilih:</span>
                                                        <span id="selectedTotalAmount" class="fw-bold">Rp 0</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <button id="updateSelectedButton" class="btn btn-success" disabled>
                                                <i class="ri-check-line align-bottom me-1"></i> Update Status Terpilih
                                            </button>
                                        </div>
                                    </div>

                                    <table id="pettyCashTable" class="stripe row-border order-column" style="width:100%">
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
                                                <th>Date</th>
                                                <th>Harga</th>
                                                <th>Company</th>
                                                <th>Request</th>
                                                <th>Status</th>
                                                <th>Date Settled</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            // Make sure to use proper SQL with soft delete condition
                                            $sql = "SELECT 
            pco.id_pc_out, 
            pco.deskripsi,
            pco.kategori,
            pco.date, 
            pco.harga,
            pco.company,
            pco.request,
            u.nama AS requestor,
            pco.status,
            pco.date_settled
        FROM 
            petty_cash_out pco
        LEFT JOIN user u ON pco.request = u.idnik
        WHERE pco.is_deleted = 0 OR pco.is_deleted IS NULL";

                                            $result = mysqli_query($koneksi, $sql);
                                            if (!$result) {
                                                die("Query failed: " . mysqli_error($koneksi));
                                            }

                                            while ($row = mysqli_fetch_assoc($result)) {
                                                // Disabled checkbox for already settled items
                                                $checkboxDisabled = ($row['status'] == 'Settled') ? 'disabled' : '';
                                                $rowClass = ($row['status'] == 'Settled') ? 'table-success' : '';

                                                // Escape special characters that could break JavaScript
                                                $deskripsi = htmlspecialchars($row['deskripsi'], ENT_QUOTES);
                                                $deskripsi_js = addslashes($deskripsi);
                                            ?>
                                                <tr class="<?= $rowClass ?>">
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input row-checkbox" type="checkbox"
                                                                value="<?= $row['id_pc_out'] ?>" <?= $checkboxDisabled ?>>
                                                        </div>
                                                    </td>
                                                    <td><?= htmlspecialchars($row['id_pc_out']); ?></td>
                                                    <td><?= $deskripsi ?></td>
                                                    <td><?= htmlspecialchars($row['kategori']) ?></td>
                                                    <td><?= htmlspecialchars($row['date']) ?></td>
                                                    <td><?= number_format($row['harga'], 0, ',', '.') ?></td>
                                                    <td><?= htmlspecialchars($row['company']) ?></td>
                                                    <td><?= htmlspecialchars($row['requestor']) ?></td>
                                                    <td>
                                                        <span class="badge <?= ($row['status'] == 'Settled') ? 'bg-success' : 'bg-warning' ?>">
                                                            <?= htmlspecialchars($row['status']) ?>
                                                        </span>
                                                    </td>
                                                    <td><?= htmlspecialchars($row['date_settled']) ?></td>
                                                    <td>
                                                        <button type="button" class="btn btn-soft-secondary btn-sm" onclick="openEditModal({
                        id_pc_out: '<?= htmlspecialchars($row['id_pc_out']) ?>',
                        deskripsi: '<?= $deskripsi_js ?>',
                        kategori: '<?= htmlspecialchars($row['kategori']) ?>',
                        date: '<?= htmlspecialchars($row['date']) ?>',
                        harga: '<?= $row['harga'] ?>',
                        company: '<?= htmlspecialchars($row['company']) ?>',
                        request: '<?= htmlspecialchars($row['request']) ?>'
                    })">
                                                            <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>Edit
                                                        </button>
                                                        <button type="button" class="btn btn-soft-danger btn-sm"
                                                            onclick="confirmDelete('<?= htmlspecialchars($row['id_pc_out']) ?>', '<?= $deskripsi_js ?>')">
                                                            <i class="ri-delete-bin-5-line align-bottom me-2 text-muted"></i>Delete
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
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

<!-- Modal for user to create petty cash -->
<div class="modal fade zoomIn" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-l">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-soft-info">
                <h5 class="modal-title" id="exampleModalLabel">Create Petty Cash</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                    id="close-modal"></button>
            </div>
            <form action="function/insert_petty_cash_out.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <input type="text" class="form-control" name="deskripsi" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date</label>
                                <input type="date" class="form-control" name="date" value="<?= date('Y-m-d') ?>" required />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Harga</label>
                                <input type="text" class="form-control" name="harga" id="harga" required onkeyup="formatCurrency(this)" />
                                <input type="hidden" name="harga_clean" id="harga_clean" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Company</label>
                                <select class="form-control" name="company" required>
                                    <option value="PT. Mineral Alam Abadi">PT. Mineral Alam Abadi</option>
                                    <option value="PT. Mitra Mineral Perkasa">PT. Mitra Mineral Perkasa</option>
                                    <option value="PT. Bima Cakra Perkasa Mineralindo">PT. Bima Cakra Perkasa Mineralindo</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select class="form-control" name="kategori" required>
                                    <option value="Pantry">Pantry</option>
                                    <option value="Operasional">Operasional</option>
                                    <option value="Civil">Civil</option>
                                    <option value="Driver">Driver</option>
                                    <option value="IT">IT</option>
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
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add-petty-cash">Add Petty Cash</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Petty Cash -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-soft-warning">
                <h5 class="modal-title" id="editModalLabel">Edit Petty Cash</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" action="function/update_petty_cash_out.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_pc_out" id="edit_id_pc_out">

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <input type="text" class="form-control" name="deskripsi" id="edit_deskripsi" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date" id="edit_date" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="text" class="form-control" id="edit_harga" data-hidden="edit_harga_clean" required onkeyup="formatCurrency(this)">
                        <input type="hidden" name="harga" id="edit_harga_clean">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Company</label>
                        <select class="form-control" name="company" id="edit_company" required>
                            <option value="PT. Mineral Alam Abadi">PT. Mineral Alam Abadi</option>
                            <option value="PT. Mitra Mineral Perkasa">PT. Mitra Mineral Perkasa</option>
                            <option value="PT. Bima Cakra Perkasa Mineralindo">PT. Bima Cakra Perkasa Mineralindo</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select class="form-control" name="kategori" id="edit_kategori" required>
                            <option value="Pantry">Pantry</option>
                            <option value="Operasional">Operasional</option>
                            <option value="Civil">Civil</option>
                            <option value="Driver">Driver</option>
                            <option value="IT">IT</option>
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
                </div>
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" name="update-petty-cash">Simpan Perubahan</button>
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


<div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-3 bg-soft-success">
                <h5 class="modal-title" id="updateModalLabel">Update Status Transaksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php
                date_default_timezone_set('Asia/Jakarta'); // Pastikan zona waktu sesuai dengan kebutuhan
                $settlementId = "STL" . date("ymdHi"); // Format: STL2503271452
                ?>

                <div class="mb-3">
                    <label for="modalSettlementId" class="form-label">ID-Sattled</label>
                    <input type="text" id="modalSettlementId" class="form-control" value="<?= $settlementId ?>">
                </div>

                <div class="mb-3">
                    <label for="modalSettlementDate" class="form-label">Tanggal Settled</label>
                    <input type="date" id="modalSettlementDate" class="form-control" value="<?= date('Y-m-d') ?>">
                </div>
                <p>Anda akan mengubah status <span id="selectedCount" class="fw-bold">0</span> transaksi menjadi "Settled".</p>
                <p>Total harga yang diupdate: <span id="modalTotalAmount" class="fw-bold">Rp 0</span></p>
                <div class="alert alert-info">
                    <i class="ri-information-line me-2"></i>
                    Aksi ini tidak dapat dibatalkan setelah disetujui.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" id="confirmUpdateBtn">
                    Konfirmasi Update
                </button>
            </div>
        </div>
    </div>
</div>

<script src="../assets/libs/@ckeditor/ckeditor5-build-classic/build/ckeditor.js"></script>

<!-- Custom Scripts -->
<script src="../assets/js/pages/project-create.init.js"></script>
<script src="../assets/js/pages/ticketdetail.init.js"></script>

<!-- SweetAlert2 (Pop-up Alerts) -->
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        var table = $('#pettyCashTable').DataTable({
            responsive: true,
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
                    title: 'Data Export Petty Cash',
                    text: 'Export Excel'
                }
            ],
            dom: 'Bfrtip',
            deferRender: true,
            columnDefs: [{
                    orderable: false,
                    targets: [0, 10]
                } // Disable sorting on checkbox and action columns
            ]
        });

        // Function to fetch and calculate total for unsettled transactions
        function fetchUnsettledTotal() {
            $.ajax({
                url: 'function/get_unsettled_total.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Use the same formatting method for consistency
                    var formattedTotal = formatNumberWithDots(response.total);
                    $('#totalUnsettled').text('Rp ' + formattedTotal);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching unsettled total:', error);
                    $('#totalUnsettled').text('Rp 0');
                }
            });
        }

        // Function to fetch and calculate balance (petty_cash_in - petty_cash_out)
        function fetchPettyCashBalance() {
            $.ajax({
                url: 'function/get_petty_cash_balance.php',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    // Use the same formatting method for consistency
                    var formattedBalance = formatNumberWithDots(response.balance);
                    $('#totalBalance').text('Rp ' + formattedBalance);

                    // Add color based on balance status
                    if (response.balance < 0) {
                        $('#totalBalance').removeClass('text-success').addClass('text-danger');
                    } else {
                        $('#totalBalance').removeClass('text-danger').addClass('text-success');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching petty cash balance:', error);
                    $('#totalBalance').text('Rp 0');
                }
            });
        }

        // Utility function to format numbers with dots for thousands
        function formatNumberWithDots(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        }

        // Initial load of totals
        fetchUnsettledTotal();
        fetchPettyCashBalance();

        // Set default date values for current month
        function setDefaultDates() {
            var today = new Date();
            var firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            var lastDay = new Date(today.getFullYear(), today.getMonth() + 1, 0);

            $('#startDate').val(firstDay.toISOString().split('T')[0]);
            $('#endDate').val(lastDay.toISOString().split('T')[0]);
        }

        setDefaultDates();

        // Handle "check all" checkbox
        $('#checkAll').on('click', function() {
            // Hanya memilih checkbox yang tidak disabled
            $('.row-checkbox:not(:disabled)').prop('checked', $(this).prop('checked'));
            updateButtonState();
            calculateSelectedTotal();
        });

        // Handle klik pada checkbox
        $(document).on('change', '.row-checkbox', function() {
            updateButtonState();
            calculateSelectedTotal();

            // Update checkAll status
            const totalCheckboxes = $('.row-checkbox:not(:disabled)').length;
            const checkedCheckboxes = $('.row-checkbox:not(:disabled):checked').length;
            $('#checkAll').prop('checked', totalCheckboxes === checkedCheckboxes && totalCheckboxes > 0);
        });

        // Update status tombol Update
        function updateButtonState() {
            const checkedCount = $('.row-checkbox:checked').length;
            $('#updateSelectedButton').prop('disabled', checkedCount === 0);
            $('#updateSelectedButton').text(
                checkedCount > 0 ?
                `Update Status (${checkedCount} terpilih)` :
                'Update Status Terpilih'
            );
        }

        // Kalkulasi total harga yang dicentang
        function calculateSelectedTotal() {
            let totalAmount = 0;

            $('.row-checkbox:checked').each(function() {
                const row = $(this).closest('tr');
                const hargaText = row.find('td:eq(5)').text(); // Kolom harga

                // Konversi dari format Rupiah (misal: "1.500.000") ke angka
                const hargaNumeric = parseInt(hargaText.replace(/\./g, '')) || 0;
                totalAmount += hargaNumeric;
            });

            // Update tampilan total
            $('#selectedTotalAmount').text('Rp ' + totalAmount.toLocaleString('id-ID'));
        }

        // Handle klik tombol Update
        $('#updateSelectedButton').on('click', function() {
            const checkedCount = $('.row-checkbox:checked').length;
            const totalAmount = $('#selectedTotalAmount').text();

            $('#selectedCount').text(checkedCount);
            $('#modalTotalAmount').text(totalAmount);
            $('#updateModal').modal('show');
        });

        // Proses konfirmasi update
        $('#confirmUpdateBtn').on('click', function() {
            const selectedIds = [];
            const selectedDate = $('#modalSettlementDate').val();
            const selectedId = $('#modalSettlementId').val();

            $('.row-checkbox:checked').each(function() {
                selectedIds.push($(this).val());
            });

            // Tampilkan loading state pada tombol
            const $btn = $(this);
            $btn.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Processing...');
            $btn.prop('disabled', true);

            // Kirim data ke server
            $.ajax({
                url: 'function/update_multi_status_pc.php',
                type: 'POST',
                data: {
                    ids: selectedIds,
                    date_settled: selectedDate,
                    id_settled: selectedId
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        // Tutup modal
                        $('#updateModal').modal('hide');

                        // Tampilkan notifikasi sukses
                        Swal.fire({
                            title: 'Sukses!',
                            text: response.message,
                            icon: 'success',
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            // Reload halaman untuk menampilkan perubahan
                            location.reload();
                        });
                    } else {
                        // Tampilkan pesan error
                        Swal.fire({
                            title: 'Error!',
                            text: response.message,
                            icon: 'error'
                        });

                        // Reset tombol
                        $btn.html('Konfirmasi Update');
                        $btn.prop('disabled', false);
                    }
                },
                error: function(xhr, status, errorMsg) {
                    console.error('AJAX Error:', status, errorMsg);
                    console.error('Response:', xhr.responseText);

                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan saat memproses data: ' + errorMsg,
                        icon: 'error'
                    });

                    // Reset tombol
                    $btn.html('Konfirmasi Update');
                    $btn.prop('disabled', false);
                }
            });
        });

        // FIXED FILTER IMPLEMENTATION
        // Apply filters to DataTable
        $('#applyFilter').on('click', function() {
            var kategori = $('#filterKategori').val();
            var status = $('#filterStatus').val();
            var startDate = $('#startDate').val();
            var endDate = $('#endDate').val();

            // Clear existing filters before applying new ones
            table.search('').columns().search('').draw();

            // Remove any existing date filter function
            if ($.fn.dataTable.ext.search.length > 0) {
                $.fn.dataTable.ext.search.pop();
            }

            // Apply kategori filter (column 3 is "Kategori")
            if (kategori) {
                table.column(3).search(kategori);
            }

            // Apply status filter (column 8 is "Status")
            if (status) {
                // Use custom filtering function for status column
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        if (!status) return true; // If no status filter, include all rows

                        var rowStatus = data[8]; // Get status column text

                        // Extract just the status text from cell (remove HTML)
                        var statusText = $('<div>').html(rowStatus).text().trim();

                        // Check if status matches
                        return statusText.indexOf(status) !== -1;
                    }
                );
            }

            // Apply date filter if dates are provided
            if (startDate || endDate) {
                // Convert string dates to Date objects
                var startDateObj = startDate ? new Date(startDate) : null;
                var endDateObj = endDate ? new Date(endDate) : null;

                // Add date filter function
                $.fn.dataTable.ext.search.push(
                    function(settings, data, dataIndex) {
                        var dateStr = data[4]; // Column 4 is "Date"
                        if (!dateStr) return true; // No date, include it

                        var rowDate = new Date(dateStr);

                        // Check if date parsing was successful
                        if (isNaN(rowDate.getTime())) {
                            console.error('Failed to parse date:', dateStr);
                            return true; // Include it if parsing failed
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

                        return true; // Include all rows if no date filters
                    }
                );
            }

            // Apply all filters
            table.draw();
        });

        // Reset filters
        $('#resetFilter').on('click', function() {
            $('#filterKategori').val('');
            $('#filterStatus').val('');
            setDefaultDates();

            // Remove all custom search functions
            while ($.fn.dataTable.ext.search.length > 0) {
                $.fn.dataTable.ext.search.pop();
            }

            // Clear all filters
            table.search('').columns().search('').draw();
        });

        // Refresh data every 5 minutes (300000 ms)
        setInterval(function() {
            fetchUnsettledTotal();
            fetchPettyCashBalance();
        }, 300000);
    });
    // Edit Modal Functions
    function openEditModal(data) {
        document.getElementById('edit_id_pc_out').value = data.id_pc_out;
        document.getElementById('edit_deskripsi').value = data.deskripsi;
        document.getElementById('edit_date').value = data.date;
        document.getElementById('edit_company').value = data.company;
        document.getElementById('edit_kategori').value = data.kategori;
        document.getElementById('edit_request').value = data.request;

        // Format harga agar tampil dengan titik pemisah ribuan
        let hargaInput = document.getElementById('edit_harga');
        let hargaCleanInput = document.getElementById('edit_harga_clean');

        hargaCleanInput.value = data.harga; // Simpan angka asli
        hargaInput.value = data.harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Format tampilan

        // Tampilkan modal
        $('#editModal').modal('show');
    }

    // Delete Confirmation
    function confirmDelete(id_pc_out, deskripsi) {
        // Set confirmation message
        document.getElementById('deleteMessage').innerHTML =
            'You are about to delete <strong>' + deskripsi + '</strong>.<br>' +
            'This action cannot be undone.';

        // Set the delete button href
        document.getElementById('deleteButton').href = 'function/delete_petty_cash_out.php?id_pc_out=' + id_pc_out;

        // Show the modal
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    // Format currency input with dots for thousands
    function formatCurrency(input) {
        // Ambil nilai tanpa karakter selain angka
        let value = input.value.replace(/\D/g, '');

        // Format angka dengan titik setiap ribuan
        let formattedValue = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');

        // Set nilai input yang ditampilkan ke user
        input.value = formattedValue;

        // Simpan nilai bersih ke input hidden
        let hiddenInputId = input.dataset.hidden;
        if (hiddenInputId) {
            let hiddenInput = document.getElementById(hiddenInputId);
            if (hiddenInput) {
                hiddenInput.value = value; // Simpan hanya angka tanpa format
            }
        }
    }

    // Form submission handlers
    document.addEventListener('DOMContentLoaded', function() {
        // For create form
        var createForm = document.querySelector('#showModal form');
        if (createForm) {
            createForm.addEventListener('submit', function(e) {
                var hargaInput = document.getElementById('harga');
                var hargaCleanInput = document.getElementById('harga_clean');

                // Make sure the clean value is set
                if (hargaInput && hargaCleanInput) {
                    hargaCleanInput.value = hargaInput.value.replace(/\./g, '');
                }
            });
        }

        // For edit form
        var editForm = document.getElementById('editForm');
        if (editForm) {
            editForm.addEventListener('submit', function() {
                var editHargaInput = document.getElementById('edit_harga');
                var editHargaCleanInput = document.getElementById('edit_harga_clean');

                if (editHargaInput && editHargaCleanInput) {
                    editHargaCleanInput.value = editHargaInput.value.replace(/\./g, ''); // Hapus titik pemisah ribuan
                }
            });
        }
    });
</script>
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
        <div class="card-header border-0">
            <div class="d-flex align-items-center">
                <div class="card-title mb-0 flex-grow-1">
                    <h5>Petty Cash In</h5>
                    <h6>Manage your petty cash income transactions here.</h6>
                </div>
                <div class="flex-shrink-0 m-3">
                    <button class="btn btn-success add-btn" data-bs-toggle="modal" data-bs-target="#showModalCashIn">
                        <i class="ri-add-line align-bottom me-1"></i> Create Petty Cash In
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body border border-dashed border-end-0 border-start-0">
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="startDateCashIn" class="form-label">Tanggal Mulai</label>
                    <input type="date" id="startDateCashIn" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="endDateCashIn" class="form-label">Tanggal Akhir</label>
                    <input type="date" id="endDateCashIn" class="form-control">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <button id="resetFilterCashIn" class="btn btn-secondary me-2">Reset Filter</button>
                    <button id="applyFilterCashIn" class="btn btn-primary">Terapkan Filter</button>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="pettyCashInTable" class="stripe row-border order-column" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Deskripsi</th>
                                <th>Date</th>
                                <th>Harga</th>
                                <th>Company</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        $sql = "SELECT id_pc_in, deskripsi, date, harga, company FROM petty_cash_in";
                        $result = mysqli_query($koneksi, $sql);
                        if (!$result) {
                            die("Query failed: " . mysqli_error($koneksi));
                        }

                        while ($row = mysqli_fetch_assoc($result)) {
                        ?>
                            <tr>
                                <td><?= $row['id_pc_in']; ?></td>
                                <td><?= $row['deskripsi'] ?></td>
                                <td><?= $row['date'] ?></td>
                                <td><?= number_format($row['harga'], 0, ',', '.') ?></td>
                                <td><?= $row['company'] ?></td>
                                <td>
                                    <button type="button" class="btn btn-soft-secondary btn-sm" onclick="openEditModalCashIn({
                                        id_pc_in: '<?= $row['id_pc_in'] ?>',
                                        deskripsi: '<?= $row['deskripsi'] ?>',
                                        date: '<?= $row['date'] ?>',
                                        harga: '<?= $row['harga'] ?>',
                                        company: '<?= $row['company'] ?>'
                                    })">
                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>Edit
                                    </button>
                                    <button type="button" class="btn btn-soft-danger btn-sm"
                                        onclick="confirmDeleteCashIn('<?= $row['id_pc_in'] ?>', '<?= $row['deskripsi'] ?>')">
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

<!-- Modal for Create Petty Cash In -->
<div class="modal fade zoomIn" id="showModalCashIn" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-l">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-soft-info">
                <h5 class="modal-title" id="exampleModalLabel">Create Petty Cash In</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <form action="function/insert_petty_cash_in.php" method="POST" enctype="multipart/form-data">
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
                                <input type="text" class="form-control" name="harga" id="hargaCashIn" required onkeyup="formatCurrency(this)" />
                                <input type="hidden" name="harga_clean" id="hargaCashIn_clean" />
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Company</label>
                                <select class="form-control" name="company" required>
                                    <option value="PT. Mineral Alam Abadi">PT. Mineral Alam Abadi</option>
                                    <option value="PT. Mitra Mineral Perkasa">PT. Mitra Mineral Perkasa</option>
                                    <option value="PT. Bima Cakra Perkasa Mineralindo">PT. Bima Cakra Perkasa Mineralindo</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <div class="hstack gap-2 justify-content-end">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" name="add-petty-cash-in">Add Petty Cash In</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Edit Petty Cash In -->
<div class="modal fade" id="editModalCashIn" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-soft-warning">
                <h5 class="modal-title" id="editModalLabel">Edit Petty Cash In</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editFormCashIn" action="function/update_petty_cash_in.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="id_pc_in" id="edit_id_pc_in">

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <input type="text" class="form-control" name="deskripsi" id="edit_deskripsi_cash_in" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Date</label>
                        <input type="date" class="form-control" name="date" id="edit_date_cash_in" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Harga</label>
                        <input type="text" class="form-control" id="edit_harga_cash_in" data-hidden="edit_harga_clean_cash_in" required onkeyup="formatCurrency(this)">
                        <input type="hidden" name="harga" id="edit_harga_clean_cash_in">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Company</label>
                        <select class="form-control" name="company" id="edit_company_cash_in" required>
                            <option value="PT. Mineral Alam Abadi">PT. Mineral Alam Abadi</option>
                            <option value="PT. Mitra Mineral Perkasa">PT. Mitra Mineral Perkasa</option>
                            <option value="PT. Bima Cakra Perkasa Mineralindo">PT. Bima Cakra Perkasa Mineralindo</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" name="update-petty-cash-in">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Delete Cash In -->
<div class="modal fade" id="deleteModalCashIn" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
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
                        <p class="text-muted mb-4" id="deleteMessageCashIn"></p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <a href="#" class="btn btn-danger" id="deleteButtonCashIn">
                        <i class="ri-delete-bin-5-line align-bottom"></i> Delete
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Improved currency formatting function
function formatCurrency(input) {
    // Remove all non-numeric characters
    let value = input.value.replace(/[^\d]/g, '');
    
    // Convert to number to remove leading zeros
    value = value ? parseInt(value, 10) : '';
    
    // Format with thousand separators
    if (value !== '') {
        input.value = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    } else {
        input.value = '';
    }
    
    // Update the hidden input with the clean numeric value
    const hiddenInput = document.getElementById(input.getAttribute('data-hidden'));
    if (hiddenInput) {
        hiddenInput.value = value || '';
    }
}

$(document).ready(function() {
    var tableCashIn = $('#pettyCashInTable').DataTable({
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
                title: 'Data Export Petty Cash In',
                text: 'Export Excel'
            }
        ],
        dom: 'Bfrtip',
        deferRender: true
    });
    
    // Apply Cash In Date Filters
    $('#applyFilterCashIn').on('click', function() {
        var startDate = $('#startDateCashIn').val();
        var endDate = $('#endDateCashIn').val();
        
        // Clear existing filters
        tableCashIn.search('').columns().search('').draw();
        
        // Remove any existing date filter function
        if ($.fn.dataTable.ext.search.length > 0) {
            $.fn.dataTable.ext.search.pop();
        }
        
        // Apply date filter if dates are provided
        if (startDate || endDate) {
            var startDateObj = startDate ? new Date(startDate) : null;
            var endDateObj = endDate ? new Date(endDate) : null;
            
            $.fn.dataTable.ext.search.push(
                function(settings, data, dataIndex) {
                    var dateStr = data[2]; // Column 2 is "Date"
                    if (!dateStr) return true;
                    
                    var rowDate = new Date(dateStr);
                    
                    if (isNaN(rowDate.getTime())) {
                        console.error('Failed to parse date:', dateStr);
                        return true;
                    }
                    
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
        tableCashIn.draw();
    });

    // Reset Cash In Filters
    $('#resetFilterCashIn').on('click', function() {
        $('#startDateCashIn').val('');
        $('#endDateCashIn').val('');
        
        // Remove all custom search functions
        while ($.fn.dataTable.ext.search.length > 0) {
            $.fn.dataTable.ext.search.pop();
        }
        
        // Clear all filters
        tableCashIn.search('').columns().search('').draw();
    });
});

// Edit Modal Function for Cash In
function openEditModalCashIn(data) {
    document.getElementById('edit_id_pc_in').value = data.id_pc_in;
    document.getElementById('edit_deskripsi_cash_in').value = data.deskripsi;
    document.getElementById('edit_date_cash_in').value = data.date;
    document.getElementById('edit_company_cash_in').value = data.company;

    // Format harga agar tampil dengan titik pemisah ribuan
    let hargaInput = document.getElementById('edit_harga_cash_in');
    let hargaCleanInput = document.getElementById('edit_harga_clean_cash_in');

    hargaCleanInput.value = data.harga; // Simpan angka asli
    hargaInput.value = data.harga.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); // Format tampilan

    // Tampilkan modal
    $('#editModalCashIn').modal('show');
}

// Delete Confirmation for Cash In
function confirmDeleteCashIn(id_pc_in, deskripsi) {
    // Set confirmation message
    document.getElementById('deleteMessageCashIn').innerHTML =
        'You are about to delete <strong>' + deskripsi + '</strong>.<br>' +
        'This action cannot be undone.';

    // Set the delete button href
    document.getElementById('deleteButtonCashIn').href = 'function/delete_petty_cash_in.php?id_pc_in=' + id_pc_in;

    // Show the modal
    new bootstrap.Modal(document.getElementById('deleteModalCashIn')).show();
}

// Add event listeners for currency formatting on page load
document.addEventListener('DOMContentLoaded', function() {
    // For Create Modal
    const hargaCashInInput = document.getElementById('hargaCashIn');
    if (hargaCashInInput) {
        hargaCashInInput.addEventListener('input', function() {
            formatCurrency(this);
        });
    }

    // For Edit Modal
    const editHargaCashInInput = document.getElementById('edit_harga_cash_in');
    if (editHargaCashInInput) {
        editHargaCashInInput.addEventListener('input', function() {
            formatCurrency(this);
        });
    }
});
</script>


<script>
// Improved currency formatting function
function formatCurrency(input) {
    // Remove all non-numeric characters
    let value = input.value.replace(/[^\d]/g, '');
    
    // Convert to number to remove leading zeros
    value = value ? parseInt(value, 10) : '';
    
    // Format with thousand separators
    if (value !== '') {
        input.value = value.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    } else {
        input.value = '';
    }
    
    // Update the hidden input with the clean numeric value
    const hiddenInput = document.getElementById(input.getAttribute('data-hidden'));
    if (hiddenInput) {
        hiddenInput.value = value || '';
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // For Create Modal
    const createForm = document.querySelector('#showModalCashIn form');
    if (createForm) {
        createForm.addEventListener('submit', function(e) {
            const hargaInput = document.getElementById('hargaCashIn');
            const hargaCleanInput = document.getElementById('hargaCashIn_clean');
            
            // Ensure the clean value is set by removing all non-numeric characters
            if (hargaInput && hargaCleanInput) {
                hargaCleanInput.value = hargaInput.value.replace(/\./g, '');
            }
        });
    }
    
    // For Edit Modal
    const editForm = document.getElementById('editFormCashIn');
    if (editForm) {
        editForm.addEventListener('submit', function(e) {
            const editHargaInput = document.getElementById('edit_harga_cash_in');
            const editHargaCleanInput = document.getElementById('edit_harga_clean_cash_in');
            
            // Ensure the clean value is set by removing all non-numeric characters
            if (editHargaInput && editHargaCleanInput) {
                editHargaCleanInput.value = editHargaInput.value.replace(/\./g, '');
            }
        });
    }

    // Add event listeners for currency formatting
    const hargaCashInInput = document.getElementById('hargaCashIn');
    if (hargaCashInInput) {
        hargaCashInInput.setAttribute('data-hidden', 'hargaCashIn_clean');
        hargaCashInInput.addEventListener('input', function() {
            formatCurrency(this);
        });
    }

    const editHargaCashInInput = document.getElementById('edit_harga_cash_in');
    if (editHargaCashInInput) {
        editHargaCashInInput.setAttribute('data-hidden', 'edit_harga_clean_cash_in');
        editHargaCashInInput.addEventListener('input', function() {
            formatCurrency(this);
        });
    }
});
</script>
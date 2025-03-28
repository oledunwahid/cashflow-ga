<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/searchpanes/2.2.0/css/searchPanes.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/select/1.7.0/css/select.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

<!-- Required Scripts -->
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/searchpanes/2.2.0/js/dataTables.searchPanes.min.js"></script>
<script src="https://cdn.datatables.net/select/1.7.0/js/dataTables.select.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.3/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.colVis.min.js"></script>

<!-- Main User Roles Table -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <div class="card-title mb-0 flex-grow-1">
                        <h5>Daftar User Roles</h5>
                        <h6>Manage user roles and permissions efficiently.</h6>
                    </div>
                    <div class="flex-shrink-0 m-3">
                        <button class="btn btn-success add-btn" data-bs-toggle="modal"  data-bs-target="#showModal">
                            <i class="ri-add-line align-bottom me-1"></i> Tambah User Role
                        </button>
                    </div>
                </div>
            </div>

           <div class="card-body border border-dashed border-end-0 border-start-0">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="userRolesTable" class="stripe row-border order-column" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>NIK</th>
                                            <th>Nama</th>
                                            <th>Role</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT ur.idnik, u.nama, r.role_name 
                                               FROM user_roles ur 
                                               INNER JOIN roles r ON r.id_role = ur.id_role
                                               INNER JOIN user u ON u.idnik = ur.idnik";
                                        $result = mysqli_query($koneksi, $sql);
                                        if (!$result) {
                                            die("Query failed: " . mysqli_error($koneksi));
                                        }

                                        while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            <tr>
                                                <td><?= $row['idnik']; ?></td>
                                                <td><?= $row['nama']; ?></td>
                                                <td><?= $row['role_name']; ?></td>
                                                <td>
                                                    <button type="button" class="btn btn-soft-secondary btn-sm" onclick="openEditModal({
                                                            idnik: '<?= $row['idnik'] ?>',
                                                            role_name: '<?= $row['role_name'] ?>'
                                                        })">
                                                        <i class="ri-pencil-fill align-bottom me-2 text-muted"></i>Edit
                                                    </button>
                                                    <button type="button" class="btn btn-soft-danger btn-sm"
                                                        onclick="confirmDelete('<?= $row['idnik'] ?>')">
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
    </div>
</div>

<!-- Add User Role Modal -->
<div class="modal fade zoomIn" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-l">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-soft-info">
                <h5 class="modal-title" id="exampleModalLabel">Tambah User Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
            </div>
            <form action="function/insert_user_role.php" method="POST">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-lg-12">
                            <div class="mb-3">
                                <label for="idnik" class="form-label">Pilih Karyawan</label>
                                <select class="form-control" name="idnik" data-choices id="idnik" required>
                                    <option value="">Pilih Karyawan</option>
                                    <?php
                                    $query = "SELECT u.idnik, u.nama FROM user u 
                                             LEFT JOIN user_roles ur ON u.idnik = ur.idnik 
                                             WHERE ur.idnik IS NULL";
                                    $result = mysqli_query($koneksi, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="' . $row['idnik'] . '">' . $row['nama'] . ' - ' . $row['idnik'] . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Role</label>
                                <select class="form-control" name="id_role" required>
                                    <option value="">Pilih Role</option>
                                    <?php
                                    $query = "SELECT * FROM roles";
                                    $result = mysqli_query($koneksi, $query);
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<option value="' . $row['id_role'] . '">' . $row['role_name'] . '</option>';
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
                        <button type="submit" class="btn btn-primary" name="add-user-role">Tambah User Role</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Role Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0">
            <div class="modal-header p-3 bg-soft-warning">
                <h5 class="modal-title" id="editModalLabel">Edit User Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="function/update_user_role.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="idnik" id="edit_idnik">
                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select class="form-control" name="id_role" id="edit_id_role" required>
                            <option value="">Pilih Role</option>
                            <?php
                            $query = "SELECT * FROM roles";
                            $result = mysqli_query($koneksi, $query);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<option value="' . $row['id_role'] . '">' . $row['role_name'] . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer mt-3">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" name="update-user-role">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header p-3 bg-soft-danger">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="mt-2">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop"
                        colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4 class="mb-3">Anda yakin?</h4>
                        <p class="text-muted mb-4">Tindakan ini akan menghapus user role dan tidak dapat dibatalkan.</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <a href="#" class="btn btn-danger" id="deleteButton">
                        <i class="ri-delete-bin-5-line align-bottom"></i> Hapus
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- DataTable Initialization Script -->
<script>
    $(document).ready(function () {
        $('#userRolesTable').DataTable({
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
                    title: 'Data Export User Roles',
                    text: 'Export Excel'
                }
            ],
            dom: 'Bfrtip',
            deferRender: true
        });
    });

    function openEditModal(data) {
        document.getElementById('edit_idnik').value = data.idnik;
        // Set the selected role in dropdown
        const roleSelect = document.getElementById('edit_id_role');
        Array.from(roleSelect.options).forEach(option => {
            if (option.text === data.role_name) {
                option.selected = true;
            }
        });
        $('#editModal').modal('show');
    }

    function confirmDelete(idnik) {
        document.getElementById('deleteButton').href = 'function/delete_user_role.php?idnik=' + idnik;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
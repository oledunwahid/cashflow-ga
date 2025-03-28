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

<!-- Tabel Daftar Area -->
<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header border-0">
                <div class="d-flex align-items-center">
                    <div class="card-title mb-0 flex-grow-1">
                        <h5>Daftar Area</h5>
                        <h6>Manage all areas efficiently.</h6>
                    </div>
                    <div class="flex-shrink-0 m-3">
                        <button class="btn btn-success add-btn" data-bs-toggle="modal" data-bs-target="#showModal">
                            <i class="ri-add-line align-bottom me-1"></i> Tambah Area
                        </button>
                    </div>
                </div>
            </div>

            <div class="card-body border border-dashed border-end-0 border-start-0">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card">
                            <div class="card-body">
                                <table id="areaTable" class="stripe row-border order-column" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID Karyawab</th>
                                            <th>Nama</th>
											<th>Divisi</th>
										
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT idnik, nama, divisi FROM user ";
                                        $result = mysqli_query($koneksi, $sql);
                                        if (!$result) {
                                            die("Query failed: " . mysqli_error($koneksi));
                                        }

                                        while ($row = mysqli_fetch_assoc($result)) {
                                            ?>
                                            <tr>
                                                <td><?= $row['idnik']; ?></td>
                                                <td><?= $row['nama']; ?></td>
												 <td><?= $row['divisi']; ?></td>
												
                                                
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


<!-- DataTable Initialization -->
<script>
    $(document).ready(function () {
        $('#areaTable').DataTable({
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
                    title: 'Data Export Area IT',
                    text: 'Export Excel'
                }
            ],
            dom: 'Bfrtip',
            deferRender: true
        });
    });

   
</script>
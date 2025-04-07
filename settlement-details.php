<?php
function formatRupiah($angka)
{
    return number_format($angka, 0, ',', '.');
}

// Get parameters from URL
$id_ca = isset($_GET['id_ca']) ? $_GET['id_ca'] : '';
$id_settlement = isset($_GET['id_settlement']) ? $_GET['id_settlement'] : '';

if (empty($id_ca) || empty($id_settlement)) {
    echo '<div class="alert alert-danger" role="alert">
            Invalid parameters. Cash Advance ID atau Settlement ID tidak ditemukan.
          </div>';
    exit();
}

// Ambil data cash advance
$query = "SELECT ca.*, u.nama AS requestor_name 
          FROM cash_advance ca
          LEFT JOIN user u ON ca.request = u.idnik
          WHERE ca.id_ca = ?";
$stmt = mysqli_prepare($koneksi, $query);
mysqli_stmt_bind_param($stmt, "i", $id_ca);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$ca_data = mysqli_fetch_assoc($result);

if (!$ca_data) {
    echo '<div class="alert alert-danger" role="alert">
            Cash Advance tidak ditemukan.
          </div>';
    exit();
}

// Ambil detail item yang sudah ada
$query_details = "SELECT * FROM detailed_cash_advance 
                 WHERE id_ca = ? AND id_settlement = ? AND is_deleted = 0
                 ORDER BY created_date ASC";
$stmt_details = mysqli_prepare($koneksi, $query_details);
mysqli_stmt_bind_param($stmt_details, "is", $id_ca, $id_settlement);
mysqli_stmt_execute($stmt_details);
$result_details = mysqli_stmt_get_result($stmt_details);

// Hitung total dari detail
$total_details = 0;
$details = [];
while ($row = mysqli_fetch_assoc($result_details)) {
    $details[] = $row;
    $total_details += $row['total_price'];
}

// Hitung sisa
$jumlah_actual = $ca_data['jumlah_actual'];
$remaining = $jumlah_actual - $total_details;

// Cek status untuk menentukan tombol yang ditampilkan
$status = $ca_data['status'];
$show_complete_button = ($status !== 'Done' && $status !== 'Refund' && $status !== 'Settled');

// Debug log
file_put_contents("debug_log.txt", date('Y-m-d H:i:s') . " - Settlement page loaded: CA ID=$id_ca, Settlement ID=$id_settlement, Status=$status\n", FILE_APPEND);
?>

<!-- Include DataTables dan styles -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.3/css/buttons.dataTables.min.css">

<style>
    textarea {
        min-height: 60px;
        overflow-y: hidden;
        resize: vertical;
        transition: height 0.1s ease-in-out;
        width: 100%;
    }

    .card-enhanced {
        transition: transform .3s, box-shadow .3s;
    }

    .card-enhanced:hover {
        transform: scale(1.01);
        box-shadow: 0 10px 20px rgba(0, 0, 0, .12), 0 4px 8px rgba(0, 0, 0, .06);
    }

    .table-hover tbody tr:hover {
        background-color: #f8f9fa;
    }

    .btn-enhanced {
        transition: background-color .3s, transform .3s;
    }

    .btn-enhanced:hover {
        transform: translateY(-2px);
    }

    .form-control {
        border-radius: 0.375rem;
    }

    .price-input {
        text-align: right;
    }

    @media (max-width: 767px) {
        .header-actions {
            flex-direction: column;
            gap: 10px;
        }

        .header-actions .btn {
            width: 100%;
        }
    }
</style>

<div class="row">
    <div class="col-12">
        <!-- Success/Error alerts -->
        <?php if (isset($_SESSION['Messages'])): ?>
            <div class="alert alert-<?= ($_SESSION['Icon'] == 'success') ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
                <i class="<?= ($_SESSION['Icon'] == 'success') ? 'fas fa-check-circle' : 'fas fa-exclamation-circle' ?> me-1"></i>
                <?= $_SESSION['Messages'] ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php
            unset($_SESSION['Messages']);
            unset($_SESSION['Icon']);
            ?>
        <?php endif; ?>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center header-actions">
                    <h5 class="card-title mb-0">Detail Settlement Cash Advance</h5>
                    <div>
                        <a href="index.php?page=CashAdvance" class="btn btn-soft-secondary btn-enhanced">
                            <i class="ri-arrow-left-line me-1"></i> Kembali ke List
                        </a>
                        <?php if ($status !== 'Done' && $status !== 'Refund' && $status !== 'Settled'): ?>
                            <?php if (empty($details)): ?>
                                <div class="badge bg-warning p-2">
                                    <small>Tambahkan minimal satu item untuk menyelesaikan settlement</small>
                                </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="badge bg-<?= $status == 'Done' ? 'success' : ($status == 'Refund' ? 'warning' : 'info') ?> p-2 ms-2">
                                Status: <?= $status ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Cash Advance Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Informasi Cash Advance</h5>
                                <div class="mb-3">
                                    <label class="fw-semibold">ID Cash Advance:</label>
                                    <span class="ms-2 badge bg-primary"><?= $id_ca ?></span>
                                    <span class="ms-2 badge bg-info"><?= $id_settlement ?></span>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-semibold">Deskripsi:</label>
                                    <p class="mb-0"><?= htmlspecialchars($ca_data['deskripsi']) ?></p>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-semibold">Requestor:</label>
                                    <span class="ms-2"><?= htmlspecialchars($ca_data['requestor_name'] ?? $ca_data['request']) ?></span>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-semibold">Kategori:</label>
                                    <span class="ms-2 badge bg-soft-secondary text-secondary"><?= htmlspecialchars($ca_data['kategori']) ?></span>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-semibold">Status:</label>
                                    <span class="ms-2 badge bg-<?=
                                                                $status == 'Pending' ? 'warning' : ($status == 'Approved' ? 'info' : ($status == 'Settled' ? 'primary' : ($status == 'Refund' ? 'warning' : ($status == 'Done' ? 'success' : 'secondary'))))
                                                                ?>"><?= $status ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Ringkasan Keuangan</h5>
                                <div class="mb-3">
                                    <label class="fw-semibold">Jumlah Permintaan:</label>
                                    <span class="ms-2">Rp <?= number_format($ca_data['jumlah_request'], 0, ',', '.') ?></span>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-semibold">Jumlah Aktual:</label>
                                    <span class="ms-2">Rp <?= number_format($ca_data['jumlah_actual'], 0, ',', '.') ?></span>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-semibold">Tanggal Settlement:</label>
                                    <span class="ms-2"><?= date('d M Y', strtotime($ca_data['date_settlement'])) ?></span>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-semibold">Total Item Detail:</label>
                                    <span class="ms-2" id="details-total">Rp <?= number_format($total_details, 0, ',', '.') ?></span>
                                </div>
                                <div class="mb-3">
                                    <label class="fw-semibold">Sisa Dana:</label>
                                    <span class="ms-2 <?= ($remaining < 0) ? 'text-danger' : 'text-success' ?>" id="remaining-amount">
                                        Rp <?= number_format($remaining, 0, ',', '.') ?>
                                    </span>
                                </div>
                                <?php if (!empty($ca_data['bukti_nota'])): ?>
                                    <div class="mb-3">
                                        <label class="fw-semibold">Bukti Nota Utama:</label>
                                        <a href="file/bukti_nota/<?= $ca_data['bukti_nota'] ?>" class="btn btn-sm btn-soft-info ms-2" download>
                                            <i class="ri-download-2-line me-1"></i> Download
                                        </a>
                                    </div>
                                <?php endif; ?>
                                <?php if (!empty($ca_data['refund_notes'])): ?>
                                    <div class="mb-3">
                                        <label class="fw-semibold">Catatan Refund:</label>
                                        <p class="mb-0 ms-2 text-<?= (strpos($ca_data['refund_notes'], 'Exceeded') !== false) ? 'danger' : 'warning' ?>">
                                            <?= htmlspecialchars($ca_data['refund_notes']) ?>
                                        </p>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php if ($show_complete_button): ?>
                    <!-- Form Tambah Item Baru - hanya tampilkan jika settlement belum selesai -->
                    <div class="card mb-4">
                        <div class="card-header bg-soft-primary">
                            <h5 class="card-title mb-0">Tambah Item Baru</h5>
                        </div>
                        <div class="card-body">
                            <form id="addItemForm" action="function/add_settlement_detail.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="id_ca" value="<?= $id_ca ?>">
                                <input type="hidden" name="id_settlement" value="<?= $id_settlement ?>">

                                <div class="row">
                                    <div class="col-md-4 mb-3">
                                        <label for="item_name" class="form-label">Nama Item</label>
                                        <input type="text" class="form-control" id="item_name" name="item_name" required>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="item_category" class="form-label">Kategori</label>
                                        <select class="form-control" id="item_category" name="item_category">
                                            <option value="Food & Beverage">Food & Beverage</option>
                                            <option value="Transportation">Transportation</option>
                                            <option value="Accommodation">Accommodation</option>
                                            <option value="Office Supplies">Office Supplies</option>
                                            <option value="Equipment">Equipment</option>
                                            <option value="Services">Services</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <div class="row">
                                            <div class="col-4">
                                                <label for="qty" class="form-label">Qty</label>
                                                <input type="number" class="form-control" id="qty" name="qty" min="1" value="1" required>
                                            </div>
                                            <div class="col-8">
                                                <label for="price" class="form-label">Harga (Rp)</label>
                                                <input type="text" class="form-control price-input" id="price" name="price" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <textarea class="form-control" id="description" name="description" rows="2"></textarea>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label for="receipt_file" class="form-label">Nota/Bukti (Opsional)</label>
                                        <input type="file" class="form-control" id="receipt_file" name="receipt_file" accept="image/*,.pdf">
                                        <small class="text-muted">Maks 2MB (JPG, PNG, PDF)</small>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-8">
                                        <p class="mb-0 mt-2">Total: <span id="item-total" class="fw-bold">Rp 0</span></p>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <button type="submit" class="btn btn-success">
                                            <i class="ri-add-line me-1"></i> Tambah Item
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Tabel Item Settlement -->
                <div class="card">
                    <div class="card-header bg-soft-success">
                        <h5 class="card-title mb-0">Daftar Item</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="settlement-items-table" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Nama Item</th>
                                        <th>Kategori</th>
                                        <th>Deskripsi</th>
                                        <th>Qty</th>
                                        <th>Harga</th>
                                        <th>Total</th>
                                        <th>Bukti</th>
                                        <?php if ($show_complete_button): ?>
                                            <th>Aksi</th>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($details)): ?>
                                        <tr>
                                            <td colspan="<?= $show_complete_button ? '8' : '7' ?>" class="text-center">Belum ada item yang ditambahkan</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($details as $detail): ?>
                                            <tr>
                                                <td><?= htmlspecialchars($detail['item_name']) ?></td>
                                                <td><span class="badge bg-light text-dark"><?= htmlspecialchars($detail['item_category']) ?></span></td>
                                                <td><?= htmlspecialchars($detail['description']) ?></td>
                                                <td><?= $detail['qty'] ?></td>
                                                <td>Rp <?= number_format($detail['price'], 0, ',', '.') ?></td>
                                                <td>Rp <?= number_format($detail['total_price'], 0, ',', '.') ?></td>
                                                <td>
                                                    <?php if (!empty($detail['receipt_file'])): ?>
                                                        <a href="file/receipts/<?= $detail['receipt_file'] ?>" class="btn btn-sm btn-soft-info" download>
                                                            <i class="ri-download-2-line"></i>
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="badge bg-light text-muted">Tidak Ada</span>
                                                    <?php endif; ?>
                                                </td>
                                                <?php if ($show_complete_button): ?>
                                                    <td>
                                                        <!-- First create a hidden form for deletion -->
                                                        <form action="function/delete_settlement_detail.php" method="POST" id="deleteItemForm<?= $detail['id_detail'] ?>" style="display:none;">
                                                            <input type="hidden" name="id_detail" value="<?= $detail['id_detail'] ?>">
                                                        </form>

                                                        <!-- Then create the visible button that triggers the form submission -->
                                                        <button type="button" class="btn btn-sm btn-soft-danger"
                                                            onclick="confirmDelete(<?= $detail['id_detail'] ?>)">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </td>
                                                <?php endif; ?>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Footer dengan tombol sederhana -->
                <div class="card-footer mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <a href="index.php?page=CashAdvance" class="btn btn-soft-secondary">
                                <i class="ri-arrow-left-line me-1"></i> Kembali ke Cash Advance
                            </a>
                        </div>
                        <div class="col-md-6 text-right d-flex justify-content-end">
                            <!-- At the bottom of your settlement-details.php file, right before the </div> closing tag -->
                            <?php if ($show_complete_button && count($details) > 0): ?>
                                <form action="function/complete_settlement.php" method="POST" id="completeSettlementForm">
                                    <input type="hidden" name="id_ca" value="<?= $id_ca ?>">
                                    <input type="hidden" name="id_settlement" value="<?= $id_settlement ?>">
                                    <button type="submit" class="btn btn-primary btn-sm mt-3 w-100">
                                        <i class="ri-check-double-line me-1"></i> Selesaikan Settlement (Direct Form)
                                    </button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id) {
        Swal.fire({
            title: 'Hapus Item',
            text: "Apakah Anda yakin ingin menghapus item ini?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Submit form delete
                document.getElementById('deleteItemForm' + id).submit();
            }
        });
    }
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Settlement page script started");

        // Ambil data ID langsung dari halaman HTML
        var idCa = <?= $id_ca ?>;
        var idSettlement = '<?= $id_settlement ?>';

        console.log("ID CA:", idCa, "Settlement ID:", idSettlement);

        // ===== HELPER FUNCTIONS =====
        function formatRibuan(angka) {
            return angka.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        function unformatRibuan(formatted) {
            if (!formatted) return '0';
            return formatted.replace(/\./g, '');
        }

        // Format harga dengan titik sebagai pemisah ribuan
        $(document).on('input', '.price-input', function() {
            var value = $(this).val().replace(/\./g, '');
            $(this).val(formatRibuan(value));
            calculateItemTotal();
        });

        // Hitung total berdasarkan qty dan harga
        function calculateItemTotal() {
            var qty = parseInt($('#qty').val()) || 0;
            var price = parseInt(unformatRibuan($('#price').val()) || '0');
            var total = qty * price;
            $('#item-total').text('Rp ' + formatRibuan(total));
        }

        // Mendengarkan perubahan pada qty atau harga
        $('#qty, #price').on('input', function() {
            calculateItemTotal();
        });

        // Validasi form tambah item
        $('#addItemForm').on('submit', function(e) {
            var price = unformatRibuan($('#price').val());

            if (isNaN(price) || parseInt(price) <= 0) {
                e.preventDefault();

                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Harga harus lebih dari nol'
                });

                return false;
            }
            return true;
        });

        // Tambahkan validasi dan konfirmasi untuk form settlement
        $('#completeSettlementForm').on('submit', function(e) {
            e.preventDefault();

            // Ambil nilai remaining dari elemen HTML
            var remaining = 0;
            var remainingText = $('#remaining-amount').text().trim();
            var remainingMatch = remainingText.match(/Rp\s+([\d\.]+)/);

            if (remainingMatch) {
                remaining = parseFloat(unformatRibuan(remainingMatch[1]));
                if (remainingText.indexOf('-') !== -1) remaining = -remaining;
            }

            // Pesan konfirmasi berbeda berdasarkan nilai remaining
            var message = 'Apakah Anda yakin ingin menyelesaikan settlement ini?';

            if (remaining > 0) {
                message = 'Masih ada dana tersisa sebesar Rp ' + formatRibuan(remaining) + ' yang belum digunakan. Dana ini akan ditandai untuk pengembalian. Lanjutkan?';
            } else if (remaining < 0) {
                message = 'Peringatan: Total pengeluaran melebihi jumlah aktual sebesar Rp ' + formatRibuan(Math.abs(remaining)) + '. Lanjutkan?';
            }

            Swal.fire({
                title: 'Selesaikan Settlement',
                html: message,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, selesaikan!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit the form directly
                    this.submit();
                }
            });
        });

        // Inisialisasi DataTable jika tersedia
        if ($.fn.DataTable) {
            $('#settlement-items-table').DataTable({
                responsive: true,
                language: {
                    paginate: {
                        previous: "<i class='ri-arrow-left-s-line'>",
                        next: "<i class='ri-arrow-right-s-line'>"
                    },
                    emptyTable: "Belum ada item yang ditambahkan"
                }
            });
        }

        console.log("Settlement page script initialized successfully");
    });
</script>
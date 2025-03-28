<?php
// Include database connection
include "../koneksi.php";

// Set header to JSON
header('Content-Type: application/json');

// Cek apakah ada data yang dikirim
if (!isset($_POST['ids']) || empty($_POST['ids'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Tidak ada data yang dipilih'
    ]);
    exit;
}

// Ambil array ID yang dikirim
$ids = $_POST['ids'];

// Ambil tanggal settled yang dikirim 
$date_settled = $_POST['date_settled'];
$id_settled = $_POST['id_settled'];

// Siapkan query untuk update multiple rows
$idList = implode(',', array_map(function ($id) use ($koneksi) {
    return "'" . mysqli_real_escape_string($koneksi, $id) . "'";
}, $ids));

// Modified query to include is_deleted check
$query = "UPDATE petty_cash_out 
          SET status = 'Settled', date_settled = '$date_settled', id_settled = '$id_settled' 
          WHERE id_pc_out IN ($idList) AND status = 'Unsettled' AND (is_deleted = 0 OR is_deleted IS NULL)";

// Jalankan query
try {
    $result = mysqli_query($koneksi, $query);

    if ($result) {
        // Dapatkan jumlah row yang berhasil diupdate
        $updatedRows = mysqli_affected_rows($koneksi);

        echo json_encode([
            'status' => 'success',
            'message' => 'Berhasil mengupdate status ' . $updatedRows . ' transaksi menjadi Settled',
            'updated_count' => $updatedRows
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal mengupdate data: ' . mysqli_error($koneksi)
        ]);
    }
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
    ]);
}

<?php
// File: function/save_reimbursement.php

// Pastikan request adalah POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        'status' => 'error',
        'message' => 'Method not allowed'
    ]);
    exit;
}

// Pastikan user sudah login
session_start();
if (!isset($_SESSION['idnik'])) {
    http_response_code(401);
    echo json_encode([
        'status' => 'error',
        'message' => 'Unauthorized'
    ]);
    exit;
}

// Koneksi database
require_once '../koneksi.php';

// Ambil data JSON dari request
$jsonData = file_get_contents('php://input');
$data = json_decode($jsonData, true);

if (!$data) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid JSON data'
    ]);
    exit;
}

// Validasi data
if (!isset($data['items']) || empty($data['items'])) {
    http_response_code(400);
    echo json_encode([
        'status' => 'error',
        'message' => 'No items provided'
    ]);
    exit;
}

// Mulai transaksi
mysqli_begin_transaction($koneksi);

try {
    // Siapkan data untuk tabel reimbursement_print
    $printReference = $data['reference'] ?? 'REF-' . date('Ymd');
    $printVoucher = $data['voucher'] ?? 'VC-' . date('YmdHis');
    $company = $data['company'] ?? '';
    $totalAmount = 0;
    $itemCount = count($data['items']);
    $notes = $data['notes'] ?? 'Reimbursement for petty cash expenses (' . $itemCount . ' items)';
    $createdBy = $_SESSION['idnik'] ?? '';

    // Hitung total amount
    foreach ($data['items'] as $item) {
        $totalAmount += $item['harga'];
    }

    // Insert ke tabel reimbursement_print
    $query = "INSERT INTO reimbursement_print (
                print_reference, 
                print_voucher, 
                company, 
                total_amount, 
                item_count, 
                notes, 
                prepared_by, 
                created_by
              ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param(
        $stmt,
        'sssdiiss',
        $printReference,
        $printVoucher,
        $company,
        $totalAmount,
        $itemCount,
        $notes,
        $_SESSION['nama'],
        $createdBy
    );

    if (!mysqli_stmt_execute($stmt)) {
        throw new Exception("Failed to save reimbursement: " . mysqli_error($koneksi));
    }

    $idPrint = mysqli_insert_id($koneksi);

    // Insert ke tabel reimbursement_print_items untuk setiap item
    foreach ($data['items'] as $item) {
        $idPcOut = $item['id'];
        $description = $item['description'];
        $category = $item['category'];
        $transDate = $item['settlementDate'] ?? date('Y-m-d'); // Gunakan settlement date
        $amount = $item['harga'];
        $qty = 1; // Default qty
        $unitPrice = $amount; // Karena qty = 1, unit price = amount
        $itemNotes = $item['category']; // Gunakan kategori sebagai catatan

        $query = "INSERT INTO reimbursement_print_items (
                    id_print,
                    id_pc_out,
                    description,
                    category,
                    trans_date,
                    amount,
                    qty,
                    unit_price,
                    notes
                  ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($koneksi, $query);
        mysqli_stmt_bind_param(
            $stmt,
            'iisssdiis',
            $idPrint,
            $idPcOut,
            $description,
            $category,
            $transDate,
            $amount,
            $qty,
            $unitPrice,
            $itemNotes
        );

        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to save reimbursement item: " . mysqli_error($koneksi));
        }
    }

    // Commit transaksi
    mysqli_commit($koneksi);

    // Kembalikan response sukses
    echo json_encode([
        'status' => 'success',
        'message' => 'Reimbursement data saved successfully',
        'data' => [
            'id_print' => $idPrint,
            'print_reference' => $printReference,
            'print_voucher' => $printVoucher
        ]
    ]);
} catch (Exception $e) {
    // Rollback transaksi jika ada error
    mysqli_rollback($koneksi);

    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

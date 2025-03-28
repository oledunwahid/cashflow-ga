<?php

/**
 * Fungsi untuk mengambil data petty cash yang sudah settled
 * 
 * @param object $koneksi Koneksi database MySQLi
 * @param array $filter Filter data yang akan diambil (opsional)
 * @return array Data petty cash yang sudah settled
 */
function getSettledPettyCashData($koneksi, $filter = [])
{
    // Default filter
    $defaultFilter = [
        'kategori' => [],
        'company' => [],
        'settlement_id' => [],
        'requestor' => [],
        'date_type' => 'date',
        'start_date' => null,
        'end_date' => null,
        'id_list' => []
    ];

    // Merge filter dengan default
    $filter = array_merge($defaultFilter, $filter);

    // Memulai query dasar
    $query = "SELECT 
                pco.id_pc_out, 
                pco.deskripsi,
                pco.kategori,
                pco.date, 
                pco.harga,
                pco.company,
                u.nama AS requestor,
                pco.id_settled,
                pco.date_settled,
                pco.request
            FROM 
                petty_cash_out pco
            LEFT JOIN user u ON pco.request = u.idnik
            WHERE pco.status = 'Settled' 
            AND (pco.is_deleted = 0 OR pco.is_deleted IS NULL)";

    // Filter berdasarkan kategori
    if (!empty($filter['kategori'])) {
        $kategoriList = array_map(function ($item) use ($koneksi) {
            return "'" . mysqli_real_escape_string($koneksi, $item) . "'";
        }, $filter['kategori']);

        $query .= " AND pco.kategori IN (" . implode(',', $kategoriList) . ")";
    }

    // Filter berdasarkan company
    if (!empty($filter['company'])) {
        $companyList = array_map(function ($item) use ($koneksi) {
            return "'" . mysqli_real_escape_string($koneksi, $item) . "'";
        }, $filter['company']);

        $query .= " AND pco.company IN (" . implode(',', $companyList) . ")";
    }

    // Filter berdasarkan settlement_id
    if (!empty($filter['settlement_id'])) {
        $settlementList = array_map(function ($item) use ($koneksi) {
            return "'" . mysqli_real_escape_string($koneksi, $item) . "'";
        }, $filter['settlement_id']);

        $query .= " AND pco.id_settled IN (" . implode(',', $settlementList) . ")";
    }

    // Filter berdasarkan requestor
    if (!empty($filter['requestor'])) {
        $requestorList = array_map(function ($item) use ($koneksi) {
            return "'" . mysqli_real_escape_string($koneksi, $item) . "'";
        }, $filter['requestor']);

        $query .= " AND u.nama IN (" . implode(',', $requestorList) . ")";
    }

    // Filter berdasarkan tanggal
    if (!empty($filter['start_date'])) {
        $startDate = mysqli_real_escape_string($koneksi, $filter['start_date']);
        $dateField = ($filter['date_type'] === 'date_settled') ? 'pco.date_settled' : 'pco.date';
        $query .= " AND $dateField >= '$startDate'";
    }

    if (!empty($filter['end_date'])) {
        $endDate = mysqli_real_escape_string($koneksi, $filter['end_date']);
        $dateField = ($filter['date_type'] === 'date_settled') ? 'pco.date_settled' : 'pco.date';
        $query .= " AND $dateField <= '$endDate'";
    }

    // Filter berdasarkan daftar ID
    if (!empty($filter['id_list'])) {
        $idList = array_map(function ($item) use ($koneksi) {
            return "'" . mysqli_real_escape_string($koneksi, $item) . "'";
        }, $filter['id_list']);

        $query .= " AND pco.id_pc_out IN (" . implode(',', $idList) . ")";
    }

    // Sorting
    $query .= " ORDER BY pco.date_settled DESC, pco.date DESC";

    // Eksekusi query
    $result = mysqli_query($koneksi, $query);
    if (!$result) {
        return [
            'status' => 'error',
            'message' => 'Query error: ' . mysqli_error($koneksi),
            'data' => []
        ];
    }

    // Ambil data
    $data = [];
    $totalHarga = 0;
    while ($row = mysqli_fetch_assoc($result)) {
        $data[] = $row;
        $totalHarga += $row['harga'];
    }

    return [
        'status' => 'success',
        'message' => 'Data retrieved successfully',
        'total' => $totalHarga,
        'count' => count($data),
        'data' => $data
    ];
}

/**
 * Fungsi untuk mengambil data petty cash berdasarkan ID
 * 
 * @param object $koneksi Koneksi database MySQLi
 * @param array $id_list Array berisi ID yang akan diambil
 * @return array Data petty cash
 */
function getPettyCashById($koneksi, $id_list)
{
    if (empty($id_list)) {
        return [
            'status' => 'error',
            'message' => 'ID list is empty',
            'data' => []
        ];
    }

    return getSettledPettyCashData($koneksi, ['id_list' => $id_list]);
}

/**
 * Fungsi untuk mendapatkan kategori unik dari petty cash yang sudah settled
 * 
 * @param object $koneksi Koneksi database MySQLi
 * @return array Daftar kategori unik
 */
function getUniqueCategories($koneksi)
{
    $query = "SELECT DISTINCT kategori FROM petty_cash_out 
              WHERE status = 'Settled' AND (is_deleted = 0 OR is_deleted IS NULL) 
              ORDER BY kategori";

    $result = mysqli_query($koneksi, $query);
    if (!$result) {
        return [];
    }

    $categories = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $categories[] = $row['kategori'];
    }

    return $categories;
}

/**
 * Fungsi untuk mendapatkan company unik dari petty cash yang sudah settled
 * 
 * @param object $koneksi Koneksi database MySQLi
 * @return array Daftar company unik
 */
function getUniqueCompanies($koneksi)
{
    $query = "SELECT DISTINCT company FROM petty_cash_out 
              WHERE status = 'Settled' AND (is_deleted = 0 OR is_deleted IS NULL) 
              ORDER BY company";

    $result = mysqli_query($koneksi, $query);
    if (!$result) {
        return [];
    }

    $companies = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $companies[] = $row['company'];
    }

    return $companies;
}

/**
 * Fungsi untuk mendapatkan settlement ID unik dari petty cash yang sudah settled
 * 
 * @param object $koneksi Koneksi database MySQLi
 * @return array Daftar settlement ID unik
 */
function getUniqueSettlementIds($koneksi)
{
    $query = "SELECT DISTINCT id_settled FROM petty_cash_out 
              WHERE status = 'Settled' AND (is_deleted = 0 OR is_deleted IS NULL) 
              AND id_settled IS NOT NULL AND id_settled <> ''
              ORDER BY id_settled DESC";

    $result = mysqli_query($koneksi, $query);
    if (!$result) {
        return [];
    }

    $ids = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $ids[] = $row['id_settled'];
    }

    return $ids;
}

/**
 * Fungsi untuk mendapatkan requestor unik dari petty cash yang sudah settled
 * 
 * @param object $koneksi Koneksi database MySQLi
 * @return array Daftar requestor unik
 */
function getUniqueRequestors($koneksi)
{
    $query = "SELECT DISTINCT u.nama, u.idnik
              FROM petty_cash_out pco
              LEFT JOIN user u ON pco.request = u.idnik
              WHERE pco.status = 'Settled' AND (pco.is_deleted = 0 OR pco.is_deleted IS NULL) 
              AND u.nama IS NOT NULL
              ORDER BY u.nama";

    $result = mysqli_query($koneksi, $query);
    if (!$result) {
        return [];
    }

    $requestors = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $requestors[] = [
            'id' => $row['idnik'],
            'name' => $row['nama']
        ];
    }

    return $requestors;
}

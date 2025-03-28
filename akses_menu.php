<?php


$pageFiles = [
    'Dashboard' => 'home.php',
    'CashAdvance' => 'cash-advance.php',
    'PettyCashOut' => 'petty-cash-out.php',
    'PettyCashIn' => 'petty-cash-in.php',


    'CashOutExport' => 'cash-out-export.php',
    'CashOutPrint' => 'cash-out-print.php',
    'Profile' => 'profile.php',
    'Barang' => 'barang.php',
    'Lokasi' => 'lokasi.php',
    'Area' => 'area.php',
    'Gudang' => 'gudang.php',
    'Karyawan' => 'karyawan.php',
    'UserRoles'  => 'user-roles.php'
];


if (isset($_SESSION['idnik'])) {
    if (array_key_exists($page, $pageFiles)) {
        include $pageFiles[$page];
    } else {
        include 'pages-404.php';
    }
} else {
    include 'login.php';
}

<?php
require_once("../koneksi.php");

if (isset($_POST["update-petty-cash"])) {
    $id_pc_out = $_POST["id_pc_out"]; // FIXED: Sesuaikan nama variabel
    $deskripsi =  $_POST["deskripsi"];
    $date = $_POST["date"];
	$request = $_POST["request"];
	
    
    // FIXED: Ambil harga dari input hidden yang berisi angka asli tanpa format
    $harga = isset($_POST["harga"]) ? str_replace(".", "", $_POST["harga"]) : 0; 

    $company = $_POST["company"];
    $kategori = $_POST["kategori"];

    // FIXED: Pastikan variabel `$id_pc_out` digunakan dengan benar
    $query = "UPDATE petty_cash_out 
              SET deskripsi='$deskripsi', date='$date', harga='$harga', company='$company', kategori='$kategori', request='$request'
              WHERE id_pc_out='$id_pc_out'";

    $result = mysqli_query($koneksi, $query);

    session_start();
    if ($result) {
        $_SESSION["Messages"] = 'Data berhasil diperbarui';
        $_SESSION["Icon"] = 'success';
    } else {
        $_SESSION["Messages"] = 'Data gagal diperbarui: ' . mysqli_error($koneksi);
        $_SESSION["Icon"] = 'error';
    }

    header('Location: ../index.php?page=PettyCashOut');
    exit();
} else {
    header('Location: ../index.php?page=Dashboard');
    exit();
}
?>

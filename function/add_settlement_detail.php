<?php
// add_settlement_detail.php
require_once("../koneksi.php");
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $id_ca = $_POST['id_ca'];
    $id_settlement = $_POST['id_settlement'];
    $item_name = $_POST['item_name'];
    $item_category = $_POST['item_category'];
    $description = $_POST['description'];
    $qty = $_POST['qty'];

    // Clean the price input - remove formatting
    $price = preg_replace('/[^\d]/', '', $_POST['price']);

    // Calculate total price
    $total_price = $qty * $price;

    // Set default receipt_file
    $receipt_file = "";

    // Handle file upload if provided
    if (isset($_FILES['receipt_file']) && $_FILES['receipt_file']['error'] == 0) {
        $upload_dir = "../file/receipts/";

        // Create directory if it doesn't exist
        if (!file_exists($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        // Get file extension
        $file_extension = pathinfo($_FILES['receipt_file']['name'], PATHINFO_EXTENSION);

        // Generate a unique filename
        $new_filename = "receipt_" . $id_ca . "_" . $id_settlement . "_" . date("YmdHis") . "." . $file_extension;
        $target_file = $upload_dir . $new_filename;

        // Move uploaded file to destination
        if (move_uploaded_file($_FILES['receipt_file']['tmp_name'], $target_file)) {
            $receipt_file = $new_filename;
        } else {
            $_SESSION["Messages"] = 'Failed to upload receipt file';
            $_SESSION["Icon"] = 'error';
            header('Location: ../index.php?page=SettlementDetails&id_ca=' . $id_ca . '&id_settlement=' . $id_settlement);
            exit();
        }
    }

    // Insert into database
    $query = "INSERT INTO detailed_cash_advance 
              (id_ca, id_settlement, item_name, item_category, description, 
               qty, price, total_price, receipt_file, created_date) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    $stmt = mysqli_prepare($koneksi, $query);
    mysqli_stmt_bind_param(
        $stmt,
        "issssiiis",
        $id_ca,
        $id_settlement,
        $item_name,
        $item_category,
        $description,
        $qty,
        $price,
        $total_price,
        $receipt_file
    );

    $result = mysqli_stmt_execute($stmt);

    if ($result) {
        $_SESSION["Messages"] = 'Item has been successfully added to settlement';
        $_SESSION["Icon"] = 'success';
    } else {
        $_SESSION["Messages"] = 'Failed to add item: ' . mysqli_error($koneksi);
        $_SESSION["Icon"] = 'error';
    }

    // Redirect back to settlement details page
    header('Location: ../index.php?page=SettlementDetails&id_ca=' . $id_ca . '&id_settlement=' . $id_settlement);
    exit();
} else {
    // If not a POST request, redirect to dashboard
    header('Location: ../index.php?page=Dashboard');
    exit();
}

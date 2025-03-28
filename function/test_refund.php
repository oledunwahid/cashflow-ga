<?php
// Place this file in the same directory as your refund_ca.php file
// Test script to debug form submission

// Log all request data
$log_message = "==== TEST REFUND SUBMISSION (" . date('Y-m-d H:i:s') . ") ====\n";
$log_message .= "REQUEST METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n";

// Log POST data
$log_message .= "\nPOST DATA:\n";
foreach ($_POST as $key => $value) {
    $log_message .= "$key: $value\n";
}

// Log FILES data
$log_message .= "\nFILES DATA:\n";
if (!empty($_FILES)) {
    foreach ($_FILES as $file_key => $file_info) {
        $log_message .= "$file_key:\n";
        foreach ($file_info as $property => $value) {
            if (is_array($value)) {
                $log_message .= "  $property: " . print_r($value, true) . "\n";
            } else {
                $log_message .= "  $property: $value\n";
            }
        }
    }
} else {
    $log_message .= "No files uploaded\n";
}

// Write to log file
file_put_contents("../refund_debug.log", $log_message, FILE_APPEND);

// Send message to user
echo "Test form submission logged. Check refund_debug.log file.";
?>
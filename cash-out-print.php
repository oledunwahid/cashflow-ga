<?php
// Pastikan user sudah login
if (!isset($_SESSION['idnik'])) {
    header("Location: index.php");
    exit();
}

// Fungsi untuk mendapatkan nama perusahaan
function getCompanyName($companyCode)
{
    switch ($companyCode) {
        case 'MAA':
            return 'PT. MINERAL ALAM ABADI';
        case 'MMP':
            return 'PT. MITRA MINERAL PERKASA';
        case 'BCPM':
            return 'PT. BIMA CAKRA PERKASA MINERALINDO';
        default:
            return 'PT. BIMA CAKRA PERKASA MINERALINDO';
    }
}

// Default company code
$companyCode = isset($_GET['company']) ? $_GET['company'] : 'BCPM';
$companyName = getCompanyName($companyCode);

// Ambil data user untuk default prepared by
$userNama = $_SESSION['nama'] ?? 'Admin HRGA';

// Fungsi format tanggal
function formatDate($date)
{
    $timestamp = strtotime($date);
    return date('d-M-y', $timestamp);
}

// Fungsi format angka ke rupiah
function formatRupiah($angka)
{
    return number_format($angka, 0, ',', '.');
}

?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reimbursement Print</title>
    <style>
        @media print {
            body {
                font-family: Arial, sans-serif;
                font-size: 12px;
                line-height: 1.4;
                color: #000;
                margin: 0;
                padding: 0;
            }

            .no-print {
                display: none !important;
            }

            .page-break {
                page-break-after: always;
            }
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background-color: #f9f9f9;
            margin: 0;
            padding: 20px;
        }

        .print-container {
            background-color: white;
            width: 100%;
            max-width: 210mm;
            /* A4 width */
            margin: 0 auto 20px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .page-header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .subtitle {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
        }

        .info-left,
        .info-right {
            width: 48%;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
        }

        .info-label {
            width: 120px;
            font-weight: bold;
        }

        .info-field {
            flex: 1;
            border-bottom: 1px solid #ccc;
            padding: 2px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .centered {
            text-align: center;
        }

        .right-aligned {
            text-align: right;
        }

        .total-row td {
            font-weight: bold;
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .signature-box {
            width: 30%;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            margin-top: 50px;
            margin-bottom: 5px;
        }

        .print-button {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .print-button:hover {
            background-color: #45a049;
        }

        .back-button {
            background-color: #f44336;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-right: 10px;
        }

        .back-button:hover {
            background-color: #d32f2f;
        }

        .info-box {
            border: 1px solid #000;
            padding: 10px;
            margin-bottom: 20px;
        }

        .note-box {
            height: 100px;
            vertical-align: top;
        }

        .action-buttons {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }

        .empty-row {
            height: 25px;
        }
    </style>
</head>

<body>
    <div class="action-buttons no-print">
        <button class="back-button" onclick="window.location.href='index.php?page=CashOutExport'">Back to Filter</button>
        <button class="print-button" onclick="window.print();">Print Reimbursement</button>
    </div>

    <div class="print-container">
        <div class="page-header">
            <div class="title">REIMBURSEMENT</div>
            <div class="subtitle"><span id="company-name"><?= $companyName ?></span></div>
        </div>

        <div class="info-section">
            <div class="info-left">
                <div class="info-row">
                    <div class="info-label">Date:</div>
                    <div class="info-field" id="print-date"><?= date('d-M-y') ?></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Source of Cash Advance / Fund:</div>
                    <div class="info-field" id="cash-advance-source">Petty Cash</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Received date:</div>
                    <div class="info-field" id="received-date"></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Via:</div>
                    <div class="info-field">Internet Banking</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Cash:</div>
                    <div class="info-field"></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Bank:</div>
                    <div class="info-field" id="bank-name-field"></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Caj BO No.:</div>
                    <div class="info-field"></div>
                </div>
            </div>
            <div class="info-right">
                <div class="info-row">
                    <div class="info-label">No. Voucher:</div>
                    <div class="info-field" id="voucher-no"></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Reference:</div>
                    <div class="info-field" id="reference-no"></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Project:</div>
                    <div class="info-field"></div>
                </div>
                <div class="info-row">
                    <div class="info-label">Term Project:</div>
                    <div class="info-field"></div>
                </div>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th style="width: 5%;">No.</th>
                    <th style="width: 10%;">Date</th>
                    <th style="width: 45%;">Description</th>
                    <th style="width: 5%;">Qty</th>
                    <th style="width: 15%;">Price / unit</th>
                    <th style="width: 20%;">Amount in IDR</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="7" style="font-weight: bold;">Beginning Balance</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>Cash Advance Settlement</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <!-- Rows will be dynamically added here from JavaScript -->
                <tr id="item-row-1" style="display: none;">
                    <td class="centered item-no">1</td>
                    <td class="item-date">01-Jan-24</td>
                    <td class="item-description">Description goes here</td>
                    <td class="centered">1</td>
                    <td class="right-aligned item-price">Rp 100.000</td>
                    <td class="right-aligned item-amount">Rp 100.000</td>
                    <td class="item-notes">Notes</td>
                </tr>

                <!-- Empty rows for manual entries or spacing -->
                <tr class="empty-row">
                    <td class="centered"></td>
                    <td></td>
                    <td></td>
                    <td class="centered"></td>
                    <td class="right-aligned"></td>
                    <td class="right-aligned"></td>
                    <td></td>
                </tr>
                <tr class="empty-row">
                    <td class="centered"></td>
                    <td></td>
                    <td></td>
                    <td class="centered"></td>
                    <td class="right-aligned"></td>
                    <td class="right-aligned"></td>
                    <td></td>
                </tr>
                <tr class="empty-row">
                    <td class="centered"></td>
                    <td></td>
                    <td></td>
                    <td class="centered"></td>
                    <td class="right-aligned"></td>
                    <td class="right-aligned"></td>
                    <td></td>
                </tr>

                <tr>
                    <td colspan="5" class="right-aligned total-row">Total Expense</td>
                    <td class="right-aligned total-row" id="total-expense">Rp 0</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="5" class="right-aligned">Remaining Balance</td>
                    <td class="right-aligned" id="remaining-balance">Rp 0</td>
                    <td></td>
                </tr>
            </tbody>
        </table>

        <div class="info-section">
            <div class="info-left">
                <div class="info-box">
                    <div><strong>Other Information:</strong></div>
                    <div>Please transfer via:</div>
                    <div>Bank: <span id="bank-name">BCA a.n Siti Nurhayati</span></div>
                    <div>Account Number: <span id="account-number">0441628763</span></div>
                    <div>Branch:</div>
                    <div>Cash:</div>
                </div>
            </div>
            <div class="info-right">
                <div class="info-box note-box">
                    <div><strong>Note:</strong></div>
                    <div id="notes-content">Reimbursement for petty cash expenses</div>
                </div>
            </div>
        </div>

        <div class="signatures">
            <div class="signature-box">
                <div>Prepared By</div>
                <div class="signature-line"></div>
                <div id="prepared-by"><?= $userNama ?></div>
            </div>
            <div class="signature-box">
                <div>Checked by</div>
                <div class="signature-line"></div>
                <div id="checked-by">Finance Manager</div>
            </div>
            <div class="signature-box">
                <div>Approved by</div>
                <div class="signature-line"></div>
                <div id="approved-by">Director</div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if we have data in sessionStorage
            var selectedItemsJson = sessionStorage.getItem('selectedCashOutItems');

            if (!selectedItemsJson) {
                document.body.innerHTML = '<div style="text-align: center; margin-top: 50px;"><h2>No data selected</h2><p>Please go back and select items to print.</p><button class="back-button" onclick="window.location.href=\'index.php?page=CashOutExport\'">Back to Filter</button></div>';
                return;
            }

            var selectedItems = JSON.parse(selectedItemsJson);

            if (selectedItems.length === 0) {
                document.body.innerHTML = '<div style="text-align: center; margin-top: 50px;"><h2>No data selected</h2><p>Please go back and select items to print.</p><button class="back-button" onclick="window.location.href=\'index.php?page=CashOutExport\'">Back to Filter</button></div>';
                return;
            }

            // Set reference number (using the first settlement ID or current date)
            var referenceNo = '';
            if (selectedItems[0].settlementId && selectedItems[0].settlementId.trim() !== '') {
                referenceNo = selectedItems[0].settlementId;
            } else {
                var today = new Date();
                referenceNo = 'REF-' + today.getFullYear() +
                    ('0' + (today.getMonth() + 1)).slice(-2) +
                    ('0' + today.getDate()).slice(-2);
            }
            document.getElementById('reference-no').textContent = referenceNo;

            // Set voucher number (using today's date with timestamp)
            var today = new Date();
            var voucherNo = 'VC-' + today.getFullYear() +
                ('0' + (today.getMonth() + 1)).slice(-2) +
                ('0' + today.getDate()).slice(-2) + '-' +
                ('0' + today.getHours()).slice(-2) +
                ('0' + today.getMinutes()).slice(-2);
            document.getElementById('voucher-no').textContent = voucherNo;

            // Company name from first item
            if (selectedItems[0].company) {
                document.getElementById('company-name').textContent = selectedItems[0].company;
            }

            // Fill the table with data
            var table = document.querySelector('tbody');
            var emptyRows = document.querySelectorAll('.empty-row');

            // Remove empty placeholder rows
            emptyRows.forEach(function(row) {
                row.remove();
            });

            // Get the row template
            var templateRow = document.getElementById('item-row-1');
            templateRow.style.display = 'none'; // Hide the template

            var totalAmount = 0;

            // Add data rows
            for (var i = 0; i < selectedItems.length; i++) {
                var item = selectedItems[i];
                var newRow = templateRow.cloneNode(true);

                // Format the date
                var dateObj = new Date(item.date);
                var formattedDate = dateObj.getDate() + '-' +
                    (['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'][dateObj.getMonth()]) + '-' +
                    (dateObj.getFullYear().toString().substr(-2));

                // Set the row ID and display
                newRow.id = 'item-row-' + (i + 1);
                newRow.style.display = '';

                // Set cell contents
                newRow.querySelector('.item-no').textContent = i + 1;
                newRow.querySelector('.item-date').textContent = formattedDate;
                newRow.querySelector('.item-description').textContent = item.description;

                // Format currency
                var price = parseInt(item.harga);
                totalAmount += price;

                newRow.querySelector('.item-price').textContent = 'Rp ' + price.toLocaleString('id-ID');
                newRow.querySelector('.item-amount').textContent = 'Rp ' + price.toLocaleString('id-ID');

                // Add notes (category)
                newRow.querySelector('.item-notes').textContent = item.category;

                // Insert row before the total row
                var totalRow = document.querySelector('.total-row').parentNode;
                table.insertBefore(newRow, totalRow);
            }

            // Add some empty rows for manual entries (if needed)
            for (var j = 0; j < 3; j++) {
                var emptyRow = document.createElement('tr');
                emptyRow.classList.add('empty-row');
                emptyRow.innerHTML = `
                    <td class="centered"></td>
                    <td></td>
                    <td></td>
                    <td class="centered"></td>
                    <td class="right-aligned"></td>
                    <td class="right-aligned"></td>
                    <td></td>
                `;
                var totalRow = document.querySelector('.total-row').parentNode;
                table.insertBefore(emptyRow, totalRow);
            }

            // Update totals
            document.getElementById('total-expense').textContent = 'Rp ' + totalAmount.toLocaleString('id-ID');
            document.getElementById('remaining-balance').textContent = 'Rp 0';

            // Set other default values if needed
            document.getElementById('notes-content').textContent = 'Reimbursement for petty cash expenses (' +
                selectedItems.length + ' items)';

            // Prepare print metadata
            if (typeof sessionStorage !== 'undefined') {
                // Clear session data after printing to prevent reusing old data accidentally
                window.addEventListener('afterprint', function() {
                    // Don't clear it immediately, give user a chance to print again if needed
                    // sessionStorage.removeItem('selectedCashOutItems');
                });
            }
        });
    </script>
</body>

</html>
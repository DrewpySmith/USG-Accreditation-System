<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program of Expenditures</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            background: #f5f7fa;
        }
        
        /* Navigation Bar */
        .navbar {
            background: #27ae60;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 10px;
        }
        .navbar-title {
            color: white;
            font-weight: bold;
            font-size: 18px;
        }
        .navbar-buttons {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }
        .btn-back { background: #6c757d; color: white; }
        .btn-add { background: #007bff; color: white; }
        .btn-download { background: #28a745; color: white; }
        .btn-print { background: #17a2b8; color: white; }
        
        .form-content {
            background: white;
            padding: 30px;
            border: 2px solid #333;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #000;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        th {
            background: #f0f0f0;
        }
        input[type="text"], input[type="number"] {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            text-align: center;
        }
        .signatures {
            margin-top: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
        }
        .signature-box {
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #000;
            margin: 40px 20px 5px 20px;
        }
        .signature-name {
            border: none;
            border-bottom: 1px solid #000;
            width: 200px;
            padding: 5px;
            text-align: center;
            margin-bottom: 5px;
        }
        @media print {
            .no-print, .navbar { display: none; }
            body { background: white; padding: 0; }
            .form-content { border: none; page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar no-print">
        <div class="navbar-title">Program of Expenditures</div>
        <div class="navbar-buttons">
            <a href="<?= base_url('organization/dashboard') ?>" class="btn btn-back">← Back</a>
            <button onclick="addRow()" class="btn btn-add">+ Add Row</button>
            <button onclick="downloadPDF()" class="btn btn-download">📥 Download PDF</button>
            <button onclick="window.print()" class="btn btn-print">🖨️ Print</button>
        </div>
    </div>

    <div class="form-content">
        <div class="header">
            <p style="margin: 0;">Republic of the Philippines</p>
            <h3 style="margin: 5px 0;">SULTAN KUDARAT STATE UNIVERSITY</h3>
            <p style="margin: 0; font-size: 14px;">ACCESS, EJC Montilla, 9800 City of Tacurong</p>
            <p style="margin: 0; font-size: 14px;">Province of Sultan Kudarat</p>
            <h3 style="margin: 20px 0;">PROGRAM OF EXPENDITURES</h3>
            <p>A.Y. <input type="text" id="academic_year" style="width: 100px; border: none; border-bottom: 1px solid #000; text-align: center;" 
            value="<?= $academic_year ?>" placeholder="2024-2025"></p>
            <p style="border-bottom: 1px solid #000; display: inline-block; min-width: 300px;">
                <?= isset($organization['name']) ? esc($organization['name']) : '' ?>
            </p>
            <p style="margin: 0; font-size: 12px;">(Organization)</p>
        </div>

        <h4>A. Expected Collections</h4>
        <table id="expenditure-table">
            <thead>
                <tr>
                    <th>Type of Fee</th>
                    <th style="width: 120px;">Amount</th>
                    <th style="width: 150px;">Frequency of Collection</th>
                    <th style="width: 120px;">Number of Students</th>
                    <th style="width: 120px;">Total</th>
                </tr>
            </thead>
            <tbody id="expenditure-body">
                <?php if (empty($expenditures)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 30px;">
                            No entries yet. Click "Add Row" to start.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($expenditures as $exp): ?>
                        <tr data-id="<?= $exp['id'] ?>">
                            <td><?= esc($exp['fee_type']) ?></td>
                            <td>₱ <?= number_format($exp['amount'], 2) ?></td>
                            <td><?= esc($exp['frequency']) ?></td>
                            <td><?= $exp['number_of_students'] ?></td>
                            <td>₱ <?= number_format($exp['total'], 2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" style="text-align: right;">Grand Total</th>
                    <th>₱ <?= number_format($grand_total ?? 0, 2) ?></th>
                </tr>
            </tfoot>
        </table>

        <div class="signatures">
            <div class="signature-box">
                <p>Prepared by:</p>
                <input type="text" id="head_name" class="signature-name" placeholder="Type name here">
                <div class="signature-line"></div>
                <p style="margin: 0;">Head of Organization</p>
            </div>
            <div class="signature-box">
                <p>Approved:</p>
                <input type="text" id="adviser_name" class="signature-name" placeholder="Type name here">
                <div class="signature-line"></div>
                <p style="margin: 0;">Adviser</p>
            </div>
        </div>
    </div>

    <!-- Add Row Modal -->
    <div id="rowModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="background: white; width: 500px; margin: 50px auto; padding: 30px; border-radius: 10px;">
            <h3>Add Expenditure Entry</h3>
            <form id="expenditureForm">
                <?= csrf_field() ?>
                <div style="margin: 15px 0;">
                    <label>Type of Fee:</label>
                    <input type="text" id="fee_type" required>
                </div>
                <div style="margin: 15px 0;">
                    <label>Amount:</label>
                    <input type="number" id="amount" step="0.01" required>
                </div>
                <div style="margin: 15px 0;">
                    <label>Frequency of Collection:</label>
                    <input type="text" id="frequency" placeholder="e.g., Once, Per Semester" required>
                </div>
                <div style="margin: 15px 0;">
                    <label>Number of Students:</label>
                    <input type="number" id="number_of_students" required>
                </div>
                <div style="margin: 15px 0;">
                    <label>Total (auto-calculated):</label>
                    <input type="text" id="total_preview" readonly style="background: #f0f0f0;">
                </div>
                <div style="text-align: right; margin-top: 20px;">
                    <button type="button" onclick="closeModal()" class="btn" style="background: #6c757d; color: white;">Cancel</button>
                    <button type="submit" class="btn" style="background: #007bff; color: white;">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const csrfHeaderName = '<?= esc(config('Security')->headerName) ?>';
        let csrfToken = '<?= csrf_hash() ?>';

        function updateCsrfToken(next) {
            if (typeof next === 'string' && next.length > 0) {
                csrfToken = next;
            }
        }

        function addRow() {
            document.getElementById('expenditureForm').reset();
            document.getElementById('rowModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('rowModal').style.display = 'none';
        }

        document.getElementById('amount').addEventListener('input', updateTotal);
        document.getElementById('number_of_students').addEventListener('input', updateTotal);

        function updateTotal() {
            const amount = parseFloat(document.getElementById('amount').value) || 0;
            const students = parseInt(document.getElementById('number_of_students').value) || 0;
            document.getElementById('total_preview').value = '₱ ' + (amount * students).toFixed(2);
        }

        document.getElementById('expenditureForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData();
            formData.append('academic_year', document.getElementById('academic_year').value);
            formData.append('fee_type', document.getElementById('fee_type').value);
            formData.append('amount', document.getElementById('amount').value);
            formData.append('frequency', document.getElementById('frequency').value);
            formData.append('number_of_students', document.getElementById('number_of_students').value);

            fetch('/organization/program-expenditure/store', {
                method: 'POST',
                headers: {
                    [csrfHeaderName]: csrfToken
                },
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                updateCsrfToken(data.csrf);
                if (data.success) {
                    alert(data.message);
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to save entry'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving');
            });
        });

        function downloadPDF() {
            const year = document.getElementById('academic_year').value;
            if (!year) {
                alert('Please enter academic year');
                return;
            }
            window.location.href = `/organization/program-expenditure/download/${year}`;
        }
    </script>
</body>
</html>
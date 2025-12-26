<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
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
        .btn-save { background: #007bff; color: white; }
        .btn-download { background: #28a745; color: white; }
        .btn-print { background: #17a2b8; color: white; }
        .btn-add { background: #6c757d; color: white; padding: 5px 10px; font-size: 14px; }
        
        .form-content {
            background: white;
            padding: 30px;
            border: 2px solid #333;
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
            border: 1px solid #ccc;
            padding: 5px;
            width: 100%;
            text-align: center;
        }
        .section-title {
            font-weight: bold;
            margin: 20px 0 10px 0;
            font-size: 16px;
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
        .file-upload {
            margin: 20px 0;
            padding: 15px;
            border: 2px dashed #ccc;
            border-radius: 5px;
            text-align: center;
        }
        @media print {
            .no-print, .navbar { display: none; }
            body { background: white; padding: 0; }
            .form-content { border: none; page-break-after: always; }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar no-print">
        <div class="navbar-title">Financial Report</div>
        <div class="navbar-buttons">
            <a href="<?= base_url('organization/dashboard') ?>" class="btn btn-back">← Back</a>
            <button onclick="saveForm()" class="btn btn-save">💾 Save</button>
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
            <h3 style="margin: 20px 0;">FINANCIAL REPORTS</h3>
            <p>A.Y. <input type="text" id="academic_year" style="width: 100px; border: none; border-bottom: 1px solid #000; text-align: center;" 
            value="<?= isset($report['academic_year']) ? $report['academic_year'] : '' ?>" placeholder="2024-2025"></p>
            <p style="border-bottom: 1px solid #000; display: inline-block; min-width: 300px;">
                <?= isset($organization['name']) ? esc($organization['name']) : '' ?>
            </p>
            <p style="margin: 0; font-size: 12px;">(Organization)</p>
        </div>

        <!-- Summary of Collection -->
        <div class="section-title">1. Summary of Collection</div>
        <table id="collections-table">
            <thead>
                <tr>
                    <th>Types of Collection</th>
                    <th style="width: 200px;">Total Amount Collected</th>
                    <th class="no-print" style="width: 80px;">Action</th>
                </tr>
            </thead>
            <tbody id="collections-body">
                <!-- Collections will be added here -->
            </tbody>
            <tfoot>
                <tr>
                    <th>Grand Total</th>
                    <th id="grand-total-collection">₱ 0.00</th>
                    <th class="no-print"></th>
                </tr>
            </tfoot>
        </table>
        <button class="btn btn-add no-print" onclick="addCollectionRow()">+ Add Collection</button>

        <!-- Summary of Expenses -->
        <div class="section-title">2. Summary of Expenses</div>
        <table id="expenses-table">
            <thead>
                <tr>
                    <th>Activity/Project/Program</th>
                    <th style="width: 200px;">Total Expenses</th>
                    <th class="no-print" style="width: 80px;">Action</th>
                </tr>
            </thead>
            <tbody id="expenses-body">
                <!-- Expenses will be added here -->
            </tbody>
            <tfoot>
                <tr>
                    <th>Grand Total</th>
                    <th id="grand-total-expenses">₱ 0.00</th>
                    <th class="no-print"></th>
                </tr>
            </tfoot>
        </table>
        <button class="btn btn-add no-print" onclick="addExpenseRow()">+ Add Expense</button>

        <!-- Cash Summary -->
        <div style="margin: 30px 0;">
            <div class="section-title">3. Cash on Bank: 
                <input type="number" id="cash_on_bank" step="0.01" style="width: 150px; display: inline-block;" 
                value="<?= isset($report['cash_on_bank']) ? $report['cash_on_bank'] : '0' ?>">
            </div>
            
            <div class="section-title">4. Cash on Hand</div>
            <table>
                <tr>
                    <td>1. Total Collection</td>
                    <td id="total-collection-summary">₱ 0.00</td>
                </tr>
                <tr>
                    <td>2. Total Expenses</td>
                    <td id="total-expenses-summary">₱ 0.00</td>
                </tr>
                <tr>
                    <td>3. Cash on Bank</td>
                    <td id="cash-on-bank-summary">₱ 0.00</td>
                </tr>
                <tr>
                    <td>4. Cash on Hand (1 – 2 – 3 =)</td>
                    <td id="cash-on-hand-result">₱ 0.00</td>
                </tr>
                <tr>
                    <th>5. Total Remaining Fund (3 + 4 =)</th>
                    <th id="total-remaining-fund">₱ 0.00</th>
                </tr>
            </table>
            <p style="font-size: 12px; margin-top: 10px;">*Note: Please attach photocopy of organization passbook.</p>
            
            <!-- File Upload for Passbook -->
            <div class="file-upload no-print">
                <label for="passbook_file" style="cursor: pointer;">
                    📎 Click to attach passbook copy (Image, PDF, DOC)
                </label>
                <input type="file" id="passbook_file" name="passbook_copy" accept="image/*,.pdf,.doc,.docx" 
                       style="display: none;" onchange="displayFileName(this)">
                <p id="file-name" style="margin-top: 10px; color: #007bff;"></p>
            </div>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="signature-box">
                <p>Prepared by:</p>
                <input type="text" id="treasurer_name" class="signature-name" placeholder="Treasurer Name" 
                value="<?= isset($report['treasurer_name']) ? esc($report['treasurer_name']) : '' ?>">
                <div class="signature-line"></div>
                <p style="margin: 0;">Treasurer</p>
            </div>
            <div class="signature-box">
                <p>Reviewed by:</p>
                <input type="text" id="auditor_name" class="signature-name" placeholder="Auditor Name" 
                value="<?= isset($report['auditor_name']) ? esc($report['auditor_name']) : '' ?>">
                <div class="signature-line"></div>
                <p style="margin: 0;">Auditor</p>
            </div>
        </div>
        <div class="signatures">
            <div class="signature-box">
                <input type="text" id="head_name" class="signature-name" placeholder="Head Name" 
                value="<?= isset($report['head_name']) ? esc($report['head_name']) : '' ?>">
                <div class="signature-line"></div>
                <p style="margin: 0;">Head of Organization</p>
            </div>
            <div class="signature-box">
                <p>Approved:</p>
                <input type="text" id="adviser_name" class="signature-name" placeholder="Adviser Name" 
                value="<?= isset($report['adviser_name']) ? esc($report['adviser_name']) : '' ?>">
                <div class="signature-line"></div>
                <p style="margin: 0;">Adviser</p>
            </div>
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

        let collections = <?= json_encode($report['collections'] ?? []) ?>;
        let expenses = <?= json_encode($report['expenses'] ?? []) ?>;

        function renderCollections() {
            const tbody = document.getElementById('collections-body');
            tbody.innerHTML = '';
            let total = 0;

            collections.forEach((item, index) => {
                const amount = parseFloat(item.amount) || 0;
                total += amount;
                tbody.innerHTML += `
                    <tr>
                        <td><input type="text" value="${item.type || ''}" onchange="updateCollection(${index}, 'type', this.value)"></td>
                        <td><input type="number" step="0.01" value="${amount}" onchange="updateCollection(${index}, 'amount', this.value)"></td>
                        <td class="no-print"><button onclick="removeCollection(${index})" class="btn btn-add">Remove</button></td>
                    </tr>
                `;
            });

            document.getElementById('grand-total-collection').textContent = '₱ ' + total.toFixed(2);
            updateSummary();
        }

        function renderExpenses() {
            const tbody = document.getElementById('expenses-body');
            tbody.innerHTML = '';
            let total = 0;

            expenses.forEach((item, index) => {
                const amount = parseFloat(item.amount) || 0;
                total += amount;
                tbody.innerHTML += `
                    <tr>
                        <td><input type="text" value="${item.activity || ''}" onchange="updateExpense(${index}, 'activity', this.value)"></td>
                        <td><input type="number" step="0.01" value="${amount}" onchange="updateExpense(${index}, 'amount', this.value)"></td>
                        <td class="no-print"><button onclick="removeExpense(${index})" class="btn btn-add">Remove</button></td>
                    </tr>
                `;
            });

            document.getElementById('grand-total-expenses').textContent = '₱ ' + total.toFixed(2);
            updateSummary();
        }

        function addCollectionRow() {
            collections.push({ type: '', amount: 0 });
            renderCollections();
        }

        function addExpenseRow() {
            expenses.push({ activity: '', amount: 0 });
            renderExpenses();
        }

        function removeCollection(index) {
            collections.splice(index, 1);
            renderCollections();
        }

        function removeExpense(index) {
            expenses.splice(index, 1);
            renderExpenses();
        }

        function updateCollection(index, field, value) {
            collections[index][field] = value;
            renderCollections();
        }

        function updateExpense(index, field, value) {
            expenses[index][field] = value;
            renderExpenses();
        }

        function updateSummary() {
            let totalCollection = collections.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);
            let totalExpenses = expenses.reduce((sum, item) => sum + (parseFloat(item.amount) || 0), 0);
            let cashOnBank = parseFloat(document.getElementById('cash_on_bank').value) || 0;
            let cashOnHand = totalCollection - totalExpenses - cashOnBank;
            let totalRemaining = cashOnBank + cashOnHand;

            document.getElementById('total-collection-summary').textContent = '₱ ' + totalCollection.toFixed(2);
            document.getElementById('total-expenses-summary').textContent = '₱ ' + totalExpenses.toFixed(2);
            document.getElementById('cash-on-bank-summary').textContent = '₱ ' + cashOnBank.toFixed(2);
            document.getElementById('cash-on-hand-result').textContent = '₱ ' + cashOnHand.toFixed(2);
            document.getElementById('total-remaining-fund').textContent = '₱ ' + totalRemaining.toFixed(2);
        }

        document.getElementById('cash_on_bank').addEventListener('input', updateSummary);

        function displayFileName(input) {
            const fileName = input.files[0]?.name;
            if (fileName) {
                document.getElementById('file-name').textContent = '✓ Selected: ' + fileName;
            }
        }

        function saveForm() {
            const formData = new FormData();
            formData.append('academic_year', document.getElementById('academic_year').value);
            formData.append('collections', JSON.stringify(collections));
            formData.append('expenses', JSON.stringify(expenses));
            formData.append('cash_on_bank', document.getElementById('cash_on_bank').value);
            formData.append('treasurer_name', document.getElementById('treasurer_name').value);
            formData.append('auditor_name', document.getElementById('auditor_name').value);
            formData.append('head_name', document.getElementById('head_name').value);
            formData.append('adviser_name', document.getElementById('adviser_name').value);

            const passbookFile = document.getElementById('passbook_file').files[0];
            if (passbookFile) {
                formData.append('passbook_copy', passbookFile);
            }

            fetch('/organization/financial-report/store', {
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
                    alert('Financial report saved successfully!');
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to save report'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the report');
            });
        }

        function downloadPDF() {
            const academicYear = document.getElementById('academic_year').value;
            if (!academicYear) {
                alert('Please enter academic year and save the form first');
                return;
            }
            window.location.href = `/organization/financial-report/download/${academicYear}`;
        }

        if (collections.length === 0) {
            addCollectionRow();
        } else {
            renderCollections();
        }

        if (expenses.length === 0) {
            addExpenseRow();
        } else {
            renderExpenses();
        }
    </script>
</body>
</html>
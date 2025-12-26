<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commitment Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
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
        
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .form-content {
            background: white;
            padding: 40px;
            border: 2px solid #333;
            margin: 20px 0;
        }
        .form-group {
            margin: 15px 0;
        }
        .underline-input {
            border: none;
            border-bottom: 1px solid #000;
            width: 200px;
            padding: 5px;
            outline: none;
            text-align: center;
        }
        .wide-input {
            width: 400px;
        }
        .form-text {
            line-height: 2;
            text-align: justify;
        }
        .signature-section {
            margin-top: 40px;
            text-align: center;
        }
        .signature-name {
            border: none;
            border-bottom: 1px solid #000;
            width: 300px;
            padding: 5px;
            text-align: center;
            margin-bottom: 5px;
        }
        .button-group {
            margin: 20px 0;
            text-align: center;
        }
        @media print {
            .button-group, .no-print, .navbar {
                display: none;
            }
            body {
                padding: 0;
            }
            .form-content {
                border: none;
                page-break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <div class="navbar no-print">
        <div class="navbar-title">Commitment Form</div>
        <div class="navbar-buttons">
            <a href="<?= base_url('organization/dashboard') ?>" class="btn btn-back">← Back</a>
            <button onclick="saveForm()" class="btn btn-save">💾 Save</button>
            <button onclick="downloadPDF()" class="btn btn-download">📥 Download PDF</button>
            <button onclick="window.print()" class="btn btn-print">🖨️ Print</button>
        </div>
    </div>

    <div class="form-content">
        <?php if (!empty($forms)): ?>
            <div class="no-print" style="margin-bottom: 25px; padding: 15px; border: 1px solid #ddd; border-radius: 6px; background: #f8f9fa;">
                <div style="font-weight: bold; margin-bottom: 10px;">Existing Commitment Forms</div>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr>
                                <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">ID</th>
                                <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Officer</th>
                                <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Position</th>
                                <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">A.Y.</th>
                                <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Status</th>
                                <th style="text-align: left; padding: 8px; border-bottom: 1px solid #ddd;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($forms as $f): ?>
                                <tr>
                                    <td style="padding: 8px; border-bottom: 1px solid #eee;"><?= esc($f['id'] ?? '') ?></td>
                                    <td style="padding: 8px; border-bottom: 1px solid #eee;"><?= esc($f['officer_name'] ?? '') ?></td>
                                    <td style="padding: 8px; border-bottom: 1px solid #eee;"><?= esc($f['position'] ?? '') ?></td>
                                    <td style="padding: 8px; border-bottom: 1px solid #eee;"><?= esc($f['academic_year'] ?? '') ?></td>
                                    <td style="padding: 8px; border-bottom: 1px solid #eee;"><?= esc($f['status'] ?? '') ?></td>
                                    <td style="padding: 8px; border-bottom: 1px solid #eee; white-space: nowrap;">
                                        <a class="btn btn-save" style="padding: 6px 10px;" href="<?= base_url('organization/commitment-form?id=' . ($f['id'] ?? '')) ?>">Open</a>
                                        <a class="btn btn-download" style="padding: 6px 10px;" href="<?= base_url('organization/commitment-form/download/' . ($f['id'] ?? '')) ?>">PDF</a>
                                        <a class="btn btn-print" style="padding: 6px 10px;" href="<?= base_url('organization/commitment-form/print/' . ($f['id'] ?? '')) ?>" target="_blank">Print</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

        <div class="header">
            <p style="margin: 0;">Republic of the Philippines</p>
            <h3 style="margin: 5px 0;">SULTAN KUDARAT STATE UNIVERSITY</h3>
            <p style="margin: 0; font-size: 14px;">ACCESS, EJC Montilla, 9800 City of Tacurong</p>
            <p style="margin: 0; font-size: 14px;">Province of Sultan Kudarat</p>
            <h3 style="margin: 20px 0 30px 0;">COMMITMENT FORM</h3>
        </div>

        <div class="form-text">
            <p style="text-align: center;">
                I <input type="text" id="officer_name" class="underline-input wide-input" 
                value="<?= isset($form['officer_name']) ? esc($form['officer_name']) : '' ?>" placeholder="Name"> 
                hereby committed to take my responsibilities and duties as the newly elected 
                <input type="text" id="position" class="underline-input" 
                value="<?= isset($form['position']) ? esc($form['position']) : '' ?>" placeholder="Position"> 
                of the <input type="text" id="organization_name" class="underline-input wide-input" 
                value="<?= isset($organization['name']) ? esc($organization['name']) : (isset($form['organization_name']) ? esc($form['organization_name']) : '') ?>" 
                placeholder="Organization" readonly style="background: #f0f0f0;">
                AY <input type="text" id="academic_year" class="underline-input" 
                value="<?= isset($form['academic_year']) ? esc($form['academic_year']) : '' ?>" placeholder="2024-2025">.I will render the best service I can give for the welfare of the said organization, 
                my fellow students, and University. I will respectfully abide the constitution and 
                By-Laws of the Republic of the Philippines and the rules and regulations of Sultan 
                Kudarat State University.
            </p>
            <p style="margin-top: 30px; text-align: center;">
                So help me God.
            </p>
        </div>

        <div class="signature-section">
            <p>
                Signed this <input type="number" id="signed_day" class="underline-input" style="width: 50px;"
                value="<?= isset($form['signed_date']) ? date('d', strtotime($form['signed_date'])) : '' ?>" 
                placeholder="Day" min="1" max="31"> day of 
                <select id="signed_month" class="underline-input" style="border: none; border-bottom: 1px solid #000; text-align: center;">
                    <option value="">Month</option>
                    <option value="01" <?= (isset($form['signed_date']) && date('m', strtotime($form['signed_date'])) == '01') ? 'selected' : '' ?>>January</option>
                    <option value="02" <?= (isset($form['signed_date']) && date('m', strtotime($form['signed_date'])) == '02') ? 'selected' : '' ?>>February</option>
                    <option value="03" <?= (isset($form['signed_date']) && date('m', strtotime($form['signed_date'])) == '03') ? 'selected' : '' ?>>March</option>
                    <option value="04" <?= (isset($form['signed_date']) && date('m', strtotime($form['signed_date'])) == '04') ? 'selected' : '' ?>>April</option>
                    <option value="05" <?= (isset($form['signed_date']) && date('m', strtotime($form['signed_date'])) == '05') ? 'selected' : '' ?>>May</option>
                    <option value="06" <?= (isset($form['signed_date']) && date('m', strtotime($form['signed_date'])) == '06') ? 'selected' : '' ?>>June</option>
                    <option value="07" <?= (isset($form['signed_date']) && date('m', strtotime($form['signed_date'])) == '07') ? 'selected' : '' ?>>July</option>
                    <option value="08" <?= (isset($form['signed_date']) && date('m', strtotime($form['signed_date'])) == '08') ? 'selected' : '' ?>>August</option>
                    <option value="09" <?= (isset($form['signed_date']) && date('m', strtotime($form['signed_date'])) == '09') ? 'selected' : '' ?>>September</option>
                    <option value="10" <?= (isset($form['signed_date']) && date('m', strtotime($form['signed_date'])) == '10') ? 'selected' : '' ?>>October</option>
                    <option value="11" <?= (isset($form['signed_date']) && date('m', strtotime($form['signed_date'])) == '11') ? 'selected' : '' ?>>November</option>
                    <option value="12" <?= (isset($form['signed_date']) && date('m', strtotime($form['signed_date'])) == '12') ? 'selected' : '' ?>>December</option>
                </select>
            </p>
            <div style="margin-top: 50px;">
                <input type="text" id="signature_name" class="signature-name" 
                       value="<?= isset($form['officer_name']) ? esc($form['officer_name']) : '' ?>" 
                       placeholder="Type your name here">
            
                <p style="margin: 5px 0;">Signature</p>
            </div>
        </div>
    </div>

    <input type="hidden" id="form_id" value="<?= isset($form['id']) ? $form['id'] : '' ?>">

    <script>
        const csrfHeaderName = '<?= esc(config('Security')->headerName) ?>';
        let csrfToken = '<?= csrf_hash() ?>';

        function updateCsrfToken(next) {
            if (typeof next === 'string' && next.length > 0) {
                csrfToken = next;
            }
        }

        function saveForm() {
            const day = document.getElementById('signed_day').value;
            const month = document.getElementById('signed_month').value;
            
            if (!day || !month) {
                alert('Please fill in the signed date (day and month)');
                return;
            }
            
            const year = new Date().getFullYear();
            const signedDate = `${year}-${month}-${day.padStart(2, '0')}`;
            
            const formData = {
                officer_name: document.getElementById('officer_name').value,
                position: document.getElementById('position').value,
                organization_name: document.getElementById('organization_name').value,
                academic_year: document.getElementById('academic_year').value,
                signed_date: signedDate
            };

            const formId = document.getElementById('form_id').value;
            const url = formId ? `/organization/commitment-form/update/${formId}` : '/organization/commitment-form/store';

            fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    [csrfHeaderName]: csrfToken
                },
                body: JSON.stringify(formData)
            })
            .then(response => response.json())
            .then(data => {
                updateCsrfToken(data.csrf);
                if (data.success) {
                    alert('Form saved successfully!');
                    if (data.id) {
                        document.getElementById('form_id').value = data.id;
                    }
                    location.reload();
                } else {
                    alert('Error: ' + (data.message || 'Failed to save form'));
                }
            })

            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving the form');
            });
        }

        function downloadPDF() {
            const formId = document.getElementById('form_id').value;
            if (!formId) {
                alert('Please save the form first');
                return;
            }
            window.location.href = `/organization/commitment-form/download/${formId}`;
        }
        
        // Auto-fill signature name when officer name changes
        document.getElementById('officer_name').addEventListener('input', function() {
            document.getElementById('signature_name').value = this.value;
        });
    </script>
</body>
</html>
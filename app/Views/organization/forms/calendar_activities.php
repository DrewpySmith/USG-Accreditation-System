<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar of Activities</title>
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
        .btn-danger { background: #dc3545; color: white; padding: 5px 10px; font-size: 12px; }
        
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
        input[type="text"], input[type="date"], textarea {
            width: 100%;
            padding: 5px;
            border: 1px solid #ccc;
            text-align: center;
        }
        select {
            width: 100%;
            padding: 5px;
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
        <div class="navbar-title">Calendar of Activities</div>
        <div class="navbar-buttons">
            <a href="<?= base_url('organization/dashboard') ?>" class="btn btn-back">← Back</a>
            <button onclick="addActivity()" class="btn btn-add">+ Add Activity</button>
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
            <h3 style="margin: 20px 0;">CALENDAR OF ACTIVITIES</h3>
            <p>A.Y. <input type="text" id="academic_year" style="width: 100px; border: none; border-bottom: 1px solid #000; text-align: center;" 
            value="<?= $academic_year ?>" placeholder="2024-2025"></p>
            <p style="border-bottom: 1px solid #000; display: inline-block; min-width: 300px;">
                <?= isset($organization['name']) ? esc($organization['name']) : '' ?>
            </p>
            <p style="margin: 0; font-size: 12px;">(Organization)</p>
        </div>

        <table id="activities-table">
            <thead>
                <tr>
                    <th style="width: 120px;">Date</th>
                    <th>Activity Title</th>
                    <th style="width: 180px;">Responsible Person</th>
                    <th style="width: 150px;">Remarks</th>
                    <th class="no-print" style="width: 100px;">Actions</th>
                </tr>
            </thead>
            <tbody id="activities-body">
                <?php if (empty($activities)): ?>
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 30px;">
                            No activities yet. Click "Add Activity" to start.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($activities as $activity): ?>
                        <tr data-id="<?= $activity['id'] ?>">
                            <td><?= date('M d, Y', strtotime($activity['activity_date'])) ?></td>
                            <td><?= esc($activity['activity_title']) ?></td>
                            <td><?= esc($activity['responsible_person']) ?></td>
                            <td><?= esc($activity['remarks']) ?></td>
                            <td class="no-print">
                                <button onclick="deleteActivity(<?= $activity['id'] ?>)" class="btn-danger">Delete</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="signatures">
            <div class="signature-box">
                <p>Prepared by:</p>
                <input type="text" id="head_name" class="signature-name" placeholder="Type name here" 
                       value="<?= isset($signatory['head_name']) ? esc($signatory['head_name']) : old('head_name', '') ?>">
                <div class="signature-line"></div>
                <p style="margin: 0;">Head of Organization</p>
            </div>
            <div class="signature-box">
                <p>Approved:</p>
                <input type="text" id="adviser_name" class="signature-name" placeholder="Type name here" 
                       value="<?= isset($signatory['adviser_name']) ? esc($signatory['adviser_name']) : old('adviser_name', '') ?>">
                <div class="signature-line"></div>
                <p style="margin: 0;">Adviser</p>
            </div>
        </div>
    </div>

    <!-- Add Activity Modal -->
    <div id="activityModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1000;">
        <div style="background: white; width: 500px; margin: 50px auto; padding: 30px; border-radius: 10px;">
            <h3 id="modalTitle">Add Activity</h3>
            <form id="activityForm">
                <?= csrf_field() ?>
                <input type="hidden" id="activity_id">
                <div style="margin: 15px 0;">
                    <label>Activity Date:</label>
                    <input type="date" id="activity_date" required>
                </div>
                <div style="margin: 15px 0;">
                    <label>Activity Title:</label>
                    <input type="text" id="activity_title" required>
                </div>
                <div style="margin: 15px 0;">
                    <label>Responsible Person:</label>
                    <input type="text" id="responsible_person" required>
                </div>
                <div style="margin: 15px 0;">
                    <label>Remarks:</label>
                    <textarea id="remarks" rows="3"></textarea>
                </div>
                <div style="margin: 15px 0;">
                    <label>Status:</label>
                    <select id="status">
                        <option value="planned">Planned</option>
                        <option value="ongoing">Ongoing</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
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

        function addActivity() {
            document.getElementById('modalTitle').textContent = 'Add Activity';
            document.getElementById('activity_id').value = '';
            document.getElementById('activityForm').reset();
            document.getElementById('activityModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('activityModal').style.display = 'none';
        }

        document.getElementById('activityForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const activityId = document.getElementById('activity_id').value;
            const url = activityId 
                ? `/organization/calendar-activities/update/${activityId}`
                : '/organization/calendar-activities/store';

            const formData = new FormData();
            formData.append('academic_year', document.getElementById('academic_year').value);
            formData.append('activity_date', document.getElementById('activity_date').value);
            formData.append('activity_title', document.getElementById('activity_title').value);
            formData.append('responsible_person', document.getElementById('responsible_person').value);
            formData.append('remarks', document.getElementById('remarks').value);
            formData.append('status', document.getElementById('status').value);
            formData.append('head_name', document.getElementById('head_name').value);
            formData.append('adviser_name', document.getElementById('adviser_name').value);

            fetch(url, {
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
                    alert('Error: ' + (data.message || 'Failed to save activity'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while saving');
            });
        });

        function deleteActivity(id) {
            if (confirm('Are you sure you want to delete this activity?')) {
                window.location.href = `/organization/calendar-activities/delete/${id}`;
            }
        }

        function downloadPDF() {
            const year = document.getElementById('academic_year').value;
            if (!year) {
                alert('Please enter academic year');
                return;
            }
            window.location.href = `/organization/calendar-activities/download/${year}`;
        }
    </script>
</body>
</html>
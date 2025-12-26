<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accomplishment Report Form</title>
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
        .btn-draft { background: #ffc107; color: #333; }
        .btn-submit { background: #28a745; color: white; }
        .btn-print { background: #17a2b8; color: white; }
        .btn-download { background: #007bff; color: white; }
        
        .form-content {
            background: white;
            padding: 40px;
            border: 2px solid #333;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 3px double #000;
            padding-bottom: 20px;
        }
        .section {
            margin: 25px 0;
        }
        .section-title {
            font-weight: bold;
            font-size: 15px;
            margin-bottom: 10px;
            text-decoration: underline;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 8px;
            font-size: 14px;
        }
        .required {
            color: #dc3545;
        }
        .form-control {
            width: 100%;
            padding: 8px;
            border: 1px solid #000;
            font-family: Arial, sans-serif;
            font-size: 14px;
            text-align: center;
        }
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
            text-align: left;
        }
        .file-section {
            border: 2px solid #000;
            padding: 20px;
            margin: 20px 0;
        }
        .file-upload-group {
            margin: 15px 0;
        }
        .file-control {
            border: 1px solid #000;
            padding: 8px;
            width: 100%;
        }
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border: 2px solid;
            border-radius: 5px;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border-color: #721c24;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border-color: #155724;
        }
        .current-files {
            margin-top: 10px;
            padding: 10px;
            background: #f9f9f9;
            border: 1px solid #ddd;
        }
        @media print {
            .navbar, .no-print { display: none; }
            body { background: white; padding: 0; }
            .form-content { border: none; page-break-inside: avoid; }
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar no-print">
        <div class="navbar-title">
            <?= isset($report) ? 'Edit Accomplishment Report' : 'Create New Accomplishment Report' ?>
        </div>
        <div class="navbar-buttons">
            <a href="<?= base_url('organization/accomplishment-report') ?>" class="btn btn-back">
                ← Back to List
            </a>
            <button type="button" onclick="saveDraft()" class="btn btn-draft">
                💾 Save as Draft
            </button>
            <button type="button" onclick="submitReport()" class="btn btn-submit">
                ✅ Submit Report
            </button>
            <?php if (isset($report)): ?>
            <button type="button" onclick="window.print()" class="btn btn-print">
                🖨️ Print
            </button>
            <a href="<?= base_url('organization/accomplishment-report/download/' . $report['id']) ?>" class="btn btn-download">
                📥 Download
            </a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="form-content">
        <!-- Official Header -->
        <div class="header">
            <p style="margin: 0;">Republic of the Philippines</p>
            <h3 style="margin: 5px 0;">SULTAN KUDARAT STATE UNIVERSITY</h3>
            <p style="margin: 0; font-size: 14px;">ACCESS, EJC Montilla, 9800 City of Tacurong</p>
            <p style="margin: 0; font-size: 14px;">Province of Sultan Kudarat</p>
            <h3 style="margin: 20px 0;">PARTS OF ACCOMPLISHMENT REPORTS</h3>
        </div>

        <?php if (session()->getFlashdata('error')): ?>
            <div class="alert alert-danger">
                <?= session()->getFlashdata('error') ?>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="alert alert-success">
                <?= session()->getFlashdata('success') ?>
            </div>
        <?php endif; ?>

        <form id="mainForm" action="<?= base_url('organization/accomplishment-report/' . (isset($report) ? 'update/' . $report['id'] : 'store')) ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <input type="hidden" name="status" id="statusInput" value="draft">

            <!-- A.Y. -->
            <div class="form-group">
                <label>A.Y. <span class="required">*</span></label>
                <input type="text" 
                       name="academic_year" 
                       class="form-control" 
                       placeholder="e.g., 2023-2024"
                       value="<?= old('academic_year', $report['academic_year'] ?? '') ?>" 
                       required>
            </div>

            <!-- Section A: Activity Title -->
            <div class="section">
                <div class="section-title">A. ACTIVITY TITLE</div>
                <div class="form-group">
                    <input type="text" 
                           name="activity_title" 
                           class="form-control" 
                           placeholder="Enter the complete title of the activity"
                           value="<?= old('activity_title', $report['activity_title'] ?? '') ?>" 
                           required>
                </div>
            </div>

            <!-- Section B: Narrative Report -->
            <div class="section">
                <div class="section-title">B. NARRATIVE REPORT</div>
                <div class="form-group">
                    <textarea name="narrative_report" 
                              class="form-control" 
                              placeholder="Provide complete details about the activity..."
                              required><?= old('narrative_report', $report['narrative_report'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- File Upload Section -->
            <div class="file-section">
                <h4 style="margin-top: 0; font-weight: bold; text-decoration: underline;">SUPPORTING DOCUMENTS</h4>

                <!-- Section C: Pictorials -->
                <div class="file-upload-group">
                    <label>C. PICTORIALS <span class="required">*</span></label>
                    <input type="file" 
                           name="pictorials[]" 
                           class="file-control" 
                           multiple
                           accept="image/*"
                           <?= !isset($report) ? 'required' : '' ?>>
                    <small style="display: block; margin-top: 5px; color: #666;">
                        Upload photos/images from the activity (Multiple files allowed)
                    </small>
                    <?php if (isset($report) && !empty($report['pictorials'])): ?>
                        <div class="current-files">
                            <strong>Current Files:</strong>
                            <?php 
                            $pictorials = is_array($report['pictorials']) ? $report['pictorials'] : json_decode($report['pictorials'], true);
                            if (is_array($pictorials)):
                                foreach ($pictorials as $file): 
                            ?>
                                <div>📄 <?= esc(basename($file)) ?></div>
                            <?php 
                                endforeach;
                            endif;
                            ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Section D: Activity Designs -->
                <div class="file-upload-group">
                    <label>D. PHOTOCOPY OF ACTIVITY DESIGNS <span class="required">*</span></label>
                    <input type="file" 
                           name="activity_designs[]" 
                           class="file-control" 
                           multiple
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           <?= !isset($report) ? 'required' : '' ?>>
                    <small style="display: block; margin-top: 5px; color: #666;">
                        Upload program/design materials (Multiple files allowed)
                    </small>
                    <?php if (isset($report) && !empty($report['activity_designs'])): ?>
                        <div class="current-files">
                            <strong>Current Files:</strong>
                            <?php 
                            $designs = is_array($report['activity_designs']) ? $report['activity_designs'] : json_decode($report['activity_designs'], true);
                            if (is_array($designs)):
                                foreach ($designs as $file): 
                            ?>
                                <div>📄 <?= esc(basename($file)) ?></div>
                            <?php 
                                endforeach;
                            endif;
                            ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Section E: Evaluation Sheets -->
                <div class="file-upload-group">
                    <label>E. SAMPLE EVALUATION SHEETS <span class="required">*</span></label>
                    <input type="file" 
                           name="evaluation_sheets[]" 
                           class="file-control" 
                           multiple
                           accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           <?= !isset($report) ? 'required' : '' ?>>
                    <small style="display: block; margin-top: 5px; color: #666;">
                        Upload evaluation forms (Multiple files allowed)
                    </small>
                    <?php if (isset($report) && !empty($report['evaluation_sheets'])): ?>
                        <div class="current-files">
                            <strong>Current Files:</strong>
                            <?php 
                            $evaluations = is_array($report['evaluation_sheets']) ? $report['evaluation_sheets'] : json_decode($report['evaluation_sheets'], true);
                            if (is_array($evaluations)):
                                foreach ($evaluations as $file): 
                            ?>
                                <div>📄 <?= esc(basename($file)) ?></div>
                            <?php 
                                endforeach;
                            endif;
                            ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </form>
    </div>

    <script>
        function saveDraft() {
            document.getElementById('statusInput').value = 'draft';
            document.getElementById('mainForm').submit();
        }

        function submitReport() {
            if (confirm('Are you sure you want to submit this report? Once submitted, it will be sent for approval.')) {
                document.getElementById('statusInput').value = 'submitted';
                document.getElementById('mainForm').submit();
            }
        }
    </script>
</body>
</html>
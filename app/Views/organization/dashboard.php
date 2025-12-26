<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Organization Dashboard - USG Accreditation</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
        }

        .navbar {
            background: #27ae60;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .navbar h1 {
            font-size: 24px;
        }

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .notification-bell {
            position: relative;
            cursor: pointer;
            font-size: 24px;
        }

        .notification-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            border-radius: 50%;
            padding: 2px 6px;
            font-size: 12px;
        }

        .btn-logout {
            padding: 8px 20px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .welcome-section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .welcome-section h2 {
            color: #27ae60;
            margin-bottom: 10px;
        }

        .forms-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .form-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-decoration: none;
            color: #2c3e50;
            transition: transform 0.2s;
            border-left: 4px solid #27ae60;
        }

        .form-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }

        .form-card .icon {
            font-size: 40px;
            margin-bottom: 15px;
        }

        .form-card h3 {
            margin-bottom: 10px;
            color: #2c3e50;
        }

        .form-card p {
            color: #7f8c8d;
            font-size: 14px;
        }

        .quick-actions {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .quick-actions h2 {
            margin-bottom: 20px;
            color: #2c3e50;
        }

        .action-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .action-btn {
            padding: 12px 24px;
            background: #27ae60;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            transition: background 0.2s;
        }

        .action-btn:hover {
            background: #229954;
        }

        .action-btn.secondary {
            background: #3498db;
        }

        .action-btn.secondary:hover {
            background: #2980b9;
        }

        .stats-section {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .stat-box {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }

        .stat-box .number {
            font-size: 32px;
            font-weight: bold;
            color: #27ae60;
        }

        .stat-box .label {
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 5px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>USG Accreditation System</h1>
        <div class="navbar-right">
            <a href="<?= base_url('organization/notifications') ?>" class="notification-bell">
                🔔
                <?php if (isset($unread_count) && $unread_count > 0): ?>
                    <span class="notification-badge"><?= $unread_count ?></span>
                <?php endif; ?>
            </a>
            <span>Welcome, <?= session()->get('username') ?></span>
            <a href="<?= base_url('logout') ?>" class="btn-logout">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-section">
            <h2><?= $organization['name'] ?? 'Organization Dashboard' ?></h2>
            <p>Manage your organization's accreditation documents and reports</p>
        </div>

        <div class="quick-actions">
            <h2>Quick Actions</h2>
            <div class="action-buttons">
                <a href="<?= base_url('organization/submissions/upload') ?>" class="action-btn">📤 Upload Documents</a>
                <a href="<?= base_url('organization/submissions') ?>" class="action-btn secondary">📋 View Submissions</a>
                <a href="<?= base_url('organization/financial-report/tracking') ?>" class="action-btn secondary">📊 Financial Tracking</a>
            </div>
        </div>

        <h2 style="margin: 30px 0 20px 0; color: #2c3e50;">Forms & Reports</h2>
        <div class="forms-grid">
            <a href="<?= base_url('organization/commitment-form') ?>" class="form-card">
                <div class="icon">📝</div>
                <h3>Commitment Form</h3>
                <p>Create and manage officer commitment forms</p>
            </a>

            <a href="<?= base_url('organization/calendar-activities') ?>" class="form-card">
                <div class="icon">📅</div>
                <h3>Calendar of Activities</h3>
                <p>Plan and track organization activities</p>
            </a>

            <a href="<?= base_url('organization/program-expenditure') ?>" class="form-card">
                <div class="icon">💰</div>
                <h3>Program of Expenditures</h3>
                <p>Manage expected collections and fees</p>
            </a>

            <a href="<?= base_url('organization/accomplishment-report') ?>" class="form-card">
                <div class="icon">✅</div>
                <h3>Accomplishment Reports</h3>
                <p>Document completed activities and events</p>
            </a>

            <a href="<?= base_url('organization/financial-report') ?>" class="form-card">
                <div class="icon">📊</div>
                <h3>Financial Reports</h3>
                <p>Track collections, expenses, and funds</p>
            </a>
        </div>

        <h2 style="margin: 30px 0 20px 0; color: #2c3e50;">Statistics Overview</h2>
        <div class="stats-section">
            <div class="stat-box">
                <div class="number"><?= $total_documents ?? 0 ?></div>
                <div class="label">Documents Submitted</div>
            </div>
            <div class="stat-box">
                <div class="number"><?= $pending_reviews ?? 0 ?></div>
                <div class="label">Pending Reviews</div>
            </div>
            <div class="stat-box">
                <div class="number"><?= $upcoming_activities ?? 0 ?></div>
                <div class="label">Upcoming Activities</div>
            </div>
            <div class="stat-box">
                <div class="number"><?= $reports_this_year ?? 0 ?></div>
                <div class="label">Reports This Year</div>
            </div>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statistics - Admin</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: #2c3e50;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin: 0 15px;
        }
        .container {
            max-width: 1400px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
        }
        .org-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }
        .org-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            cursor: pointer;
            transition: transform 0.2s;
            border-left: 4px solid #3498db;
        }
        .org-card:hover {
            transform: translateY(-5px);
        }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary { background: #3498db; color: white; }
        .btn-success { background: #27ae60; color: white; }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .pill-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 10px;
        }

        .pill {
            padding: 10px 12px;
            border: 1px solid #e6e9ef;
            border-radius: 10px;
            background: #f8f9fa;
        }

        .pill input { margin-right: 8px; }

        .muted { color: #7f8c8d; }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 10px;
            border-bottom: 1px solid #ecf0f1;
            text-align: left;
            font-size: 14px;
        }

        th { background: #f8f9fa; }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>USG Accreditation - Statistics</h1>
        <div>
            <a href="<?= base_url('admin/dashboard') ?>">Dashboard</a>
            <a href="<?= base_url('admin/organizations') ?>">Organizations</a>
            <a href="<?= base_url('admin/documents') ?>">Documents</a>
            <a href="<?= base_url('admin/statistics') ?>">Statistics</a>
            <a href="<?= base_url('logout') ?>">Logout</a>
        </div>
    </nav>

    <div class="container">
        <!-- Statistics Overview Cards -->
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 30px;">
            <div class="card" style="text-align: center;">
                <h3 style="color: #3498db; margin: 0 0 10px 0;"><?= number_format($uploaded_docs_this_year ?? 0) ?></h3>
                <p style="margin: 0; color: #7f8c8d;">Uploaded Docs This School Year</p>
            </div>
            <div class="card" style="text-align: center;">
                <h3 style="color: #27ae60; margin: 0 0 10px 0;"><?= number_format($total_orgs ?? 0) ?></h3>
                <p style="margin: 0; color: #7f8c8d;">Total Organizations</p>
            </div>
            <div class="card" style="text-align: center;">
                <h3 style="color: #e67e22; margin: 0 0 10px 0;">
                    <?= number_format(array_sum(array_column($activities_per_org ?? [], 'count'))) ?>
                </h3>
                <p style="margin: 0; color: #7f8c8d;">Total Activities</p>
            </div>
        </div>

        <!-- Activities per Organization Chart -->
        <div class="card">
            <h3>Activities per Organization</h3>
            <div style="margin-top: 20px;">
                <canvas id="activitiesChart" height="120"></canvas>
            </div>
        </div>

        <div class="card">
            <h2>Organization Financial Statistics</h2>
            <p style="color: #7f8c8d;">View and compare financial data across organizations</p>
        </div>

        <div class="card">
            <h3>Select Organization to View Details</h3>
            <div class="org-grid">
                <?php if (empty($organizations)): ?>
                    <p style="grid-column: 1/-1; text-align: center; padding: 30px;">No organizations available</p>
                <?php else: ?>
                    <?php foreach ($organizations as $org): ?>
                        <div class="org-card" onclick="window.location.href='<?= base_url('admin/statistics/organization/' . $org['id']) ?>'">
                            <h3><?= esc($org['name']) ?></h3>
                            <?php if (!empty($org['acronym'])): ?>
                                <p style="color: #7f8c8d;"><?= esc($org['acronym']) ?></p>
                            <?php endif; ?>
                            <p style="margin-top: 15px;">
                                <a href="<?= base_url('admin/statistics/organization/' . $org['id']) ?>" 
                                   class="btn btn-primary" style="font-size: 14px;">View Statistics →</a>
                            </p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>

        <div class="card">
            <h3>Compare Multiple Organizations</h3>
            <form id="comparisonForm">
                <?= csrf_field() ?>
                <div class="form-row" style="margin-bottom: 20px;">
                    <div>
                        <label style="font-weight: 600; display: block; margin-bottom: 10px;">Select Organizations:</label>
                        <div class="pill-grid">
                            <?php foreach ($organizations as $org): ?>
                                <label class="pill">
                                    <input type="checkbox" name="organizations[]" value="<?= $org['id'] ?>">
                                    <?= esc($org['name']) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div>
                        <label style="font-weight: 600; display: block; margin-bottom: 10px;">Select Years:</label>
                        <?php if (empty($years)): ?>
                            <div class="muted">No academic years found from financial reports yet.</div>
                        <?php else: ?>
                            <div class="pill-grid">
                                <?php foreach ($years as $yr): ?>
                                    <label class="pill">
                                        <input type="checkbox" name="years[]" value="<?= esc($yr) ?>">
                                        <?= esc($yr) ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="font-weight: 600; display: block; margin-bottom: 10px;">Metric:</label>
                    <select id="metric" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                        <option value="collection">Total Collection</option>
                        <option value="expenses">Total Expenses</option>
                        <option value="remaining">Remaining Fund</option>
                    </select>
                    <div class="muted" style="margin-top: 8px; font-size: 13px;">Chart shows one line per organization across the selected years.</div>
                </div>

                <button type="submit" class="btn btn-success">Generate Comparison</button>
            </form>

            <div id="comparisonResults" style="margin-top: 30px; display: none;">
                <h3>Comparison Results</h3>
                <div style="margin-top: 12px;">
                    <canvas id="comparisonChart" height="140"></canvas>
                </div>

                <div style="margin-top: 25px; overflow-x: auto;">
                    <table id="comparisonTable"></table>
                </div>
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

        function formatCurrency(value) {
            const num = Number(value || 0);
            return '₱ ' + num.toLocaleString(undefined, { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        document.getElementById('comparisonForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {
                organizations: formData.getAll('organizations[]'),
                years: formData.getAll('years[]')
            };

            if (data.organizations.length === 0 || data.years.length === 0) {
                alert('Please select at least one organization and one year');
                return;
            }

            fetch('/admin/statistics/comparison', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    [csrfHeaderName]: csrfToken
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                updateCsrfToken(result.csrf);
                if (result.success) {
                    const years = data.years;
                    const metric = document.getElementById('metric').value;
                    displayComparisonChart(result.data, years, metric);
                    renderComparisonTable(result.data, years, metric);
                    document.getElementById('comparisonResults').style.display = 'block';
                } else {
                    alert(result.message);
                }
            });
        });

        let comparisonChartInstance = null;

        function metricLabel(metric) {
            if (metric === 'expenses') return 'Total Expenses';
            if (metric === 'remaining') return 'Remaining Fund';
            return 'Total Collection';
        }

        function metricColor(metric, idx) {
            const palette = ['#3498db', '#27ae60', '#e67e22', '#9b59b6', '#e74c3c', '#16a085', '#2c3e50'];
            return palette[idx % palette.length];
        }

        function displayComparisonChart(data, years, metric) {
            const ctx = document.getElementById('comparisonChart').getContext('2d');

            const labels = [...years].reverse();
            const datasets = data.map((org, idx) => {
                const points = labels.map(y => {
                    const row = org.years && org.years[y] ? org.years[y] : null;
                    return row ? Number(row[metric] || 0) : 0;
                });
                return {
                    label: org.name + ' - ' + metricLabel(metric),
                    data: points,
                    backgroundColor: metricColor(metric, idx),
                    borderColor: metricColor(metric, idx),
                    borderWidth: 1
                };
            });

            if (comparisonChartInstance) {
                comparisonChartInstance.destroy();
            }

            comparisonChartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: datasets
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return context.dataset.label + ': ' + formatCurrency(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) { return formatCurrency(value); }
                            }
                        }
                    }
                }
            });
        }

        function renderComparisonTable(data, years, metric) {
            const labels = [...years].reverse();
            const table = document.getElementById('comparisonTable');

            let html = '';
            html += '<thead><tr>';
            html += '<th>Organization</th>';
            labels.forEach(y => {
                html += '<th>' + y + '</th>';
            });
            html += '</tr></thead>';

            html += '<tbody>';
            data.forEach(org => {
                html += '<tr>';
                html += '<td><strong>' + (org.name || '') + '</strong></td>';
                labels.forEach(y => {
                    const row = org.years && org.years[y] ? org.years[y] : null;
                    const val = row ? Number(row[metric] || 0) : 0;
                    html += '<td>' + formatCurrency(val) + '</td>';
                });
                html += '</tr>';
            });
            html += '</tbody>';

            table.innerHTML = html;
        }

        // Initialize Activities Chart
        function initActivitiesChart() {
            const activitiesData = <?= json_encode($activities_per_org ?? []) ?>;
            const ctx = document.getElementById('activitiesChart').getContext('2d');

            const labels = activitiesData.map(item => item.name);
            const data = activitiesData.map(item => item.count);

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Number of Activities',
                        data: data,
                        backgroundColor: '#3498db',
                        borderColor: '#2980b9',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        }

        // Initialize charts on page load
        document.addEventListener('DOMContentLoaded', function() {
            initActivitiesChart();
        });
    </script>
</body>
</html>
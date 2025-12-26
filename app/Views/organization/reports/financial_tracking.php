<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Financial Tracking - Organization</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f5f7fa;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background: #27ae60;
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
        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary { background: #27ae60; color: white; }
        .btn-secondary { background: #3498db; color: white; }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border-bottom: 1px solid #ecf0f1;
            text-align: left;
        }
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        .muted { color: #7f8c8d; }
        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
        }
        .year-list label {
            display: inline-block;
            margin-right: 16px;
            margin-top: 8px;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>Financial Tracking</h1>
        <div>
            <a href="<?= base_url('organization/dashboard') ?>">Dashboard</a>
            <a href="<?= base_url('organization/financial-report') ?>">Financial Report</a>
            <a href="<?= base_url('organization/submissions') ?>">Submissions</a>
            <a href="<?= base_url('logout') ?>">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="card">
            <a class="btn btn-secondary" href="<?= base_url('organization/dashboard') ?>">← Back</a>
            <h2 style="margin: 15px 0 5px 0;">
                <?= esc($organization['name'] ?? 'Organization') ?>
            </h2>
            <p class="muted" style="margin: 0;">Track yearly financial totals and compare years.</p>
        </div>

        <div class="grid">
            <div class="card">
                <h3 style="margin-top: 0;">Available Years</h3>
                <?php if (empty($years)): ?>
                    <p class="muted">No saved financial reports yet.</p>
                    <a class="btn btn-primary" href="<?= base_url('organization/financial-report') ?>">Create Financial Report</a>
                <?php else: ?>
                    <form id="compareForm">
                        <?= csrf_field() ?>
                        <div class="year-list">
                            <?php foreach ($years as $yr): ?>
                                <label>
                                    <input type="checkbox" name="years[]" value="<?= esc($yr) ?>">
                                    <?= esc($yr) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <div style="margin-top: 15px;">
                            <button type="submit" class="btn btn-primary">Compare Selected Years</button>
                            <a class="btn btn-secondary" href="<?= base_url('organization/financial-report') ?>">Go to Financial Report</a>
                        </div>
                        <p class="muted" style="margin-top: 10px;">
                            Tip: select 2 years for a two-year comparison.
                        </p>
                    </form>
                <?php endif; ?>
            </div>

            <div class="card">
                <h3 style="margin-top: 0;">Comparison Chart</h3>
                <canvas id="comparisonChart" width="400" height="220"></canvas>
                <p id="chartHint" class="muted" style="margin-top: 10px;">Select years and click Compare.</p>
            </div>
        </div>

        <div class="card">
            <h3 style="margin-top: 0;">Yearly Financial Summary</h3>
            <table>
                <thead>
                    <tr>
                        <th>Academic Year</th>
                        <th>Total Collection</th>
                        <th>Total Expenses</th>
                        <th>Cash on Bank</th>
                        <th>Remaining Fund</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($reports)): ?>
                        <tr>
                            <td colspan="6" class="muted" style="text-align:center; padding: 25px;">
                                No financial reports found.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($reports as $r): ?>
                            <tr>
                                <td><?= esc($r['academic_year'] ?? '') ?></td>
                                <td><?= esc($r['total_collection'] ?? 0) ?></td>
                                <td><?= esc($r['total_expenses'] ?? 0) ?></td>
                                <td><?= esc($r['cash_on_bank'] ?? 0) ?></td>
                                <td><?= esc($r['total_remaining_fund'] ?? 0) ?></td>
                                <td>
                                    <?php if (!empty($r['id'])): ?>
                                        <a class="btn btn-secondary" href="<?= base_url('organization/financial-report/download/' . $r['id']) ?>">Download</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
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

        let chart;
        function renderChart(rows) {
            const ctx = document.getElementById('comparisonChart').getContext('2d');
            const labels = rows.map(r => r.year);

            const collections = rows.map(r => Number(r.total_collection || 0));
            const expenses = rows.map(r => Number(r.total_expenses || 0));
            const remaining = rows.map(r => Number(r.remaining_fund || 0));

            if (chart) chart.destroy();
            chart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [
                        { label: 'Total Collection', data: collections, backgroundColor: '#27ae60' },
                        { label: 'Total Expenses', data: expenses, backgroundColor: '#e74c3c' },
                        { label: 'Remaining Fund', data: remaining, backgroundColor: '#3498db' },
                    ]
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

        const form = document.getElementById('compareForm');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const fd = new FormData(form);
                const years = fd.getAll('years[]');

                if (!years || years.length === 0) {
                    alert('Please select at least one year');
                    return;
                }

                fetch('/organization/financial-report/comparison', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        [csrfHeaderName]: csrfToken
                    },
                    body: JSON.stringify({ years })
                })
                .then(r => r.json())
                .then(result => {
                    updateCsrfToken(result.csrf);
                    if (!result.success) {
                        alert(result.message || 'Comparison failed');
                        return;
                    }
                    document.getElementById('chartHint').textContent = '';
                    renderChart(result.data || []);
                })
                .catch(err => {
                    console.error(err);
                    alert('An error occurred while generating the comparison');
                });
            });
        }
    </script>
</body>
</html>

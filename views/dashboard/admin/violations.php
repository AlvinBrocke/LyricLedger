<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Violation Reports - LyricLedger</title>
    <link rel="stylesheet" href="../../../assets/css/admin_dashboard.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .panel {
            background: #1a1a1a;
            border-radius: 8px;
            padding: 20px;
            margin-top: 20px;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .panel-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: #fff;
        }

        .filter-bar {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        .filter-bar select {
            background: #2a2a2a;
            color: #fff;
            border: 1px solid #3a3a3a;
            padding: 8px 12px;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        .filter-bar label {
            color: #fff;
            font-size: 0.9rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #2a2a2a;
        }

        th {
            font-weight: 500;
            color: #888;
            font-size: 0.9rem;
        }

        td {
            color: #fff;
            font-size: 0.9rem;
        }

        .status-label {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        .status-label.pending {
            background: #ffd700;
            color: #000;
        }

        .status-label.resolved {
            background: #28a745;
            color: #fff;
        }

        .status-label.escalated {
            background: #dc3545;
            color: #fff;
        }

        .status-label.investigating {
            background: #17a2b8;
            color: #fff;
        }

        .action-column {
            display: flex;
            gap: 8px;
        }

        .btn {
            background: #2a2a2a;
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.2s;
        }

        .btn:hover {
            background: #3a3a3a;
        }

        .btn.view {
            background: #2a2a2a;
        }

        .btn.resolve {
            background: #28a745;
        }

        .btn.escalate {
            background: #dc3545;
        }

        .btn i {
            font-size: 0.9rem;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #888;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 10px;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #1a1a1a;
            border-radius: 8px;
            padding: 20px;
        }

        .stat-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 10px;
            color: #888;
            font-size: 0.9rem;
        }

        .stat-value {
            font-size: 1.8rem;
            font-weight: 600;
            color: #fff;
            margin-bottom: 5px;
        }

        .stat-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stat-icon.icon-red {
            background: rgba(220, 53, 69, 0.1);
            color: #dc3545;
        }

        .stat-icon.icon-yellow {
            background: rgba(255, 193, 7, 0.1);
            color: #ffc107;
        }

        .stat-icon.icon-green {
            background: rgba(40, 167, 69, 0.1);
            color: #28a745;
        }

        .stat-icon.icon-blue {
            background: rgba(23, 162, 184, 0.1);
            color: #17a2b8;
        }
    </style>
</head>
<body>
    <div class="container">
        <?php require_once '../../includes/sidebar.php'; sidebar(); ?>

        <div class="main-content">
            <div class="header">
                <h1>Violation Reports</h1>
                <div class="search-date">
                    <div class="search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Search violations...">
                    </div>
                    <div class="date"><?= date('l, F j') ?></div>
                </div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="success-message">
                    <i class="fas fa-check-circle"></i>
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message">
                    <i class="fas fa-exclamation-circle"></i>
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-title">
                        <span>Total Violations</span>
                        <div class="stat-icon icon-red">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= count($violations) ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">
                        <span>Pending Review</span>
                        <div class="stat-icon icon-yellow">
                            <i class="fas fa-clock"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= count(array_filter($violations, fn($v) => $v['status'] === 'pending')) ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">
                        <span>Resolved</span>
                        <div class="stat-icon icon-green">
                            <i class="fas fa-check-circle"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= count(array_filter($violations, fn($v) => $v['status'] === 'resolved')) ?></div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">
                        <span>Escalated</span>
                        <div class="stat-icon icon-blue">
                            <i class="fas fa-arrow-up"></i>
                        </div>
                    </div>
                    <div class="stat-value"><?= count(array_filter($violations, fn($v) => $v['status'] === 'escalated')) ?></div>
                </div>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">Violation Reports</div>
                    <div class="filter-bar">
                        <label for="violation-filter">Filter by status:</label>
                        <select id="violation-filter" onchange="filterViolations(this.value)">
                            <option value="all">All</option>
                            <option value="pending">Pending</option>
                            <option value="resolved">Resolved</option>
                            <option value="escalated">Escalated</option>
                            <option value="investigating">Investigating</option>
                        </select>
                    </div>
                </div>

                <?php if (empty($violations)): ?>
                    <div class="empty-state">
                        <i class="fas fa-shield-alt"></i>
                        <p>No violation reports found.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Reported By</th>
                                    <th>Detected URL</th>
                                    <th>Similarity Score</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($violations as $v): ?>
                                    <tr class="<?= $v['status'] ?>">
                                        <td><?= htmlspecialchars($v['reported_by']) ?></td>
                                        <td>
                                            <a href="<?= htmlspecialchars($v['detected_url']) ?>" target="_blank" class="btn view">
                                                <i class="fas fa-external-link-alt"></i> View Source
                                            </a>
                                        </td>
                                        <td>
                                            <div class="similarity-score <?= $v['similarity_score'] >= 80 ? 'high' : ($v['similarity_score'] >= 50 ? 'medium' : 'low') ?>">
                                                <?= htmlspecialchars($v['similarity_score']) ?>%
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-label <?= $v['status'] ?>">
                                                <?= ucfirst($v['status']) ?>
                                            </span>
                                        </td>
                                        <td class="action-column">
                                            <a href="/violations/view/<?= $v['id'] ?>" class="btn view">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <a href="/violations/resolve/<?= $v['id'] ?>" class="btn resolve">
                                                <i class="fas fa-check"></i> Resolve
                                            </a>
                                            <a href="/violations/escalate/<?= $v['id'] ?>" class="btn escalate">
                                                <i class="fas fa-arrow-up"></i> Escalate
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    function filterViolations(status) {
        const rows = document.querySelectorAll("table tbody tr");
        rows.forEach(row => {
            if (status === "all" || row.classList.contains(status)) {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        });
    }

    // Add search functionality
    document.querySelector('.search input').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll("table tbody tr");
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? "" : "none";
        });
    });
    </script>
</body>
</html>
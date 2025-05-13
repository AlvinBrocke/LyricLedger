<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Royalty Transactions - LyricLedger</title>
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

        .status-label.completed {
            background: #28a745;
            color: #fff;
        }

        .status-label.failed {
            background: #dc3545;
            color: #fff;
        }

        .btn.details {
            background: #2a2a2a;
            color: #fff;
            padding: 6px 12px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background-color 0.2s;
        }

        .btn.details:hover {
            background: #3a3a3a;
        }

        .export-links {
            display: flex;
            gap: 10px;
            margin-top: 20px;
        }

        .export-links a {
            background: #2a2a2a;
            color: #fff;
            padding: 8px 16px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.2s;
        }

        .export-links a:hover {
            background: #3a3a3a;
        }

        .export-links a i {
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
    </style>
</head>
<body>
    <div class="container">
        <?php require_once '../../includes/sidebar.php'; sidebar(); ?>

        <div class="main-content">
            <div class="header">
                <h1>Royalty Transactions</h1>
                <div class="search-date">
                    <div class="search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Search transactions...">
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

            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">Transaction History</div>
                    <div class="filter-bar">
                        <label for="transaction-filter">Filter by status:</label>
                        <select id="transaction-filter" onchange="filterTransaction(this.value)">
                            <option value="all">All</option>
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                </div>

                <?php if (empty($transactions)): ?>
                    <div class="empty-state">
                        <i class="fas fa-money-bill-wave"></i>
                        <p>No royalty transactions found.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th>Transaction Hash</th>
                                    <th>Artist</th>
                                    <th>Amount ($)</th>
                                    <th>Blockchain Status</th>
                                    <th>Payment Method</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($transactions as $tx): ?>
                                    <tr class="<?= $tx['blockchain_status'] ?>">
                                        <td><?= htmlspecialchars($tx['transaction_hash']) ?></td>
                                        <td><?= htmlspecialchars($tx['artist_name']) ?></td>
                                        <td><?= number_format($tx['amount'], 2) ?></td>
                                        <td>
                                            <span class="status-label <?= $tx['blockchain_status'] ?>">
                                                <?= ucfirst($tx['blockchain_status']) ?>
                                            </span>
                                        </td>
                                        <td><?= htmlspecialchars($tx['payment_method']) ?></td>
                                        <td><?= date('M d, Y H:i', strtotime($tx['created_at'])) ?></td>
                                        <td>
                                            <a href="/royalties/view/<?= $tx['id'] ?>" class="btn details">
                                                <i class="fas fa-eye"></i> Details
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="export-links">
                        <a href="/royalties/export/csv">
                            <i class="fas fa-file-csv"></i> Export CSV
                        </a>
                        <a href="/royalties/export/pdf">
                            <i class="fas fa-file-pdf"></i> Export PDF
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
    function filterTransaction(status) {
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


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LyricLedger Administrator Dashboard</title>
    <link rel="stylesheet" href="../../../assets/css/dashboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <?php require_once '../../includes/sidebar.php'; sidebar(); ?>

        <div class="main-content">
            <div class="header">
                <h1>Dashboard</h1>
                <div class="search-date">
                    <div class="search">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="text" placeholder="Search">
                    </div>
                    <div class="date">Monday, July 2</div>
                </div>
            </div>
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-title">
                        <span>Total Revenue</span>
                        <div class="stat-icon icon-green">
                            <i class="fa-solid fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="stat-value">$12,896</div>
                    <div class="stat-change change-up">
                        <i class="fa-solid fa-arrow-up"></i>+3.67%
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">
                        <span>Total Expense</span>
                        <div class="stat-icon icon-red">
                            <i class="fa-solid fa-dollar-sign"></i>
                        </div>
                    </div>
                    <div class="stat-value">$6,886</div>
                    <div class="stat-change change-down">
                        <i class="fa-solid fa-arrow-down"></i>-2.67%
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">
                        <span>Total Registrations</span>
                        <div class="stat-icon icon-blue">
                            <i class="fa-solid fa-user"></i>
                        </div>
                    </div>
                    <div class="stat-value">1874</div>
                    <div class="stat-change change-up">
                        <i class="fa-solid fa-arrow-up"></i>+2.54%
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">
                        <span>Copyright Usage %</span>
                        <div class="stat-icon icon-teal">
                            <i class="fa-solid fa-percent"></i>
                        </div>
                    </div>
                    <div class="stat-value">75%</div>
                    <div class="stat-change change-down">
                        <i class="fa-solid fa-arrow-down"></i>-2.57%
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">Current Registrations</div>
                    <div class="view-all">View All</div>
                </div>
                <div class="registration-list">
                    <div class="registration-item">
                        <div class="registration-img">
                            <img src="/api/placeholder/32/32" alt="User">
                        </div>
                        <div class="registration-user">Michelle Rivera</div>
                        <div class="registration-time">17:40</div>
                        <div class="registration-id">K-1</div>
                        <div class="registration-count">4 Songs</div>
                        <div class="registration-status">Confirmed</div>
                        <div class="registration-action">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </div>
                    </div>
                    <div class="registration-item">
                        <div class="registration-img">
                            <img src="/api/placeholder/32/32" alt="User">
                        </div>
                        <div class="registration-user">Arlene McCoy</div>
                        <div class="registration-time">17:40</div>
                        <div class="registration-id">T-3</div>
                        <div class="registration-count">5 Songs</div>
                        <div class="registration-status">Confirmed</div>
                        <div class="registration-action">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </div>
                    </div>
                    <div class="registration-item">
                        <div class="registration-img">
                            <img src="/api/placeholder/32/32" alt="User">
                        </div>
                        <div class="registration-user">Savannah Nguyen</div>
                        <div class="registration-time">17:40</div>
                        <div class="registration-id">K-1</div>
                        <div class="registration-count">3 Songs</div>
                        <div class="registration-status">Confirmed</div>
                        <div class="registration-action">
                            <i class="fa-solid fa-pen-to-square"></i> Edit
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid-2">
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">Registrations Per Day</div>
                        <div class="tab-buttons">
                            <button class="tab-btn active">Weekly</button>
                            <button class="tab-btn">Monthly</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="registrationsChart"></canvas>
                    </div>
                </div>
                <div class="panel">
                    <div class="panel-header">
                        <div class="panel-title">Average Royalty (USD)</div>
                        <div class="tab-buttons">
                            <button class="tab-btn active">Weekly</button>
                            <button class="tab-btn">Monthly</button>
                        </div>
                    </div>
                    <div class="chart-container">
                        <canvas id="royaltyChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="panel">
                <div class="panel-header">
                    <div class="panel-title">Most Popular Tracks</div>
                    <div class="tab-buttons">
                        <button class="tab-btn active">Weekly</button>
                        <button class="tab-btn">Monthly</button>
                    </div>
                </div>
                <div class="popular-list">
                    <div class="popular-item">
                        <div class="popular-img">
                            <i class="fa-solid fa-music"></i>
                        </div>
                        <div class="popular-name">Summer Breeze (feat. Emma Lynn)</div>
                        <div class="popular-price">68$</div>
                        <div class="popular-revenue">4,500$</div>
                    </div>
                    <div class="popular-item">
                        <div class="popular-img">
                            <i class="fa-solid fa-music"></i>
                        </div>
                        <div class="popular-name">Electric Dreams (Remix)</div>
                        <div class="popular-price">76$</div>
                        <div class="popular-revenue">4,500$</div>
                    </div>
                    <div class="popular-item">
                        <div class="popular-img">
                            <i class="fa-solid fa-music"></i>
                        </div>
                        <div class="popular-name">Midnight City Lights</div>
                        <div class="popular-price">55$</div>
                        <div class="popular-revenue">4,500$</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../assets/js/dashboard.js"></script>
</body>
</html>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="/TechShop/public/assets/css/cssAdmin/admin_style.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <?php require_once __DIR__ . '/../../app/helpers/CSRF.php'; ?>
    <meta name="csrf-token" content="<?= CSRF::token(); ?>">
</head>
<body>
    
    <div class="app-wrapper">
        
        <?php 
        $active_page = 'dashboard'; 
        
        include __DIR__ . '/../includes/Admin/layout_sidebar.php'; 
        ?>

        <main class="main-content">
            
            <div class="dashboard-grid">

                <div class="card card-span-1">
                    <div class="card-header">
                        <h5 class="card-title">Weekly Sales</h5>
                    </div>
                    <div class="card-body">
                        <div class="stat-main">
                            <div class="stat-number">$47K</div>
                            <div class="stat-change positive">+3.5%</div>
                        </div>
                        <div class="stat-chart-small">[Biểu đồ cột nhỏ]</div>
                    </div>
                </div>

                <div class="card card-span-1">
                    <div class="card-header">
                        <h5 class="card-title">Total Order</h5>
                    </div>
                    <div class="card-body">
                        <div class="stat-main">
                            <div class="stat-number">58.4K</div>
                            <div class="stat-change negative">-13.6%</div>
                        </div>
                        <div class="stat-chart-small">[Biểu đồ đường nhỏ]</div>
                    </div>
                </div>

                <div class="card card-span-1">
                    <div class="card-header">
                        <h5 class="card-title">Market Share</h5>
                    </div>
                    <div class="card-body card-market-share">
                        <ul>
                            <li><span>Samsung</span> <span>33%</span></li>
                            <li><span>Huawei</span> <span>29%</span></li>
                            <li><span>Apple</span> <span>20%</span></li>
                        </ul>
                        <div class="stat-chart-donut">[Biểu đồ tròn]</div>
                    </div>
                </div>

                <div class="card card-span-1 card-weather">
                    <div class="card-header">
                        <h5 class="card-title">Weather</h5>
                    </div>
                    <div class="card-body">
                        <div class="weather-icon">[☀️]</div>
                        <div class="weather-info">
                            <div class="weather-city">New York City</div>
                            <div class="weather-status">Sunny</div>
                        </div>
                        <div class="weather-temp">
                            31°
                        </div>
                    </div>
                </div>

                <div class="card card-span-2"> <div class="card-header">
                        <h5 class="card-title">Running Projects</h5>
                    </div>
                    <div class="card-body">
                        <table class="projects-table">
                            <thead>
                                <tr>
                                    <th>Project</th>
                                    <th>Progress</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Falcon</td>
                                    <td><span class="progress-bar" style="width: 38%;"></span> 38%</td>
                                    <td>12:50:00</td>
                                </tr>
                                <tr>
                                    <td>Reign</td>
                                    <td><span class="progress-bar" style="width: 78%;"></span> 78%</td>
                                    <td>25:20:00</td>
                                </tr>
                                <tr>
                                    <td>Boots4</td>
                                    <td><span class="progress-bar" style="width: 50%;"></span> 50%</td>
                                    <td>58:20:00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card card-span-2"> <div class="card-header">
                        <h5 class="card-title">Total Sales</h5>
                    </div>
                    <div class="card-body">
                        <div class="chart-main-placeholder">
                            [Vị trí đặt Biểu đồ Doanh thu 7 Ngày Gần Nhất]
                        </div>
                    </div>
                </div>

        </main>
    </div>
    <script src="/TechShop/public/assets/js/admin.js"></script>
</body>
</html>
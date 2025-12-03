<?php
    require_once __DIR__ . '/../../app/helpers/CSRF.php';
    $csrf = CSRF::token();
    requireAdmin();

    // kết nối DB
    require_once __DIR__ . '/../../app/helpers/DB.php';
    $pdo = DB::getInstance()->getConnection();

    // Query 1: Sản phẩm bán chạy nhất
    $stmt = $pdo->prepare("
        SELECT sp.id, sp.ten_sp, COALESCE(SUM(ct.so_luong),0) AS total_sold
        FROM chi_tiet_don_hang ct
        JOIN san_pham sp ON sp.id = ct.id_san_pham
        JOIN don_hang dh ON dh.id = ct.id_don_hang
        WHERE dh.trang_thai_don != 'huy'
        GROUP BY sp.id, sp.ten_sp
        ORDER BY total_sold DESC
        LIMIT 1
    ");
    $stmt->execute();
    $best = $stmt->fetch();

    // Query 2: Tổng doanh thu
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(ct.so_luong * ct.don_gia),0) AS total_revenue
        FROM chi_tiet_don_hang ct
        JOIN don_hang dh ON dh.id = ct.id_don_hang
        WHERE dh.trang_thai_don != 'huy'
    ");
    $stmt->execute();
    $totalRevenue = $stmt->fetchColumn() ?: 0;

    // Query 3: Doanh thu theo tháng
    $stmt = $pdo->prepare("
        SELECT DATE_FORMAT(dh.ngay_dat_hang, '%Y-%m') AS month, COALESCE(SUM(ct.so_luong * ct.don_gia),0) AS revenue
        FROM don_hang dh
        JOIN chi_tiet_don_hang ct ON ct.id_don_hang = dh.id
        WHERE dh.trang_thai_don != 'huy' AND dh.ngay_dat_hang >= DATE_SUB(CURDATE(), INTERVAL 11 MONTH)
        GROUP BY month
        ORDER BY month ASC
    ");
    $stmt->execute();
    $rows = $stmt->fetchAll();
    $months = [];
    for ($i = 11; $i >= 0; $i--) {
        $m = date('Y-m', strtotime("-{$i} months"));
        $months[$m] = 0;
    }
    foreach ($rows as $r) {
        $months[$r['month']] = (float)$r['revenue'];
    }

    // Query thống kê tổng
    $stmt = $pdo->query("SELECT COUNT(*) FROM nguoi_dung");
    $totalUsers = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM don_hang WHERE trang_thai_don != 'huy'");
    $totalOrders = $stmt->fetchColumn();

    $stmt = $pdo->query("SELECT COUNT(*) FROM san_pham");
    $totalProducts = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <meta name="csrf-token" content="<?= $csrf ?>">
    <link rel="stylesheet" href="/TechShop/public/assets/css/cssAdmin/admin_style.css"> 
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    
    <div class="app-wrapper">
        
        <?php 
        $active_page = 'dashboard'; 
        include __DIR__ . '/../includes/Admin/layout_sidebar.php'; 
        ?>

        <main class="main-content">
            
            <div class="dashboard-top-grid">
                <div class="card stat-card">
                    <div class="card-header">
                        <h5 class="card-title">Người dùng</h5>
                    </div>
                    <div class="card-body">
                        <div class="stat-main">
                            <div>
                                <div class="stat-number"><?= (int)$totalUsers ?></div>
                                <div class="stat-label">Tổng số người dùng</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="card-header">
                        <h5 class="card-title">Đơn hàng</h5>
                    </div>
                    <div class="card-body">
                        <div class="stat-main">
                            <div>
                                <div class="stat-number"><?= (int)$totalOrders ?></div>
                                <div class="stat-label">Tổng số đơn hàng</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="card-header">
                        <h5 class="card-title">Sản phẩm</h5>
                    </div>
                    <div class="card-body">
                        <div class="stat-main">
                            <div>
                                <div class="stat-number"><?= (int)$totalProducts ?></div>
                                <div class="stat-label">Tổng số sản phẩm</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card stat-card">
                    <div class="card-header">
                        <h5 class="card-title">Sản phẩm bán chạy nhất</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($best && $best['total_sold'] > 0): ?>
                            <div class="stat-main">
                                <div>
                                    <div class="stat-number"><?= htmlspecialchars($best['ten_sp']) ?></div>
                                    <div class="stat-label"><?= (int)$best['total_sold'] ?> sản phẩm</div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="stat-label">Chưa có dữ liệu bán hàng</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="dashboard-bottom-grid">
                <div class="card">
                    <div class="card-header" style="display:flex;justify-content:space-between;align-items:center;">
                        <h5 class="card-title">Tổng doanh thu</h5>
                        <span class="stat-number"><?= number_format($totalRevenue, 0, ',', '.') ?>₫</span>
                    </div>
                    <div class="card-body">
                        <div class="stat-label" style="margin-bottom:16px;">Tổng từ đơn đã hoàn tất</div>
                        <h5 class="card-title" style="margin-bottom:12px;">Doanh thu theo tháng (12 tháng)</h5>
                        <canvas id="revenueChart" class="revenue-chart"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        const revenueLabels = <?= json_encode(array_keys($months)) ?>;
        const revenueData = <?= json_encode(array_values($months)) ?>;

        (function(){
            // Lấy màu từ biến CSS để đồng bộ với theme (Light/Dark)
            const styles = getComputedStyle(document.body);
            const textColor = styles.getPropertyValue('--text-color').trim() || '#58687a';
            const textMuted = styles.getPropertyValue('--text-muted').trim() || '#6c757d';
            const borderColor = 'rgba(200, 200, 200, 0.1)';

            const ctx = document.getElementById('revenueChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: revenueLabels,
                    datasets: [{
                        label: 'Doanh thu (₫)',
                        data: revenueData,
                        borderColor: 'rgb(0, 234, 255)',
                        backgroundColor: 'rgba(0, 234, 255, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 5,
                        pointBackgroundColor: 'rgb(0, 234, 255)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointHoverRadius: 7,
                        pointHoverBackgroundColor: 'rgb(127, 94, 255)',
                        hoverBorderColor: 'rgb(127, 94, 255)',
                        hoverBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { 
                            display: true,
                            labels: {
                                color: textColor,
                                font: { size: 13, weight: '600' },
                                padding: 15,
                                usePointStyle: true
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            padding: 12,
                            titleFont: { size: 13, weight: 'bold' },
                            bodyFont: { size: 12 },
                            borderColor: 'rgb(0, 234, 255)',
                            borderWidth: 1,
                            callbacks: {
                                label: function(context) {
                                    const v = Number(context.parsed.y || context.parsed);
                                    return v.toLocaleString('vi-VN') + ' ₫';
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            ticks: { 
                                maxRotation: 45,
                                minRotation: 0,
                                autoSkip: false,
                                color: textMuted,
                                font: { size: 12 }
                            },
                            grid: {
                                color: borderColor,
                                drawBorder: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: textMuted,
                                font: { size: 12 },
                                callback: function(value) {
                                    return Number(value).toLocaleString('vi-VN') + ' ₫';
                                }
                            },
                            grid: {
                                color: borderColor,
                                drawBorder: false
                            }
                        }
                    }
                }
            });
        })();
    </script>

    <script src="/TechShop/public/assets/js/admin.js"></script>
</body>
</html>
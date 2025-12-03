<?php
    require_once __DIR__ . '/../../app/helpers/CSRF.php';
    $csrf = CSRF::token();
    requireAdmin();

    // kết nối DB
    require_once __DIR__ . '/../../app/helpers/DB.php';
    $pdo = DB::getInstance()->getConnection();

    // Sản phẩm bán chạy nhất (loại trừ đơn huỷ)
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

    // Tổng doanh thu (loại trừ đơn huỷ)
    $stmt = $pdo->prepare("
        SELECT COALESCE(SUM(ct.so_luong * ct.don_gia),0) AS total_revenue
        FROM chi_tiet_don_hang ct
        JOIN don_hang dh ON dh.id = ct.id_don_hang
        WHERE dh.trang_thai_don != 'huy'
    ");
    $stmt->execute();
    $totalRevenue = $stmt->fetchColumn() ?: 0;

    // Doanh thu theo tháng (12 tháng gần nhất)
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

    // đảm bảo đủ 12 tháng (nếu cần)
    $months = [];
    for ($i = 11; $i >= 0; $i--) {
        $m = date('Y-m', strtotime("-{$i} months"));
        $months[$m] = 0;
    }
    foreach ($rows as $r) {
        $months[$r['month']] = (float)$r['revenue'];
    }
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
            
            <div class="dashboard-grid">

                <!-- Card: Sản phẩm bán chạy nhất -->
                <div class="card card-span-1">
                    <div class="card-header">
                        <h5 class="card-title">Sản phẩm bán chạy nhất</h5>
                    </div>
                    <div class="card-body">
                        <?php if ($best && $best['total_sold'] > 0): ?>
                            <div class="stat-main">
                                <div>
                                    <div class="stat-number"><?= htmlspecialchars($best['ten_sp']) ?></div>
                                    <div class="stat-change positive"><?= (int)$best['total_sold'] ?> sản phẩm</div>
                                </div>
                            </div>
                        <?php else: ?>
                            <div>Chưa có dữ liệu bán hàng</div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Card: Tổng doanh thu -->
                <div class="card card-span-1">
                    <div class="card-header">
                        <h5 class="card-title">Tổng doanh thu</h5>
                    </div>
                    <div class="card-body">
                        <div class="stat-main">
                            <div>
                                <div class="stat-number"><?= number_format($totalRevenue, 0, ',', '.') ?>₫</div>
                                <div class="stat-chart-small">Tổng từ đơn đã hoàn tất</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card: Doanh thu theo tháng -->
                <div class="card card-span-2">
                    <div class="card-header">
                        <h5 class="card-title">Doanh thu theo tháng (12 tháng)</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="revenueChart" class="revenue-chart"></canvas>
                    </div>
                </div>

        </main>
    </div>

    <script>
        // Chuẩn bị dữ liệu từ PHP
        const revenueLabels = <?= json_encode(array_keys($months)) ?>;
        const revenueData = <?= json_encode(array_values($months)) ?>;

        // Khởi tạo Chart.js khi DOM đã sẵn sàng
        (function(){
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
                                color: getComputedStyle(document.body).getPropertyValue('--text-color').trim() || '#58687a',
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
                                color: getComputedStyle(document.body).getPropertyValue('--text-muted').trim() || '#6c757d',
                                font: { size: 12 }
                            },
                            grid: {
                                color: 'rgba(200, 200, 200, 0.1)',
                                drawBorder: false
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                color: getComputedStyle(document.body).getPropertyValue('--text-muted').trim() || '#6c757d',
                                font: { size: 12 },
                                callback: function(value) {
                                    return Number(value).toLocaleString('vi-VN') + ' ₫';
                                }
                            },
                            grid: {
                                color: 'rgba(200, 200, 200, 0.1)',
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
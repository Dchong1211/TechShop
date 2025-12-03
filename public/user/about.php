<?php
// ================================================================
// FILE: public/user/about.php
// ================================================================

// 1. Xác định thư mục 'public'
define('PUBLIC_PATH', dirname(__DIR__)); 

// 2. KẾT NỐI DATABASE (Logic tìm file thông minh)
$db_file = PUBLIC_PATH . '/includes/db.php';
if (file_exists($db_file)) {
    require_once $db_file;
} else {
    $db_root = dirname(PUBLIC_PATH) . '/includes/db.php';
    if (file_exists($db_root)) require_once $db_root;
}

// 3. Cấu hình trang
$PAGE_TITLE = 'Về chúng tôi - TechShop';

// 4. GỌI HEADER
$header_path = PUBLIC_PATH . '/includes/User/header.php';
if (file_exists($header_path)) include $header_path;
?>

<link rel="stylesheet" href="/TechShop/public/assets/css/cssUser/about.css?v=FIX_FINAL">

<main class="about-page">
    
    <section class="about-hero">
        <div class="hero-overlay"></div>
        <div class="about-hero-content">
            <h1 class="about-title">TECHSHOP <span class="highlight">GAMING</span> UNIVERSE</h1>
            <p class="about-subtitle">
                Không chỉ bán máy tính. Chúng tôi cung cấp vũ khí tối thượng cho game thủ và công cụ sáng tạo cho các chuyên gia.
            </p>
        </div>
    </section>

    <div class="about-container">
        
        <section class="section-block">
            <div class="story-grid">
                <div class="story-content">
                    <h3 class="neon-text">Khởi nguồn đam mê</h3>
                    <p>
                        TechShop ra đời năm 2020 từ một nhóm kỹ sư đam mê phần cứng. Chúng tôi hiểu cảm giác khó chịu khi FPS tụt, máy nóng hay gear không "ngon".
                    </p>
                    <p>
                        Sứ mệnh của chúng tôi rất đơn giản: Mang đến cấu hình <strong>tối ưu nhất</strong> trong tầm giá và dịch vụ hậu mãi khiến bạn an tâm tuyệt đối.
                    </p>
                </div>
                <div class="story-tech-visual">
                    <div class="tech-circle"></div>
                    <div class="tech-lines"></div>
                </div>
            </div>
        </section>

        <section class="section-block">
            <div class="stats-section glass-effect">
                <div class="stat-item">
                    <h4 class="counter-glow">05+</h4>
                    <p>Năm kinh nghiệm</p>
                </div>
                <div class="stat-item">
                    <h4 class="counter-glow">10K+</h4>
                    <p>Khách hàng hài lòng</p>
                </div>
                <div class="stat-item">
                    <h4 class="counter-glow">100%</h4>
                    <p>Hàng chính hãng</p>
                </div>
                <div class="stat-item">
                    <h4 class="counter-glow">24/7</h4>
                    <p>Hỗ trợ kỹ thuật</p>
                </div>
            </div>
        </section>

        <section class="section-block">
            <div class="section-heading">
                <h2 class="neon-text">Giá Trị Cốt Lõi</h2>
                <div class="divider-glow"></div>
            </div>
            <div class="values-grid">
                <div class="value-card tech-card">
                    <div class="card-border-glow"></div>
                    <div class="value-icon"><i class="fa-solid fa-microchip"></i></div>
                    <h3>Chất Lượng Đỉnh Cao</h3>
                    <p>Chỉ cung cấp linh kiện chính hãng, hiệu năng thực tế đã được kiểm định nghiêm ngặt.</p>
                </div>
                <div class="value-card tech-card">
                    <div class="card-border-glow"></div>
                    <div class="value-icon"><i class="fa-solid fa-wand-magic-sparkles"></i></div>
                    <h3>PC Custom Độc Bản</h3>
                    <p>Thiết kế dàn máy theo cá tính riêng. Đi dây nghệ thuật, tối ưu luồng khí và thẩm mỹ cực chất.</p>
                </div>
                <div class="value-card tech-card">
                    <div class="card-border-glow"></div>
                    <div class="value-icon"><i class="fa-solid fa-headset"></i></div>
                    <h3>Tư Vấn Thực Chiến</h3>
                    <p>Build PC theo đúng nhu cầu và ngân sách, không "vẽ" thêm để tối ưu chi phí cho bạn.</p>
                </div>
            </div>
        </section>

        <section class="section-block">
            <div class="section-heading">
                <h2 class="neon-text">Biệt Đội TechShop</h2>
                <div class="divider-glow"></div>
            </div>
            <div class="team-grid">
                <div class="team-card">
                    <div class="member-avatar"><i class="fa-solid fa-user-astronaut"></i></div>
                    <h3>Admin Long</h3>
                    <span class="member-role">Founder / Master Builder</span>
                    <div class="member-stats">
                        <div class="stat-row"><span>Kỹ năng:</span> <div class="bar"><div style="width: 95%"></div></div></div>
                        <div class="stat-row"><span>Tốc độ:</span> <div class="bar"><div style="width: 90%"></div></div></div>
                    </div>
                </div>
                <div class="team-card">
                    <div class="member-avatar"><i class="fa-solid fa-robot"></i></div>
                    <h3>Support Bot</h3>
                    <span class="member-role">Hỗ trợ kỹ thuật</span>
                    <div class="member-stats">
                        <div class="stat-row"><span>Kỹ năng:</span> <div class="bar"><div style="width: 85%"></div></div></div>
                        <div class="stat-row"><span>Tốc độ:</span> <div class="bar"><div style="width: 99%"></div></div></div>
                    </div>
                </div>
                <div class="team-card">
                    <div class="member-avatar"><i class="fa-solid fa-headset"></i></div>
                    <h3>Sales Pro</h3>
                    <span class="member-role">Chăm sóc khách hàng</span>
                    <div class="member-stats">
                        <div class="stat-row"><span>Kỹ năng:</span> <div class="bar"><div style="width: 80%"></div></div></div>
                        <div class="stat-row"><span>Tốc độ:</span> <div class="bar"><div style="width: 92%"></div></div></div>
                    </div>
                </div>
            </div>
        </section>

        <section class="section-block" style="padding-bottom: 40px;">
            <div class="section-heading">
                <h2 class="neon-text" style="font-size: 1.5rem">Đối Tác Phần Cứng</h2>
            </div>
            <div class="brand-marquee">
                <div class="marquee-content">
                    <span>ASUS ROG</span> <span>MSI GAMING</span> <span>GIGABYTE</span> <span>CORSAIR</span>
                    <span>RAZER</span> <span>INTEL</span> <span>AMD</span> <span>NVIDIA</span>
                    <span>ASUS ROG</span> <span>MSI GAMING</span> <span>GIGABYTE</span> <span>CORSAIR</span>
                    <span>RAZER</span> <span>INTEL</span> <span>AMD</span> <span>NVIDIA</span>
                </div>
            </div>
        </section>

        <div class="cta-container">
            <div class="cta-box">
                <h2 class="neon-text">Bạn Đã Sẵn Sàng?</h2>
                <p style="color: var(--text-muted); margin-bottom: 20px;">Build ngay cỗ máy chiến game trong mơ của bạn ngay hôm nay!</p>
                
                <a href="/TechShop/public/user/index.php?cate=pc" class="btn-neon-glitch">
                    BUILD PC NGAY <i class="fa-solid fa-arrow-right"></i>
                </a>
                
            </div>
        </div>

    </div>
</main>

<?php
$footer_path = PUBLIC_PATH . '/includes/User/footer.php';
if (file_exists($footer_path)) include $footer_path;
?>
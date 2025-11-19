(function() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
    } else {
        document.body.classList.remove('dark-mode');
    }
})();
document.addEventListener('DOMContentLoaded', function() {
    
    const toggleButton = document.querySelector('.sidebar-toggle');
    const appWrapper = document.querySelector('.app-wrapper');
    if (toggleButton && appWrapper) {
        toggleButton.addEventListener('click', function() {
            appWrapper.classList.toggle('sidebar-collapsed');
        });
    }


    const themeToggle = document.getElementById('theme-toggle');

    if (themeToggle) {
        themeToggle.addEventListener('click', function() {
            document.body.classList.toggle('dark-mode');
            let currentTheme = 'light';
            if (document.body.classList.contains('dark-mode')) {
                currentTheme = 'dark';
            }
            localStorage.setItem('theme', currentTheme);
        });
    }

    const logoutLinks = document.querySelectorAll('a[href*="login.php"], a[href*="logout"]');
    
    logoutLinks.forEach(link => {
        // Chỉ xử lý link có chữ "Đăng xuất" hoặc class logout
        if (link.textContent.includes('Đăng xuất') || link.classList.contains('sidebar-logout')) {
            link.addEventListener('click', async function(e) {
                e.preventDefault();
                
                if (!confirm('Bạn có chắc chắn muốn đăng xuất?')) return;

                try {
                    const formData = new FormData();
                    // Lấy CSRF token từ meta tag (sẽ thêm vào head) hoặc input hidden
                    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
                    formData.append('csrf', csrf);

                    const res = await fetch('/TechShop/public/logout', {
                        method: 'POST',
                        body: formData
                    });
                    const data = await res.json();

                    if (data.success) {
                        window.location.href = '/TechShop/public/admin/login.php';
                    } else {
                        alert('Lỗi: ' + data.message);
                    }
                } catch (err) {
                    console.error(err);
                    // Fallback nếu API lỗi
                    window.location.href = '/TechShop/public/admin/login.php'; 
                }
            });
        }
    });

});
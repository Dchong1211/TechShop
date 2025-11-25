(function() {
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark') {
        document.body.classList.add('dark-mode');
    }
})();

document.addEventListener('DOMContentLoaded', function() {

    const toggleButton = document.querySelector('.sidebar-toggle');
    const appWrapper = document.querySelector('.app-wrapper');

    if (toggleButton && appWrapper) {
        toggleButton.addEventListener('click', () => {
            appWrapper.classList.toggle('sidebar-collapsed');
        });
    }

    const themeToggle = document.getElementById('theme-toggle');
    if (themeToggle) {
        themeToggle.addEventListener('click', () => {
            document.body.classList.toggle('dark-mode');
            localStorage.setItem(
                'theme',
                document.body.classList.contains('dark-mode') ? 'dark' : 'light'
            );
        });
    }

    const logoutLinks = document.querySelectorAll('a[href*="logout"], .sidebar-logout');

    logoutLinks.forEach(link => {
        link.addEventListener('click', async (e) => {
            e.preventDefault();

            if (!confirm('Bạn có chắc chắn muốn đăng xuất?')) return;

            try {
                const formData = new FormData();
                const csrf = document
                    .querySelector('meta[name="csrf-token"]')?.content || '';

                formData.append('csrf', csrf);

                const res = await fetch('/TechShop/logout', {
                    method: 'POST',
                    body: formData
                });

                const data = await res.json();

                if (data.success) {
                    window.location.href = '/TechShop/login';
                } else {
                    alert('Lỗi: ' + data.message);
                }
            } catch (err) {
                console.error(err);
                window.location.href = '/TechShop/login';
            }
        });
    });

});

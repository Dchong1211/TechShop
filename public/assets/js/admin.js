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

});
document.getElementById('form-login').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const btn = this.querySelector('button');
    const msgBox = document.getElementById('msg-box');
    const formData = new FormData(this);

    btn.textContent = 'Đang xử lý...';
    btn.classList.add('loading');
    msgBox.style.display = 'none';

    try {
        const response = await fetch('/TechShop/public/login', {
            method: 'POST',
            body: formData
        });

        const data = await response.json();

        if (data.success) {
            msgBox.className = 'message-box success';
            msgBox.textContent = 'Đăng nhập thành công! Đang chuyển hướng...';
            msgBox.style.display = 'block';
            
            setTimeout(() => {
                if (data.user.role === 'admin') {
                    window.location.href = '/TechShop/admin';
                } else {
                    window.location.href = '/TechShop/user';
                }
            }, 800);
        } else {
            throw new Error(data.message || 'Đăng nhập thất bại');
        }

    } catch (error) {
        msgBox.className = 'message-box error';
        msgBox.textContent = error.message;
        msgBox.style.display = 'block';
    } finally {
        btn.textContent = 'Đăng Nhập';
        btn.classList.remove('loading');
    }
});
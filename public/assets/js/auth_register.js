const msgBox = document.getElementById('message-box');
const registerStep = document.getElementById('register-step');
const otpStep = document.getElementById('otp-step');

function showMessage(msg, type = 'error') {
    msgBox.textContent = msg;
    msgBox.className = 'message-box ' + type;
    msgBox.style.display = 'block';
}

//  Gửi thông tin đăng ký 
document.getElementById('form-register').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const pass = document.getElementById('password').value;
    const confirmPass = document.getElementById('confirm_password').value;
    const btn = this.querySelector('button');

    if (pass !== confirmPass) {
        showMessage('Mật khẩu nhập lại không khớp!');
        return;
    }

    const formData = new FormData(this);
    btn.textContent = 'Đang đăng ký...';
    btn.classList.add('btn-loading');
    msgBox.style.display = 'none';

    try {
        const res = await fetch('/TechShop/register', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            showMessage(data.message, 'success');
            const email = document.getElementById('email').value;
            document.getElementById('otp-email-display').textContent = email;
            document.getElementById('verify_email').value = email;
            
            registerStep.classList.add('hidden');
            otpStep.classList.remove('hidden');
        } else {
            showMessage(data.message);
        }
    } catch (err) {
        console.error(err);
        showMessage('Lỗi kết nối máy chủ');
    } finally {
        btn.textContent = 'Đăng Ký';
        btn.classList.remove('btn-loading');
    }
});

//  Xác minh OTP 
document.getElementById('form-verify').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = this.querySelector('button');
    const formData = new FormData(this);

    btn.textContent = 'Đang xác minh...';
    btn.classList.add('btn-loading');
    
    try {
        const res = await fetch('/TechShop/verify-email', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            showMessage('Tài khoản đã kích hoạt! Đang chuyển đến đăng nhập...', 'success');
            setTimeout(() => {
                window.location.href = '/TechShop/login';
            }, 1500);
        } else {
            showMessage(data.message);
        }
    } catch (err) {
        showMessage('Lỗi kết nối xác minh');
    } finally {
        btn.textContent = 'Xác Minh';
        btn.classList.remove('btn-loading');
    }
});
const msgBox = document.getElementById('msg-box');
const stepEmail = document.getElementById('step-email');
const stepOtp = document.getElementById('step-otp');
const stepReset = document.getElementById('step-reset');

function showMsg(text, type = 'error') {
    msgBox.textContent = text;
    msgBox.className = 'message-box ' + type;
    msgBox.style.display = 'block';
}

//  Gửi Email nhận OTP
document.getElementById('form-send-otp').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = this.querySelector('button');
    const emailVal = document.getElementById('email').value;
    const formData = new FormData(this);

    btn.textContent = 'Đang gửi...';
    msgBox.style.display = 'none';

    try {
        const res = await fetch('/TechShop/public/forgot-password', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            showMsg(data.message, 'success');
            document.getElementById('otp_email_hidden').value = emailVal;
            stepEmail.classList.add('hidden');
            stepOtp.classList.remove('hidden');
        } else {
            showMsg(data.message);
        }
    } catch (err) { showMsg('Lỗi kết nối!'); } 
    finally { btn.textContent = 'Gửi mã xác minh'; }
});

//  Verify OTP 
document.getElementById('form-verify-otp').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = this.querySelector('button');
    const formData = new FormData(this);

    btn.textContent = 'Đang kiểm tra...';
    
    try {
        const res = await fetch('/TechShop/public/verify-reset-otp', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            showMsg('OTP chính xác. Mời nhập mật khẩu mới.', 'success');
            document.getElementById('reset_user_id').value = data.user_id;
            stepOtp.classList.add('hidden');
            stepReset.classList.remove('hidden');
        } else {
            showMsg(data.message);
        }
    } catch (err) { showMsg('Lỗi kết nối!'); }
    finally { btn.textContent = 'Xác nhận OTP'; }
});

//  Đổi mật khẩu
document.getElementById('form-reset-pass').addEventListener('submit', async function(e) {
    e.preventDefault();
    const p1 = document.getElementById('new_pass').value;
    const p2 = document.getElementById('confirm_pass').value;
    const btn = this.querySelector('button');

    if(p1 !== p2) {
        showMsg('Mật khẩu nhập lại không khớp!');
        return;
    }

    btn.textContent = 'Đang xử lý...';
    const formData = new FormData(this);

    try {
        const res = await fetch('/TechShop/public/reset-password-otp', {
            method: 'POST',
            body: formData
        });
        const data = await res.json();

        if (data.success) {
            showMsg('Đổi mật khẩu thành công! Đang chuyển về trang đăng nhập...', 'success');
            setTimeout(() => {
                window.location.href = 'public/admin/login.php';
            }, 2000);
        } else {
            showMsg(data.message);
        }
    } catch (err) { 
        showMsg('Lỗi kết nối!'); 
    }
    finally { 
        btn.textContent = 'Đổi mật khẩu'; 
    }
});
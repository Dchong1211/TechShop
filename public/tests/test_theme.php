<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
  <meta charset="UTF-8" />
  <title>Neon Pulse Tech Theme 💠</title>
  <style>
  :root {
    /* ===== BACKGROUND ===== */
    --c-bg-dark: #111a2b;
    --c-bg-darker: #0d1322;
    --c-bg-page: radial-gradient(circle at top right, #0a0e1a 30%, #111a2b 100%);

    /* ===== TEXT ===== */
    --c-text: #e6f7ff;
    --c-text-dim: rgba(230, 247, 255, 0.7);

    /* ===== ACCENT ===== */
    --c-neon: #00eaff;
    --c-purple: #7f5eff;

    /* ===== BORDERS & SHADOW ===== */
    --c-border: rgba(0,234,255,0.35);
    --c-border-soft: rgba(0,234,255,0.15);
    --c-glow: 0 0 20px rgba(0,234,255,0.25);
    --c-glow-soft: 0 0 12px rgba(127,94,255,0.3);

    /* ===== TITLE ===== */
    --c-title-gradient: linear-gradient(90deg, #00eaff, #7f5eff);
    --c-title-shadow: 0 0 12px rgba(127,94,255,0.6);

    /* ===== BUTTON ===== */
    --c-btn-bg: linear-gradient(90deg, #00eaff, #7f5eff);
    --c-btn-text: #111a2b;
    --c-btn-hover: brightness(1.25);

    /* ===== INPUT ===== */
    --c-input-bg: #0a0e1a;
    --c-input-border: rgba(0,234,255,0.35);
    --c-input-shadow: 0 0 8px rgba(0,234,255,0.18);
  }
  body {
    margin: 0;
    font-family: 'Segoe UI', sans-serif;
    background: var(--c-bg-darker);
    color: var(--c-text);
    height: 100vh;
    display: flex;
  }

  /* LEFT SIDE */
  .left-panel {
    flex: 1;
    background: var(--c-bg-page);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 30px;
    box-shadow: inset 0 0 60px rgba(0, 234, 255, 0.08);
  }

  .left-panel h1 {
    font-size: 70px;
    background: var(--c-title-gradient);
    background-clip: text;
    color: transparent;
    text-shadow: var(--c-title-shadow);
  }

  .left-panel p {
    font-size: 18px;
    opacity: 0.75;
    max-width: 430px;
    text-align: center;
  }

  /* RIGHT SIDE */
  .right-panel {
    width: 520px;
    background: var(--c-bg-dark);
    padding: 40px;
    box-shadow: -6px 0 30px rgba(0, 0, 0, 0.25);
    overflow-y: auto;
  }

  h2 {
    text-align: center;
    margin-bottom: 20px;
    font-size: 26px;
    font-weight: 700;
    background: var(--c-title-gradient);
    background-clip: text;
    color: transparent;
    text-shadow: var(--c-glow-soft);
  }

  .box {
    background: var(--c-bg-darker);
    border: 1px solid var(--c-border-soft);
    border-radius: 12px;
    padding: 18px;
    margin-bottom: 25px;
    box-shadow: var(--c-glow);
  }

  .box h3 {
    margin-top: 0;
    font-size: 18px;
    color: var(--c-purple);
  }

  input {
    width: 100%;
    padding: 10px;
    margin: 6px 0 12px 0;
    border: 1px solid var(--c-input-border);
    border-radius: 6px;
    background: var(--c-input-bg);
    color: var(--c-text);
    font-size: 14px;
    box-shadow: var(--c-input-shadow);
  }

  button {
    background: var(--c-btn-bg);
    border: none;
    padding: 10px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-weight: 600;
    color: var(--c-btn-text);
    transition: 0.25s;
  }

  button:hover {
    filter: var(--c-btn-hover);
    transform: scale(1.03);
  }

  pre {
    background: var(--c-input-bg);
    border: 1px solid var(--c-border-soft);
    padding: 14px;
    border-radius: 10px;
    max-height: 280px;
    overflow-y: auto;
    font-size: 13px;
    color: var(--c-neon);
  }

  </style>
</head>
<body>
  <!-- LEFT -->
  <div class="left-panel">
    <h1>TechShop</h1>
    <p>Trang test API người dùng — kiểm tra Register, Login, Verify OTP, Reset Password bằng UI trực quan.</p>
  </div>

  <!-- RIGHT -->
  <div class="right-panel">

    <h2>🧪 Test API User</h2>

    <!-- REGISTER -->
    <div class="box">
      <h3>Đăng ký tài khoản</h3>
      <input type="text" id="regName" placeholder="Tên người dùng">
      <input type="email" id="regEmail" placeholder="Email">
      <input type="password" id="regPassword" placeholder="Mật khẩu">
      <button onclick="registerUser()">Đăng ký</button>
    </div>

    <!-- VERIFY -->
    <div class="box">
      <h3>Xác minh Email (OTP)</h3>
      <input type="email" id="verifyEmail" placeholder="Email">
      <input type="text" id="verifyOtp" placeholder="OTP 6 số">
      <button onclick="verifyEmail()">Xác minh</button>
    </div>

    <!-- LOGIN -->
    <div class="box">
      <h3>Đăng nhập</h3>
      <input type="email" id="loginEmail" placeholder="Email">
      <input type="password" id="loginPassword" placeholder="Mật khẩu">
      <button onclick="loginUser()">Đăng nhập</button>
      <button onclick="logoutUser()">Đăng xuất</button>
    </div>

    <!-- FORGOT -->
    <div class="box">
      <h3>Quên mật khẩu</h3>
      <input type="email" id="forgotEmail" placeholder="Email">
      <button onclick="forgotPassword()">Gửi link reset</button>
    </div>

    <!-- RESET -->
    <div class="box">
      <h3>Đặt lại mật khẩu</h3>
      <input type="text" id="resetToken" placeholder="Token reset">
      <input type="password" id="newPass" placeholder="Mật khẩu mới">
      <input type="password" id="newPass2" placeholder="Nhập lại mật khẩu">
      <button onclick="resetPassword()">Đổi mật khẩu</button>
    </div>

    <h3 style="color:#00eaff">Kết quả:</h3>
    <pre id="output">{}</pre>

  </div>

  <script>
    const apiBase = "http://localhost/TechShop/public";

    function show(data) {
      document.getElementById("output").textContent =
        JSON.stringify(data, null, 2);
    }

    async function post(url, body = {}) {
      const res = await fetch(apiBase + url, {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams(body)
      });
      return await res.json();
    }

    async function registerUser() {
      const data = await post("/register", {
        name: regName.value,
        email: regEmail.value,
        password: regPassword.value,
        csrf: "123"
      });
      show(data);
    }

    async function verifyEmail() {
      const data = await post("/verify-email", {
        email: verifyEmail.value,
        otp: verifyOtp.value,
        csrf: "123"
      });
      show(data);
    }

    async function loginUser() {
      const data = await post("/login", {
        email: loginEmail.value,
        password: loginPassword.value,
        csrf: "123"
      });
      show(data);
    }

    async function logoutUser() {
      const data = await post("/logout", { csrf: "123" });
      show(data);
    }

    async function forgotPassword() {
      const data = await post("/forgot-password", {
        email: forgotEmail.value,
        csrf: "123"
      });
      show(data);
    }

    async function resetPassword() {
      const data = await post("/reset-password", {
        token: resetToken.value,
        password: newPass.value,
        password_confirm: newPass2.value,
        csrf: "123"
      });
      show(data);
    }
  </script>
</body>
</html>

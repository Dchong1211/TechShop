-- =========================================================
-- 🗑 XÓA DB CŨ (NẾU CÓ)
-- =========================================================
DROP DATABASE IF EXISTS techshop;

-- =========================================================
-- 🛠 TẠO DATABASE MỚI
-- =========================================================
CREATE DATABASE techshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE techshop;


/* =========================================================
   🧑‍💻 TABLE: USERS
   ========================================================= */
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user','admin') DEFAULT 'user',

    -- Email verify
    email_verified TINYINT(1) DEFAULT 0,
    email_verified_at DATETIME,

    otp VARCHAR(6),
    otp_expires_at DATETIME,

    -- Reset password
    reset_token VARCHAR(64),
    reset_expires_at DATETIME,

    reset_otp VARCHAR(6),
    reset_otp_expires DATETIME,

    -- Tình trạng tài khoản
    status TINYINT(1) DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Tạo admin mặc định
INSERT INTO users (name, email, password, role, email_verified, status) VALUES (
    'Admin TechShop',
    'techshopNT@gmail.com',
    '$2y$10$5mue1FeO5.w4FNKrQGLpNOvfKhCk3huX3KwjNQH6QCSRN2dXsRKmC',  -- Admin@123
    'admin',
    1,
    1
);



/* =========================================================
   📍 TABLE: USER ADDRESSES (Địa chỉ nhận hàng)
   ========================================================= */
CREATE TABLE user_addresses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    address TEXT NOT NULL,
    is_default TINYINT(1) DEFAULT 0,

    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);



/* =========================================================
   🏷 TABLE: CATEGORIES
   ========================================================= */
CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO categories (name, description) VALUES
('Laptop', 'Các dòng laptop, notebook, ultrabook'),
('Chuột', 'Chuột gaming có dây & không dây'),
('Bàn phím', 'Bàn phím cơ & văn phòng'),
('Tai nghe', 'Headset, earphone, bluetooth');



/* =========================================================
   🛒 TABLE: PRODUCTS
   ========================================================= */
CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    price DECIMAL(12,2) NOT NULL,
    image VARCHAR(255),
    category_id INT,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (category_id) REFERENCES categories(id)
        ON DELETE SET NULL
        ON UPDATE CASCADE
);

INSERT INTO products (name, description, price, image, category_id) VALUES
('Laptop Asus TUF F15', 'Gaming RTX 4060, i7 Gen13', 23990000, 'asus_tuf_f15.jpg', 1),
('Laptop Acer Nitro 5', 'Gaming RTX 3050, Ryzen 5', 19990000, 'acer_nitro_5.jpg', 1),
('Chuột Logitech G Pro X', 'Chuột gaming không dây', 2190000, 'logitech_gprox.jpg', 2),
('Chuột Razer DeathAdder V2', 'Chuột LED RGB nổi tiếng', 1590000, 'razer_da_v2.jpg', 2),
('Bàn phím Keychron K8', 'Bàn phím cơ Bluetooth cao cấp', 2690000, 'keychron_k8.jpg', 3),
('Bàn phím Akko 3068B', 'Bàn phím cơ không dây', 1890000, 'akko_3068b.jpg', 3),
('Tai nghe Razer BlackShark V2', 'Headset 7.1, gaming', 2990000, 'razer_blackshark.jpg', 4),
('Tai nghe Logitech G733', 'RGB wireless', 3490000, 'logitech_g733.jpg', 4);



/* =========================================================
   🛍 TABLE: CART ITEMS (GIỎ HÀNG)
   ========================================================= */
CREATE TABLE cart_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE CASCADE
);



/* =========================================================
   ❤️ TABLE: WISHLIST (Sản phẩm yêu thích)
   ========================================================= */
CREATE TABLE wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    product_id INT NOT NULL,

    UNIQUE(user_id, product_id),

    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE CASCADE
);



/* =========================================================
   📦 TABLE: ORDERS (Đơn hàng)
   ========================================================= */
CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    address TEXT NOT NULL,
    total_price DECIMAL(12,2) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,

    status ENUM('pending','paid','shipping','completed','cancelled')
        DEFAULT 'pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
);

INSERT INTO orders (user_id, total_price, address, payment_method) VALUES
(1, 23990000, '123 Nguyễn Trãi, TP.HCM', 'COD'),
(1, 4690000,  '45 Trần Phú, Nha Trang', 'Chuyển khoản');



/* =========================================================
   📦 TABLE: ORDER ITEMS (Sản phẩm trong đơn)
   ========================================================= */
CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    price DECIMAL(12,2) NOT NULL,   -- giá tại thời điểm mua

    FOREIGN KEY (order_id) REFERENCES orders(id)
        ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE CASCADE
);

INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 1, 23990000),
(2, 3, 2, 2190000);

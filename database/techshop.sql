-- ============================================
-- 📦 DATABASE: TechShop
-- ============================================

DROP DATABASE IF EXISTS techshop;
CREATE DATABASE techshop CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE techshop;

-- ============================================
-- 👤 USERS TABLE
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('user', 'admin') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO users (name, email, password, role) VALUES
('Admin TechShop', 'admin@gmail.com', '$2y$10$5mue1FeO5.w4FNKrQGLpNOvfKhCk3huX3KwjNQH6QCSRN2dXsRKmC', 'admin');

-- ============================================
-- 🏷️ CATEGORIES TABLE
-- ============================================

CREATE TABLE categories (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Dữ liệu mẫu danh mục
INSERT INTO categories (name, description) VALUES
('Laptop', 'Các dòng laptop, notebook, ultrabook'),
('Chuột', 'Chuột có dây, không dây, gaming'),
('Bàn phím', 'Bàn phím cơ, văn phòng, RGB'),
('Tai nghe', 'Headset, earphone, bluetooth');

-- ============================================
-- 💻 PRODUCTS TABLE
-- ============================================

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

-- Dữ liệu mẫu sản phẩm
INSERT INTO products (name, description, price, image, category_id) VALUES
('Laptop Asus TUF F15', 'Laptop gaming RTX 4060, i7 Gen13', 23990000, 'asus_tuf_f15.jpg', 1),
('Laptop Acer Nitro 5', 'Laptop gaming RTX 3050, Ryzen 5', 19990000, 'acer_nitro_5.jpg', 1),
('Chuột Logitech G Pro X', 'Chuột gaming không dây Logitech', 2190000, 'logitech_gprox.jpg', 2),
('Chuột Razer DeathAdder V2', 'Chuột có dây Razer, LED RGB', 1590000, 'razer_da_v2.jpg', 2),
('Bàn phím Keychron K8', 'Bàn phím cơ Bluetooth cao cấp', 2690000, 'keychron_k8.jpg', 3),
('Bàn phím Akko 3068B', 'Bàn phím cơ không dây Akko', 1890000, 'akko_3068b.jpg', 3),
('Tai nghe Razer BlackShark V2', 'Tai nghe gaming 7.1 âm thanh vòm', 2990000, 'razer_blackshark.jpg', 4),
('Tai nghe Logitech G733', 'Tai nghe không dây RGB', 3490000, 'logitech_g733.jpg', 4);

-- ============================================
-- 📦 ORDERS TABLE
-- ============================================

CREATE TABLE orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    total_price DECIMAL(12,2) NOT NULL,
    address VARCHAR(255) NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

-- Dữ liệu mẫu đơn hàng
INSERT INTO orders (user_id, total_price, address, payment_method) VALUES
(1, 23990000, '123 Nguyễn Trãi, TP.HCM', 'COD'),
(2, 4690000, '45 Trần Phú, Nha Trang', 'Chuyển khoản');

-- ============================================
-- 📦 ORDER ITEMS TABLE
-- ============================================

CREATE TABLE order_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT DEFAULT 1,
    price DECIMAL(12,2) NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id)
        ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id)
        ON DELETE CASCADE
);

-- Dữ liệu mẫu chi tiết đơn hàng
INSERT INTO order_items (order_id, product_id, quantity, price) VALUES
(1, 1, 1, 23990000),
(2, 3, 2, 2190000);

-- ============================================
-- ✅ KẾT THÚC FILE SQL
-- ============================================

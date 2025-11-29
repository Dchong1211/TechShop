-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2025 at 11:03 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `techshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_don_hang`
--

CREATE TABLE `chi_tiet_don_hang` (
  `id` int(11) NOT NULL,
  `id_don_hang` int(11) NOT NULL,
  `id_san_pham` int(11) NOT NULL,
  `so_luong` int(11) NOT NULL,
  `don_gia` decimal(10,2) NOT NULL COMMENT 'Giá tại thời điểm mua',
  `gia_von` decimal(10,2) NOT NULL COMMENT 'Giá vốn của sản phẩm tại thời điểm bán'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chi_tiet_don_hang`
--

INSERT INTO `chi_tiet_don_hang` (`id`, `id_don_hang`, `id_san_pham`, `so_luong`, `don_gia`, `gia_von`) VALUES
(1, 1, 101, 1, 32990000.00, 0.00),
(2, 1, 111, 1, 3590000.00, 0.00),
(3, 1, 137, 2, 6500000.00, 0.00),
(4, 1, 128, 1, 1090000.00, 0.00),
(5, 2, 120, 1, 11500000.00, 0.00),
(6, 2, 134, 1, 3290000.00, 0.00),
(7, 2, 136, 2, 5990000.00, 0.00),
(8, 3, 108, 1, 3500000.00, 0.00),
(9, 3, 118, 1, 1800000.00, 0.00),
(10, 3, 150, 1, 9900000.00, 0.00),
(11, 3, 176, 1, 400000.00, 0.00),
(12, 3, 165, 1, 450000.00, 0.00),
(13, 3, 167, 2, 750000.00, 0.00),
(14, 4, 142, 1, 52000000.00, 0.00),
(15, 4, 157, 1, 650000.00, 0.00),
(16, 4, 158, 2, 2350000.00, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `chi_tiet_gio_hang`
--

CREATE TABLE `chi_tiet_gio_hang` (
  `id` int(11) NOT NULL,
  `id_nguoi_dung` int(11) NOT NULL COMMENT 'Khóa ngoại tới nguoi_dung',
  `id_san_pham` int(11) NOT NULL COMMENT 'Khóa ngoại tới san_pham',
  `so_luong` int(11) NOT NULL COMMENT 'Số lượng sản phẩm trong giỏ'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `danh_muc`
--

CREATE TABLE `danh_muc` (
  `id` int(11) NOT NULL,
  `ten_dm` varchar(100) NOT NULL,
  `mo_ta` text DEFAULT NULL,
  `trang_thai` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Hiển thị, 0: Ẩn'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `danh_muc`
--

INSERT INTO `danh_muc` (`id`, `ten_dm`, `mo_ta`, `trang_thai`) VALUES
(1, 'Laptop Gaming', 'Máy tính xách tay cấu hình cao chuyên chơi game.', 1),
(2, 'Laptop Văn Phòng/Học Sinh', 'Máy tính xách tay mỏng nhẹ, hiệu năng ổn định cho công việc.', 1),
(3, 'PC Desktop', 'Máy tính để bàn, bao gồm cả PC Gaming và Workstation.', 1),
(4, 'Màn Hình', 'Các loại màn hình máy tính từ gaming đến đồ họa.', 1),
(5, 'Bàn Phím Cơ', 'Bàn phím sử dụng switch cơ học, độ bền cao.', 1),
(6, 'Chuột Máy Tính', 'Chuột có dây và không dây, từ văn phòng đến gaming.', 1),
(7, 'Tai Nghe & Âm Thanh', 'Tai nghe chụp tai, in-ear, loa di động.', 1),
(8, 'Ổ Cứng SSD/HDD', 'Các loại ổ cứng thể rắn và cơ học.', 1),
(9, 'RAM & Bộ Nhớ', 'Thanh RAM DDR4, DDR5 và các module bộ nhớ khác.', 1),
(10, 'Card Màn Hình (VGA)', 'Card đồ họa chuyên dụng cho game và đồ họa.', 1),
(11, 'CPU (Bộ Vi Xử Lý)', 'Bộ vi xử lý trung tâm của Intel và AMD.', 1),
(12, 'Mainboard (Bo Mạch Chủ)', 'Bo mạch chủ kết nối các linh kiện chính.', 1),
(13, 'Webcam & Thiết Bị Hội Nghị', 'Thiết bị ghi hình và micro cho họp trực tuyến.', 1),
(14, 'Phần Mềm & Bản Quyền', 'Hệ điều hành, Office, phần mềm diệt virus.', 1),
(15, 'Thiết Bị Mạng', 'Router, Modem, Access Point.', 1),
(16, 'Pin & Sạc Dự Phòng', 'Các thiết bị lưu trữ và cung cấp năng lượng di động.', 1),
(17, 'Loa Di Động', 'Loa Bluetooth và loa không dây.', 1),
(18, 'Máy In & Thiết Bị Văn Phòng', 'Máy in, máy scan, máy fax.', 1),
(19, 'Phụ Kiện Điện Thoại', 'Ốp lưng, dán màn hình, cáp sạc.', 1),
(20, 'Thiết Bị Đeo Thông Minh', 'Đồng hồ thông minh, vòng tay sức khỏe.', 1);

-- --------------------------------------------------------

--
-- Table structure for table `danh_sach_yeu_thich`
--

CREATE TABLE `danh_sach_yeu_thich` (
  `id_nguoi_dung` int(11) NOT NULL COMMENT 'Khóa ngoại tới nguoi_dung',
  `id_san_pham` int(11) NOT NULL COMMENT 'Khóa ngoại tới san_pham',
  `ma_chi_tiet` varchar(255) DEFAULT NULL COMMENT 'Khóa chi tiết (cho biến thể sản phẩm)',
  `ngay_them` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Thời gian thêm vào wishlist'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `don_hang`
--

CREATE TABLE `don_hang` (
  `id` int(11) NOT NULL,
  `id_khach_hang` int(11) NOT NULL,
  `tong_tien` decimal(10,2) NOT NULL,
  `phuong_thuc_thanh_toan` varchar(50) NOT NULL,
  `ten_nguoi_nhan` varchar(100) NOT NULL,
  `sdt_nguoi_nhan` varchar(15) NOT NULL,
  `dia_chi_giao_hang` varchar(255) NOT NULL,
  `trang_thai_don` enum('cho_xac_nhan','da_xac_nhan','dang_giao','da_giao','huy') NOT NULL DEFAULT 'cho_xac_nhan',
  `ngay_dat_hang` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `don_hang`
--

INSERT INTO `don_hang` (`id`, `id_khach_hang`, `tong_tien`, `phuong_thuc_thanh_toan`, `ten_nguoi_nhan`, `sdt_nguoi_nhan`, `dia_chi_giao_hang`, `trang_thai_don`, `ngay_dat_hang`) VALUES
(1, 2, 53280000.00, 'Chuyển khoản', 'Nguyễn Văn A', '0912345678', '45 Lê Lợi, Quận 1, TP.HCM', 'da_giao', '2025-10-26 10:00:00'),
(2, 3, 26980000.00, 'COD', 'Trần Thị B', '0987654321', '100 Trần Phú, Hải Châu, Đà Nẵng', 'dang_giao', '2025-11-01 14:30:00'),
(3, 4, 18270000.00, 'Thẻ Tín Dụng', 'Lê Văn C', '0909888999', '22 Nguyễn Huệ, Quận Ninh Kiều, Cần Thơ', 'cho_xac_nhan', '2025-11-10 11:15:00'),
(4, 5, 47790000.00, 'Chuyển khoản', 'Phạm Thị D', '0977666555', '50 Hùng Vương, Nha Trang, Khánh Hòa', 'da_xac_nhan', '2025-11-12 09:40:00'),
(5, 2, 1090000.00, 'COD', 'Nguyễn Văn A', '0912345678', '45 Lê Lợi, Quận 1, TP.HCM', 'huy', '2025-11-13 10:30:00');

-- --------------------------------------------------------

--
-- Table structure for table `nguoi_dung`
--

CREATE TABLE `nguoi_dung` (
  `id` int(11) NOT NULL,
  `ho_ten` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `mat_khau` varchar(255) NOT NULL,
  `dien_thoai` varchar(15) DEFAULT NULL,
  `dia_chi` varchar(255) DEFAULT NULL,
  `vai_tro` enum('khach','admin') NOT NULL DEFAULT 'khach',
  `email_verified` tinyint(1) DEFAULT 0 COMMENT '0: Chưa xác minh, 1: Đã xác minh',
  `email_verified_at` datetime DEFAULT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `otp_expires_at` datetime DEFAULT NULL,
  `reset_token` varchar(64) DEFAULT NULL,
  `reset_expires_at` datetime DEFAULT NULL,
  `reset_otp` varchar(6) DEFAULT NULL,
  `reset_otp_expires` datetime DEFAULT NULL,
  `trang_thai` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Hoạt động, 0: Bị khóa',
  `ngay_tao` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nguoi_dung`
--

INSERT INTO `nguoi_dung` (`id`, `ho_ten`, `email`, `mat_khau`, `dien_thoai`, `dia_chi`, `vai_tro`, `email_verified`, `email_verified_at`, `otp`, `otp_expires_at`, `reset_token`, `reset_expires_at`, `reset_otp`, `reset_otp_expires`, `trang_thai`, `ngay_tao`) VALUES
(1, 'Admin', 'techshopNT@gmail.com', '$2y$10$sseAAOmARUnwvDgQk5ajWuliRMlZ71bMTV6NElq/1Nd/LGLhQwuAe', '0901234567', 'Số 1, Phố Công Nghệ, Hà Nội', 'admin', 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-11-26 16:08:45'),
(2, 'Nguyễn Văn A', 'vana@gmail.com', '$2y$10$iM3.Xn3O0wLgYcW5vY7gU.0d1o8e/mPqC1c3ZqW9o0b6mJ8yL7zY.', '0912345678', '45 Lê Lợi, Quận 1, TP.HCM', 'khach', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-11-26 16:08:45'),
(3, 'Trần Thị B', 'thib@yahoo.com', '$2y$10$iM3.Xn3O0wLgYcW5vY7gU.0d1o8e/mPqC1c3ZqW9o0b6mJ8yL7zY.', '0987654321', '100 Trần Phú, Hải Châu, Đà Nẵng', 'khach', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-11-26 16:08:45'),
(4, 'Lê Văn C', 'vanc@outlook.com', '$2y$10$iM3.Xn3O0wLgYcW5vY7gU.0d1o8e/mPqC1c3ZqW9o0b6mJ8yL7zY.', '0909888999', '22 Nguyễn Huệ, Quận Ninh Kiều, Cần Thơ', 'khach', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-11-26 16:08:45'),
(5, 'Phạm Thị D', 'thid@hotmail.com', '$2y$10$iM3.Xn3O0wLgYcW5vY7gU.0d1o8e/mPqC1c3ZqW9o0b6mJ8yL7zY.', '0977666555', '50 Hùng Vương, Nha Trang, Khánh Hòa', 'khach', 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '2025-11-26 16:08:45');

-- --------------------------------------------------------
INSERT INTO `nguoi_dung` (`id`, `ho_ten`, `email`, `mat_khau`, `dien_thoai`, `dia_chi`, `vai_tro`, `email_verified`, `email_verified_at`, `otp`, `otp_expires_at`, `reset_token`, `reset_expires_at`, `reset_otp`, `reset_otp_expires`, `trang_thai`, `ngay_tao`) 
VALUES (NULL, 'quyvuongbattu', 'nguyentrongthoi31@gmail.com', '$2y$10$fZFJ.JyewveYEOsSR82/juGo71yv0M0X3lhj/goLJmKclaXqKEEfe', NULL, NULL, 'khach', '1', '2025-11-28 08:45:46', NULL, NULL, NULL, NULL, NULL, NULL, '1', '2025-11-28 08:45:01');
INSERT INTO `nguoi_dung` (`id`, `ho_ten`, `email`, `mat_khau`, `dien_thoai`, `dia_chi`, `vai_tro`, `email_verified`, `email_verified_at`, `otp`, `otp_expires_at`, `reset_token`, `reset_expires_at`, `reset_otp`, `reset_otp_expires`, `trang_thai`, `ngay_tao`) 
VALUES (NULL, 'Dchong', 'vdinhtrong580@gmail.com', '$2y$10$TmGiltoOT0jLSiBGgIllqurY7Uxhym55rPiB9JzS0R61WKbUnE5rW', NULL, NULL, 'khach', '1', '2025-11-29 19:50:13', NULL, NULL, NULL, NULL, NULL, NULL, '1', '2025-11-29 19:49:51');
INSERT INTO `nguoi_dung` (`id`, `ho_ten`, `email`, `mat_khau`, `dien_thoai`, `dia_chi`, `vai_tro`, `email_verified`, `email_verified_at`, `otp`, `otp_expires_at`, `reset_token`, `reset_expires_at`, `reset_otp`, `reset_otp_expires`, `trang_thai`, `ngay_tao`) 
VALUES (NULL, 'minhhung', 'hung.nm.64cntt@ntu.edu.vn', '$2y$10$NjhMXNGZWoqpTuXDDSS8VO7D8ZJFKi9KJ2BApC6IDI668HvJeVI6q', NULL, NULL, 'khach', '1', '2025-11-29 19:55:19', NULL, NULL, NULL, NULL, NULL, NULL, '1', '2025-11-29 19:54:51');
INSERT INTO `nguoi_dung` (`id`, `ho_ten`, `email`, `mat_khau`, `dien_thoai`, `dia_chi`, `vai_tro`, `email_verified`, `email_verified_at`, `otp`, `otp_expires_at`, `reset_token`, `reset_expires_at`, `reset_otp`, `reset_otp_expires`, `trang_thai`, `ngay_tao`) 
VALUES (NULL, 'duykhanh', 'khanh.tdu.64cntt@ntu.edu.vn', '$2y$10$FBUZG/khUCYDs8.8ROJ8UOXFieDiN/iSJeVsxktAQMsRWNf9/1NO6', NULL, NULL, 'khach', '1', '2025-11-29 19:59:51', NULL, NULL, NULL, NULL, NULL, NULL, '1', '2025-11-29 19:57:25');
INSERT INTO `nguoi_dung` (`id`, `ho_ten`, `email`, `mat_khau`, `dien_thoai`, `dia_chi`, `vai_tro`, `email_verified`, `email_verified_at`, `otp`, `otp_expires_at`, `reset_token`, `reset_expires_at`, `reset_otp`, `reset_otp_expires`, `trang_thai`, `ngay_tao`) 
VALUES (NULL, 'minhchau', 'chau.hm.64cntt@ntu.edu.vn', '$2y$10$AMW3saXBRKwWJNvWWJ3pVOlpmMV45Ms68j/P5l/wMAVCKpTRYLMeq', NULL, NULL, 'khach', '1', '2025-11-29 20:05:57', NULL, NULL, NULL, NULL, NULL, NULL, '1', '2025-11-29 20:03:36');
-- Table structure for table `san_pham`
--

CREATE TABLE `san_pham` (
  `id` int(11) NOT NULL,
  `id_dm` int(11) NOT NULL,
  `ten_sp` varchar(255) NOT NULL,
  `gia` decimal(10,2) NOT NULL,
  `gia_khuyen_mai` decimal(10,2) DEFAULT NULL,
  `so_luong_ton` int(11) NOT NULL DEFAULT 0,
  `hinh_anh` varchar(255) DEFAULT NULL COMMENT 'Đường dẫn file ảnh',
  `mo_ta_ngan` varchar(500) DEFAULT NULL,
  `chi_tiet` longtext DEFAULT NULL,
  `ngay_nhap` datetime NOT NULL DEFAULT current_timestamp(),
  `luot_xem` int(11) NOT NULL DEFAULT 0,
  `trang_thai` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1: Bán, 0: Ngừng bán'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `san_pham`
--

INSERT INTO `san_pham` (`id`, `id_dm`, `ten_sp`, `gia`, `gia_khuyen_mai`, `so_luong_ton`, `hinh_anh`, `mo_ta_ngan`, `chi_tiet`, `ngay_nhap`, `luot_xem`, `trang_thai`) VALUES
(101, 1, 'Laptop Gaming ASUS ROG Strix G16', 35000000.00, 32990000.00, 15, 'rog_strix_g16.jpg', 'Core i9, RAM 32GB, RTX 4080, Màn hình 240Hz', 'Chi tiết cấu hình: Core i9-13980HX, 32GB DDR5, 1TB SSD, RTX 4080 12GB, 16-inch QHD+ 240Hz.', '2025-09-01 10:00:00', 520, 1),
(102, 1, 'Laptop Gaming Acer Nitro 5', 21000000.00, 19990000.00, 25, 'acer_nitro_5.jpg', 'Core i5, RAM 16GB, RTX 3050, Màn hình 144Hz', 'Chi tiết cấu hình: Core i5-12500H, 16GB DDR4, 512GB SSD, RTX 3050 4GB, 15.6-inch FHD 144Hz.', '2025-09-05 11:30:00', 480, 1),
(103, 2, 'Laptop Văn Phòng Dell Inspiron 14', 18000000.00, 16500000.00, 30, 'dell_inspiron_14.jpg', 'Core i7, RAM 16GB, SSD 512GB, Màn hình OLED', 'Máy tính xách tay mỏng nhẹ, hiệu năng cao cho công việc, Core i7-1355U.', '2025-09-10 14:00:00', 350, 1),
(104, 2, 'Laptop Văn Phòng HP Pavilion Aero 13', 15500000.00, NULL, 18, 'hp_aero_13.jpg', 'Ryzen 5, RAM 8GB, Siêu nhẹ chỉ 0.9kg', 'Thiết kế cao cấp, thời lượng pin dài, Ryzen 5 7535U, 8GB DDR4, 512GB SSD.', '2025-09-15 09:45:00', 290, 1),
(105, 3, 'PC Gaming T-Rex i7 4070', 45000000.00, 42000000.00, 10, 'pc_t_rex.jpg', 'PC chiến game mạnh mẽ: i7-14700K, RTX 4070 12GB', 'Cấu hình khủng, tản nhiệt nước AIO, 32GB RAM DDR5.', '2025-09-20 16:15:00', 600, 1),
(106, 3, 'PC Văn Phòng Mini i3', 9500000.00, NULL, 40, 'pc_mini_i3.jpg', 'PC cỡ nhỏ, Core i3-13100, 8GB RAM, SSD 256GB', 'Máy tính nhỏ gọn cho các tác vụ văn phòng cơ bản.', '2025-09-25 08:30:00', 150, 1),
(107, 4, 'Màn Hình Gaming Samsung Odyssey G7 27 inch', 14000000.00, 12990000.00, 50, 'samsung_g7.jpg', '2K QHD, 240Hz, 1ms, Cong 1000R', 'Màn hình cao cấp cho trải nghiệm gaming tuyệt đỉnh.', '2025-10-01 13:00:00', 750, 1),
(108, 4, 'Màn Hình Văn Phòng LG 24 inch IPS', 3500000.00, NULL, 65, 'lg_ips_24.jpg', 'FHD, Tấm nền IPS, Thiết kế không viền', 'Màu sắc chính xác, góc nhìn rộng, lý tưởng cho công việc.', '2025-10-05 15:20:00', 310, 1),
(109, 5, 'Bàn Phím Cơ AKKO 3098B Plus Multi-mode', 2900000.00, 2750000.00, 70, 'akko_3098b.jpg', '98 phím, Kết nối 3 chế độ (dây, Bluetooth, 2.4GHz), Switch Akko V3', 'Phím cơ chất lượng cao, LED RGB.', '2025-10-10 10:40:00', 820, 1),
(110, 5, 'Bàn Phím Cơ Fuhlen D87', 1200000.00, NULL, 80, 'fuhlen_d87.jpg', '87 phím TKL, Blue Switch, LED Rainbow', 'Phím cơ giá rẻ, bền bỉ cho người mới.', '2025-10-15 09:00:00', 450, 1),
(111, 6, 'Chuột Gaming Logitech G Pro X Superlight', 3800000.00, 3590000.00, 45, 'g_pro_x_superlight.jpg', 'Chuột không dây, siêu nhẹ 63g, Cảm biến HERO 25K', 'Thiết kế công thái học, chuyên nghiệp cho game thủ eSports.', '2025-10-20 11:10:00', 950, 1),
(112, 6, 'Chuột Gaming Razer DeathAdder V2', 1500000.00, 1390000.00, 55, 'razer_deathadder_v2.jpg', 'Chuột có dây, Cảm biến quang học 20K DPI', 'Chuột bán chạy nhất mọi thời đại của Razer.', '2025-10-25 14:50:00', 700, 1),
(113, 7, 'Tai Nghe Sony WH-1000XM5', 7500000.00, 6990000.00, 20, 'sony_xm5.jpg', 'Chống ồn chủ động (ANC) hàng đầu, Pin 30 giờ', 'Tai nghe chụp tai cao cấp, âm thanh chất lượng.', '2025-11-01 10:00:00', 400, 1),
(114, 7, 'Tai Nghe Gaming HyperX Cloud Alpha', 2300000.00, 2190000.00, 35, 'hyperx_alpha.jpg', 'Tai nghe có dây, Driver Dual Chamber, Micro khử ồn', 'Âm thanh chi tiết, thoải mái khi đeo lâu.', '2025-11-05 13:20:00', 580, 1),
(115, 8, 'SSD Samsung 990 Pro 1TB NVMe Gen4', 4000000.00, 3750000.00, 90, 'samsung_990pro_1tb.jpg', 'Tốc độ đọc/ghi siêu nhanh, Dùng cho PC/Laptop', 'SSD hiệu năng cao nhất hiện nay.', '2025-11-10 09:30:00', 1100, 1),
(116, 8, 'SSD Kingston NV2 500GB NVMe Gen4', 1500000.00, NULL, 120, 'kingston_nv2_500gb.jpg', 'SSD giá rẻ, hiệu năng tốt, Tốc độ 3500MB/s', 'Ổ cứng thể rắn dung lượng vừa, phù hợp nâng cấp.', '2025-11-15 15:00:00', 650, 1),
(117, 9, 'RAM Corsair Vengeance RGB 32GB (2x16GB) DDR5 6000MHz', 4800000.00, 4500000.00, 50, 'corsair_32gb_ddr5.jpg', 'Tốc độ cao 6000MHz, có LED RGB đẹp mắt', 'Bộ nhớ RAM cho các hệ thống PC cao cấp.', '2025-11-20 11:00:00', 380, 1),
(118, 9, 'RAM Kingston Fury Beast 16GB DDR4 3200MHz', 1800000.00, NULL, 75, 'kingston_16gb_ddr4.jpg', 'Hiệu năng ổn định, không kén main', 'RAM phổ thông, nâng cấp dễ dàng.', '2025-11-25 14:10:00', 490, 1),
(119, 10, 'VGA ASUS ROG Strix GeForce RTX 4090 OC 24GB', 75000000.00, 72000000.00, 5, 'rtx_4090.jpg', 'Card đồ họa mạnh nhất, 24GB GDDR6X', 'Dùng cho các tác vụ AI, Render 3D và Gaming 8K.', '2025-12-01 10:20:00', 1200, 1),
(120, 10, 'VGA Gigabyte RTX 3060 Ti Eagle 8GB', 12000000.00, 11500000.00, 25, 'rtx_3060_ti.jpg', 'Card tầm trung, hiệu năng/giá tốt', 'Phù hợp chơi game FHD/2K.', '2025-12-05 16:30:00', 880, 1),
(121, 11, 'CPU Intel Core i5-14600K', 8500000.00, 7990000.00, 30, 'i5_14600k.jpg', 'Bộ vi xử lý 14 nhân, hiệu năng cao', 'CPU thế hệ mới nhất của Intel.', '2025-12-10 09:50:00', 620, 1),
(122, 11, 'CPU AMD Ryzen 7 7700X', 9000000.00, NULL, 28, 'r7_7700x.jpg', 'Bộ vi xử lý 8 nhân 16 luồng, kiến trúc Zen 4', 'CPU hiệu suất đa nhiệm và gaming tốt.', '2025-12-15 14:00:00', 550, 1),
(123, 12, 'Mainboard ASUS ROG Strix Z790-E Gaming WIFI II', 15000000.00, 14500000.00, 15, 'z790_e.jpg', 'Hỗ trợ CPU Intel đời 13/14, RAM DDR5', 'Bo mạch chủ cao cấp cho hệ thống gaming.', '2025-12-20 11:20:00', 410, 1),
(124, 12, 'Mainboard Gigabyte B650 Gaming X AX', 5500000.00, NULL, 22, 'b650_gaming.jpg', 'Hỗ trợ CPU AMD Ryzen 7000 Series', 'Bo mạch chủ tầm trung, đầy đủ tính năng.', '2025-12-25 15:40:00', 300, 1),
(125, 13, 'Webcam Logitech C922 Pro Stream', 2500000.00, 2290000.00, 40, 'logitech_c922.jpg', 'Độ phân giải Full HD 1080p, 60fps', 'Webcam chuyên dụng cho Streamer và họp trực tuyến.', '2026-01-01 10:00:00', 250, 1),
(126, 14, 'Phần Mềm Microsoft Office Home and Student 2021', 3000000.00, NULL, 100, 'office_2021.jpg', 'Bản quyền vĩnh viễn Word, Excel, PowerPoint', 'Phần mềm văn phòng thiết yếu.', '2026-01-05 12:30:00', 180, 1),
(127, 15, 'Router Wifi 6 ASUS AX5400', 4000000.00, 3800000.00, 30, 'router_ax5400.jpg', 'Tốc độ cực nhanh, phủ sóng mạnh mẽ', 'Thiết bị mạng cao cấp cho gia đình và văn phòng.', '2026-01-10 15:15:00', 330, 1),
(128, 16, 'Sạc Dự Phòng Anker PowerCore 20000mAh', 1200000.00, 1090000.00, 50, 'anker_powercore.jpg', 'Dung lượng lớn, sạc nhanh PD 20W', 'Sạc được nhiều lần cho điện thoại, máy tính bảng.', '2026-01-15 11:45:00', 500, 1),
(129, 17, 'Loa Bluetooth JBL Flip 6', 3200000.00, 2990000.00, 35, 'jbl_flip6.jpg', 'Âm thanh bass mạnh mẽ, chống nước IPX7', 'Loa di động, pin 12 giờ.', '2026-01-20 14:20:00', 450, 1),
(130, 18, 'Máy In Canon Pixma G3010', 4500000.00, 4200000.00, 15, 'canon_g3010.jpg', 'Máy in đa chức năng, có hệ thống mực in liên tục', 'In, Scan, Copy, kết nối Wifi.', '2026-01-25 09:00:00', 210, 1),
(131, 1, 'Laptop Gaming MSI Katana B13VEK', 28000000.00, 25990000.00, 12, 'msi_katana.jpg', 'Core i7, RAM 16GB, RTX 4050', 'Màn hình 144Hz, SSD 1TB.', '2026-02-01 10:00:00', 400, 1),
(132, 2, 'Laptop Văn Phòng Lenovo ThinkPad X1 Carbon Gen 11', 32000000.00, NULL, 10, 'lenovo_x1.jpg', 'Core i7, RAM 32GB, Siêu bền', 'Laptop doanh nhân cao cấp.', '2026-02-05 11:30:00', 320, 1),
(133, 4, 'Màn Hình Cong Dell S3222DGM 32 inch', 8500000.00, 7990000.00, 30, 'dell_s3222dgm.jpg', '2K QHD, 165Hz, Cong 1800R', 'Màn hình lớn cho công việc và giải trí.', '2026-02-10 14:00:00', 550, 1),
(134, 5, 'Bàn Phím Cơ Logitech G Pro TKL', 3500000.00, 3290000.00, 50, 'logitech_pro_tkl.jpg', '87 phím TKL, Switch GX Blue, RGB LIGHTSYNC', 'Phím cơ chuyên nghiệp, nhỏ gọn.', '2026-02-15 09:45:00', 650, 1),
(135, 6, 'Chuột Không Dây Logitech MX Master 3S', 2800000.00, 2590000.00, 40, 'mx_master_3s.jpg', 'Thiết kế công thái học, cảm biến 8000 DPI', 'Chuột đa năng cho dân đồ họa, lập trình.', '2026-02-20 16:15:00', 780, 1),
(136, 7, 'Tai Nghe True Wireless Apple AirPods Pro 2', 6500000.00, 5990000.00, 25, 'airpods_pro_2.jpg', 'Chống ồn thông minh, Âm thanh không gian', 'Tai nghe nhét tai cao cấp.', '2026-02-25 08:30:00', 850, 1),
(137, 8, 'SSD WD Black SN850X 2TB NVMe Gen4', 7000000.00, 6500000.00, 20, 'wd_sn850x_2tb.jpg', 'Tốc độ cực nhanh, dung lượng lớn', 'Phù hợp cho game thủ và sáng tạo nội dung.', '2026-03-01 13:00:00', 450, 1),
(138, 9, 'RAM G.Skill Trident Z5 RGB 64GB (2x32GB) DDR5 6400MHz', 9000000.00, NULL, 15, 'gskill_z5_64gb.jpg', 'Hiệu năng cực cao, thiết kế đẹp', 'Dành cho PC Workstation và Gaming khủng.', '2026-03-05 15:20:00', 280, 1),
(139, 10, 'VGA AMD Radeon RX 7900 XTX 24GB', 35000000.00, 33000000.00, 8, 'rx_7900xtx.jpg', 'Đối thủ trực tiếp của RTX 4080', 'Card đồ họa AMD RDNA 3 mạnh mẽ.', '2026-03-10 10:40:00', 600, 1),
(140, 11, 'CPU Intel Core i9-14900K', 15000000.00, 14500000.00, 10, 'i9_14900k.jpg', 'Bộ vi xử lý mạnh nhất thế giới', 'Dành cho các tác vụ nặng nhất.', '2026-03-15 09:00:00', 950, 1),
(141, 15, 'Mesh Wifi TP-Link Deco X50 (3-Pack)', 7000000.00, 6500000.00, 18, 'deco_x50.jpg', 'Hệ thống Wifi Mesh diện tích lớn, chuẩn Wifi 6', 'Phủ sóng toàn bộ nhà nhiều tầng.', '2026-03-20 11:10:00', 420, 1),
(142, 1, 'Laptop Gaming Dell Alienware m18', 55000000.00, 52000000.00, 8, 'alienware_m18.jpg', 'i9, RTX 4090, Màn hình 18-inch QHD+', 'Laptop gaming thay thế PC.', '2026-03-25 14:50:00', 700, 1),
(143, 2, 'Laptop MacBook Air M2 13 inch', 25000000.00, 23990000.00, 20, 'macbook_air_m2.jpg', 'Chip Apple M2, RAM 8GB, SSD 512GB', 'Thiết kế đẹp, hiệu năng tối ưu.', '2026-04-01 10:00:00', 800, 1),
(144, 4, 'Màn Hình Chuyên Đồ Họa Dell UltraSharp U2723QE', 18000000.00, 16990000.00, 15, 'dell_u2723qe.jpg', '4K UHD, USB-C Power Delivery 90W', 'Màn hình màu sắc chuẩn xác cho thiết kế.', '2026-04-05 13:20:00', 520, 1),
(145, 5, 'Bàn Phím Cơ Razer BlackWidow V4 Pro', 5000000.00, 4690000.00, 25, 'razer_v4_pro.jpg', 'Full size, Razer Green Switch, Dial đa năng', 'Bàn phím cơ cao cấp, nhiều tính năng.', '2026-04-10 09:30:00', 580, 1),
(146, 6, 'Chuột Gaming Corsair Dark Core RGB Pro SE', 2000000.00, 1890000.00, 30, 'corsair_dark_core.jpg', 'Không dây, sạc không dây Qi, Cảm biến 18K DPI', 'Chuột gaming không dây, pin trâu.', '2026-04-15 15:00:00', 450, 1),
(147, 7, 'Tai Nghe Chụp Tai Marshall Major IV', 4500000.00, 4200000.00, 20, 'marshall_major_iv.jpg', 'Thiết kế cổ điển, Pin 80 giờ, sạc không dây', 'Tai nghe thời trang, âm thanh ấm áp.', '2026-04-20 11:00:00', 300, 1),
(148, 8, 'SSD Crucial P5 Plus 1TB NVMe Gen4', 3500000.00, NULL, 50, 'crucial_p5_1tb.jpg', 'Hiệu năng cao, Tốc độ 6600MB/s', 'SSD đáng tin cậy.', '2026-04-25 14:10:00', 400, 1),
(149, 9, 'RAM PNY XLR8 Gaming 16GB DDR4 3600MHz', 2000000.00, 1850000.00, 45, 'pny_16gb_ddr4.jpg', 'Có tản nhiệt, tốc độ 3600MHz', 'RAM cho các cấu hình tầm trung.', '2026-05-01 10:20:00', 350, 1),
(150, 10, 'VGA Zotac RTX 4060 Ti Twin Edge 8GB', 10500000.00, 9900000.00, 30, 'rtx_4060_ti.jpg', 'Card đồ họa tầm trung mới nhất', 'Hiệu năng ray tracing tốt.', '2026-05-05 16:30:00', 750, 1),
(151, 11, 'CPU AMD Ryzen 5 7600', 6500000.00, 6200000.00, 35, 'r5_7600.jpg', 'Bộ vi xử lý 6 nhân, hiệu năng/giá tốt', 'CPU phổ thông AM5.', '2026-05-10 09:50:00', 480, 1),
(152, 12, 'Mainboard MSI MAG B760 TOMAHAWK WIFI', 4800000.00, NULL, 28, 'b760_tomahawk.jpg', 'Hỗ trợ CPU Intel đời 12/13/14, RAM DDR5', 'Bo mạch chủ tầm trung chất lượng.', '2026-05-15 14:00:00', 350, 1),
(153, 14, 'Phần Mềm Adobe Creative Cloud (1 năm)', 10000000.00, 9500000.00, 15, 'adobe_cc.jpg', 'Bộ công cụ thiết kế chuyên nghiệp', 'Bản quyền 1 năm cho tất cả ứng dụng.', '2026-05-20 11:20:00', 100, 1),
(154, 1, 'Laptop Gaming HP Victus 15', 17000000.00, 15990000.00, 22, 'hp_victus_15.jpg', 'Core i5, RTX 3050, Màn hình 144Hz', 'Laptop gaming giá phải chăng.', '2026-05-25 15:40:00', 410, 1),
(155, 2, 'Laptop Văn Phòng Asus ZenBook 14 OLED', 23000000.00, 21990000.00, 18, 'asus_zenbook_14.jpg', 'Core i7, Màn hình OLED, Thiết kế mỏng nhẹ', 'Hiển thị tuyệt đẹp cho công việc sáng tạo.', '2026-06-01 10:00:00', 500, 1),
(156, 4, 'Màn Hình UltraWide LG 34WP65G-B 34 inch', 9000000.00, NULL, 12, 'lg_ultrawide.jpg', 'Độ phân giải 21:9 UW-QHD, Tấm nền IPS', 'Màn hình rộng cho đa nhiệm và làm việc.', '2026-06-05 12:30:00', 350, 1),
(157, 5, 'Bàn Phím Cơ Redragon K617 Fizz', 700000.00, 650000.00, 60, 'redragon_k617.jpg', '60% layout, Hot-swap, Red Switch', 'Bàn phím cơ mini, giá rẻ.', '2026-06-10 15:15:00', 600, 1),
(158, 6, 'Chuột Gaming SteelSeries Aerox 3 Wireless', 2500000.00, 2350000.00, 35, 'aerox_3.jpg', 'Siêu nhẹ 68g, Không dây, Chống nước IP54', 'Thiết kế lưới độc đáo, tốc độ phản hồi nhanh.', '2026-06-15 11:45:00', 400, 1),
(159, 7, 'Tai Nghe Gaming E-DRA EH410', 500000.00, NULL, 50, 'edra_eh410.jpg', 'Âm thanh 7.1 giả lập, Có LED RGB', 'Tai nghe gaming giá rẻ.', '2026-06-20 14:20:00', 300, 1),
(160, 8, 'SSD Adata XPG Spectrix S40G 1TB RGB', 3200000.00, 3000000.00, 40, 'adata_s40g.jpg', 'SSD có LED RGB, tốc độ 3500MB/s', 'Vừa nhanh vừa đẹp.', '2026-06-25 09:00:00', 550, 1),
(161, 9, 'RAM Colorful Battle-AX 8GB DDR4 3200MHz', 900000.00, NULL, 70, 'colorful_8gb.jpg', 'RAM giá rẻ, hiệu năng ổn định.', 'RAM 8GB, 3200MHz, có tản nhiệt.', '2026-07-01 10:00:00', 250, 1),
(162, 10, 'VGA Inno3D RTX 4070 Twin X2 12GB', 17000000.00, 16500000.00, 18, 'rtx_4070.jpg', 'Card đồ họa hiệu năng cao cho 2K gaming.', 'Thông số: 12GB GDDR6X, Tốc độ Boost Clock cao.', '2026-07-05 11:30:00', 580, 1),
(163, 11, 'CPU Intel Core i7-13700F', 10000000.00, 9500000.00, 25, 'i7_13700f.jpg', 'Bộ vi xử lý 16 nhân, không tích hợp đồ họa', 'Thông số: 8P+8E cores, 24 luồng, Max Turbo 5.2GHz.', '2026-07-10 14:00:00', 450, 1),
(164, 12, 'Mainboard ASUS Prime B660M-A WIFI D4', 3500000.00, NULL, 30, 'b660m_a.jpg', 'Hỗ trợ CPU Intel đời 12/13, RAM DDR4', 'Tính năng: Khe M.2 PCIe 4.0, LAN 2.5Gb.', '2026-07-15 09:45:00', 380, 1),
(165, 13, 'Webcam Rapoo C260 Full HD', 500000.00, 450000.00, 50, 'rapoo_c260.jpg', 'Full HD 1080p, tích hợp mic', 'Độ phân giải video: 1920x1080 @ 30fps, góc nhìn rộng.', '2026-07-20 16:15:00', 200, 1),
(166, 15, 'Bộ Phát Wifi Di Động TP-Link M7350', 2000000.00, 1800000.00, 20, 'tp_link_m7350.jpg', 'Phát Wifi từ Sim 4G, pin 8 giờ', 'Tốc độ 4G LTE-A Cat.4, hỗ trợ 10 thiết bị.', '2026-07-25 08:30:00', 150, 1),
(167, 16, 'Sạc Dự Phòng Samsung 10000mAh 25W', 800000.00, 750000.00, 45, 'samsung_10000mah.jpg', 'Sạc siêu nhanh 25W, nhỏ gọn', 'Công nghệ Super Fast Charging, bảo vệ đa lớp.', '2026-08-01 13:00:00', 300, 1),
(168, 17, 'Loa Bluetooth Sony SRS-XB13', 1500000.00, 1390000.00, 30, 'sony_xb13.jpg', 'Âm thanh Extra Bass, Chống nước IP67', 'Pin 16 giờ, công nghệ DSP, âm thanh 360 độ.', '2026-08-05 15:20:00', 420, 1),
(169, 18, 'Máy In HP LaserJet Pro M102a', 3000000.00, 2800000.00, 10, 'hp_m102a.jpg', 'Máy in laser trắng đen, tốc độ in nhanh', 'Tốc độ in: 22 trang/phút, độ phân giải 600x600 dpi.', '2026-08-10 10:40:00', 180, 1),
(170, 19, 'Ốp Lưng Điện Thoại iPhone 15 Pro Max', 200000.00, NULL, 100, 'op_iphone_15.jpg', 'Ốp lưng silicon bảo vệ', 'Chất liệu silicon cao cấp, chống sốc, ôm sát máy.', '2026-08-15 09:00:00', 150, 0),
(171, 20, 'Đồng Hồ Thông Minh Samsung Galaxy Watch 6 Classic', 8000000.00, 7500000.00, 15, 'galaxy_watch_6.jpg', 'Mặt xoay vật lý, theo dõi sức khỏe chuyên sâu', 'Mặt 47mm, BIA Sensor, GPS, Wear OS.', '2026-08-20 11:10:00', 300, 1),
(172, 1, 'Laptop Gaming Gigabyte AORUS 17H', 40000000.00, 38000000.00, 10, 'aorus_17h.jpg', 'Core i7, RTX 4070, Màn 240Hz', 'Thông số: i7-13700H, RTX 4070 8GB, 17.3-inch QHD 240Hz.', '2026-08-25 14:50:00', 500, 1),
(173, 2, 'Laptop HP EliteBook 840 G10', 28000000.00, NULL, 15, 'hp_elitebook.jpg', 'Core i5, RAM 16GB, Siêu bảo mật', 'Thiết kế kim loại, tính năng HP Sure View.', '2026-09-01 10:00:00', 350, 1),
(174, 3, 'PC Đồ Họa Workstation Xeon E5', 25000000.00, 23500000.00, 7, 'pc_workstation.jpg', 'Xeon E5, RAM 64GB, Quadro P2000', 'Chuyên Render: CPU 12 Core, 64GB ECC RAM, SSD NVMe.', '2026-09-05 13:20:00', 400, 1),
(175, 4, 'Màn Hình ViewSonic VX3276-2K-MHD 32 inch', 6000000.00, 5500000.00, 40, 'viewsonic_vx3276.jpg', '2K QHD, Tấm nền IPS, Thiết kế siêu mỏng', 'Độ phân giải 2560x1440, viền siêu mỏng.', '2026-09-10 09:30:00', 380, 1),
(176, 6, 'Chuột Không Dây Xiaomi Silent Mouse', 400000.00, NULL, 60, 'xiaomi_silent.jpg', 'Chuột văn phòng không dây, nút bấm im lặng', 'Kết nối 2.4GHz, 1200 DPI, thiết kế công thái học.', '2026-09-15 15:00:00', 300, 1),
(177, 7, 'Tai Nghe Bluetooth Sennheiser Momentum 4 Wireless', 8500000.00, 7990000.00, 10, 'sennheiser_m4.jpg', 'Âm thanh Hi-Fi, Pin 60 giờ', 'ANC cao cấp, chất lượng âm thanh Audiophile.', '2026-09-20 11:00:00', 250, 1),
(178, 8, 'SSD Samsung 870 EVO 1TB SATA III', 2500000.00, 2300000.00, 55, 'samsung_870_evo.jpg', 'Ổ cứng SATA phổ thông, tốc độ 560MB/s', 'Độ bền 600 TBW, Controller Samsung V-NAND.', '2026-09-25 14:10:00', 450, 1),
(179, 10, 'VGA PNY RTX A4000 16GB', 20000000.00, NULL, 5, 'pny_a4000.jpg', 'Card đồ họa chuyên nghiệp cho thiết kế', 'Dành cho Workstation. GPU NVIDIA Ampere, 16GB GDDR6 ECC.', '2026-10-01 10:20:00', 300, 1),
(180, 20, 'Đồng Hồ Thông Minh Apple Watch Ultra 2', 22000000.00, 20990000.00, 10, 'apple_watch_ultra_2.jpg', 'Thiết kế bền bỉ, GPS tần số kép', 'Chất liệu Titanium, màn hình sáng 3000 nits, WR100.', '2026-10-05 16:30:00', 500, 1);

-- --------------------------------------------------------

--
-- Table structure for table `thong_bao`
--

CREATE TABLE `thong_bao` (
  `id` int(11) NOT NULL,
  `id_nguoi_dung` int(11) NOT NULL COMMENT 'Khóa ngoại tới nguoi_dung',
  `loai_thong_bao` varchar(50) NOT NULL COMMENT 'Loại thông báo (e.g., price_drop, back_in_stock)',
  `noi_dung` text NOT NULL COMMENT 'Nội dung thông báo',
  `da_doc` tinyint(1) NOT NULL DEFAULT 0 COMMENT 'Trạng thái đã đọc (0: Chưa, 1: Rồi)',
  `ngay_tao` datetime NOT NULL DEFAULT current_timestamp() COMMENT 'Thời gian tạo thông báo'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `thong_ke_doanh_thu`
--

CREATE TABLE `thong_ke_doanh_thu` (
  `id` int(11) NOT NULL,
  `ngay_thong_ke` date NOT NULL COMMENT 'Ngày thống kê (hoặc đầu tháng)',
  `tong_doanh_thu` decimal(14,2) NOT NULL COMMENT 'Tổng doanh thu (Gross Revenue)',
  `tong_don_hang` int(11) NOT NULL COMMENT 'Tổng số lượng đơn hàng hoàn thành',
  `cap_nhat_lan_cuoi` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp() COMMENT 'Lần cuối dữ liệu được cập nhật'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_order_product` (`id_don_hang`,`id_san_pham`),
  ADD KEY `id_san_pham` (`id_san_pham`);

--
-- Indexes for table `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uc_user_product` (`id_nguoi_dung`,`id_san_pham`),
  ADD KEY `fk_cart_product` (`id_san_pham`);

--
-- Indexes for table `danh_muc`
--
ALTER TABLE `danh_muc`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten_dm` (`ten_dm`);

--
-- Indexes for table `danh_sach_yeu_thich`
--
ALTER TABLE `danh_sach_yeu_thich`
  ADD PRIMARY KEY (`id_nguoi_dung`,`id_san_pham`),
  ADD KEY `fk_wishlist_product` (`id_san_pham`);

--
-- Indexes for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_khach_hang` (`id_khach_hang`);

--
-- Indexes for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `email_2` (`email`);

--
-- Indexes for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ten_sp` (`ten_sp`),
  ADD KEY `id_dm` (`id_dm`);

--
-- Indexes for table `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_read` (`id_nguoi_dung`,`da_doc`);

--
-- Indexes for table `thong_ke_doanh_thu`
--
ALTER TABLE `thong_ke_doanh_thu`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ngay_thong_ke` (`ngay_thong_ke`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `danh_muc`
--
ALTER TABLE `danh_muc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `don_hang`
--
ALTER TABLE `don_hang`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `nguoi_dung`
--
ALTER TABLE `nguoi_dung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `san_pham`
--
ALTER TABLE `san_pham`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=181;

--
-- AUTO_INCREMENT for table `thong_bao`
--
ALTER TABLE `thong_bao`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `thong_ke_doanh_thu`
--
ALTER TABLE `thong_ke_doanh_thu`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `chi_tiet_don_hang`
--
ALTER TABLE `chi_tiet_don_hang`
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_1` FOREIGN KEY (`id_don_hang`) REFERENCES `don_hang` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `chi_tiet_don_hang_ibfk_2` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `chi_tiet_gio_hang`
--
ALTER TABLE `chi_tiet_gio_hang`
  ADD CONSTRAINT `fk_cart_product` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_cart_user` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `danh_sach_yeu_thich`
--
ALTER TABLE `danh_sach_yeu_thich`
  ADD CONSTRAINT `fk_wishlist_product` FOREIGN KEY (`id_san_pham`) REFERENCES `san_pham` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_wishlist_user` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `don_hang`
--
ALTER TABLE `don_hang`
  ADD CONSTRAINT `don_hang_ibfk_1` FOREIGN KEY (`id_khach_hang`) REFERENCES `nguoi_dung` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `san_pham`
--
ALTER TABLE `san_pham`
  ADD CONSTRAINT `san_pham_ibfk_1` FOREIGN KEY (`id_dm`) REFERENCES `danh_muc` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `thong_bao`
--
ALTER TABLE `thong_bao`
  ADD CONSTRAINT `fk_notification_user` FOREIGN KEY (`id_nguoi_dung`) REFERENCES `nguoi_dung` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */; 
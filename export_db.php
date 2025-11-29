<?php
// =========================
// EXPORT DATABASE Y HỆT PHPMyAdmin
// =========================

// === CONFIG ===
$host     = "127.0.0.1";
$user     = "root";
$pass     = "";  // Nếu MySQL có password thì điền vào
$database = "techshop";

// === Đường dẫn output ===
$backup_file = __DIR__ . "/database/techshop.sql";

// === Kết nối MySQL ===
$conn = new mysqli($host, $user, $pass, $database);
if ($conn->connect_error) {
    die("Lỗi kết nối MySQL: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// ========================================
// LẤY DANH SÁCH TẤT CẢ BẢNG TRONG DATABASE
// ========================================
$tables = [];
$res = $conn->query("SHOW TABLES");
while ($row = $res->fetch_array()) {
    $tables[] = $row[0];
}

// ========================================
// KHỞI TẠO HEADER GIỐNG PHPMyAdmin EXPORT
// ========================================
$sql = "";
$sql .= "-- phpMyAdmin SQL Export\n";
$sql .= "-- Host: 127.0.0.1\n";
$sql .= "-- Database: `$database`\n";
$sql .= "-- Exported at: " . date("Y-m-d H:i:s") . "\n\n";
$sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
$sql .= "SET time_zone = \"+00:00\";\n\n";
$sql .= "START TRANSACTION;\n";
$sql .= "/*!40101 SET NAMES utf8mb4 */;\n\n";

// ========================================
// XUẤT TỪNG BẢNG: DROP → CREATE → INSERT
// ========================================
foreach ($tables as $table) {

    // ----- DROP TABLE -----
    $sql .= "-- ------------------------------------\n";
    $sql .= "-- Structure for table `$table`\n";
    $sql .= "-- ------------------------------------\n\n";

    $sql .= "DROP TABLE IF EXISTS `$table`;\n\n";

    // ----- CREATE TABLE -----
    $res = $conn->query("SHOW CREATE TABLE `$table`");
    $row = $res->fetch_assoc();
    $sql .= $row["Create Table"] . ";\n\n";

    // ----- SELECT DATA -----
    $sql .= "-- Dumping data for table `$table`\n";

    $result = $conn->query("SELECT * FROM `$table`");
    if ($result->num_rows > 0) {

        // Lưu hết rows vào array
        $rows = [];
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }

        // Lấy danh sách cột
        $cols = array_keys($rows[0]);

        // Mở đầu câu INSERT
        $sql .= "INSERT INTO `$table` (`" . implode("`,`", $cols) . "`) VALUES\n";

        // Build từng row
        $total = count($rows);
        foreach ($rows as $index => $data) {
            $vals = array_values($data);

            // Escape dữ liệu
            foreach ($vals as &$v) {
                if ($v === null) {
                    $v = "NULL";
                } else {
                    $v = "'" . $conn->real_escape_string($v) . "'";
                }
            }

            // Thêm row
            $sql .= "  (" . implode(",", $vals) . ")";

            // Dòng cuối kết thúc bằng ;
            if ($index < $total - 1) {
                $sql .= ",\n";
            } else {
                $sql .= ";\n\n";
            }
        }
    }

    $sql .= "\n";
}

// ========================================
// KẾT THÚC FILE
// ========================================
$sql .= "COMMIT;\n";

// Ghi ra file
file_put_contents($backup_file, $sql);

echo "EXPORT DATABASE THÀNH CÔNG!";

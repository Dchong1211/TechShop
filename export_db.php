<?php
// =========================
// EXPORT DATABASE Y HỆT PHPMYADMIN
// =========================

// === CONFIG ===
$host     = "127.0.0.1";
$user     = "root";
$pass     = ""; // nếu MySQL có password thì điền vào
$database = "techshop";

// Đường dẫn file để ghi SQL
$backup_file = __DIR__ . "/database/techshop.sql";

// Kết nối DB
$conn = new mysqli($host, $user, $pass, $database);
if ($conn->connect_error) {
    die("Lỗi kết nối MySQL: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");

// ==================
// LẤY DANH SÁCH BẢNG
// ==================
$tables = [];
$res = $conn->query("SHOW TABLES");
while ($row = $res->fetch_array()) {
    $tables[] = $row[0];
}

$sql = "";
$sql .= "-- phpMyAdmin-like SQL Export\n";
$sql .= "-- Host: 127.0.0.1\n";
$sql .= "-- Database: `$database`\n\n";
$sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
$sql .= "SET time_zone = \"+00:00\";\n\n";
$sql .= "START TRANSACTION;\n\n";
$sql .= "/*!40101 SET NAMES utf8mb4 */;\n\n";

// ==================
// XUẤT TỪNG BẢNG
// ==================
foreach ($tables as $table) {

    // --- DROP TABLE ---
    $sql .= "-- -------------------------------\n";
    $sql .= "-- Structure for table `$table`\n";
    $sql .= "-- -------------------------------\n";
    $sql .= "DROP TABLE IF EXISTS `$table`;\n\n";

    // --- CREATE TABLE ---
    $res = $conn->query("SHOW CREATE TABLE `$table`");
    $row = $res->fetch_assoc();
    $sql .= $row["Create Table"] . ";\n\n";

    // --- INSERT DATA ---
    $sql .= "-- Dumping data for table `$table`\n";

    $result = $conn->query("SELECT * FROM `$table`");
    if ($result->num_rows > 0) {

        while ($data = $result->fetch_assoc()) {
            $cols = array_keys($data);
            $vals = array_values($data);

            // Convert dữ liệu
            foreach ($vals as &$v) {
                if ($v === null) {
                    $v = "NULL";
                } else {
                    $v = "'" . $conn->real_escape_string($v) . "'";
                }
            }

            $sql .= "INSERT INTO `$table` (`" . implode("`,`", $cols) . "`) VALUES (" . implode(",", $vals) . ");\n";
        }
    }
    $sql .= "\n\n";
}

// ==================
// KẾT THÚC FILE EXPORT
// ==================
$sql .= "COMMIT;\n";

// GHI FILE
file_put_contents($backup_file, $sql);

echo "EXPORT DATABASE THÀNH CÔNG!";

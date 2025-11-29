<?php
// === CONFIG ===
$host     = "127.0.0.1";
$user     = "root";
$pass     = "";  
$database = "techshop";

// === Đường dẫn file xuất ===
$backup_file = __DIR__ . "/database/techshop.sql";

// === Kết nối DB ===
$conn = new mysqli($host, $user, $pass, $database);
if ($conn->connect_error) {
    die("Lỗi kết nối MySQL: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Lấy danh sách bảng
$tables = [];
$res = $conn->query("SHOW TABLES");
while ($row = $res->fetch_array()) {
    $tables[] = $row[0];
}

// === HEADER GIỐNG PHPMYADMIN ===
$sql = "";
$sql .= "-- phpMyAdmin SQL Export\n";
$sql .= "-- Host: 127.0.0.1\n";
$sql .= "-- Database: `$database`\n";
$sql .= "-- Exported at: " . date("Y-m-d H:i:s") . "\n\n";

$sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

$sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
$sql .= "SET time_zone = \"+00:00\";\n\n";
$sql .= "START TRANSACTION;\n";
$sql .= "/*!40101 SET NAMES utf8mb4 */;\n\n";

// XUẤT TỪNG BẢNG
foreach ($tables as $table) {

    // DROP TABLE
    $sql .= "-- ------------------------------------\n";
    $sql .= "-- Structure for table `$table`\n";
    $sql .= "-- ------------------------------------\n\n";
    $sql .= "DROP TABLE IF EXISTS `$table`;\n\n";

    // CREATE TABLE
    $res = $conn->query("SHOW CREATE TABLE `$table`");
    $row = $res->fetch_assoc();
    $sql .= $row['Create Table'] . ";\n\n";

    // SELECT DATA
    $sql .= "-- Dumping data for table `$table`\n";

    $result = $conn->query("SELECT * FROM `$table`");
    if ($result->num_rows > 0) {

        $rows = [];
        while ($r = $result->fetch_assoc()) {
            $rows[] = $r;
        }

        $columns = array_keys($rows[0]);

        // Start INSERT
        $sql .= "INSERT INTO `$table` (`" . implode("`,`", $columns) . "`) VALUES\n";

        $total = count($rows);
        foreach ($rows as $i => $rowData) {

            $vals = array_values($rowData);

            foreach ($vals as &$val) {
                if ($val === null) {
                    $val = "NULL";
                } else {
                    $val = "'" . $conn->real_escape_string($val) . "'";
                }
            }

            $sql .= "  (" . implode(",", $vals) . ")";

            if ($i < $total - 1) {
                $sql .= ",\n";
            } else {
                $sql .= ";\n\n";
            }
        }
    }

    $sql .= "\n";
}

// FOOTER
$sql .= "COMMIT;\n";
$sql .= "\nSET FOREIGN_KEY_CHECKS=1;\n";

file_put_contents($backup_file, $sql);

echo "EXPORT DATABASE THÀNH CÔNG!";

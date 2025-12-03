<?php
// === CONFIG ===
$host     = "127.0.0.1";
$user     = "root";
$pass     = "";
$database = "techshop";

// === OUTPUT FILE ===
$backup_file = __DIR__ . "/database/techshop.sql";

$conn = new mysqli($host, $user, $pass, $database);
if ($conn->connect_error) die("Lỗi kết nối MySQL: " . $conn->connect_error);
$conn->set_charset("utf8mb4");

// Lấy danh sách bảng
$tables = [];
$res = $conn->query("SHOW TABLES");
while ($row = $res->fetch_array()) $tables[] = $row[0];

// === HEADER ===
$sql  = "-- phpMyAdmin SQL Dump\n";
$sql .= "-- version 5.2.1\n";
$sql .= "-- https://www.phpmyadmin.net/\n";
$sql .= "--\n";
$sql .= "-- Host: localhost\n";
$sql .= "-- Generation Time: " . date("Y-m-d H:i:s") . "\n";
$sql .= "-- Server version: 10.4.28-MariaDB\n";
$sql .= "-- PHP Version: " . PHP_VERSION . "\n\n";

$sql .= "SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";\n";
$sql .= "START TRANSACTION;\n";
$sql .= "SET time_zone = \"+00:00\";\n\n";
$sql .= "/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;\n";
$sql .= "/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;\n";
$sql .= "/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;\n";
$sql .= "/*!40101 SET NAMES utf8mb4 */;\n\n";

// === BEGIN EXPORT TABLES ===
foreach ($tables as $table) {

    $sql .= "-- --------------------------------------------------------\n\n";

    $sql .= "--\n";
    $sql .= "-- Table structure for table `$table`\n";
    $sql .= "--\n\n";

    // DROP TABLE
    $sql .= "DROP TABLE IF EXISTS `$table`;\n";

    // CREATE TABLE
    $res = $conn->query("SHOW CREATE TABLE `$table`");
    $row = $res->fetch_assoc();
    $sql .= $row["Create Table"] . ";\n\n";

    // DATA HEADER
    $sql .= "--\n";
    $sql .= "-- Dumping data for table `$table`\n";
    $sql .= "--\n\n";

    // LOCK & DISABLE KEYS
    $sql .= "LOCK TABLES `$table` WRITE;\n";
    $sql .= "/*!40000 ALTER TABLE `$table` DISABLE KEYS */;\n\n";

    // Dump data
    $result = $conn->query("SELECT * FROM `$table`");

    if ($result->num_rows > 0) {
        $rows = [];
        while ($r = $result->fetch_assoc()) $rows[] = $r;

        $columns = array_keys($rows[0]);

        $sql .= "INSERT INTO `$table` (`" . implode("`,`", $columns) . "`) VALUES\n";

        $total = count($rows);
        foreach ($rows as $i => $rowData) {
            $vals = array_values($rowData);

            foreach ($vals as &$val) {
                if ($val === null) $val = "NULL";
                else $val = "'" . $conn->real_escape_string($val) . "'";
            }

            $sql .= "(" . implode(",", $vals) . ")";

            if ($i < $total - 1) $sql .= ",\n";
            else $sql .= ";\n\n";
        }
    }

    // ENABLE KEYS
    $sql .= "/*!40000 ALTER TABLE `$table` ENABLE KEYS */;\n";
    $sql .= "UNLOCK TABLES;\n\n";
}

// FOOTER
$sql .= "COMMIT;\n\n";
$sql .= "/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;\n";
$sql .= "/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;\n";
$sql .= "/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;\n";

file_put_contents($backup_file, $sql);

echo "🎉 EXPORT SUCCESS — Format = phpMyAdmin 100%!";

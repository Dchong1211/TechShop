<?php
$host     = "127.0.0.1";
$user     = "root";
$pass     = "";
$database = "techshop";

$backup_file = __DIR__ . "/database/techshop.sql";

$conn = new mysqli($host, $user, $pass, $database);
if ($conn->connect_error) die("Lỗi kết nối MySQL");
$conn->set_charset("utf8mb4");

$tables = [];
$res = $conn->query("SHOW TABLES");
while ($row = $res->fetch_array()) $tables[] = $row[0];

$sql = "";
$sql .= "SET FOREIGN_KEY_CHECKS = 0;\n\n";

foreach ($tables as $table) {

    // DROP TABLE
    $sql .= "DROP TABLE IF EXISTS `$table`;\n";

    // CREATE TABLE
    $res = $conn->query("SHOW CREATE TABLE `$table`");
    $row = $res->fetch_assoc();
    $sql .= $row["Create Table"] . ";\n\n";

    // DATA
    $result = $conn->query("SELECT * FROM `$table`");
    if ($result->num_rows > 0) {
        $rows = [];
        while ($r = $result->fetch_assoc()) $rows[] = $r;

        $columns = array_keys($rows[0]);
        $sql .= "INSERT INTO `$table` (`" . implode("`,`", $columns) . "`) VALUES\n";

        foreach ($rows as $i => $rowData) {
            $vals = array_values($rowData);

            foreach ($vals as &$val) {
                if ($val === null) $val = "NULL";
                else $val = "'" . $conn->real_escape_string($val) . "'";
            }

            $sql .= "(" . implode(",", $vals) . ")";
            $sql .= ($i < count($rows) - 1) ? ",\n" : ";\n\n";
        }
    }
}

$sql .= "SET FOREIGN_KEY_CHECKS = 1;\n";

file_put_contents($backup_file, $sql);
echo "OK";

<?php
$host     = "127.0.0.1";
$user     = "root";
$pass     = "";
$database = "techshop";

$backup_file = __DIR__ . "/database/techshop.sql";

$conn = new mysqli($host, $user, $pass, $database);
$conn->set_charset("utf8mb4");

$sql  = "SET NAMES utf8mb4;\n";
$sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

$tables = [];
$res = $conn->query("SHOW TABLES");
while ($r = $res->fetch_array()) $tables[] = $r[0];

foreach ($tables as $table) {

    $sql .= "DROP TABLE IF EXISTS `$table`;\n";

    $res = $conn->query("SHOW CREATE TABLE `$table`");
    $row = $res->fetch_assoc();
    $sql .= $row["Create Table"] . ";\n\n";

    $rs = $conn->query("SELECT * FROM `$table`");
    if ($rs->num_rows > 0) {
        $cols = array_keys($rs->fetch_assoc());
        $rs->data_seek(0);

        $sql .= "INSERT INTO `$table` (`" . implode("`,`", $cols) . "`) VALUES\n";

        while ($row = $rs->fetch_assoc()) {
            $vals = [];
            foreach ($row as $v) {
                $vals[] = ($v === null ? "NULL" : "'" . $conn->real_escape_string($v) . "'");
            }
            $sql .= "(" . implode(",", $vals) . "),\n";
        }
        $sql = rtrim($sql, ",\n") . ";\n\n";
    }
}

$sql .= "SET FOREIGN_KEY_CHECKS=1;\n";

file_put_contents($backup_file, $sql);
echo "OK";

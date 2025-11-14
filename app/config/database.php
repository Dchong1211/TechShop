<?php
require_once __DIR__ . "/env.php";

class Database {
    public $conn;

    public function __construct() {
        $this->conn = new mysqli(
            env("DB_HOST"),
            env("DB_USER"),
            env("DB_PASS"),
            env("DB_NAME")
        );

        if ($this->conn->connect_error) {
            error_log("DB ERROR: " . $this->conn->connect_error);
            die("Database error");
        }

        $this->conn->set_charset("utf8");
    }

    public function prepare($query) {
        return $this->conn->prepare($query);
    }

    public function getConnection() {
        return $this->conn;
    }
}

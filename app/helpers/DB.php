<?php
class DB {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        $host = '127.0.0.1';
        $db   = 'techshop';   // chỉnh tên database nếu khác
        $user = 'root';       // chỉnh user nếu cần
        $pass = '';           // chỉnh password nếu cần
        $dsn  = "mysql:host=$host;dbname=$db;charset=utf8mb4";

        $this->pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new DB();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
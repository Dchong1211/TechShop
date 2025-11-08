<?php
class Database {
    private $host = "localhost";
    private $user = "root"; // user mặc định của XAMPP
    private $pass = ""; // nếu bạn có mật khẩu MySQL thì thêm vào đây
    private $dbname = "techshop";
    public $conn;

    public function getConnection() {
        $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);

        if ($this->conn->connect_error) {
            die("Kết nối thất bại: " . $this->conn->connect_error);
        }

        // Đảm bảo dữ liệu hiển thị đúng tiếng Việt
        $this->conn->set_charset("utf8mb4");

        return $this->conn;
    }
}

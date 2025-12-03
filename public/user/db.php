<?php
class Database {
    private $host = 'localhost';
    private $user = 'root';      // User mặc định của XAMPP
    private $pass = '';          // Mật khẩu mặc định của XAMPP là rỗng
    private $dbname = 'techshop'; // Tên database (dựa theo tên file sql của bạn)
    
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Kết nối MySQLi
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
            
            // Xử lý lỗi font tiếng Việt
            $this->conn->set_charset("utf8");

            if ($this->conn->connect_error) {
                die("Lỗi kết nối: " . $this->conn->connect_error);
            }
        } catch(Exception $e) {
            echo "Lỗi kết nối: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>
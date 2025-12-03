<?php
class Database {
    private $host = 'localhost';
    private $user = 'root';      // Tên đăng nhập mặc định XAMPP
    private $pass = '';          // Mật khẩu mặc định XAMPP để trống
    private $dbname = 'techshop'; // Tên database của bạn (theo file .sql bạn gửi)
    
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new mysqli($this->host, $this->user, $this->pass, $this->dbname);
            
            // Xử lý font tiếng Việt không bị lỗi
            $this->conn->set_charset("utf8");

            if ($this->conn->connect_error) {
                die("Lỗi kết nối CSDL: " . $this->conn->connect_error);
            }
        } catch(Exception $e) {
            echo "Lỗi Exception: " . $e->getMessage();
        }
        return $this->conn;
    }
}
?>
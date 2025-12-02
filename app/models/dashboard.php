<?php

require_once __DIR__ . '/../config/database.php';

class Dashboard {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();
    }

    // ======================
    // HÀM LẤY GIÁ TRỊ 1 Ô (COUNT, SUM)
    // ======================
    private function fetchValue(string $sql) {
        $result = $this->conn->query($sql);
        if ($result && $row = $result->fetch_row()) {
            return $row[0];
        }
        return 0;
    }

    // ======================
    // TỔNG QUAN
    // ======================
    public function countUsers() {
        return $this->fetchValue("SELECT COUNT(*) FROM tai_khoan");
    }

    public function countProducts() {
        return $this->fetchValue("SELECT COUNT(*) FROM san_pham");
    }

    public function countOrders() {
        return $this->fetchValue("SELECT COUNT(*) FROM don_hang");
    }

    public function totalRevenue() {
        return $this->fetchValue("
            SELECT COALESCE(SUM(tong_tien), 0)
            FROM don_hang
            WHERE trang_thai = 'da_giao'
        ");
    }

    // ======================
    // TÍNH THEO THÁNG
    // ======================

    public function countUsersByMonth($year, $month) {
        return $this->fetchValue("
            SELECT COUNT(*) 
            FROM tai_khoan 
            WHERE MONTH(ngay_tao) = $month AND YEAR(ngay_tao) = $year
        ");
    }

    public function countProductsByMonth($year, $month) {
        return $this->fetchValue("
            SELECT COUNT(*) 
            FROM san_pham
            WHERE MONTH(ngay_nhap) = $month AND YEAR(ngay_nhap) = $year
        ");
    }

    public function countOrdersByMonth($year, $month) {
        return $this->fetchValue("
            SELECT COUNT(*) 
            FROM don_hang
            WHERE MONTH(ngay_tao) = $month AND YEAR(ngay_tao) = $year
        ");
    }

    public function revenueByMonth($year, $month) {
        return $this->fetchValue("
            SELECT COALESCE(SUM(tong_tien), 0)
            FROM don_hang
            WHERE MONTH(ngay_tao) = $month 
              AND YEAR(ngay_tao) = $year
              AND trang_thai = 'da_giao'
        ");
    }

    // ======================
    // TÍNH % TĂNG GIẢM
    // ======================
    public function percentChange($current, $prev) {
        if ($prev == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $prev) / $prev) * 100, 2);
    }
}

<?php
// models/Order.php
require_once __DIR__ . '/../config/database.php';

class OrderModel {
    private $conn;

    public function __construct() {
        $this->conn = (new Database())->getConnection();    
    }

    /**
     * Lấy tất cả đơn hàng + tên khách
     */
    public function all(): array {
        $sql = "
            SELECT 
                d.*, 
                u.ho_ten AS ten_khach
            FROM don_hang d
            LEFT JOIN nguoi_dung u ON d.id_khach_hang = u.id
            ORDER BY d.ngay_dat_hang DESC
        ";

        $res = $this->conn->query($sql);
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }

    /**
     * Lấy đơn hàng theo ID + tên khách
     */
    public function getById(int $id): ?array {
        $sql = "
            SELECT 
                d.*, 
                u.ho_ten AS ten_khach
            FROM don_hang d
            LEFT JOIN nguoi_dung u ON d.id_khach_hang = u.id
            WHERE d.id = ?
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $res = $stmt->get_result()->fetch_assoc();

        return $res ?: null;
    }

    /**
     * Tạo đơn hàng mới
     */
    public function create(array $data): int {
        $stmt = $this->conn->prepare("
            INSERT INTO don_hang 
                (id_khach_hang, tong_tien, phuong_thuc_thanh_toan, 
                 ten_nguoi_nhan, sdt_nguoi_nhan, dia_chi_giao_hang, trang_thai_don)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "idsssss",
            $data['id_khach_hang'],
            $data['tong_tien'],
            $data['phuong_thuc_thanh_toan'],
            $data['ten_nguoi_nhan'],
            $data['sdt_nguoi_nhan'],
            $data['dia_chi_giao_hang'],
            $data['trang_thai_don']
        );
        $stmt->execute();
        return $this->conn->insert_id;
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(int $id, string $status): bool {
        $stmt = $this->conn->prepare("
            UPDATE don_hang SET trang_thai_don = ? WHERE id = ?
        ");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }

    /**
     * Xóa đơn hàng
     */
    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM don_hang WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }

    /**
     * Tìm kiếm theo:
     *  - mã đơn
     *  - tên khách hàng
     *  - tên người nhận
     *  - sdt người nhận
     */
    public function search(string $query): array {
        $like = "%$query%";

        $sql = "
            SELECT 
                d.*, 
                u.ho_ten AS ten_khach
            FROM don_hang d
            LEFT JOIN nguoi_dung u ON d.id_khach_hang = u.id
            WHERE d.id LIKE ? 
               OR u.ho_ten LIKE ?
               OR d.ten_nguoi_nhan LIKE ?
               OR d.sdt_nguoi_nhan LIKE ?
            ORDER BY d.ngay_dat_hang DESC
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("ssss", $like, $like, $like, $like);
        $stmt->execute();

        $res = $stmt->get_result();
        return $res ? $res->fetch_all(MYSQLI_ASSOC) : [];
    }
}

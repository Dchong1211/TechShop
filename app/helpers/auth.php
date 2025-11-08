<?php
function requireAdmin() {
    session_start();
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        echo json_encode(['success' => false, 'message' => 'Không có quyền truy cập!']);
        exit;
    }
}
?>
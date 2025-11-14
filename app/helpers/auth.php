<?php

function requireLogin() {
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập!']);
        exit;
    }
}

function requireAdmin() {
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập!']);
        exit;
    }

    if ($_SESSION['user']['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Bạn không có quyền admin!']);
        exit;
    }
}

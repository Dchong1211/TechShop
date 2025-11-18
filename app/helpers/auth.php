<?php

if (session_status() === PHP_SESSION_NONE) session_start(); // middleware

// yêu cầu user đã đăng nhập
function requireLogin() {
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập!'], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// yêu cầu quyền admin
function requireAdmin() {
    if (!isset($_SESSION['user'])) {
        http_response_code(401);
        echo json_encode(['success' => false, 'message' => 'Bạn chưa đăng nhập!'], JSON_UNESCAPED_UNICODE);
        exit;
    }

    if ($_SESSION['user']['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Bạn không có quyền admin!'], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

// yêu cầu cho khách chưa đăng nhập
function requireGuest() {
    if (isset($_SESSION['user'])) {
        http_response_code(409);
        echo json_encode(['success' => false, 'message' => 'Bạn đã đăng nhập!'], JSON_UNESCAPED_UNICODE);
        exit;
    }
}

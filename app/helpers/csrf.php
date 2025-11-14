<?php

class CSRF {

    // Tạo token
    public static function token() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Token cố định 1 phiên làm việc
        if (!isset($_SESSION['csrf']) || empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf'];
    }

    // Kiểm tra token
    public static function check($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['csrf']) || !$token) {
            return false;
        }

        return hash_equals($_SESSION['csrf'], $token);
    }

    // Dùng cho các route POST
    public static function requireToken() {
        if ($_SERVER['REQUEST_METHOD'] === "GET") {
            return; // GET không cần token
        }

        // Lấy token từ body hoặc header
        $token = $_POST['csrf']
            ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? null);

        if (!self::check($token)) {
            http_response_code(403);
            echo json_encode([
                "success" => false,
                "message" => "Invalid CSRF token!"
            ]);
            exit;
        }
    }
}

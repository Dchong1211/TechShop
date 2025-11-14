<?php

class CSRF {

    public static function token() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf'];
    }

    public static function check($token) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Không có token → reject
        if (!$token || !isset($_SESSION['csrf'])) {
            return false;
        }

        // So sánh thời gian bảo mật
        $valid = hash_equals($_SESSION['csrf'], $token);

        // Tự động tạo token mới (chống replay attack)
        $_SESSION['csrf'] = bin2hex(random_bytes(32));

        return $valid;
    }

    // middleware: chặn request nếu sai token
    public static function requireToken() {
        $token = $_POST['csrf'] ?? null;
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

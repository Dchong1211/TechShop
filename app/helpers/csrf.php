<?php

class CSRF {

    public static function token() {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['csrf'])) {
            $_SESSION['csrf'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf'];
    }

    public static function check($token) {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (empty($_SESSION['csrf']) || empty($token)) {
            return false;
        }

        return hash_equals($_SESSION['csrf'], $token);
    }

    public static function requireToken() {
        if ($_SERVER['REQUEST_METHOD'] === "GET") return;

        $token = $_POST["csrf"]
            ?? ($_SERVER["HTTP_X_CSRF_TOKEN"] ?? null);

        if (!self::check($token)) {
            http_response_code(403);
            echo json_encode([
                "success" => false,
                "message" => "Invalid CSRF token!"
            ], JSON_UNESCAPED_UNICODE);
            exit;
        }
    }
}

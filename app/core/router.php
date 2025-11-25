<?php

class Router {
    private array $routes = [];
    private string $basePath = 'TechShop'; // tên thư mục gốc của dự án

    public function get($path, $callback) {
        $this->routes['GET'][$this->normalize($path)] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$this->normalize($path)] = $callback;
    }

    private function normalize($path) {
        return trim($path, '/');
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];

        // Lấy URL browser gửi
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Loại bỏ base path (TechShop)
        if (strpos($uri, '/' . $this->basePath) === 0) {
            $uri = substr($uri, strlen($this->basePath) + 1);
        }

        // Khi yêu cầu file tĩnh (css, js, images) thì bypass router
        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|ico)$/', $uri)) {
            return false; // để Apache/XAMPP xử lý
        }

        // Loại /public (nếu có)
        if (strpos($uri, '/public') === 0) {
            $uri = substr($uri, 7);
        }

        $uri = trim($uri, '/');
        if ($uri === '') $uri = '/';

        foreach ($this->routes[$method] ?? [] as $route => $callback) {
            $pattern = preg_replace('/\{[^\/]+\}/', '([^/]+)', $route);

            if (preg_match("#^$pattern$#", $uri, $matches)) {
                array_shift($matches);

                if (is_array($callback)) {
                    $controller = new $callback[0];
                    return call_user_func_array([$controller, $callback[1]], $matches);
                }
                return call_user_func_array($callback, $matches);
            }
        }

        http_response_code(404);
        echo json_encode([
            "success" => false,
            "message" => "404 - Route không tồn tại!",
            "uri" => $uri
        ], JSON_UNESCAPED_UNICODE);
    }

}

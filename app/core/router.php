<?php

class Router {
    private array $routes = [];

    public function get($path, $callback) {
        $this->routes['GET'][$this->normalize($path)] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['POST'][$this->normalize($path)] = $callback;
    }

    // Chuẩn hóa route
    private function normalize($path) {
        return trim($path, '/');
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];

        // Lấy URL thật
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        // Tự động detect base path (TechShop/public)
        $base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $base = trim($base, '/');

        // Nếu URL bắt đầu bằng base path -> xoá đi
        if ($base !== '' && strpos($uri, '/' . $base) === 0) {
            $uri = substr($uri, strlen($base) + 1);
        }

        $uri = trim($uri, '/');

        if ($uri === '') $uri = '/';

        // Tìm route match
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
            "uri" => $uri,
        ], JSON_UNESCAPED_UNICODE);
    }
}

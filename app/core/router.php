<?php

class Router {
    private array $routes = [];

    public function get($path, $callback) {
        $this->routes["GET"][trim($path, "/")] = $callback;
    }

    public function post($path, $callback) {
        $this->routes["POST"][trim($path, "/")] = $callback;
    }

    public function run() {
        $method = $_SERVER['REQUEST_METHOD'];
        $uri = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

        $base = "TechShop/public";
        if (strpos($uri, $base) === 0) {
            $uri = trim(substr($uri, strlen($base)), "/");
        }

        foreach ($this->routes[$method] ?? [] as $route => $callback) {
            $pattern = preg_replace('/\{[^\/]+\}/', '([^/]+)', $route);

            if (preg_match("#^{$pattern}$#", $uri, $matches)) {
                array_shift($matches);

                if (is_array($callback)) {
                    $controller = new $callback[0];
                    return call_user_func_array([$controller, $callback[1]], $matches);
                }

                return call_user_func_array($callback, $matches);
            }
        }

        http_response_code(404);
        echo "404 NOT FOUND";
    }
}
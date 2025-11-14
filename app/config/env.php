<?php

function env($key, $default = null) {
    static $data;

    if (!$data) {
        $data = [];
        $path = __DIR__ . '/.env';

        if (file_exists($path)) {
            $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

            foreach ($lines as $line) {
                [$k, $v] = explode('=', $line, 2);
                $data[$k] = $v;
            }
        }
    }

    return $data[$key] ?? $default;
}

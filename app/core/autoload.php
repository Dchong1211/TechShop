<?php

spl_autoload_register(function ($class) {
    $paths = [
        __DIR__ . '/../controllers/' . $class . '.php',
        __DIR__ . '/../models/' . $class . '.php',
        __DIR__ . '/../helpers/' . $class . '.php',
        __DIR__ . '/' . $class . '.php'
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

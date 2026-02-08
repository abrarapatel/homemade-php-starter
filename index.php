<?php
// public/index.php

// Start session for authentication
session_start();

// Simple autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/./app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Define Base URL
$scriptName = $_SERVER['SCRIPT_NAME']; // e.g., /sami-art/index.php
$basePath = str_replace(['/public/index.php', '/index.php'], '', $scriptName); // /sami-art or empty
define('BASE_URL', $basePath . '/');

use App\Core\Router;

$router = new Router();
$router->resolve();

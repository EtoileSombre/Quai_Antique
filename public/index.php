<?php

// Autoloader simple basé sur les namespaces
spl_autoload_register(function (string $class): void {
    // App\Core\Database → app/Core/Database.php
    $prefix = 'App\\';
    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relativeClass = substr($class, strlen($prefix));
    $file = __DIR__ . '/../app/' . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Charger la configuration
require __DIR__ . '/../config/database.php';

// Charger le routeur et les routes
use App\Core\Router;
use App\Controllers\HomeController;

$router = new Router();

// --- Routes ---
$router->get('', HomeController::class, 'index');
$router->get('accueil', HomeController::class, 'index');

// Résoudre la requête
$router->resolve();

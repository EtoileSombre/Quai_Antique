<?php

use App\Core\Router;

$router = new Router();

// ROUTES PUBLIQUES

// Page d'accueil
$router->get('/', 'App\Controllers\Public\HomeController', 'index');

return $router;

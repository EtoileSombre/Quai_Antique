<?php

use App\Core\Router;

$router = new Router();

// ROUTES PUBLIQUES

// Page d'accueil
$router->get('/', 'App\Controllers\Public\AccueilController', 'index');

// La Carte (publique)
$router->get('/carte', 'App\Controllers\Public\CarteController', 'index');

// AUTHENTIFICATION
$router->get('/connexion', 'App\Controllers\Auth\AuthentificationController', 'loginForm');
$router->post('/connexion', 'App\Controllers\Auth\AuthentificationController', 'login');
$router->get('/inscription', 'App\Controllers\Auth\AuthentificationController', 'registerForm');
$router->post('/inscription', 'App\Controllers\Auth\AuthentificationController', 'register');
$router->get('/deconnexion', 'App\Controllers\Auth\AuthentificationController', 'logout');

// ADMIN — Carte
$router->get('/admin', 'App\Controllers\Admin\AdminCarteController', 'index');
$router->get('/admin/carte', 'App\Controllers\Admin\AdminCarteController', 'index');

// Admin — Catégories
$router->post('/admin/carte/categories/ajouter', 'App\Controllers\Admin\AdminCarteController', 'createCategory');
$router->get('/admin/carte/categories/modifier', 'App\Controllers\Admin\AdminCarteController', 'editCategoryForm');
$router->post('/admin/carte/categories/modifier', 'App\Controllers\Admin\AdminCarteController', 'updateCategory');
$router->post('/admin/carte/categories/supprimer', 'App\Controllers\Admin\AdminCarteController', 'deleteCategory');

// Admin — Plats
$router->get('/admin/carte/plats/ajouter', 'App\Controllers\Admin\AdminCarteController', 'createDishForm');
$router->post('/admin/carte/plats/ajouter', 'App\Controllers\Admin\AdminCarteController', 'createDish');
$router->get('/admin/carte/plats/modifier', 'App\Controllers\Admin\AdminCarteController', 'editDishForm');
$router->post('/admin/carte/plats/modifier', 'App\Controllers\Admin\AdminCarteController', 'updateDish');
$router->post('/admin/carte/plats/supprimer', 'App\Controllers\Admin\AdminCarteController', 'deleteDish');

// Admin — Menus
$router->get('/admin/carte/menus/ajouter', 'App\Controllers\Admin\AdminCarteController', 'createMenuForm');
$router->post('/admin/carte/menus/ajouter', 'App\Controllers\Admin\AdminCarteController', 'createMenu');
$router->get('/admin/carte/menus/modifier', 'App\Controllers\Admin\AdminCarteController', 'editMenuForm');
$router->post('/admin/carte/menus/modifier', 'App\Controllers\Admin\AdminCarteController', 'updateMenu');
$router->post('/admin/carte/menus/supprimer', 'App\Controllers\Admin\AdminCarteController', 'deleteMenu');

// Admin — Paramètres (horaires + capacité)
$router->get('/admin/parametres', 'App\Controllers\Admin\AdminParametresController', 'index');
$router->post('/admin/parametres/horaires', 'App\Controllers\Admin\AdminParametresController', 'updateHours');
$router->post('/admin/parametres/capacite', 'App\Controllers\Admin\AdminParametresController', 'updateCapacity');

return $router;

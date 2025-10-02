<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Ruta por defecto de CodeIgniter
$routes->get('/', 'Home::index');

// ============================================
// Web Routes - Users Module (Vistas HTML)
// ============================================
$routes->group('users', ['namespace' => 'Modules\User\Infrastructure\Http'], function($routes) {
    $routes->get('/', 'UserController::listView'); // Lista de usuarios
    $routes->get('create', 'UserController::formView'); // Formulario de creación
    $routes->get('edit/(:num)', 'UserController::formView/$1'); // Formulario de edición
});

// ============================================
// API Routes - Users Module (RESTful)
// ============================================
$routes->group('api', ['namespace' => 'Modules\User\Infrastructure\Http'], function($routes) {
    // User CRUD endpoints
    $routes->get('users', 'UserController::index');              // GET all users
    $routes->get('users/(:num)', 'UserController::show/$1');     // GET user by ID
    $routes->post('users', 'UserController::create');            // POST create user
    $routes->put('users/(:num)', 'UserController::update/$1');   // PUT update user
    $routes->delete('users/(:num)', 'UserController::delete/$1'); // DELETE user
});
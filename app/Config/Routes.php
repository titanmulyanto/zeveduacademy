<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('admin', function($routes) {
    $routes->get('dashboard', '\App\Controllers\Admin\DashboardController::index');
    $routes->get('users', '\App\Controllers\Admin\UserController::index');
    $routes->get('transactions', '\App\Controllers\Admin\UserController::transactions');
});

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
    $routes->get('materi', '\App\Controllers\Admin\MateriController::index');
    $routes->get('materi/detail/(:num)', '\App\Controllers\Admin\MateriController::detail/$1');
});

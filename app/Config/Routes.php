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
    $routes->get('ujian', '\App\Controllers\Admin\UjianController::index');
    $routes->get('ujian/questions/(:num)', '\App\Controllers\Admin\UjianController::questions/$1');
    $routes->get('ujian/results', '\App\Controllers\Admin\UjianController::results');
    
    $routes->group('cms', function($routes) {
        $routes->get('slider', '\App\Controllers\Admin\CmsController::slider');
        $routes->get('features', '\App\Controllers\Admin\CmsController::features');
        $routes->get('faq', '\App\Controllers\Admin\CmsController::faq');
        $routes->get('testimoni', '\App\Controllers\Admin\CmsController::testimoni');
    });

    $routes->get('sertifikat', '\App\Controllers\Admin\SertifikatController::index');
    $routes->get('sertifikat/preview/(:num)', '\App\Controllers\Admin\SertifikatController::preview/$1');
});

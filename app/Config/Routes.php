<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =====================================================
// WEB ROUTES (Myth Auth session)
// =====================================================

$routes->get('/', 'Web\DashboardController::index');

$routes->group('admin', [
    'namespace' => 'App\Controllers\Web\Admin',
    'filter'    => 'login'
], function ($routes) {
    $routes->get('dashboard', 'DashboardController::index');
    $routes->get('products', 'ProductsController::index');
    $routes->get('promos', 'PromoController::index');
    $routes->get('users', 'UserController::index');
});

$routes->group('pos', ['filter' => 'login'], function ($routes) {
    $routes->get('/', 'Api\POSController::index');
});


// =====================================================
// API ROUTES (Vue SPA)
// =====================================================

$routes->group('api', [
    'namespace' => 'App\Controllers\Api',
    'filter'    => 'cors'
], function ($routes) {

    // ---------- AUTH ----------
    $routes->options('login', fn() => service('response'));
    $routes->options('logout', fn() => service('response'));
    $routes->options('user/me', fn() => service('response'));

    $routes->post('login', 'AuthController::login');
    $routes->post('logout', 'AuthController::logout');
    $routes->get('user/me', 'UserController::me');


    // ---------- POS ----------
    $routes->options('pos/checkout', fn() => service('response'));
    $routes->post('pos/checkout', 'POSController::checkout');


    // ---------- PROMO ----------
    $routes->options('promos/active', fn() => service('response'));
    $routes->get('promos/active', 'PromoController::getActive');


    // ---------- DASHBOARD ----------
    $routes->options('dashboard/summary', fn() => service('response'));
    $routes->get('dashboard/summary', 'DashboardController::summary');
});


// =====================================================
// SYNC ROUTES
// =====================================================

$routes->group('api/sync', [
    'namespace' => 'App\Controllers\Api\Sync',
    'filter'    => 'cors'
], function ($routes) {

    $routes->options('products', fn() => service('response'));
    $routes->options('categories', fn() => service('response'));

    $routes->get('products', 'ProductSyncController::pull');
    $routes->get('categories', 'CategorySyncController::pull');
});
<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// -----------------------------------------------------
// PUBLIC ROUTES
// -----------------------------------------------------
$routes->get('/', 'Web\DashboardController::index');

// Halaman register user baru (Web)
$routes->get('register', 'Api\UserController::registerForm');
$routes->post('register', 'Api\UserController::register');

// -----------------------------------------------------
// API ROUTES
// -----------------------------------------------------
$routes->group('api', ['filter' => 'cors', 'namespace' => 'App\Controllers\Api'], function ($routes) {

    // ===== PRE-FLIGHT (WAJIB UNTUK CORS) =====
    $routes->options('pos/checkout', fn() => service('response')); 
    $routes->options('pos/(:num)/receipt', fn() => service('response')); // ⬅️ INI PENTING

    // ===== POS API =====
    $routes->post('pos/checkout', 'POSController::checkout');
    $routes->get('pos/(:num)/receipt', 'POSController::receipt/$1'); // ⬅️ INI PENTING

    // ===== SYNC API =====
    $routes->options('sync/push', fn() => service('response'));
    $routes->options('sync/pull', fn() => service('response'));
    $routes->post('sync/push', 'SyncController::push');
    $routes->get('sync/pull', 'SyncController::pull');

    // ===== PROMO API =====
    $routes->options('promo/active', fn() => service('response'));
    $routes->options('promo/products', fn() => service('response'));
    $routes->get('promo/active', 'PromoController::active');
    $routes->get('promo/products', 'PromoController::products');
});



// -----------------------------------------------------
// WEB ROUTES (REQUIRE LOGIN)
// -----------------------------------------------------
$routes->group('', ['filter' => 'login'], function ($routes) {
    $routes->get('dashboard', 'Web\DashboardController::index');
});


// -----------------------------------------------------
// SHOP MANAGEMENT
// -----------------------------------------------------
$routes->group('shop', ['filter' => 'login'], function ($routes) {
    $routes->get('register', 'Api\ShopController::register');
    $routes->post('register', 'Api\ShopController::saveRegister');
});


// -----------------------------------------------------
// USER MANAGEMENT
// -----------------------------------------------------
$routes->group('users', ['filter' => 'role:owner,admin'], function ($routes) {
    $routes->get('/', 'Api\UserController::index');
    $routes->get('create', 'Api\UserController::create');
    $routes->post('save', 'Api\UserController::save');
    $routes->get('edit/(:num)', 'Api\UserController::edit/$1');
    $routes->post('update/(:num)', 'Api\UserController::update/$1');
    $routes->get('delete/(:num)', 'Api\UserController::delete/$1');
});


// -----------------------------------------------------
// PRODUCT MANAGEMENT
// -----------------------------------------------------
$routes->group('products', ['filter' => 'role:owner,admin'], function ($routes) {

    $routes->get('/', 'Api\ProductsController::index');
    $routes->get('create', 'Api\ProductsController::create');
    $routes->post('save', 'Api\ProductsController::save');

    $routes->get('edit/(:num)', 'Api\ProductsController::edit/$1');
    $routes->post('update/(:num)', 'Api\ProductsController::update/$1');

    $routes->get('delete/(:num)', 'Api\ProductsController::delete/$1');
    $routes->get('import-template', 'Api\ProductsController::importTemplate');
    $routes->post('import', 'Api\ProductsController::import');
});


// -----------------------------------------------------
// POS (WEB UI)
// -----------------------------------------------------
$routes->group('pos', ['filter' => 'login'], function ($routes) {

    $routes->get('/', 'Api\POSController::index');
    $routes->get('get/(:num)', 'Api\POSController::get/$1');
    $routes->get('get-by-barcode/(:any)', 'Api\POSController::getByBarcode/$1');
    $routes->get('search', 'Api\POSController::search');

    $routes->post('save', 'Api\POSController::saveTransaction');
    $routes->post('calc-discount', 'Api\POSController::calculateDiscount');
    $routes->post('add-to-cart', 'Api\POSController::addToCart');
    $routes->get('get-cart', 'Api\POSController::getCart');
    $routes->post('remove-item', 'Api\POSController::removeItem');
});


// -----------------------------------------------------
// REPORTS
// -----------------------------------------------------
$routes->group('reports', ['filter' => 'login'], function ($routes) {
    $routes->get('sales', 'Api\SalesController::index');
    $routes->get('sales/export/excel', 'Api\SalesController::exportExcel');
    $routes->get('sales/export/pdf', 'Api\SalesController::exportPdf');
});


// -----------------------------------------------------
// PROMO (WEB UI)
// -----------------------------------------------------
$routes->group('promo', ['filter' => 'login'], function ($routes) {
    $routes->get('/', 'Api\PromoController::index');
    $routes->get('create', 'Api\PromoController::create');
    $routes->post('store', 'Api\PromoController::store');
    $routes->get('edit/(:num)', 'Api\PromoController::edit/$1');
    $routes->post('update/(:num)', 'Api\PromoController::update/$1');
    $routes->get('delete/(:num)', 'Api\PromoController::delete/$1');
});

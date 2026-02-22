<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Auth routes
$routes->get('/', 'AuthController::login');
$routes->get('/login', 'AuthController::login');
$routes->post('/login', 'AuthController::attemptLogin');
$routes->get('/logout', 'AuthController::logout');
$routes->post('/logout', 'AuthController::processLogout');

// Dashboard routes
$routes->get('/dashboard', 'DashboardController::index');

// Admin routes
$routes->group('', ['filter' => 'csrf'], static function ($routes) {
    // Soal routes
    $routes->get('/soal', 'SoalController::index');
    $routes->get('/soal/create', 'SoalController::create');
    $routes->post('/soal', 'SoalController::store');
    $routes->get('/soal/edit/(:num)', 'SoalController::edit/$1');
    $routes->post('/soal/update/(:num)', 'SoalController::update/$1');
    $routes->post('/soal/delete/(:num)', 'SoalController::delete/$1');
    $routes->post('/soal/toggle-status/(:num)', 'SoalController::toggleStatus/$1');

    // Level routes
    $routes->get('/level', 'LevelController::index');
    $routes->get('/level/create', 'LevelController::create');
    $routes->post('/level', 'LevelController::store');
    $routes->get('/level/edit/(:num)', 'LevelController::edit/$1');
    $routes->post('/level/update/(:num)', 'LevelController::update/$1');
    $routes->post('/level/delete/(:num)', 'LevelController::delete/$1');

    // Peserta routes
    $routes->get('/peserta', 'PesertaController::index');
    $routes->get('/peserta/create', 'PesertaController::create');
    $routes->post('/peserta', 'PesertaController::store');
    $routes->get('/peserta/edit/(:num)', 'PesertaController::edit/$1');
    $routes->post('/peserta/update/(:num)', 'PesertaController::update/$1');
    $routes->post('/peserta/delete/(:num)', 'PesertaController::delete/$1');
    $routes->get('/peserta/reset-token/(:num)', 'PesertaController::resetToken/$1');
    $routes->get('/peserta/token/(:num)', 'PesertaController::showToken/$1');

    // Hasil routes
    $routes->get('/hasil', 'HasilController::index');
    $routes->get('/hasil/(:num)', 'HasilController::show/$1');
    $routes->post('/hasil/delete/(:num)', 'HasilController::delete/$1');
    $routes->get('/hasil/export/(:num)', 'HasilController::exportPdf/$1');
});

// API routes (no CSRF filter for API)
$routes->group('api', ['namespace' => 'App\Controllers\Api'], static function ($routes) {
    // Auth routes
    $routes->post('auth/login', 'ApiAuthController::login');
    $routes->post('auth/logout', 'ApiAuthController::logout');
    $routes->post('auth/register', 'ApiAuthController::register');
    $routes->get('auth/me', 'ApiAuthController::me', ['filter' => 'apiAuth']);

    // Soal routes
    $routes->get('soal', 'ApiSoalController::index', ['filter' => 'apiAuth']);
    $routes->get('soal/levels', 'ApiSoalController::levels', ['filter' => 'apiAuth']);
    $routes->get('soal/random', 'ApiSoalController::random', ['filter' => 'apiAuth']);
    $routes->get('soal/(:num)', 'ApiSoalController::show/$1', ['filter' => 'apiAuth']);

    // Kuis routes
    $routes->post('kuis/start', 'ApiKuisController::start', ['filter' => 'apiAuth']);
    $routes->post('kuis/submit', 'ApiKuisController::submit', ['filter' => 'apiAuth']);
    $routes->post('kuis/finish', 'ApiKuisController::finish', ['filter' => 'apiAuth']);
    $routes->get('kuis/active', 'ApiKuisController::active', ['filter' => 'apiAuth']);
    $routes->post('kuis/cancel', 'ApiKuisController::cancel', ['filter' => 'apiAuth']);

    // Hasil routes
    $routes->get('hasil', 'ApiHasilController::index', ['filter' => 'apiAuth']);
    $routes->get('hasil/latest', 'ApiHasilController::latest', ['filter' => 'apiAuth']);
    $routes->get('hasil/statistics', 'ApiHasilController::statistics', ['filter' => 'apiAuth']);
    $routes->get('hasil/(:num)', 'ApiHasilController::show/$1', ['filter' => 'apiAuth']);
});

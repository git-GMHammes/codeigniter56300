<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api/v1', ['namespace' => 'App\Controllers'], function ($routes) {
    require APPPATH . 'Routes/API/v1/UserManagement/api_routes.php';
});

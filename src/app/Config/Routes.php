<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api/v1', ['namespace' => 'App\Controllers'], function ($routes) {
    require APPPATH . 'Routes/API/v1/UserManagement/api_routes.php';
    require APPPATH . 'Routes/API/v1/UserCustomer/api_routes.php';
    require APPPATH . 'Routes/API/v1/UserCustomerFile/api_routes.php';
    require APPPATH . 'Routes/API/v1/ContactUs/api_routes.php';
    require APPPATH . 'Routes/API/v1/ContactUsFile/api_routes.php';
    require APPPATH . 'Routes/API/v1/Log/api_routes.php';
    require APPPATH . 'Routes/API/v1/UserCustomerManagement/api_routes.php';
});

<?php

/**
 * Rotas da API - Módulo UserCustomerFile (v1)
 *
 * Arquivo: app/Routes/API/v1/UserCustomerFile/api_routes.php
 */

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->group('', ['filter' => 'auth'], function ($routes) {
    # ========================================================================
    # LISTAGEM - Uploads de UserCustomer
    # ========================================================================

    # GET /api/v1/user-customer-files
    # Lista todos os arquivos (ativos)
    $routes->get('user-customer-files', 'API\v1\UserCustomerFile\ManagerController::index', ['as' => 'api.v1.user-customer-files.index']);

    # GET /api/v1/user-customer-files/with-deleted
    # Lista todos os arquivos (incluindo deletados)
    $routes->get('user-customer-files/with-deleted', 'API\v1\UserCustomerFile\ManagerController::indexWithDeleted', ['as' => 'api.v1.user-customer-files.with-deleted']);

    # GET /api/v1/user-customer-files/only-deleted
    # Lista apenas os arquivos deletados
    $routes->get('user-customer-files/only-deleted', 'API\v1\UserCustomerFile\ManagerController::indexOnlyDeleted', ['as' => 'api.v1.user-customer-files.only-deleted']);

    # ========================================================================
    # BUSCA / SHOW
    # ========================================================================

    # GET /api/v1/user-customer-files/(:num)
    # Recupera um arquivo por ID
    $routes->get('user-customer-files/(:num)', 'API\v1\UserCustomerFile\ManagerController::show/$1', ['as' => 'api.v1.user-customer-files.show']);

    # POST /api/v1/user-customer-files/(:num)/with-deleted
    # Recupera um arquivo por ID incluindo deletados
    $routes->post('user-customer-files/(:num)/with-deleted', 'API\v1\UserCustomerFile\ManagerController::showWithDeleted/$1', ['as' => 'api.v1.user-customer-files.show-with-deleted']);

    # POST /api/v1/user-customer-files/search
    # Busca avançada de arquivos
    $routes->post('user-customer-files/search', 'API\v1\UserCustomerFile\ManagerController::search', ['as' => 'api.v1.user-customer-files.search']);

    # ========================================================================
    # MANIPULAÇÃO / EXCLUSÃO DE ARQUIVOS
    # ========================================================================

    # DELETE /api/v1/user-customer-files/(:num)
    # Soft delete de um arquivo
    $routes->delete('user-customer-files/(:num)', 'API\v1\UserCustomerFile\ManagerController::delete/$1', ['as' => 'api.v1.user-customer-files.delete']);

    # PATCH /api/v1/user-customer-files/(:num)/restore
    # Restaura um arquivo soft-deleted
    $routes->patch('user-customer-files/(:num)/restore', 'API\v1\UserCustomerFile\ManagerController::restore/$1', ['as' => 'api.v1.user-customer-files.restore']);

    # ========================================================================
    # METADADOS
    # ========================================================================

    # GET /api/v1/user-customer-files/columns
    $routes->get('user-customer-files/columns', 'API\v1\UserCustomerFile\ManagerController::getColumnsMetadata', ['as' => 'api.v1.user-customer-files.columns']);

    # GET /api/v1/user-customer-files/column-names
    $routes->get('user-customer-files/column-names', 'API\v1\UserCustomerFile\ManagerController::getColumnNames', ['as' => 'api.v1.user-customer-files.column-names']);
});
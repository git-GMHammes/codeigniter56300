<?php

/**
 * Rotas da API - Módulo User Customer Management (v1)
 * 
 * @package    App\Routes\v1
 * @author     Gustavo - HABILIDADE
 * @version    1.0.0
 * @since      22/11/2025
 */

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->group('', ['filter' => 'auth'], function ($routes) {
    # ========================================================================
    # LISTAGEM
    # ========================================================================

    # GET /api/v1/user-customer
    # Lista todos os registros ativos (sem deleted_at)
    $routes->get('user-customer', 'API\v1\UserCustomerManagement\ManagerController::index', ['as' => 'api.v1.user-customer.index']);

    # GET /api/v1/user-customer/with-deleted
    # Lista todos os registros incluindo os marcados como deletados
    $routes->get('user-customer/with-deleted', 'API\v1\UserCustomerManagement\ManagerController::indexWithDeleted', ['as' => 'api.v1.user-customer.with-deleted']);

    # GET /api/v1/user-customer/only-deleted
    # Lista apenas os registros marcados como deletados
    $routes->get('user-customer/only-deleted', 'API\v1\UserCustomerManagement\ManagerController::indexOnlyDeleted', ['as' => 'api.v1.user-customer.only-deleted']);

    # ========================================================================
    # BUSCA
    # ========================================================================

    # GET /api/v1/user-customer/(:num)
    # Busca um registro específico por ID (apenas ativos)
    $routes->get('user-customer/(:num)', 'API\v1\UserCustomerManagement\ManagerController::show/$1', ['as' => 'api.v1.user-customer.show']);

    # POST /api/v1/user-customer/(:num)/with-deleted
    # Busca um registro específico por ID incluindo deletados
    $routes->post('user-customer/(:num)/with-deleted', 'API\v1\UserCustomerManagement\ManagerController::showWithDeleted/$1', ['as' => 'api.v1.user-customer.show-with-deleted']);

    # POST /api/v1/user-customer/search
    # Busca avançada com múltiplos filtros, operadores e opções
    $routes->post('user-customer/search', 'API\v1\UserCustomerManagement\ManagerController::search', ['as' => 'api.v1.user-customer.search']);

    # ========================================================================
    # METADADOS
    # ========================================================================

    # GET /api/v1/user-customer/columns
    # Retorna metadados completos das colunas da tabela
    $routes->get('user-customer/columns', 'API\v1\UserCustomerManagement\ManagerController::getColumnsMetadata', ['as' => 'api.v1.user-customer.columns']);

    # GET /api/v1/user-customer/column-names
    # Retorna apenas os nomes das colunas da tabela
    $routes->get('user-customer/column-names', 'API\v1\UserCustomerManagement\ManagerController::getColumnNames', ['as' => 'api.v1.user-customer.column-names']);

});
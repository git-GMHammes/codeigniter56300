<?php

/**
 * Rotas da API - Módulo Log Management (v1)
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

    # GET /api/v1/log-management
    # Lista todos os registros ativos (sem deleted_at)
    $routes->get('log-management', 'API\v1\Log\ManagerController::index', ['as' => 'api.v1.log-management.index']);

    # GET /api/v1/log-management/only-deleted
    # Lista apenas os registros marcados como deletados
    $routes->get('log-management/only-deleted', 'API\v1\Log\ManagerController::indexOnlyDeleted', ['as' => 'api.v1.log-management.only-deleted']);

    # ========================================================================
    # BUSCA
    # ========================================================================

    # GET /api/v1/log-management/(:num)
    # Busca um registro específico por ID (apenas ativos)
    $routes->get('log-management/(:num)', 'API\v1\Log\ManagerController::show/$1', ['as' => 'api.v1.log-management.show']);

    # POST /api/v1/log-management/(:num)/with-deleted
    # Busca um registro específico por ID incluindo deletados
    $routes->post('log-management/(:num)/with-deleted', 'API\v1\Log\ManagerController::showWithDeleted/$1', ['as' => 'api.v1.log-management.show-with-deleted']);

    # GET /api/v1/log-management/(:num)/with-deleted
    # Busca um registro específico por ID incluindo deletados
    $routes->get('log-management/with-deleted', 'API\v1\Log\ManagerController::showWithDeleted/$1', ['as' => 'api.v1.log-management.show-with-deleted']);

    # POST /api/v1/log-management/search
    # Busca avançada com múltiplos filtros, operadores e opções
    $routes->post('log-management/search', 'API\v1\Log\ManagerController::search', ['as' => 'api.v1.log-management.search']);

    # ========================================================================
    # MANIPULAÇÃO
    # ========================================================================

    # POST /api/v1/log-management
    # Cria um novo registro
    $routes->post('log-management', 'API\v1\Log\ManagerController::store', ['as' => 'api.v1.log-management.create']);

    # PUT /api/v1/log-management
    # Atualiza um registro existente
    $routes->put('log-management', 'API\v1\Log\ManagerController::modify', ['as' => 'api.v1.log-management.update']);

    # ========================================================================
    # METADADOS
    # ========================================================================

    # GET /api/v1/log-management/columns
    # Retorna metadados completos das colunas da tabela
    $routes->get('log-management/columns', 'API\v1\Log\ManagerController::getColumnsMetadata', ['as' => 'api.v1.log-management.columns']);

    # GET /api/v1/log-management/column-names
    # Retorna apenas os nomes das colunas da tabela
    $routes->get('log-management/column-names', 'API\v1\Log\ManagerController::getColumnNames', ['as' => 'api.v1.log-management.column-names']);
});
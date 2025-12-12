<?php

/**
 * Rotas da API - Módulo UserCustomer Management (v1)
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

    # GET /api/v1/customer-management
    # Lista todos os registros ativos (sem deleted_at)
    $routes->get('customer-management', 'API\v1\UserCustomer\ManagerController::index', ['as' => 'api.v1.customer-management.index']);

    # GET /api/v1/customer-management/with-deleted
    # Lista todos os registros incluindo os marcados como deletados
    $routes->get('customer-management/with-deleted', 'API\v1\UserCustomer\ManagerController::indexWithDeleted', ['as' => 'api.v1.customer-management.with-deleted']);

    # POST /api/v1/customer-management/(:num)/with-deleted
    # Busca um registro específico por ID incluindo deletados
    $routes->post('customer-management/(:num)/with-deleted', 'API\v1\Log\ManagerController::showWithDeleted/$1', ['as' => 'api.v1.customer-management.show-with-deleted']);

    # GET /api/v1/customer-management/only-deleted
    # Lista apenas os registros marcados como deletados
    $routes->get('customer-management/only-deleted', 'API\v1\UserCustomer\ManagerController::indexOnlyDeleted', ['as' => 'api.v1.customer-management.only-deleted']);

    # ========================================================================
    # BUSCA
    # ========================================================================

    # GET /api/v1/customer-management/(:num)
    # Busca um registro específico por ID (apenas ativos)
    $routes->get('customer-management/(:num)', 'API\v1\UserCustomer\ManagerController::show/$1', ['as' => 'api.v1.customer-management.show']);

    # POST /api/v1/customer-management/(:num)/with-deleted
    # Busca um registro específico por ID incluindo deletados
    $routes->post('customer-management/(:num)/with-deleted', 'API\v1\UserCustomer\ManagerController::showWithDeleted/$1', ['as' => 'api.v1.customer-management.show-with-deleted']);

    # POST /api/v1/customer-management/search
    # Busca avançada com múltiplos filtros, operadores e opções
    $routes->post('customer-management/search', 'API\v1\UserCustomer\ManagerController::search', ['as' => 'api.v1.customer-management.search']);

    # ========================================================================
    # MANIPULAÇÃO
    # ========================================================================

    # PUT /api/v1/customer-management
    # Atualiza um registro existente
    $routes->put('customer-management', 'API\v1\UserCustomer\ManagerController::modify', ['as' => 'api.v1.customer-management.update']);

    # ========================================================================
    # EXCLUSÃO
    # ========================================================================

    # DELETE /api/v1/customer-management/(:num)
    # Soft delete - marca o registro como deletado (deleted_at)
    $routes->delete('customer-management/(:num)', 'API\v1\UserCustomer\ManagerController::delete/$1', ['as' => 'api.v1.customer-management.delete']);

    # DELETE /api/v1/customer-management/(:num)/hard
    # Hard delete - exclui permanentemente do banco de dados
    $routes->delete('customer-management/(:num)/hard', 'API\v1\UserCustomer\ManagerController::hardDelete/$1', ['as' => 'api.v1.customer-management.hard-delete']);

    # DELETE /api/v1/customer-management/clear
    # Limpa todos os registros marcados como deletados (hard delete em lote)
    $routes->delete('customer-management/clear', 'API\v1\UserCustomer\ManagerController::clearDeleted', ['as' => 'api.v1.customer-management.clear-deleted']);

    # ========================================================================
    # RESTAURAÇÃO
    # ========================================================================

    # PATCH /api/v1/customer-management/(:num)/restore
    # Restaura um registro soft deleted (remove deleted_at)
    $routes->patch('customer-management/(:num)/restore', 'API\v1\UserCustomer\ManagerController::restore/$1', ['as' => 'api.v1.customer-management.restore']);

    # ========================================================================
    # METADADOS
    # ========================================================================

    # GET /api/v1/customer-management/columns
    # Retorna metadados completos das colunas da tabela
    $routes->get('customer-management/columns', 'API\v1\UserCustomer\ManagerController::getColumnsMetadata', ['as' => 'api.v1.customer-management.columns']);

    # GET /api/v1/customer-management/column-names
    # Retorna apenas os nomes das colunas da tabela
    $routes->get('customer-management/column-names', 'API\v1\UserCustomer\ManagerController::getColumnNames', ['as' => 'api.v1.customer-management.column-names']);
});

# ========================================================================
# MANIPULAÇÃO
# ========================================================================

# POST /api/v1/customer-management
# Cria um novo registro
$routes->post('customer-management', 'API\v1\UserCustomer\ManagerController::store', ['as' => 'api.v1.customer-management.create']);
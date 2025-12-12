<?php

/**
 * Rotas da API - Módulo User Management (v1)
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

    # GET /api/v1/user-management
    # Lista todos os registros ativos (sem deleted_at)
    $routes->get('user-management', 'API\v1\UserManagement\ManagerController::index', ['as' => 'api.v1.user-management.index']);

    # GET /api/v1/user-management/with-deleted
    # Lista todos os registros incluindo os marcados como deletados
    $routes->get('user-management/with-deleted', 'API\v1\UserManagement\ManagerController::indexWithDeleted', ['as' => 'api.v1.user-management.with-deleted']);

    # GET /api/v1/user-management/only-deleted
    # Lista apenas os registros marcados como deletados
    $routes->get('user-management/only-deleted', 'API\v1\UserManagement\ManagerController::indexOnlyDeleted', ['as' => 'api.v1.user-management.only-deleted']);

    # ========================================================================
    # BUSCA
    # ========================================================================

    # GET /api/v1/user-management/(:num)
    # Busca um registro específico por ID (apenas ativos)
    $routes->get('user-management/(:num)', 'API\v1\UserManagement\ManagerController::show/$1', ['as' => 'api.v1.user-management.show']);

    # POST /api/v1/user-management/(:num)/with-deleted  
    # Busca um registro específico por ID incluindo deletados
    $routes->post('user-management/(:num)/with-deleted', 'API\v1\UserManagement\ManagerController::showWithDeleted/$1', ['as' => 'api.v1.user-management.show-with-deleted']);

    # POST /api/v1/user-management/search   
    # Busca avançada com múltiplos filtros, operadores e opções
    $routes->post('user-management/search', 'API\v1\UserManagement\ManagerController::search', ['as' => 'api.v1.user-management.search']);

    # ========================================================================  
    # MANIPULAÇÃO 
    # ========================================================================

    # PUT /api/v1/user-management   
    # Atualiza um registro existente
    $routes->put('user-management', 'API\v1\UserManagement\ManagerController::modify', ['as' => 'api.v1.user-management.update']);

    # ========================================================================  
    # EXCLUSÃO    
    # ========================================================================

    # DELETE /api/v1/user-management/(:num) 
    # Soft delete - marca o registro como deletado (deleted_at)
    $routes->delete('user-management/(:num)', 'API\v1\UserManagement\ManagerController::delete/$1', ['as' => 'api.v1.user-management.delete']);

    # DELETE /api/v1/user-management/(:num)/hard    
    # Hard delete - exclui permanentemente do banco de dados
    $routes->delete('user-management/(:num)/hard', 'API\v1\UserManagement\ManagerController::hardDelete/$1', ['as' => 'api.v1.user-management.hard-delete']);

    # DELETE /api/v1/user-management/clear  
    # Limpa todos os registros marcados como deletados (hard delete em lote)
    $routes->delete('user-management/clear', 'API\v1\UserManagement\ManagerController::clearDeleted', ['as' => 'api.v1.user-management.clear-deleted']);

    # ========================================================================  
    # RESTAURAÇÃO
    # ========================================================================

    # PATCH /api/v1/user-management/(:num)/restore  # Restaura um registro soft deleted (remove deleted_at)
    $routes->patch('user-management/(:num)/restore', 'API\v1\UserManagement\ManagerController::restore/$1', ['as' => 'api.v1.user-management.restore']);

    # ========================================================================  
    # METADADOS   
    # ========================================================================

    # GET /api/v1/user-management/columns   # Retorna metadados completos das colunas da tabela
    $routes->get('user-management/columns', 'API\v1\UserManagement\ManagerController::getColumnsMetadata', ['as' => 'api.v1.user-management.columns']);

    # GET /api/v1/user-management/column-names  # Retorna apenas os nomes das colunas da tabela
    $routes->get('user-management/column-names', 'API\v1\UserManagement\ManagerController::getColumnNames', ['as' => 'api.v1.user-management.column-names']);
});

# ========================================================================   
# LOGIN    
# ========================================================================  
# POST /api/v1/user-management/login
# Autenticar usuário e gerar token de acesso
$routes->post('user-management/login', 'API\v1\UserManagement\AuthController::login', ['as' => 'api.v1.user-management.login']);

# POST /api/v1/user-management  
# Cria um novo registro
$routes->post('user-management', 'API\v1\UserManagement\ManagerController::store', ['as' => 'api.v1.user-management.create']);

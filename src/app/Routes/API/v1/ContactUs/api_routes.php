<?php

/**
 * Rotas da API - Módulo ContactUs Management (v1)
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

    # GET /api/v1/contact-management
    # Lista todos os registros ativos (sem deleted_at)
    $routes->get('contact-management', 'API\v1\ContactUs\ManagerController::index', ['as' => 'api.v1.contact-management.index']);

    # GET /api/v1/contact-management/with-deleted
    # Lista todos os registros incluindo os marcados como deletados
    $routes->get('contact-management/with-deleted', 'API\v1\ContactUs\ManagerController::indexWithDeleted', ['as' => 'api.v1.contact-management.with-deleted']);

    # POST /api/v1/contact-management/(:num)/with-deleted
    # Busca um registro específico por ID incluindo deletados
    $routes->post('contact-management/(:num)/with-deleted', 'API\v1\Log\ManagerController::showWithDeleted/$1', ['as' => 'api.v1.contact-management.show-with-deleted']);

    # GET /api/v1/contact-management/only-deleted
    # Lista apenas os registros marcados como deletados
    $routes->get('contact-management/only-deleted', 'API\v1\ContactUs\ManagerController::indexOnlyDeleted', ['as' => 'api.v1.contact-management.only-deleted']);

    # ========================================================================
    # BUSCA
    # ========================================================================

    # GET /api/v1/contact-management/(:num)
    # Busca um registro específico por ID (apenas ativos)
    $routes->get('contact-management/(:num)', 'API\v1\ContactUs\ManagerController::show/$1', ['as' => 'api.v1.contact-management.show']);

    # POST /api/v1/contact-management/(:num)/with-deleted
    # Busca um registro específico por ID incluindo deletados
    $routes->post('contact-management/(:num)/with-deleted', 'API\v1\ContactUs\ManagerController::showWithDeleted/$1', ['as' => 'api.v1.contact-management.show-with-deleted']);

    # POST /api/v1/contact-management/search
    # Busca avançada com múltiplos filtros, operadores e opções
    $routes->post('contact-management/search', 'API\v1\ContactUs\ManagerController::search', ['as' => 'api.v1.contact-management.search']);

    # ========================================================================
    # MANIPULAÇÃO
    # ========================================================================

    # PUT /api/v1/contact-management
    # Atualiza um registro existente
    $routes->put('contact-management', 'API\v1\ContactUs\ManagerController::modify', ['as' => 'api.v1.contact-management.update']);

    # ========================================================================
    # EXCLUSÃO
    # ========================================================================

    # DELETE /api/v1/contact-management/(:num)
    # Soft delete - marca o registro como deletado (deleted_at)
    $routes->delete('contact-management/(:num)', 'API\v1\ContactUs\ManagerController::delete/$1', ['as' => 'api.v1.contact-management.delete']);

    # DELETE /api/v1/contact-management/(:num)/hard
    # Hard delete - exclui permanentemente do banco de dados
    $routes->delete('contact-management/(:num)/hard', 'API\v1\ContactUs\ManagerController::hardDelete/$1', ['as' => 'api.v1.contact-management.hard-delete']);

    # DELETE /api/v1/contact-management/clear
    # Limpa todos os registros marcados como deletados (hard delete em lote)
    $routes->delete('contact-management/clear', 'API\v1\ContactUs\ManagerController::clearDeleted', ['as' => 'api.v1.contact-management.clear-deleted']);

    # ========================================================================
    # RESTAURAÇÃO
    # ========================================================================

    # PATCH /api/v1/contact-management/(:num)/restore
    # Restaura um registro soft deleted (remove deleted_at)
    $routes->patch('contact-management/(:num)/restore', 'API\v1\ContactUs\ManagerController::restore/$1', ['as' => 'api.v1.contact-management.restore']);

    # ========================================================================
    # METADADOS
    # ========================================================================

    # GET /api/v1/contact-management/columns
    # Retorna metadados completos das colunas da tabela
    $routes->get('contact-management/columns', 'API\v1\ContactUs\ManagerController::getColumnsMetadata', ['as' => 'api.v1.contact-management.columns']);

    # GET /api/v1/contact-management/column-names
    # Retorna apenas os nomes das colunas da tabela
    $routes->get('contact-management/column-names', 'API\v1\ContactUs\ManagerController::getColumnNames', ['as' => 'api.v1.contact-management.column-names']);
});

# ========================================================================
# MANIPULAÇÃO
# ========================================================================

# POST /api/v1/contact-management
# Cria um novo registro
$routes->post('contact-management', 'API\v1\ContactUs\ManagerController::store', ['as' => 'api.v1.contact-management.create']);
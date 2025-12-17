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

    # GET /api/v1/contact-us
    # Lista todos os registros ativos (sem deleted_at)
    $routes->get('contact-us', 'API\v1\ContactUs\ManagerController::index', ['as' => 'api.v1.contact-us.index']);

    # GET /api/v1/contact-us/with-deleted
    # Lista todos os registros incluindo os marcados como deletados
    $routes->get('contact-us/with-deleted', 'API\v1\ContactUs\ManagerController::indexWithDeleted', ['as' => 'api.v1.contact-us.with-deleted']);

    # POST /api/v1/contact-us/(:num)/with-deleted
    # Busca um registro específico por ID incluindo deletados
    $routes->post('contact-us/(:num)/with-deleted', 'API\v1\Log\ManagerController::showWithDeleted/$1', ['as' => 'api.v1.contact-us.show-with-deleted']);

    # GET /api/v1/contact-us/only-deleted
    # Lista apenas os registros marcados como deletados
    $routes->get('contact-us/only-deleted', 'API\v1\ContactUs\ManagerController::indexOnlyDeleted', ['as' => 'api.v1.contact-us.only-deleted']);

    # ========================================================================
    # BUSCA
    # ========================================================================

    # GET /api/v1/contact-us/(:num)
    # Busca um registro específico por ID (apenas ativos)
    $routes->get('contact-us/(:num)', 'API\v1\ContactUs\ManagerController::show/$1', ['as' => 'api.v1.contact-us.show']);

    # POST /api/v1/contact-us/(:num)/with-deleted
    # Busca um registro específico por ID incluindo deletados
    $routes->post('contact-us/(:num)/with-deleted', 'API\v1\ContactUs\ManagerController::showWithDeleted/$1', ['as' => 'api.v1.contact-us.show-with-deleted']);

    # POST /api/v1/contact-us/search
    # Busca avançada com múltiplos filtros, operadores e opções
    $routes->post('contact-us/search', 'API\v1\ContactUs\ManagerController::search', ['as' => 'api.v1.contact-us.search']);

    # ========================================================================
    # MANIPULAÇÃO
    # ========================================================================

    # PUT /api/v1/contact-us
    # Atualiza um registro existente
    $routes->put('contact-us', 'API\v1\ContactUs\ManagerController::modify', ['as' => 'api.v1.contact-us.update']);

    # ========================================================================
    # EXCLUSÃO
    # ========================================================================

    # DELETE /api/v1/contact-us/(:num)
    # Soft delete - marca o registro como deletado (deleted_at)
    $routes->delete('contact-us/(:num)', 'API\v1\ContactUs\ManagerController::delete/$1', ['as' => 'api.v1.contact-us.delete']);

    # DELETE /api/v1/contact-us/(:num)/hard
    # Hard delete - exclui permanentemente do banco de dados
    $routes->delete('contact-us/(:num)/hard', 'API\v1\ContactUs\ManagerController::hardDelete/$1', ['as' => 'api.v1.contact-us.hard-delete']);

    # DELETE /api/v1/contact-us/clear
    # Limpa todos os registros marcados como deletados (hard delete em lote)
    $routes->delete('contact-us/clear', 'API\v1\ContactUs\ManagerController::clearDeleted', ['as' => 'api.v1.contact-us.clear-deleted']);

    # ========================================================================
    # RESTAURAÇÃO
    # ========================================================================

    # PATCH /api/v1/contact-us/(:num)/restore
    # Restaura um registro soft deleted (remove deleted_at)
    $routes->patch('contact-us/(:num)/restore', 'API\v1\ContactUs\ManagerController::restore/$1', ['as' => 'api.v1.contact-us.restore']);

    # ========================================================================
    # METADADOS
    # ========================================================================

    # GET /api/v1/contact-us/columns
    # Retorna metadados completos das colunas da tabela
    $routes->get('contact-us/columns', 'API\v1\ContactUs\ManagerController::getColumnsMetadata', ['as' => 'api.v1.contact-us.columns']);

    # GET /api/v1/contact-us/column-names
    # Retorna apenas os nomes das colunas da tabela
    $routes->get('contact-us/column-names', 'API\v1\ContactUs\ManagerController::getColumnNames', ['as' => 'api.v1.contact-us.column-names']);
});

# ========================================================================
# MANIPULAÇÃO
# ========================================================================

# POST /api/v1/contact-us
# Cria um novo registro
$routes->post('contact-us', 'API\v1\ContactUs\ManagerController::store', ['as' => 'api.v1.contact-us.create']);
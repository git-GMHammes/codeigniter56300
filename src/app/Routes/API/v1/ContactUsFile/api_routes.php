<?php

/**
 * Rotas da API - Módulo ContactUsFile (v1)
 *
 * Arquivo: app/Routes/API/v1/ContactUsFile/api_routes.php
 */

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->group('', ['filter' => 'auth'], function ($routes) {
    # ========================================================================
    # LISTAGEM - Uploads de ContactUsFile
    # ========================================================================

    # GET /api/v1/contact-file
    # Lista todos os arquivos (ativos)
    $routes->get('contact-file', 'API\v1\ContactUsFile\ManagerController::index', ['as' => 'api.v1.contact-file.index']);

    # GET /api/v1/contact-file/with-deleted
    # Lista todos os arquivos (incluindo deletados)
    $routes->get('contact-file/with-deleted', 'API\v1\ContactUsFile\ManagerController::indexWithDeleted', ['as' => 'api.v1.contact-file.with-deleted']);

    # GET /api/v1/contact-file/only-deleted
    # Lista apenas os arquivos deletados
    $routes->get('contact-file/only-deleted', 'API\v1\ContactUsFile\ManagerController::indexOnlyDeleted', ['as' => 'api.v1.contact-file.only-deleted']);

    # ========================================================================
    # BUSCA / SHOW
    # ========================================================================

    # GET /api/v1/contact-file/(:num)
    # Recupera um arquivo por ID
    $routes->get('contact-file/(:num)', 'API\v1\ContactUsFile\ManagerController::show/$1', ['as' => 'api.v1.contact-file.show']);

    # POST /api/v1/contact-file/(:num)/with-deleted
    # Recupera um arquivo por ID incluindo deletados
    $routes->post('contact-file/(:num)/with-deleted', 'API\v1\ContactUsFile\ManagerController::showWithDeleted/$1', ['as' => 'api.v1.contact-file.show-with-deleted']);

    # POST /api/v1/contact-file/search
    # Busca avançada de arquivos
    $routes->post('contact-file/search', 'API\v1\ContactUsFile\ManagerController::search', ['as' => 'api.v1.contact-file.search']);

    # ========================================================================
    # MANIPULAÇÃO / EXCLUSÃO DE ARQUIVOS
    # ========================================================================

    # DELETE /api/v1/contact-file/(:num)
    # Soft delete de um arquivo
    $routes->delete('contact-file/(:num)', 'API\v1\ContactUsFile\ManagerController::delete/$1', ['as' => 'api.v1.contact-file.delete']);

    # PATCH /api/v1/contact-file/(:num)/restore
    # Restaura um arquivo soft-deleted
    $routes->patch('contact-file/(:num)/restore', 'API\v1\ContactUsFile\ManagerController::restore/$1', ['as' => 'api.v1.contact-file.restore']);

    # ========================================================================
    # METADADOS
    # ========================================================================

    # GET /api/v1/contact-file/columns
    $routes->get('contact-file/columns', 'API\v1\ContactUsFile\ManagerController::getColumnsMetadata', ['as' => 'api.v1.contact-file.columns']);

    # GET /api/v1/contact-file/column-names
    $routes->get('contact-file/column-names', 'API\v1\ContactUsFile\ManagerController::getColumnNames', ['as' => 'api.v1.contact-file.column-names']);
});
<?php

namespace App\Controllers\API\v1\UserCustomerFile;

use App\Controllers\API\v1\BaseManagerController;
use App\Requests\v1\UserCustomerFile\SearchRequest;
use App\Services\v1\UserCustomerFile\ManagerService;

class ManagerController extends BaseManagerController
{
    protected $searchRequest;
    protected $service;

    public function __construct()
    {
        parent::__construct();

        // Requests / Services específicos do módulo de arquivos
        $this->searchRequest = new SearchRequest();
        $this->service = new ManagerService();
    }

    # ========================================================================
    # LISTAGEM
    # ========================================================================

    # GET /api/v1/user-customer-files
    public function index()
    {
        $params = $this->getPaginationParams();

        return $this->executeService(
            fn() => $this->service->index($params['page'], $params['perPage']),
            'Uploads recuperados com sucesso.',
            true
        );
    }

    # GET /api/v1/user-customer-files/with-deleted
    public function indexWithDeleted()
    {
        $params = $this->getPaginationParams();

        return $this->executeService(
            fn() => $this->service->indexWithDeleted($params['page'], $params['perPage']),
            'Uploads (incluindo deletados) recuperados com sucesso.',
            true
        );
    }

    # GET /api/v1/user-customer-files/only-deleted
    public function indexOnlyDeleted()
    {
        $params = $this->getPaginationParams();

        return $this->executeService(
            fn() => $this->service->indexOnlyDeleted($params['page'], $params['perPage']),
            'Uploads deletados recuperados com sucesso.',
            true
        );
    }

    # ========================================================================
    # BUSCA / SHOW
    # ========================================================================

    # GET /api/v1/user-customer-files/(:num)
    public function show($id = null)
    {
        if ($error = $this->validateId($id)) {
            return $error;
        }

        return $this->executeService(
            fn() => $this->service->show($id),
            'Upload recuperado com sucesso.'
        );
    }

    # POST /api/v1/user-customer-files/(:num)/with-deleted
    public function showWithDeleted($id = null)
    {
        if ($error = $this->validateId($id)) {
            return $error;
        }

        return $this->executeService(
            fn() => $this->service->showWithDeleted($id),
            'Upload (incluindo deletado) recuperado com sucesso.'
        );
    }

    # POST /api/v1/user-customer-files/search
    public function search()
    {
        $validation = $this->validateRequest($this->searchRequest, 'validateSearch');
        if ($validation['hasError']) {
            return $validation['response'];
        }

        $params = $this->getPaginationParams();

        $result = $this->service->search($validation['data'], $params['page'], $params['perPage']);

        if (!$result['success']) {
            return $this->apiResponse->internalError($result['message']);
        }

        if (empty($result['data']['data']) || ($result['data']['meta']['total'] ?? 0) === 0) {
            return $this->apiResponse->notFound('Nenhum upload encontrado com os critérios informados.');
        }

        $this->apiResponse->setPagination($result['data']['meta']);
        $this->apiResponse->setUrlMetadata();

        return $this->apiResponse->success(
            $result['data']['data'],
            'Busca de uploads realizada com sucesso.'
        );
    }

    # ========================================================================
    # EXCLUSÃO
    # ========================================================================

    # DELETE /api/v1/user-customer-files/(:num)
    public function delete($id = null)
    {
        if ($error = $this->validateId($id)) {
            return $error;
        }

        return $this->executeService(
            fn() => $this->service->delete($id),
            'Upload marcado como deletado com sucesso.'
        );
    }

    # ========================================================================
    # RESTAURAÇÃO
    # ========================================================================

    # PATCH /api/v1/user-customer-files/(:num)/restore
    public function restore($id = null)
    {
        if ($error = $this->validateId($id)) {
            return $error;
        }

        return $this->executeService(
            fn() => $this->service->restore((int) $id),
            'Upload restaurado com sucesso.'
        );
    }

    # ========================================================================
    # METADADOS
    # ========================================================================

    # GET /api/v1/user-customer-files/columns
    public function getColumnsMetadata()
    {
        return $this->executeService(
            fn() => $this->service->getColumnsMetadata(),
            'Metadados das colunas de uploads recuperados com sucesso.'
        );
    }

    # GET /api/v1/user-customer-files/column-names
    public function getColumnNames()
    {
        return $this->executeService(
            fn() => $this->service->getColumnNames(),
            'Nomes das colunas de uploads recuperados com sucesso.'
        );
    }
}
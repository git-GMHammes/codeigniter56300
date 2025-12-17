<?php

namespace App\Controllers\API\v1\UserCustomerManagement;

use App\Controllers\API\v1\BaseManagerController;
use App\Requests\v1\UserCustomerManagement\SearchRequest;
// use App\Requests\v1\UserCustomerManagement\StoreRequest;
// use App\Requests\v1\UserCustomerManagement\ModifyRequest;
use App\Services\v1\UserCustomerManagement\ManagerService;

class ManagerController extends BaseManagerController
{
    protected $searchRequest;
    // protected $storeRequest;
    // protected $modifyRequest;
    public function __construct()
    {
        parent::__construct();
        $this->searchRequest = new SearchRequest();
        // $this->modifyRequest = new ModifyRequest();
        // $this->storeRequest = new StoreRequest();
        $this->service = new ManagerService();
    }

    # ========================================================================
    # LISTAGEM
    # ========================================================================

    # GET /api/v1/user-customer
    public function index()
    {
        $params = $this->getPaginationParams();

        return $this->executeService(
            fn() => $this->service->index($params['page'], $params['perPage']),
            'Registros recuperados com sucesso.',
            true
        );
    }

    # GET /api/v1/user-customer/with-deleted
    public function indexWithDeleted()
    {
        $params = $this->getPaginationParams();

        return $this->executeService(
            fn() => $this->service->indexWithDeleted($params['page'], $params['perPage']),
            'Registros (incluindo deletados) recuperados com sucesso.',
            true
        );
    }

    # GET /api/v1/user-customer/only-deleted
    public function indexOnlyDeleted()
    {
        $params = $this->getPaginationParams();

        return $this->executeService(
            fn() => $this->service->indexOnlyDeleted($params['page'], $params['perPage']),
            'Registros deletados recuperados com sucesso.',
            true
        );
    }

    # ========================================================================
    # BUSCA
    # ========================================================================

    # GET /api/v1/user-customer/(:num)
    public function show($id = null)
    {
        if ($error = $this->validateId($id))
            return $error;

        return $this->executeService(
            fn() => $this->service->show($id),
            'Registro recuperado com sucesso.'
        );
    }

    # POST /api/v1/user-customer/(:num)/with-deleted
    public function showWithDeleted($id = null)
    {
        if ($error = $this->validateId($id))
            return $error;

        return $this->executeService(
            fn() => $this->service->showWithDeleted($id),
            'Registro (incluindo deletado) recuperado com sucesso.'
        );
    }

    # POST /api/v1/user-customer/search
    public function search()
    {
        $validation = $this->validateRequest($this->searchRequest, 'validateSearch');
        if ($validation['hasError'])
            return $validation['response'];

        $params = $this->getPaginationParams();

        $result = $this->service->search($validation['data'], $params['page'], $params['perPage']);

        if (!$result['success']) {
            return $this->apiResponse->internalError($result['message']);
        }

        if (empty($result['data']['data']) || $result['data']['meta']['total'] === 0) {
            return $this->apiResponse->notFound('Nenhum registro encontrado com os critérios informados.');
        }

        $this->apiResponse->setPagination($result['data']['meta']);
        $this->apiResponse->setUrlMetadata();

        return $this->apiResponse->success(
            $result['data']['data'],
            'Busca realizada com sucesso.'
        );
    }

    # ========================================================================
    # METADADOS
    # ========================================================================

    # GET /api/v1/user-customer/columns
    public function getColumnsMetadata()
    {
        return $this->executeService(
            fn() => $this->service->getColumnsMetadata(),
            'Metadados das colunas recuperados com sucesso.'
        );
    }

    # GET /api/v1/user-customer/column-names
    public function getColumnNames()
    {
        return $this->executeService(
            fn() => $this->service->getColumnNames(),
            'Nomes das colunas recuperados com sucesso.'
        );
    }
}
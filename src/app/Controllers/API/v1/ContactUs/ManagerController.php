<?php

namespace App\Controllers\API\v1\ContactUs;

use App\Controllers\API\v1\BaseManagerController;
use App\Requests\v1\ContactUs\StoreRequest;
use App\Requests\v1\ContactUs\SearchRequest;
use App\Requests\v1\ContactUs\ModifyRequest;
use App\Services\v1\ContactUs\ManagerService;

class ManagerController extends BaseManagerController
{
    protected $storeRequest;
    protected $searchRequest;
    protected $modifyRequest;

    public function __construct()
    {
        parent::__construct();
        $this->storeRequest = new StoreRequest();
        $this->searchRequest = new SearchRequest();
        $this->modifyRequest = new ModifyRequest();
        $this->service = new ManagerService();
    }

    # ========================================================================
    # LISTAGEM
    # ========================================================================

    # GET /api/v1/contact-us
    public function index()
    {
        $params = $this->getPaginationParams();

        return $this->executeService(
            fn() => $this->service->index($params['page'], $params['perPage']),
            'Registros recuperados com sucesso.',
            true
        );
    }

    # GET /api/v1/contact-us/with-deleted
    public function indexWithDeleted()
    {
        $params = $this->getPaginationParams();

        return $this->executeService(
            fn() => $this->service->indexWithDeleted($params['page'], $params['perPage']),
            'Registros (incluindo deletados) recuperados com sucesso.',
            true
        );
    }

    # GET /api/v1/contact-us/only-deleted
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

    # GET /api/v1/contact-us/(:num)
    public function show($id = null)
    {
        if ($error = $this->validateId($id))
            return $error;

        return $this->executeService(
            fn() => $this->service->show($id),
            'Registro recuperado com sucesso.'
        );
    }

    # POST /api/v1/contact-us/(:num)/with-deleted
    public function showWithDeleted($id = null)
    {
        if ($error = $this->validateId($id))
            return $error;

        return $this->executeService(
            fn() => $this->service->showWithDeleted($id),
            'Registro (incluindo deletado) recuperado com sucesso.'
        );
    }

    # POST /api/v1/contact-us/search
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
    # MANIPULAÇÃO
    # ========================================================================

    # POST /api/v1/contact-us
    public function store()
    {
        $validation = $this->validateRequest($this->storeRequest, 'validateCreate');
        if ($validation['hasError'])
            return $validation['response'];

        // Pegar arquivos enviados pelo campo 'files[]'
        // Retorna array de UploadedFile ou array vazia
        $files = $this->request->getFileMultiple('files');
        $files = is_array($files) ? $files : [];

        return $this->executeService(
            fn() => $this->service->store($validation['data'], $files),
            'Usuário criado com sucesso.',
            false,
            201
        );
    }


    # PUT /api/v1/contact-us
    public function modify()
    {
        $validation = $this->validateRequest($this->modifyRequest, 'validateUpdate');
        if ($validation['hasError'])
            return $validation['response'];

        return $this->executeService(
            fn() => $this->service->modify($validation['data']),
            'Usuário atualizado com sucesso.'
        );
    }

    # ========================================================================
    # EXCLUSÃO
    # ========================================================================

    # DELETE /api/v1/contact-us/(:num)
    public function delete($id = null)
    {
        if ($error = $this->validateId($id))
            return $error;

        return $this->executeService(
            fn() => $this->service->delete($id),
            'Registro deletado com sucesso.'
        );
    }

    # DELETE /api/v1/contact-us/(:num)/hard
    public function hardDelete($id = null)
    {
        if ($error = $this->validateId($id))
            return $error;

        return $this->executeService(
            fn() => $this->service->hardDelete($id),
            'Registro excluído permanentemente com sucesso.'
        );
    }

    # DELETE /api/v1/contact-us/clear
    public function clearDeleted()
    {
        return $this->executeService(
            fn() => $this->service->clearDeleted(),
            'Registros soft deleted limpos com sucesso.'
        );
    }

    # ========================================================================
    # RESTAURAÇÃO
    # ========================================================================

    # PATCH /api/v1/contact-us/(:num)/restore
    public function restore($id = null)
    {
        if ($error = $this->validateId($id))
            return $error;

        return $this->executeService(
            fn() => $this->service->restore((int) $id),
            'Registro restaurado com sucesso.'
        );
    }

    # ========================================================================
    # METADADOS
    # ========================================================================

    # GET /api/v1/contact-us/columns
    public function getColumnsMetadata()
    {
        return $this->executeService(
            fn() => $this->service->getColumnsMetadata(),
            'Metadados das colunas recuperados com sucesso.'
        );
    }

    # GET /api/v1/contact-us/column-names
    public function getColumnNames()
    {
        return $this->executeService(
            fn() => $this->service->getColumnNames(),
            'Nomes das colunas recuperados com sucesso.'
        );
    }
}
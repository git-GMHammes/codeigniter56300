<?php

namespace App\Controllers\API\v1\UserManagement;

use CodeIgniter\RESTful\ResourceController;
use App\Requests\v1\UserManagement\StoreRequest;
use App\Requests\v1\UserManagement\SearchRequest;
use App\Services\v1\UserManagement\ManagerService;
use App\Requests\v1\UserManagement\ModifyRequest;
use App\Libraries\ApiResponse;

# IMPORTS DOS REQUESTS (criar depois)
# use App\Requests\v1\UserManagement\ModifyRequest;
# use App\Requests\v1\UserManagement\SearchRequest;

class ManagerController extends ResourceController
{
    protected $storeRequest;
    protected $searchRequest;
    protected $modifyRequest;
    protected $service;
    protected $apiResponse;


    # ========================================================================
    # LISTAGEM
    # ========================================================================

    public function __construct()
    {
        $this->storeRequest = new StoreRequest();
        $this->searchRequest = new SearchRequest();
        $this->modifyRequest = new ModifyRequest();
        $this->service = new ManagerService();
        $this->apiResponse = new ApiResponse();

    }

    # GET /api/v1/user-management
    public function index()
    {
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = min(100, max(1, (int) ($this->request->getGet('limit') ?? 15)));

        $result = $this->service->index($page, $perPage);

        if (!$result['success']) {
            return $this->apiResponse->internalError($result['message']);
        }

        $this->apiResponse->setPagination($result['data']['meta']);
        $this->apiResponse->setUrlMetadata();

        return $this->apiResponse->success(
            $result['data']['data'],
            'Registros recuperados com sucesso.'
        );
    }

    # GET /api/v1/user-management/with-deleted
    public function indexWithDeleted()
    {
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = min(100, max(1, (int) ($this->request->getGet('limit') ?? 15)));

        $result = $this->service->indexWithDeleted($page, $perPage);

        if (!$result['success']) {
            return $this->apiResponse->internalError($result['message']);
        }

        $this->apiResponse->setPagination($result['data']['meta']);
        $this->apiResponse->setUrlMetadata();

        return $this->apiResponse->success(
            $result['data']['data'],
            'Registros (incluindo deletados) recuperados com sucesso.'
        );
    }

    # GET /api/v1/user-management/only-deleted
    public function indexOnlyDeleted()
    {
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = min(100, max(1, (int) ($this->request->getGet('limit') ?? 15)));

        $result = $this->service->indexOnlyDeleted($page, $perPage);

        if (!$result['success']) {
            return $this->apiResponse->internalError($result['message']);
        }

        $this->apiResponse->setPagination($result['data']['meta']);
        $this->apiResponse->setUrlMetadata();

        return $this->apiResponse->success(
            $result['data']['data'],
            'Registros deletados recuperados com sucesso.'
        );
    }

    # ========================================================================
    # BUSCA
    # ========================================================================

    # GET /api/v1/user-management/(:num)
    public function show($id = null)
    {
        if (!$id) {
            return $this->apiResponse->badRequest('ID não fornecido.');
        }

        $result = $this->service->show($id);

        if (!$result['success']) {
            return $this->apiResponse->notFound($result['message']);
        }

        return $this->apiResponse->success($result['data'], 'Registro recuperado com sucesso.');
    }

    # POST /api/v1/user-management/(:num)/with-deleted
    public function showWithDeleted($id = null)
    {
        if (!$id) {
            return $this->apiResponse->badRequest('ID não fornecido.');
        }

        $result = $this->service->showWithDeleted($id);

        if (!$result['success']) {
            return $this->apiResponse->notFound($result['message']);
        }

        $this->apiResponse->setUrlMetadata();

        return $this->apiResponse->success($result['data'], 'Registro (incluindo deletado) recuperado com sucesso.');
    }

    # POST /api/v1/user-management/search
    public function search()
    {
        // [1] VALIDAÇÃO
        $validation = $this->searchRequest->validateSearch();

        if (!$validation['valid']) {
            return $this->apiResponse->validationError(
                $validation['errors'],
                'Dados de busca inválidos.'
            );
        }

        // [2] PARÂMETROS DE PAGINAÇÃO
        $page = max(1, (int) ($this->request->getGet('page') ?? 1));
        $perPage = min(100, max(1, (int) ($this->request->getGet('limit') ?? 15)));

        // [3] SERVICE
        $result = $this->service->search($validation['data'], $page, $perPage);

        if (!$result['success']) {
            return $this->apiResponse->internalError($result['message']);
        }

        // [4] VERIFICA SE ENCONTROU RESULTADOS
        if (empty($result['data']['data']) || $result['data']['meta']['total'] === 0) {
            return $this->apiResponse->notFound('Nenhum registro encontrado com os critérios informados.');
        }

        // [5] RESPOSTA
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

    # POST /api/v1/user-management
    public function store()
    {
        // [1] VALIDAÇÃO
        $validation = $this->storeRequest->validateCreate();

        if (!$validation['valid']) {
            return $this->apiResponse->validationError(
                $validation['errors'],
                'Dados de entrada inválidos.'
            );
        }

        // [2] VERIFICAÇÃO EXTRA
        if (empty($validation['data']) || !is_array($validation['data'])) {
            return $this->apiResponse->internalError(
                'Erro ao processar dados de entrada.'
            );
        }

        // [3] SERVICE
        $result = $this->service->store($validation['data']);

        if (!$result['success']) {
            return $this->apiResponse->internalError($result['message']);
        }

        // [4] SUCESSO
        return $this->apiResponse->created(
            $result['data'],
            'Usuário criado com sucesso.'
        );
    }

    # PUT /api/v1/user-management
    public function modify()
    {
        // [1] VALIDAÇÃO
        $validation = $this->modifyRequest->validateUpdate();

        if (!$validation['valid']) {
            return $this->apiResponse->validationError(
                $validation['errors'],
                'Dados de entrada inválidos.'
            );
        }

        // [2] SERVICE
        $result = $this->service->modify($validation['data']);

        if (!$result['success']) {
            return $this->apiResponse->internalError($result['message']);
        }

        // [3] SUCESSO
        return $this->apiResponse->success(
            $result['data'],
            'Usuário atualizado com sucesso.'
        );
    }

    # ========================================================================
    # EXCLUSÃO
    # EXCLUSÃO
    # ========================================================================

    # DELETE /api/v1/user-management/(:num)
    public function delete($id = null)
    {
        if (!$id) {
            return $this->apiResponse->badRequest('ID não fornecido.');
        }

        $result = $this->service->delete($id);

        if (!$result['success']) {
            return $this->apiResponse->notFound($result['message']);
        }

        return $this->apiResponse->success($result['data'], 'Registro deletado com sucesso.');
    }

    # DELETE /api/v1/user-management/(:num)/hard
    public function hardDelete($id = null)
    {
        if (!$id) {
            return $this->apiResponse->badRequest('ID não fornecido.');
        }

        $result = $this->service->hardDelete($id);

        if (!$result['success']) {
            return $this->apiResponse->notFound($result['message']);
        }

        return $this->apiResponse->success($result['data'], 'Registro excluído permanentemente com sucesso.');
    }

    # DELETE /api/v1/user-management/clear
    public function clearDeleted()
    {
        $result = $this->service->clearDeleted();

        if (!$result['success']) {
            return $this->apiResponse->notFound($result['message']);
        }

        return $this->apiResponse->success(
            $result['data'],
            'Registros soft deleted limpos com sucesso.'
        );
    }

    # ========================================================================
    # RESTAURAÇÃO
    # ========================================================================

    # PATCH /api/v1/user-management/(:num)/restore
    public function restore($id = null)
    {
        if (!$id) {
            return $this->apiResponse->badRequest('ID não fornecido.');
        }

        $result = $this->service->restore((int) $id);

        if (!$result['success']) {
            // Segue padrão dos outros métodos: quando falha retornar notFound com a mensagem
            return $this->apiResponse->notFound($result['message']);
        }

        return $this->apiResponse->success(
            $result['data'],
            'Registro restaurado com sucesso.'
        );
    }

    # ========================================================================
    # METADADOS
    # ========================================================================

    # GET /api/v1/user-management/columns
    public function getColumnsMetadata()
    {
        $result = $this->service->getColumnsMetadata();

        if (!$result['success']) {
            return $this->apiResponse->internalError($result['message'] ?? 'Erro ao recuperar metadados.');
        }

        return $this->apiResponse->success(
            $result['data'],
            'Metadados das colunas recuperados com sucesso.'
        );
    }

    # GET /api/v1/user-management/column-names
    public function getColumnNames()
    {
        $result = $this->service->getColumnNames();

        if (!$result['success']) {
            return $this->apiResponse->internalError($result['message'] ?? 'Erro ao recuperar nomes das colunas.');
        }

        return $this->apiResponse->success(
            $result['data'],
            'Nomes das colunas recuperados com sucesso.'
        );
    }
}
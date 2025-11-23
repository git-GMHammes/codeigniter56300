<?php

namespace App\Controllers\API\v1\UserManagement;

use CodeIgniter\RESTful\ResourceController;
use App\Requests\v1\UserManagement\StoreRequest;
use App\Services\v1\UserManagement\ManagerService;

# IMPORTS DOS REQUESTS (criar depois)
# use App\Requests\v1\UserManagement\ModifyRequest;
# use App\Requests\v1\UserManagement\SearchRequest;

class ManagerController extends ResourceController
{
    protected $storeRequest;
    protected $service;

    # ========================================================================
    # LISTAGEM
    # ========================================================================

    public function __construct()
    {
        $this->storeRequest = new StoreRequest();
        $this->service = new ManagerService();

    }

    # GET /api/v1/user-management
    public function index()
    {
        exit('index');
    }

    # GET /api/v1/user-management/with-deleted
    public function indexWithDeleted()
    {
        exit('indexWithDeleted');
    }

    # GET /api/v1/user-management/only-deleted
    public function indexOnlyDeleted()
    {
        exit('indexOnlyDeleted');
    }

    # ========================================================================
    # BUSCA
    # ========================================================================

    # GET /api/v1/user-management/(:num)
    public function show($id = null)
    {
        exit('show');
    }

    # POST /api/v1/user-management/(:num)/with-deleted
    public function showWithDeleted($id = null)
    {
        exit('showWithDeleted');
    }

    # POST /api/v1/user-management/search
    # ✅ PRECISA DE REQUEST - SearchRequest
    public function search()
    {
        # Exemplo de uso futuro:
        # $validation = new SearchRequest();
        # if (!$validation->validate($this->request->getPost())) {
        #     return $this->failValidationErrors($validation->getErrors());
        # }

        exit('search');
    }

    # ========================================================================
    # MANIPULAÇÃO
    # ========================================================================

    # POST /api/v1/user-management
    # ✅ PRECISA DE REQUEST - StoreRequest
    public function store()
    {
        # Valida os dados recebidos
        $validation = $this->storeRequest->validateCreate();

        echo "=== PASSO 1: VALIDACAO ===<br/><br/>";
        myPrint('-----------', $validation, true);
        echo "<br/><br/>";

        # Verifica se a validacao passou
        if (!$validation['valid']) {
            echo "=== VALIDACAO FALHOU ===<br/><br/>";
            echo "Erros encontrados:<br/>";
            myPrint('------------', $validation['errors']);
        }

        echo "=== VALIDACAO PASSOU ===<br/><br/>";

        # ADICIONE ESTA VERIFICAÇÃO EXTRA ✅
        if (empty($validation['data']) || !is_array($validation['data'])) {
            echo "=== ERRO: DADOS INVALIDOS ===<br/><br/>";
            echo "validation['data'] esta vazio ou nao e array<br/>";
            exit();
        }

        # Chama o Service para criar o usuario
        $result = $this->service->store($validation['data']);

        echo "=== PASSO 2: SERVICE ===<br/><br/>";
        print_r($result);
        echo "<br/><br/>";

        # Verifica o resultado do Service
        if ($result['success']) {
            echo "=== USUARIO CRIADO COM SUCESSO ===<br/><br/>";
            echo "Dados do usuario:<br/>";
            myPrint('----------', $result['data']);
        } else {
            echo "=== ERRO AO CRIAR USUARIO ===<br/><br/>";
            myPrint('Mensagem: ', $result['message']);
        }

        exit();
    }

    # PUT /api/v1/user-management
    # ✅ PRECISA DE REQUEST - ModifyRequest
    public function modify()
    {
        # Exemplo de uso futuro:
        # $validation = new ModifyRequest();
        # if (!$validation->validate($this->request->getJSON(true))) {
        #     return $this->failValidationErrors($validation->getErrors());
        # }

        exit('modify');
    }

    # ========================================================================
    # EXCLUSÃO
    # ========================================================================

    # DELETE /api/v1/user-management/(:num)
    public function delete($id = null)
    {
        exit('delete');
    }

    # DELETE /api/v1/user-management/(:num)/hard
    public function hardDelete($id = null)
    {
        exit('hardDelete');
    }

    # DELETE /api/v1/user-management/clear
    public function clearDeleted()
    {
        exit('clearDeleted');
    }

    # ========================================================================
    # RESTAURAÇÃO
    # ========================================================================

    # PATCH /api/v1/user-management/(:num)/restore
    public function restore($id = null)
    {
        exit('restore');
    }

    # ========================================================================
    # METADADOS
    # ========================================================================

    # GET /api/v1/user-management/columns
    public function getColumnsMetadata()
    {
        exit('getColumnsMetadata');
    }

    # GET /api/v1/user-management/column-names
    public function getColumnNames()
    {
        exit('getColumnNames');
    }
}
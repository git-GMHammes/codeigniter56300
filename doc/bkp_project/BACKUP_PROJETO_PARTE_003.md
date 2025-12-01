# Backup - Módulo User Management API v1

**Sistema:** CodeIgniter 4.6  
**Projeto:** PRODERJ - Government of Rio de Janeiro State  
**Desenvolvedor:** Gustavo - Senior Systems Analyst  
**Data:** 30/11/2025

---

## 📋 Contexto do Projeto

Este backup contém a implementação completa de um módulo de gerenciamento de usuários (User Management) desenvolvido em CodeIgniter 4.6, seguindo padrões profissionais de arquitetura MVC + Services + Requests.

### Características Principais

- ✅ API RESTful completa (CRUD + operações avançadas)
- ✅ Soft Delete implementado
- ✅ Sistema de busca avançada com filtros e operadores
- ✅ Validação robusta com Request classes
- ✅ Paginação com metadados
- ✅ Respostas padronizadas (ApiResponse)
- ✅ Autenticação com password hash
- ✅ Metadados de colunas da tabela
- ✅ Múltiplas conexões de banco de dados

### Estrutura do Módulo

```
src/
├── app/
│   ├── Routes/API/v1/UserManagement/
│   │   └── api_routes.php
│   ├── Controllers/API/v1/UserManagement/
│   │   └── ManagerController.php
│   ├── Models/v1/UserManagement/
│   │   └── ResourceModel.php
│   ├── Services/v1/UserManagement/
│   │   └── ManagerService.php
│   ├── Requests/v1/UserManagement/
│   │   ├── StoreRequest.php
│   │   ├── ModifyRequest.php
│   │   └── SearchRequest.php
│   └── Libraries/
│       └── ApiResponse.php
```

---

## 📝 Prompt Original

**Solicitação do Desenvolvedor:**

> Crie um Backup (README.md) com esse prompt e os anexos abaixo relacionados:
>
> - C:\laragon\www\codeigniter56300\src\app\Routes\API\v1\UserManagement\api_routes.php
> - C:\laragon\www\codeigniter56300\src\app\Controllers\API\v1\UserManagement\ManagerController.php
> - C:\laragon\www\codeigniter56300\src\app\Models\v1\UserManagement\ResourceModel.php
> - C:\laragon\www\codeigniter56300\src\app\Services\v1\UserManagement\ManagerService.php
> - C:\laragon\www\codeigniter56300\src\app\Requests\v1\UserManagement\StoreRequest.php
> - C:\laragon\www\codeigniter56300\src\app\Requests\v1\UserManagement\ModifyRequest.php
> - C:\laragon\www\codeigniter56300\src\app\Requests\v1\UserManagement\SearchRequest.php
> - C:\laragon\www\codeigniter56300\src\app\Libraries\ApiResponse.php
>
> Por fim irei baixar esse UNICO arquivo.md e manter para a próxima conversa.

---

## 🗂️ Arquivos do Módulo

### 1️⃣ Routes - api_routes.php

**Caminho:** `src/app/Routes/API/v1/UserManagement/api_routes.php`

```php
<?php

/**
 * Rotas da API - Módulo User Management (v1)
 *
 * @package    App\Routes\v1
 * @author     Gustavo - PRODERJ
 * @version    1.0.0
 * @since      22/11/2025
 */

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

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

# POST /api/v1/user-management
# Cria um novo registro
$routes->post('user-management', 'API\v1\UserManagement\ManagerController::store', ['as' => 'api.v1.user-management.create']);

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

# PATCH /api/v1/user-management/(:num)/restore
# Restaura um registro soft deleted (remove deleted_at)
$routes->patch('user-management/(:num)/restore', 'API\v1\UserManagement\ManagerController::restore/$1', ['as' => 'api.v1.user-management.restore']);

# ========================================================================
# METADADOS
# ========================================================================

# GET /api/v1/user-management/columns
# Retorna metadados completos das colunas da tabela
$routes->get('user-management/columns', 'API\v1\UserManagement\ManagerController::getColumnsMetadata', ['as' => 'api.v1.user-management.columns']);

# GET /api/v1/user-management/column-names
# Retorna apenas os nomes das colunas da tabela
$routes->get('user-management/column-names', 'API\v1\UserManagement\ManagerController::getColumnNames', ['as' => 'api.v1.user-management.column-names']);
```

---

### 2️⃣ Controller - ManagerController.php

**Caminho:** `src/app/Controllers/API/v1/UserManagement/ManagerController.php`

```php
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
```

---

### 3️⃣ Model - ResourceModel.php

**Caminho:** `src/app/Models/v1/UserManagement/ResourceModel.php`

**⚠️ NOTA:** Este arquivo não foi incluído nos documentos anexados. Você precisará adicionar o conteúdo manualmente ou solicitá-lo em outra mensagem.

```php
<?php

namespace App\Models\v1\UserManagement;

// [CONTEÚDO DO RESOURCEMODEL.PHP DEVE SER ADICIONADO AQUI]
// Solicite o arquivo completo se necessário

```

---

### 4️⃣ Service - ManagerService.php

**Caminho:** `src/app/Services/v1/UserManagement/ManagerService.php`

```php
<?php

namespace App\Services\v1\UserManagement;

use App\Models\v1\UserManagement\ResourceModel;

class ManagerService
{
    protected $model;

    public function __construct()
    {
        $this->model = new ResourceModel();
    }

    # Utilizado por: public function index()
    # Rota: GET /api/v1/user-management
    public function index(int $page, int $perPage): array
    {
        try {
            $result = $this->model->paginateWithMeta($perPage, $page);

            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    # Utilizado por: public function indexWithDeleted()
    # Rota: GET /api/v1/user-management/with-deleted
    public function indexWithDeleted(int $page, int $perPage): array
    {
        try {
            $result = $this->model->findAllWithDeleted($perPage, null, true, $page);

            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    # Utilizado por: public function indexOnlyDeleted()
    # Rota: GET /api/v1/user-management/only-deleted
    public function indexOnlyDeleted(int $page, int $perPage): array
    {
        try {
            $result = $this->model->findOnlyDeleted($perPage, null, true, $page);

            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    # Utilizado por: public function store()
    # Rota: POST /api/v1/user-management
    public function store(array $data): array
    {
        try {
            $id = $this->model->insert($data);

            if (!$id) {
                return [
                    'success' => false,
                    'message' => 'Erro ao inserir registro.'
                ];
            }

            return [
                'success' => true,
                'data' => ['id' => $id]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    # Utilizado por: public function show($id = null)
    # Rota: GET /api/v1/user-management/(:num)
    public function show(int $id): array
    {
        try {
            $result = $this->model->find($id);

            if (!$result) {
                return [
                    'success' => false,
                    'message' => 'Registro não encontrado.'
                ];
            }

            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    # Utilizado por: public function showWithDeleted($id = null)
    # Rota: POST /api/v1/user-management/(:num)/with-deleted
    public function showWithDeleted(int $id): array
    {
        try {
            $result = $this->model->findWithDeleted($id);

            if (!$result) {
                return [
                    'success' => false,
                    'message' => 'Registro não encontrado.'
                ];
            }

            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    # Utilizado por: public function search()
    # Rota: POST /api/v1/user-management/search
    public function search(array $filters, int $page, int $perPage): array
    {
        try {
            $result = $this->model->search($filters, [], true, $page, $perPage);

            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    # Utilizado por: public function modify()
    # Rota: PUT /api/v1/user-management
    public function modify(array $data): array
    {
        try {
            $id = $data['id'];

            // Remove ID dos dados (não atualiza o ID)
            unset($data['id']);

            // Verifica se há dados para atualizar
            if (empty($data)) {
                return [
                    'success' => false,
                    'message' => 'Nenhum dado fornecido para atualização.'
                ];
            }

            // Atualiza APENAS se NÃO estiver deletado
            $updated = $this->model->updateNotDeleted($id, $data);

            if (!$updated) {
                return [
                    'success' => false,
                    'message' => 'Registro não encontrado ou está deletado.'
                ];
            }

            return [
                'success' => true,
                'data' => ['id' => $id]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    # Utilizado por: public function delete($id = null)
    # Rota: DELETE /api/v1/user-management/(:num)
    public function delete(int $id): array
    {
        try {
            // Verifica se existe (sem soft deleted)
            $exists = $this->model->find($id);

            if (!$exists) {
                return [
                    'success' => false,
                    'message' => 'Registro não encontrado.'
                ];
            }

            // Soft delete (useSoftDeletes = true faz automaticamente)
            $deleted = $this->model->delete($id);

            if (!$deleted) {
                return [
                    'success' => false,
                    'message' => 'Erro ao deletar registro.'
                ];
            }

            return [
                'success' => true,
                'data' => ['id' => $id]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    # Utilizado por: public function hardDelete($id = null)
    # Rota: DELETE /api/v1/user-management/(:num)/hard
    public function hardDelete(int $id): array
    {
        try {
            // Verifica se existe E está ATIVO (sem soft delete)
            $exists = $this->model->find($id);

            if (!$exists) {
                return [
                    'success' => false,
                    'message' => 'Registro não encontrado ou já está deletado. Use /restore antes de excluir permanentemente.'
                ];
            }

            // Hard delete (exclusão permanente)
            $deleted = $this->model->hardDelete($id);

            if (!$deleted) {
                return [
                    'success' => false,
                    'message' => 'Erro ao deletar permanentemente.'
                ];
            }

            return [
                'success' => true,
                'data' => ['id' => $id]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    # Utilizado por: public function clearDeleted()
    # Rota: DELETE /api/v1/user-management/clear
    public function clearDeleted(): array
    {
        try {
            // Limpa TODOS os registros soft deleted
            $result = $this->model->clearDeleted();

            if ($result['count'] === 0) {
                return [
                    'success' => false,
                    'message' => 'Nenhum registro soft deleted encontrado para limpar.'
                ];
            }

            return [
                'success' => true,
                'data' => [
                    'IDs' => $result['ids'],
                    'deleted_count' => $result['count']
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    # Utilizado por: public function restore($id = null)
    # Rota: # PATCH /api/v1/user-management/(:num)/restore
    public function restore(int $id): array
    {
        $id = (int) $id;
        if ($id <= 0) {
            return [
                'success' => false,
                'data' => null,
                'message' => 'ID inválido.'
            ];
        }

        // [1] Buscar registro incluindo deletados
        $record = $this->model->findWithDeleted($id);

        if (!$record) {
            return [
                'success' => false,
                'data' => null,
                'message' => 'Registro não encontrado.'
            ];
        }

        // [2] Verificar se já está ativo (não deletado)
        if (!empty($record['deleted_at']) && $record['deleted_at'] !== null) {
            // Está deletado — proceder com restore
        } else {
            // Se deleted_at é null/ vazio => não está deletado
            return [
                'success' => false,
                'data' => null,
                'message' => 'Registro não está deletado.'
            ];
        }

        // [3] Executar restauração via Model
        $ok = $this->model->restore($id);

        if (!$ok) {
            return [
                'success' => false,
                'data' => null,
                'message' => 'Falha ao restaurar registro.'
            ];
        }

        // [4] Buscar registro restaurado (find usa callbacks afterFind, remove hidden fields e formata)
        $restored = $this->model->find($id);

        return [
            'success' => true,
            'data' => $restored,
            'message' => 'Registro restaurado com sucesso.'
        ];
    }

    # Utilizado por: public function getColumnsMetadata()
    # Rota: # GET /api/v1/user-management/columns
    public function getColumnsMetadata(): array
    {
        try {
            $metadata = $this->model->getColumnsMetadata();

            return [
                'success' => true,
                'data' => $metadata
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    # Utilizado por: public function getColumnNames()
    # Rota: # GET /api/v1/user-management/column-names
    public function getColumnNames(): array
    {
        try {
            $names = $this->model->getColumnNames();

            return [
                'success' => true,
                'data' => $names
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

}
```

---

### 5️⃣ Request - StoreRequest.php

**Caminho:** `src/app/Requests/v1/UserManagement/StoreRequest.php`

```php
<?php

namespace App\Requests\v1\UserManagement;

use CodeIgniter\HTTP\IncomingRequest;

class StoreRequest
{
    # Request do CodeIgniter
    # @var IncomingRequest
    protected $request;

    # Servico de validacao
    # @var \CodeIgniter\Validation\Validation
    protected $validation;

    # Construtor
    public function __construct()
    {
        $this->request = service('request');
        $this->validation = service('validation');
    }

    # Valida dados para criacao de Usuario
    # @return array ['valid' => bool, 'errors' => array|null, 'data' => array|null]
    public function validateCreate(): array
    {
        // Captura dados do request
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        // ========================================================================
        // CONFIGURAÇÃO DE VALIDAÇÃO
        // ========================================================================

        $config = [
            'table' => 'user_management',
            'connection' => DB_GROUP_001,
            'rules' => [
                'user' => 'required|min_length[6]|max_length[50]|is_unique[user_management.user]',
                'password' => 'required|min_length[8]|max_length[200]',
            ],
            'messages' => [
                'user' => [
                    'required' => 'O campo Usuario e obrigatorio.',
                    'min_length' => 'O Usuario deve ter no minimo 6 caracteres.',
                    'max_length' => 'O Usuario deve ter no maximo 50 caracteres.',
                    'is_unique' => 'Este Usuario ja esta cadastrado no sistema.',
                ],
                'password' => [
                    'required' => 'O campo Senha e obrigatorio.',
                    'min_length' => 'A Senha deve ter no minimo 8 caracteres.',
                    'max_length' => 'A Senha deve ter no maximo 200 caracteres.',
                ],
            ]
        ];

        // ========================================================================
        // VALIDAÇÃO COM CONEXÃO ESPECÍFICA
        // ========================================================================

        $this->validation->setRules($config['rules'], $config['messages']);

        // Executa validação NA CONEXÃO CORRETA
        if (!$this->validation->run($data, null, $config['connection'])) {
            return [
                'valid' => false,
                'errors' => $this->validation->getErrors(),
                'data' => null
            ];
        }

        // ========================================================================
        // HASH DE PASSWORD (OBRIGATÓRIO PARA SEGURANÇA)
        // ========================================================================

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // ========================================================================
        // RETORNO
        // ========================================================================

        return [
            'valid' => true,
            'errors' => null,
            'data' => $data
        ];
    }
}
```

---

### 6️⃣ Request - ModifyRequest.php

**Caminho:** `src/app/Requests/v1/UserManagement/ModifyRequest.php`

```php
<?php

namespace App\Requests\v1\UserManagement;

use CodeIgniter\HTTP\IncomingRequest;

class ModifyRequest
{
    # Request do CodeIgniter
    # @var IncomingRequest
    protected $request;

    # Servico de validacao
    # @var \CodeIgniter\Validation\Validation
    protected $validation;

    # Construtor
    public function __construct()
    {
        $this->request = service('request');
        $this->validation = service('validation');
    }

    # Valida dados para atualizacao de Usuario
    # @return array ['valid' => bool, 'errors' => array|null, 'data' => array|null]
    public function validateUpdate(): array
    {
        // Captura dados do request
        $data = $this->request->getJSON(true) ?? $this->request->getRawInput();

        // ========================================================================
        // VALIDAÇÃO DE ID (OBRIGATÓRIO)
        // ========================================================================

        if (!isset($data['id']) || empty($data['id'])) {
            return [
                'valid' => false,
                'errors' => [
                    'id' => 'O campo ID é obrigatório para atualização.'
                ],
                'data' => null
            ];
        }

        // ========================================================================
        // CONFIGURAÇÃO DE VALIDAÇÃO
        // ========================================================================

        $config = [
            'table' => 'user_management',
            'connection' => DB_GROUP_001,
            'rules' => [
                'id' => 'required|numeric|is_not_unique[user_management.id]',
                'user' => 'permit_empty|string|max_length[50]|is_unique[user_management.user,id,{id}]',
                'password' => 'permit_empty|min_length[8]|max_length[200]',
            ],
            'messages' => [
                'id' => [
                    'required' => 'O campo ID é obrigatório.',
                    'numeric' => 'O campo ID deve ser numérico.',
                    'is_not_unique' => 'Registro não encontrado.'
                ],
                'user' => [
                    'string' => 'O campo usuário deve ser uma string.',
                    'max_length' => 'O campo usuário deve ter no máximo 50 caracteres.',
                    'is_unique' => 'Este usuário já está em uso.'
                ],
                'password' => [
                    'min_length' => 'A senha deve ter no mínimo 8 caracteres.',
                    'max_length' => 'A senha deve ter no máximo 200 caracteres.'
                ],
            ]
        ];

        // ========================================================================
        // VALIDAÇÃO COM CONEXÃO ESPECÍFICA
        // ========================================================================

        $this->validation->setRules($config['rules'], $config['messages']);

        // Executa validação NA CONEXÃO CORRETA
        if (!$this->validation->run($data, null, $config['connection'])) {
            return [
                'valid' => false,
                'errors' => $this->validation->getErrors(),
                'data' => null
            ];
        }

        // ========================================================================
        // REMOVE CAMPOS VAZIOS (atualização parcial)
        // ========================================================================

        $cleanData = [];

        foreach ($data as $field => $value) {
            // Mantém ID sempre
            if ($field === 'id') {
                $cleanData[$field] = $value;
                continue;
            }

            // Remove campos vazios (para atualização parcial)
            if ($value !== null && $value !== '') {
                $cleanData[$field] = $value;
            }
        }

        // ========================================================================
        // HASH DE PASSWORD (se vier)
        // ========================================================================

        if (isset($cleanData['password'])) {
            $cleanData['password'] = password_hash($cleanData['password'], PASSWORD_DEFAULT);
        }

        // ========================================================================
        // RETORNO
        // ========================================================================

        return [
            'valid' => true,
            'errors' => null,
            'data' => $cleanData
        ];
    }
}
```

---

### 7️⃣ Request - SearchRequest.php

**Caminho:** `src/app/Requests/v1/UserManagement/SearchRequest.php`

```php
<?php

namespace App\Requests\v1\UserManagement;

use CodeIgniter\HTTP\IncomingRequest;

class SearchRequest
{
    # Request do CodeIgniter
    # @var IncomingRequest
    protected $request;

    # Servico de validacao
    # @var \CodeIgniter\Validation\Validation
    protected $validation;

    # Construtor
    public function __construct()
    {
        $this->request = service('request');
        $this->validation = service('validation');
    }

    # Valida dados para criacao de Usuario
    # @return array ['valid' => bool, 'errors' => array|null, 'data' => array|null]
    public function validateSearch(): array
    {
        // Captura dados do request
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        // Remove password se vier (segurança)
        if (isset($data['password'])) {
            return [
                'valid' => false,
                'errors' => [
                    'Unprocessable Entity'
                ],
                'data' => [
                    'O campo X não é permitido para esta requisição',
                ]
            ];
        }

        // ========================================================================
        // CONFIGURAÇÃO DE VALIDAÇÃO
        // ========================================================================

        $config = [
            'table' => 'user_management',
            'connection' => DB_GROUP_001,
            'rules' => [
                'id' => 'permit_empty|numeric',
                'user' => 'permit_empty|string|max_length[50]',
                'created_at' => 'permit_empty|valid_date',
                'updated_at' => 'permit_empty|valid_date',
                'deleted_at' => 'permit_empty|valid_date',
            ],
            'messages' => [
                'id' => [
                    'numeric' => 'O campo ID deve ser numérico.'
                ],
                'user' => [
                    'string' => 'O campo usuário deve ser uma string.',
                    'max_length' => 'O campo usuário deve ter no máximo 50 caracteres.',
                ],
                'created_at' => [
                    'valid_date' => 'O campo created_at deve ser uma data válida.'
                ],
                'updated_at' => [
                    'valid_date' => 'O campo updated_at deve ser uma data válida.'
                ],
                'deleted_at' => [
                    'valid_date' => 'O campo deleted_at deve ser uma data válida.'
                ],
            ]
        ];

        // ========================================================================
        // VALIDAÇÃO COM CONEXÃO ESPECÍFICA
        // ========================================================================

        $this->validation->setRules($config['rules'], $config['messages']);

        // Executa validação NA CONEXÃO CORRETA
        if (!$this->validation->run($data, null, $config['connection'])) {
            return [
                'valid' => false,
                'errors' => $this->validation->getErrors(),
                'data' => null
            ];
        }

        // ========================================================================
        // PREPARA DADOS PARA BUSCA (adiciona operador LIKE em campos texto)
        // ========================================================================

        $searchData = [];

        foreach ($data as $field => $value) {
            // Ignora campos vazios
            if ($value === null || $value === '') {
                continue;
            }

            // Campos de texto usam LIKE (busca parcial)
            if (in_array($field, ['user'])) {
                $searchData[$field] = [
                    'value' => $value,
                    'operator' => 'like'
                ];
            }
            // Campos numéricos e datas usam igualdade exata
            else {
                $searchData[$field] = $value;
            }
        }

        // ========================================================================
        // RETORNO
        // ========================================================================

        return [
            'valid' => true,
            'errors' => null,
            'data' => $searchData
        ];
    }
}
```

---

### 8️⃣ Library - ApiResponse.php

**Caminho:** `src/app/Libraries/ApiResponse.php`

**⚠️ NOTA:** Este arquivo não foi incluído nos documentos anexados. Você precisará adicionar o conteúdo manualmente ou solicitá-lo em outra mensagem.

```php
<?php

namespace App\Libraries;

// [CONTEÚDO DO APIRESPONSE.PHP DEVE SER ADICIONADO AQUI]
// Solicite o arquivo completo se necessário

```

---

## 📊 Endpoints da API

### Listagem

- `GET /api/v1/user-management` - Lista registros ativos
- `GET /api/v1/user-management/with-deleted` - Lista todos incluindo deletados
- `GET /api/v1/user-management/only-deleted` - Lista apenas deletados

### Busca

- `GET /api/v1/user-management/{id}` - Busca por ID (ativos)
- `POST /api/v1/user-management/{id}/with-deleted` - Busca incluindo deletados
- `POST /api/v1/user-management/search` - Busca avançada

### Manipulação

- `POST /api/v1/user-management` - Criar usuário
- `PUT /api/v1/user-management` - Atualizar usuário

### Exclusão

- `DELETE /api/v1/user-management/{id}` - Soft delete
- `DELETE /api/v1/user-management/{id}/hard` - Hard delete
- `DELETE /api/v1/user-management/clear` - Limpar todos soft deleted

### Restauração

- `PATCH /api/v1/user-management/{id}/restore` - Restaurar soft deleted

### Metadados

- `GET /api/v1/user-management/columns` - Metadados das colunas
- `GET /api/v1/user-management/column-names` - Nomes das colunas

---

## 🔧 Requisitos Técnicos

- CodeIgniter 4.6+
- PHP 8.0+
- MySQL 5.7+ / MariaDB 10.3+
- Extensões PHP: PDO, Mbstring, JSON

---

## 📝 Notas Importantes

1. **Conexão de Banco**: Utiliza constante `DB_GROUP_001` definida no sistema
2. **Segurança**: Passwords são automaticamente hasheados com `PASSWORD_DEFAULT`
3. **Soft Delete**: Campo `deleted_at` gerencia exclusões lógicas
4. **Paginação**: Limite máximo de 100 registros por página
5. **Validação**: Todas as requisições são validadas antes de chegar ao Service

---

## ⚠️ Arquivos Faltantes

Os seguintes arquivos precisam ser adicionados manualmente:

1. **ResourceModel.php** - Modelo completo com métodos de busca/paginação
2. **ApiResponse.php** - Biblioteca de resposta padronizada

---

## 📌 Próximos Passos

Ao retomar o desenvolvimento em uma nova conversa, forneça este README.md completo para que Claude tenha todo o contexto necessário do projeto.

---

**Fim do Backup**  
_Arquivo gerado automaticamente em 30/11/2025_

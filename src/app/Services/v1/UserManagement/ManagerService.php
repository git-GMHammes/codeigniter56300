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
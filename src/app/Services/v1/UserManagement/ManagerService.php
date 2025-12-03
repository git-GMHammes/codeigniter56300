<?php

namespace App\Services\v1\UserManagement;

use App\Services\v1\BaseManagerService;
use App\Models\v1\UserManagement\ResourceModel;

class ManagerService extends BaseManagerService
{
    public function __construct()
    {
        $this->model = new ResourceModel();
    }

    # GET /api/v1/user-management
    public function index(int $page, int $perPage): array
    {
        return $this->execute(
            fn() => $this->model->paginateWithMeta($perPage, $page)
        );
    }

    # GET /api/v1/user-management/with-deleted
    public function indexWithDeleted(int $page, int $perPage): array
    {
        return $this->execute(
            fn() => $this->model->findAllWithDeleted($perPage, null, true, $page)
        );
    }

    # GET /api/v1/user-management/only-deleted
    public function indexOnlyDeleted(int $page, int $perPage): array
    {
        return $this->execute(
            fn() => $this->model->findOnlyDeleted($perPage, null, true, $page)
        );
    }

    # POST /api/v1/user-management
    public function store(array $data): array
    {
        try {
            $id = $this->model->insert($data);

            if (!$id) {
                return $this->errorResponse('Erro ao inserir registro.');
            }

            return $this->successResponse(['id' => $id]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    # GET /api/v1/user-management/(:num)
    public function show(int $id): array
    {
        return $this->executeWithValidation(
            fn() => $this->model->find($id)
        );
    }

    # POST /api/v1/user-management/(:num)/with-deleted
    public function showWithDeleted(int $id): array
    {
        return $this->executeWithValidation(
            fn() => $this->model->findWithDeleted($id)
        );
    }

    # POST /api/v1/user-management/search
    public function search(array $filters, int $page, int $perPage): array
    {
        return $this->execute(
            fn() => $this->model->search($filters, [], true, $page, $perPage)
        );
    }

    # PUT /api/v1/user-management
    public function modify(array $data): array
    {
        try {
            $id = $data['id'];
            unset($data['id']);

            if (empty($data)) {
                return $this->errorResponse('Nenhum dado fornecido para atualização.');
            }

            $updated = $this->model->updateNotDeleted($id, $data);

            if (!$updated) {
                return $this->errorResponse('Registro não encontrado ou está deletado.');
            }

            return $this->successResponse(['id' => $id]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    # DELETE /api/v1/user-management/(:num)
    public function delete(int $id): array
    {
        try {
            $exists = $this->model->find($id);

            if (!$exists) {
                return $this->errorResponse('Registro não encontrado.');
            }

            $deleted = $this->model->delete($id);

            if (!$deleted) {
                return $this->errorResponse('Erro ao deletar registro.');
            }

            return $this->successResponse(['id' => $id]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    # DELETE /api/v1/user-management/(:num)/hard
    public function hardDelete(int $id): array
    {
        try {
            $exists = $this->model->find($id);

            if (!$exists) {
                return $this->errorResponse('Registro não encontrado ou já está deletado. Use /restore antes de excluir permanentemente.');
            }

            $deleted = $this->model->hardDelete($id);

            if (!$deleted) {
                return $this->errorResponse('Erro ao deletar permanentemente.');
            }

            return $this->successResponse(['id' => $id]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    # DELETE /api/v1/user-management/clear
    public function clearDeleted(): array
    {
        try {
            $result = $this->model->clearDeleted();

            if ($result['count'] === 0) {
                return $this->errorResponse('Nenhum registro soft deleted encontrado para limpar.');
            }

            return $this->successResponse([
                'IDs' => $result['ids'],
                'deleted_count' => $result['count']
            ]);
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    # PATCH /api/v1/user-management/(:num)/restore
    public function restore(int $id): array
    {
        if ($id <= 0) {
            return $this->errorResponse('ID inválido.', null);
        }

        try {
            $record = $this->model->findWithDeleted($id);

            if (!$record) {
                return $this->errorResponse('Registro não encontrado.', null);
            }

            if (empty($record['deleted_at'])) {
                return $this->errorResponse('Registro não está deletado.', null);
            }

            $ok = $this->model->restore($id);

            if (!$ok) {
                return $this->errorResponse('Falha ao restaurar registro.', null);
            }

            $restored = $this->model->find($id);

            return $this->successResponse($restored, 'Registro restaurado com sucesso.');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), null);
        }
    }

    # GET /api/v1/user-management/columns
    public function getColumnsMetadata(): array
    {
        return $this->execute(
            fn() => $this->model->getColumnsMetadata()
        );
    }

    # GET /api/v1/user-management/column-names
    public function getColumnNames(): array
    {
        return $this->execute(
            fn() => $this->model->getColumnNames()
        );
    }
}
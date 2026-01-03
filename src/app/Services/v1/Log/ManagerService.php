<?php

namespace App\Services\v1\Log;

use App\Services\v1\BaseManagerService;
use App\Models\v1\Log\ResourceModel;

class ManagerService extends BaseManagerService
{
    public function __construct()
    {
        $this->model = new ResourceModel();
    }

    # GET /api/v1/log-management
    public function index(int $page, int $perPage): array
    {
        return $this->execute(
            fn() => $this->model->paginateWithMeta($perPage, $page)
        );
    }

    # GET /api/v1/log-management/only-deleted
    public function indexOnlyDeleted(int $page, int $perPage): array
    {
        return $this->execute(
            fn() => $this->model->findOnlyDeleted($perPage, null, true, $page)
        );
    }

    # POST /api/v1/log-management
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

    # GET /api/v1/log-management/(:num)
    public function show(int $id): array
    {
        return $this->executeWithValidation(
            fn() => $this->model->find($id)
        );
    }

    # POST /api/v1/log-management/(:num)/with-deleted
    public function showWithDeleted(int $id): array
    {
        return $this->executeWithValidation(
            fn() => $this->model->findWithDeleted($id)
        );
    }

    # POST /api/v1/log-management/search
    public function search(array $filters, int $page, int $perPage): array
    {
        return $this->execute(
            fn() => $this->model->search($filters, [], true, $page, $perPage)
        );
    }

    # PUT /api/v1/log-management
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

    # DELETE /api/v1/log-management/(:num)
    public function delete(int $id): array
    {
        try {
            // Verifica se o registro existe
            $exists = $this->model->find($id);

            if (!$exists) {
                return $this->errorResponse('Registro não encontrado.');
            }

            // Soft delete não precisa verificar FK (apenas marca como deletado)
            $deleted = $this->model->delete($id);

            if (!$deleted) {
                return $this->errorResponse('Erro ao deletar registro.');
            }

            return $this->successResponse(
                ['id' => $id],
                'Registro marcado como deletado com sucesso (soft delete).'
            );
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
    
    # GET /api/v1/log-management/columns
    public function getColumnsMetadata(): array
    {
        return $this->execute(
            fn() => $this->model->getColumnsMetadata()
        );
    }

    # GET /api/v1/log-management/column-names
    public function getColumnNames(): array
    {
        return $this->execute(
            fn() => $this->model->getColumnNames()
        );
    }
}
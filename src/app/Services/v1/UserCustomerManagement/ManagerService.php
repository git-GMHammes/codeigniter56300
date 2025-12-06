<?php

namespace App\Services\v1\UserCustomerManagement;

use App\Services\v1\BaseManagerService;
use App\Models\v1\UserCustomerManagement\ResourceModel;

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
<?php

namespace App\Services\v1\UserCustomerFile;

use App\Services\v1\BaseManagerService;
use App\Models\v1\UserCustomer\FileModel;

class ManagerService extends BaseManagerService
{
    public function __construct()
    {
        $this->model = new FileModel();
    }

    # GET /api/v1/contact-customer-files
    public function index(int $page, int $perPage): array
    {
        return $this->execute(
            fn() => $this->model->paginateWithMeta($perPage, $page)
        );
    }

    # GET /api/v1/contact-customer-files/with-deleted
    public function indexWithDeleted(int $page, int $perPage): array
    {
        return $this->execute(
            fn() => $this->model->findAllWithDeleted($perPage, null, true, $page)
        );
    }

    # GET /api/v1/contact-customer-files/only-deleted
    public function indexOnlyDeleted(int $page, int $perPage): array
    {
        return $this->execute(
            fn() => $this->model->findOnlyDeleted($perPage, null, true, $page)
        );
    }

    # GET /api/v1/contact-customer-files/(:num)
    public function show(int $id): array
    {
        return $this->executeWithValidation(
            fn() => $this->model->find($id)
        );
    }

    # POST /api/v1/contact-customer-files/(:num)/with-deleted
    public function showWithDeleted(int $id): array
    {
        return $this->executeWithValidation(
            fn() => $this->model->findWithDeleted($id)
        );
    }

    # POST /api/v1/contact-customer-files/search
    public function search(array $filters, int $page, int $perPage): array
    {
        return $this->execute(
            fn() => $this->model->search($filters, [], true, $page, $perPage)
        );
    }

    # DELETE /api/v1/contact-customer-files/(:num)
    public function delete(int $id): array
    {
        try {
            $exists = $this->model->find($id);

            if (!$exists) {
                return $this->errorResponse('Upload não encontrado.');
            }

            $deleted = $this->model->delete($id);

            if (!$deleted) {
                return $this->errorResponse('Erro ao deletar upload.');
            }

            return $this->successResponse(
                ['id' => $id],
                'Upload marcado como deletado com sucesso (soft delete).'
            );
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    # PATCH /api/v1/contact-customer-files/(:num)/restore
    public function restore(int $id): array
    {
        if ($id <= 0) {
            return $this->errorResponse('ID inválido.', null);
        }

        try {
            $record = $this->model->findWithDeleted($id);

            if (!$record) {
                return $this->errorResponse('Upload não encontrado.', null);
            }

            if (empty($record['deleted_at'])) {
                return $this->errorResponse('Upload não está deletado.', null);
            }

            $ok = $this->model->restore($id);

            if (!$ok) {
                return $this->errorResponse('Falha ao restaurar upload.', null);
            }

            $restored = $this->model->find($id);

            return $this->successResponse($restored, 'Upload restaurado com sucesso.');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage(), null);
        }
    }

    # GET /api/v1/contact-customer-files/columns
    public function getColumnsMetadata(): array
    {
        return $this->execute(
            fn() => $this->model->getColumnsMetadata()
        );
    }

    # GET /api/v1/contact-customer-files/column-names
    public function getColumnNames(): array
    {
        return $this->execute(
            fn() => $this->model->getColumnNames()
        );
    }
}
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

    # DELETE /api/v1/user-management/(:num)/hard
    public function hardDelete(int $id): array
    {
        try {
            // Verifica se o registro existe
            $exists = $this->model->find($id);

            if (!$exists) {
                return $this->errorResponse(
                    'Registro não encontrado ou já está deletado. Use /restore antes de excluir permanentemente.'
                );
            }

            // ===================================================================
            // NOVA VERIFICAÇÃO: Checa dependências ANTES de tentar deletar
            // ===================================================================
            $dependencyError = $this->checkDependenciesBeforeDelete($id);

            if ($dependencyError) {
                return $dependencyError;
            }

            // Se passou na verificação, tenta o hard delete
            $deleted = $this->model->hardDelete($id);

            if (!$deleted) {
                return $this->errorResponse('Erro ao deletar permanentemente.');
            }

            return $this->successResponse(
                ['id' => $id],
                'Registro excluído permanentemente com sucesso.'
            );

        } catch (\Throwable $e) {
            // ===================================================================
            // CAPTURA ERROS DE FK QUE POSSAM TER ESCAPADO
            // ===================================================================
            if ($this->isForeignKeyError($e)) {
                // Tenta buscar dependências detalhadas
                try {
                    $dependencies = $this->model->checkForeignKeyDependencies($id);
                    return $this->foreignKeyErrorResponse($id, $dependencies, $e);
                } catch (\Throwable $e2) {
                    // Se falhar ao buscar dependências, usa apenas a exceção original
                    return $this->foreignKeyErrorResponse($id, null, $e);
                }
            }

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

            // ===================================================================
            // VERIFICA DEPENDÊNCIAS PARA CADA ID ANTES DE LIMPAR
            // ===================================================================
            $cannotDelete = [];

            foreach ($result['ids'] as $deletedId) {
                $dependencies = $this->model->checkForeignKeyDependencies($deletedId);

                if (!empty($dependencies)) {
                    $cannotDelete[$deletedId] = $dependencies;
                }
            }

            // Se algum registro não pode ser deletado por FK
            if (!empty($cannotDelete)) {
                $message = sprintf(
                    'Foram encontrados %d registro%s soft deleted, mas %d dele%s não pode%s ser excluído%s permanentemente devido a dependências de foreign key.',
                    $result['count'],
                    $result['count'] > 1 ? 's' : '',
                    count($cannotDelete),
                    count($cannotDelete) > 1 ? 's' : '',
                    count($cannotDelete) > 1 ? 'm' : '',
                    count($cannotDelete) > 1 ? 's' : ''
                );

                return [
                    'success' => false,
                    'message' => $message,
                    'error_type' => 'foreign_key_constraint',
                    'data' => [
                        'total_soft_deleted' => $result['count'],
                        'cannot_delete_count' => count($cannotDelete),
                        'ids_with_dependencies' => $cannotDelete,
                        'suggestions' => [
                            'Remova os registros relacionados antes de limpar',
                            'Considere manter estes registros como soft deleted',
                            'Execute /clear novamente após resolver as dependências'
                        ]
                    ]
                ];
            }

            return $this->successResponse([
                'IDs' => $result['ids'],
                'deleted_count' => $result['count']
            ], sprintf(
                '%d registro%s soft deleted excluído%s permanentemente com sucesso.',
                $result['count'],
                $result['count'] > 1 ? 's' : '',
                $result['count'] > 1 ? 's' : ''
            ));

        } catch (\Throwable $e) {
            // Captura erros de FK durante clearDeleted
            if ($this->isForeignKeyError($e)) {
                return [
                    'success' => false,
                    'message' => 'Erro ao limpar registros deletados devido a restrições de foreign key. Alguns registros possuem dependências em outras tabelas.',
                    'error_type' => 'foreign_key_constraint',
                    'data' => [
                        'raw_error' => $e->getMessage(),
                        'suggestions' => [
                            'Remova as dependências manualmente antes de limpar',
                            'Use /hard em IDs específicos para identificar quais possuem dependências'
                        ]
                    ]
                ];
            }

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
<?php

namespace App\Models\v1;

use CodeIgniter\Model;

abstract class BaseResourceModel extends Model
{
    # ============================================================
    # CONFIGURAÇÕES PADRÃO (podem ser sobrescritas nos filhos)
    # ============================================================

    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $dateFormat = 'datetime';

    protected array $casts = [];  // ← ÚNICO com tipo

    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = ['removeHiddenFields', 'formatAfterFind'];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    public $hiddenFields = [];

    # ============================================================
    # MÉTODOS DE PAGINAÇÃO
    # ============================================================

    /**
     * Paginação com metadados completos
     * Rota: GET /api/v1/objeto?page=1&per_page=15
     */
    public function paginateWithMeta(int $perPage = 15, int $page = 1, array $filters = [], array $options = []): array
    {
        $builder = $this->builder();

        // Aplicar soft delete manualmente (excluir registros deletados)
        if (!isset($options['with_deleted']) || $options['with_deleted'] !== true) {
            $builder->where($this->table . '.' . $this->deletedField . ' IS NULL', null, false);
        }

        // Aplicar filtros se existirem
        if (!empty($filters)) {
            $this->applyFilters($builder, $filters);
        }

        // Ordenação
        if (isset($options['order_by'])) {
            $orderDirection = $options['order_direction'] ?? 'ASC';
            $builder->orderBy($options['order_by'], $orderDirection);
        }

        // Contar total de registros
        $total = $builder->countAllResults(false);

        // Calcular offset
        $offset = ($page - 1) * $perPage;

        // Buscar dados
        $data = $builder->limit($perPage, $offset)->get()->getResultArray();

        // Remover campos sensíveis
        $data = $this->removeHiddenFieldsFromArray($data);

        // Calcular metadados
        $totalPages = (int) ceil($total / $perPage);
        $from = $total > 0 ? $offset + 1 : 0;
        $to = min($offset + $perPage, $total);

        // Gerar links de paginação
        $baseUrl = current_url();
        $queryParams = http_build_query(array_merge($_GET, ['limit' => $perPage]));
        $baseUrl = strtok($baseUrl, '?');

        $links = [
            'first' => $baseUrl . '?page=1&' . $queryParams,
            'prev' => $page > 1 ? $baseUrl . '?page=' . ($page - 1) . '&' . $queryParams : null,
            'next' => $page < $totalPages ? $baseUrl . '?page=' . ($page + 1) . '&' . $queryParams : null,
            'last' => $baseUrl . '?page=' . $totalPages . '&' . $queryParams,
        ];

        return [
            'data' => $data,
            'meta' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => $totalPages,
                'from' => $from,
                'to' => $to,
                'has_next_page' => $page < $totalPages,
                'has_previous_page' => $page > 1,
                'links' => $links
            ]
        ];
    }

    /**
     * Buscar todos os registros incluindo os deletados (com paginação)
     * Rota: GET /api/v1/objeto/with-deleted?page=1&per_page=15
     */
    public function findAllWithDeleted(?int $limit = null, ?int $offset = null, bool $paginated = false, int $page = 1): array
    {
        if ($paginated) {
            $perPage = $limit ?? 15;
            return $this->paginateWithMeta($perPage, $page, [], ['with_deleted' => true]);
        }

        $builder = $this->builder();

        if ($limit !== null) {
            $builder->limit($limit, $offset ?? 0);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Buscar apenas os registros deletados (com paginação)
     * Rota: GET /api/v1/objeto/only-deleted?page=1&per_page=15
     */
    public function findOnlyDeleted(?int $limit = null, ?int $offset = null, bool $paginated = false, int $page = 1): array
    {
        if ($paginated) {
            $perPage = $limit ?? 15;
            $builder = $this->builder();
            $builder->where($this->deletedField . ' IS NOT NULL', null, false);

            // Contar total
            $total = $builder->countAllResults(false);

            // Calcular offset
            $offset = ($page - 1) * $perPage;

            // Buscar dados
            $data = $builder->limit($perPage, $offset)->get()->getResultArray();

            // Remover campos sensíveis
            $data = $this->removeHiddenFieldsFromArray($data);

            // Calcular metadados
            $totalPages = (int) ceil($total / $perPage);
            $from = $total > 0 ? $offset + 1 : 0;
            $to = min($offset + $perPage, $total);

            // Gerar links de paginação
            $baseUrl = current_url();
            $queryParams = http_build_query(array_merge($_GET, ['limit' => $perPage]));
            $baseUrl = strtok($baseUrl, '?');

            $links = [
                'first' => $baseUrl . '?page=1&' . $queryParams,
                'prev' => $page > 1 ? $baseUrl . '?page=' . ($page - 1) . '&' . $queryParams : null,
                'next' => $page < $totalPages ? $baseUrl . '?page=' . ($page + 1) . '&' . $queryParams : null,
                'last' => $baseUrl . '?page=' . $totalPages . '&' . $queryParams,
            ];

            return [
                'data' => $data,
                'meta' => [
                    'current_page' => $page,
                    'per_page' => $perPage,
                    'total' => $total,
                    'total_pages' => $totalPages,
                    'from' => $from,
                    'to' => $to,
                    'has_next_page' => $page < $totalPages,
                    'has_previous_page' => $page > 1,
                    'links' => $links
                ]
            ];
        }

        $builder = $this->builder();
        $builder->where($this->deletedField . ' IS NOT NULL', null, false);

        if ($limit !== null) {
            $builder->limit($limit, $offset ?? 0);
        }

        return $builder->get()->getResultArray();
    }

    /**
     * Busca avançada por múltiplos campos com operadores e filtros (com paginação)
     * Rota: POST /api/v1/objeto/search?page=1&per_page=15
     */
    public function search(array $filters = [], array $options = [], bool $paginated = false, int $page = 1, int $perPage = 15): array
    {
        if ($paginated) {
            return $this->paginateWithMeta($perPage, $page, $filters, $options);
        }

        $builder = $this->builder();

        // Aplicar filtros
        if (!empty($filters)) {
            $this->applyFilters($builder, $filters);
        }

        // Incluir deletados se solicitado
        if (isset($options['with_deleted']) && $options['with_deleted'] === true) {
            $this->tempUseSoftDeletes = false;
        }

        // Ordenação
        if (isset($options['order_by'])) {
            $orderDirection = $options['order_direction'] ?? 'ASC';
            $builder->orderBy($options['order_by'], $orderDirection);
        }

        // Limite
        if (isset($options['limit'])) {
            $offset = $options['offset'] ?? 0;
            $builder->limit($options['limit'], $offset);
        }

        return $builder->get()->getResultArray();
    }

    # ============================================================
    # MÉTODOS DE SOFT DELETE
    # ============================================================

    /**
     * Buscar registro por ID incluindo deletados
     * Rota: POST /api/v1/objeto/{id}/with-deleted
     */
    public function findWithDeleted(int $id): ?array
    {
        $builder = $this->builder();
        $result = $builder->where($this->primaryKey, $id)->get()->getRowArray();

        if (!$result) {
            return null;
        }

        return $this->formatRecord($result);
    }

    /**
     * Restaurar registro soft deleted (remove deleted_at)
     * Rota: PATCH /api/v1/objeto/{id}/restore
     */
    public function restore(int $id): bool
    {
        $builder = $this->builder();
        return $builder->where($this->primaryKey, $id)
            ->set($this->deletedField, null)
            ->update();
    }

    /**
     * Atualiza registro APENAS se NÃO estiver soft deleted
     * Rota: PUT /api/v1/objeto
     */
    public function updateNotDeleted(int $id, array $data): bool
    {
        // Verifica se o registro existe E não está deletado
        $exists = $this->find($id);

        if (!$exists) {
            return false;
        }

        // Atualiza usando o método nativo
        return $this->update($id, $data);
    }

    /**
     * Hard delete - Exclusão permanente do banco
     * Rota: DELETE /api/v1/objeto/{id}/hard
     */
    public function hardDelete(int $id): bool
    {
        return $this->delete($id, true);
    }

    /**
     * Limpar todos os registros marcados como deletados
     * Rota: DELETE /api/v1/objeto/clear
     */
    public function clearDeleted(): array
    {
        $builder = $this->builder();
        $builder->where($this->deletedField . ' IS NOT NULL', null, false);

        // Busca os IDs ANTES de deletar
        $records = $builder->select($this->primaryKey)->get()->getResultArray();
        $ids = array_column($records, $this->primaryKey);
        $count = count($ids);

        // Deleta permanentemente
        if ($count > 0) {
            $builder = $this->builder();
            $builder->where($this->deletedField . ' IS NOT NULL', null, false);
            $builder->delete();
        }

        return [
            'ids' => $ids,
            'count' => $count
        ];
    }

    # ============================================================
    # MÉTODOS DE METADADOS
    # ============================================================

    /**
     * Obter metadados completos das colunas da tabela
     * Rota: GET /api/v1/objeto/columns
     */
    public function getColumnsMetadata(): array
    {
        $db = \Config\Database::connect($this->DBGroup);
        $fields = $db->getFieldData($this->table);

        $metadata = [];
        foreach ($fields as $field) {
            $metadata[] = [
                'COLUMN_NAME' => $field->name,
                'COLUMN_TYPE' => $field->type,
                'IS_NULLABLE' => $field->nullable ? 'YES' : 'NO',
                'COLUMN_KEY' => $field->primary_key ? 'PRI' : '',
                'COLUMN_DEFAULT' => $field->default,
                'MAX_LENGTH' => $field->max_length ?? null,
            ];
        }

        return $metadata;
    }

    /**
     * Obter apenas os nomes das colunas da tabela
     * Rota: GET /api/v1/objeto/column-names
     */
    public function getColumnNames(): array
    {
        $db = \Config\Database::connect($this->DBGroup);
        $fields = $db->getFieldData($this->table);

        return array_map(function ($field) {
            return $field->name;
        }, $fields);
    }

    # ============================================================
    # MÉTODOS DE VERIFICAÇÃO E CONTAGEM
    # ============================================================

    /**
     * Verifica se um registro existe por ID
     */
    public function exists(int $id): bool
    {
        return $this->find($id) !== null;
    }

    /**
     * Conta total de registros incluindo deletados
     */
    public function countAllWithDeleted(): int
    {
        $builder = $this->builder();
        return $builder->countAllResults();
    }

    /**
     * Conta apenas registros deletados
     */
    public function countOnlyDeleted(): int
    {
        $builder = $this->builder();
        $builder->where($this->deletedField . ' IS NOT NULL', null, false);
        return $builder->countAllResults();
    }

    # ============================================================
    # MÉTODOS AUXILIARES PRIVADOS
    # ============================================================

    /**
     * Método privado para aplicar filtros (DRY - Don't Repeat Yourself)
     */
    private function applyFilters(&$builder, array $filters): void
    {
        foreach ($filters as $field => $value) {
            if (is_array($value)) {
                $operator = $value['operator'] ?? '=';
                $filterValue = $value['value'] ?? null;

                if ($filterValue !== null) {
                    switch (strtolower($operator)) {
                        case 'like':
                        case 'contains':
                            $builder->like($field, $filterValue);
                            break;
                        case 'starts_with':
                            $builder->like($field, $filterValue, 'after');
                            break;
                        case 'ends_with':
                            $builder->like($field, $filterValue, 'before');
                            break;
                        case 'in':
                            $builder->whereIn($field, $filterValue);
                            break;
                        case 'not_in':
                            $builder->whereNotIn($field, $filterValue);
                            break;
                        case '!=':
                        case '<>':
                            $builder->where($field . ' !=', $filterValue);
                            break;
                        case '>':
                            $builder->where($field . ' >', $filterValue);
                            break;
                        case '>=':
                            $builder->where($field . ' >=', $filterValue);
                            break;
                        case '<':
                            $builder->where($field . ' <', $filterValue);
                            break;
                        case '<=':
                            $builder->where($field . ' <=', $filterValue);
                            break;
                        default:
                            $builder->where($field, $filterValue);
                            break;
                    }
                }
            } else {
                $builder->where($field, $value);
            }
        }
    }

    /**
     * Remove campos sensíveis definidos em $hiddenFields
     */
    protected function removeHiddenFieldsFromArray(array $data): array
    {
        if (empty($this->hiddenFields)) {
            return $data;
        }

        // Processar múltiplos registros (lista)
        foreach ($data as $key => $row) {
            if (is_array($row)) {
                $data[$key] = array_diff_key($row, array_flip($this->hiddenFields));
            }
        }

        return $data;
    }

    /**
     * Callback afterFind - Remove campos sensíveis (para find, findAll, etc)
     */
    protected function removeHiddenFields(array $data): array
    {
        if (empty($this->hiddenFields)) {
            return $data;
        }

        // Processar múltiplos registros
        if (isset($data['data']) && is_array($data['data'])) {
            foreach ($data['data'] as $key => $row) {
                if (is_array($row)) {
                    $data['data'][$key] = array_diff_key($row, array_flip($this->hiddenFields));
                }
            }
            return $data;
        }

        // Processar registro único
        if (is_array($data) && !empty($data)) {
            return array_diff_key($data, array_flip($this->hiddenFields));
        }

        return $data;
    }

    /**
     * Formata dados após busca do banco
     * Remove campos sensíveis e padroniza formato de timestamps
     */
    protected function formatAfterFind(array $data): array
    {
        if (!isset($data['data'])) {
            return $data;
        }

        // Registro único
        if (isset($data['data']['id'])) {
            $data['data'] = $this->formatRecord($data['data']);
            return $data;
        }

        // Múltiplos registros
        if (is_array($data['data'])) {
            foreach ($data['data'] as $key => $record) {
                if (is_array($record)) {
                    $data['data'][$key] = $this->formatRecord($record);
                }
            }
        }

        return $data;
    }

    /**
     * Formata um único registro
     */
    private function formatRecord(array $record): array
    {
        // Remove password
        unset($record['password']);

        // Converte timestamps DateTime para string
        foreach (['created_at', 'updated_at', 'deleted_at'] as $field) {
            if (isset($record[$field]) && is_object($record[$field])) {
                $record[$field] = $record[$field]->format('Y-m-d H:i:s');
            }
        }

        // Converte ID para string (manter padrão da API)
        if (isset($record['id'])) {
            $record['id'] = (string) $record['id'];
        }

        return $record;
    }
}
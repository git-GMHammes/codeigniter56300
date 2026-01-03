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
     * Busca um registro específico incluindo deletados
     * Rota: POST /api/v1/objeto/(:num)/with-deleted
     */
    public function findWithDeleted(int $id): ?array
    {
        $builder = $this->builder();
        $result = $builder->where($this->primaryKey, $id)->get()->getRowArray();

        return $result ?: null;
    }

    /**
     * Atualiza um registro se ele NÃO estiver deletado
     * Rota: PUT /api/v1/objeto
     */
    public function updateNotDeleted(int $id, array $data): bool
    {
        $builder = $this->builder();
        $builder->where($this->primaryKey, $id);
        $builder->where($this->deletedField . ' IS NULL', null, false);

        return $builder->update($data);
    }

    /**
     * Hard Delete - Exclui permanentemente do banco
     * Rota: DELETE /api/v1/objeto/(:num)/hard
     */
    public function hardDelete(int $id): bool
    {
        $builder = $this->builder();
        return $builder->where($this->primaryKey, $id)->delete();
    }

    /**
     * Restaura um registro soft deleted
     * Rota: PATCH /api/v1/objeto/(:num)/restore
     */
    public function restore(int $id): bool
    {
        $builder = $this->builder();
        $builder->where($this->primaryKey, $id);

        return $builder->update([
            $this->deletedField => null,
            $this->updatedField => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Limpa TODOS os registros soft deleted (hard delete em lote)
     * Rota: DELETE /api/v1/objeto/clear
     */
    public function clearDeleted(): array
    {
        // Primeiro, busca os IDs dos registros deletados
        $builder = $this->builder();
        $builder->where($this->deletedField . ' IS NOT NULL', null, false);
        $deletedRecords = $builder->get()->getResultArray();

        $ids = array_column($deletedRecords, $this->primaryKey);
        $count = count($ids);

        // Depois, faz o hard delete
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
    # MÉTODOS DE VERIFICAÇÃO DE FOREIGN KEYS
    # ============================================================

    /**
     * Verifica se existem dependências de foreign keys para um ID
     * Retorna array com informações sobre tabelas relacionadas
     * 
     * @param int $id ID do registro a verificar
     * @return array Lista de dependências encontradas
     */
    public function checkForeignKeyDependencies(int $id): array
    {
        $db = \Config\Database::connect($this->DBGroup);
        $database = $db->getDatabase();

        // Busca foreign keys que REFERENCIAM esta tabela
        $sql = "
            SELECT 
                kcu.TABLE_NAME as dependent_table,
                kcu.COLUMN_NAME as dependent_column,
                kcu.CONSTRAINT_NAME as constraint_name,
                rc.UPDATE_RULE as update_rule,
                rc.DELETE_RULE as delete_rule
            FROM 
                INFORMATION_SCHEMA.KEY_COLUMN_USAGE kcu
            JOIN 
                INFORMATION_SCHEMA.REFERENTIAL_CONSTRAINTS rc
                ON kcu.CONSTRAINT_NAME = rc.CONSTRAINT_NAME
                AND kcu.CONSTRAINT_SCHEMA = rc.CONSTRAINT_SCHEMA
            WHERE 
                kcu.REFERENCED_TABLE_SCHEMA = ?
                AND kcu.REFERENCED_TABLE_NAME = ?
                AND kcu.REFERENCED_COLUMN_NAME = ?
        ";

        $foreignKeys = $db->query($sql, [$database, $this->table, $this->primaryKey])->getResultArray();

        $dependencies = [];

        foreach ($foreignKeys as $fk) {
            // Conta quantos registros relacionados existem
            $countQuery = "SELECT COUNT(*) as count FROM {$fk['dependent_table']} WHERE {$fk['dependent_column']} = ?";
            $count = $db->query($countQuery, [$id])->getRowArray()['count'] ?? 0;

            if ($count > 0) {
                $dependencies[] = [
                    'table' => $fk['dependent_table'],
                    'column' => $fk['dependent_column'],
                    'constraint' => $fk['constraint_name'],
                    'count' => (int) $count,
                    'delete_rule' => $fk['delete_rule'],
                    'update_rule' => $fk['update_rule']
                ];
            }
        }

        return $dependencies;
    }

    /**
     * Verifica se um ID pode ser deletado (sem dependências)
     * 
     * @param int $id ID do registro a verificar
     * @return bool True se pode deletar, False se tem dependências
     */
    public function canBeDeleted(int $id): bool
    {
        $dependencies = $this->checkForeignKeyDependencies($id);
        return empty($dependencies);
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
                // ...  código existente para operadores estruturados ... 
            } else {
                // Detecta se o campo é string no banco
                $db = \Config\Database::connect($this->DBGroup);
                $fieldData = $db->getFieldData($this->table);
                $isTextField = false;

                foreach ($fieldData as $f) {
                    if ($f->name === $field && in_array($f->type, ['varchar', 'text', 'char'])) {
                        $isTextField = true;
                        break;
                    }
                }

                // Se tem wildcards OU é campo de texto, usa LIKE
                if (is_string($value) && ($isTextField || strpos($value, '%') !== false || strpos($value, '*') !== false)) {
                    $value = str_replace('*', '%', $value);

                    // Se não tem wildcards, adiciona automaticamente
                    if (strpos($value, '%') === false) {
                        $value = '%' . $value . '%';
                    }

                    $builder->like($field, $value);
                } else {
                    $builder->where($field, $value);
                }
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
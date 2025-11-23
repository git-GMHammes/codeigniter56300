<?php

namespace App\Models\v1\UserCustomer;

use CodeIgniter\Model;

class ResourceModel extends Model
{
    # Grupo de conexão do banco de dados (DB_GROUP_001, DB_GROUP_002 ou DB_GROUP_003)
    protected $DBGroup = DB_GROUP_001;

    # Nome da tabela no banco de dados
    protected $table = 'user_customer';

    # Chave primária da tabela
    protected $primaryKey = 'id';

    # Tipo de retorno: 'array' ou 'object'
    protected $returnType = 'array';

    # Ativa soft delete (deleted_at ao invés de DELETE físico)
    protected $useSoftDeletes = true;

    # Campo usado para soft delete
    protected $deletedField = 'deleted_at';

    # Ativa timestamps automáticos (created_at e updated_at)
    protected $useTimestamps = true;

    # Campo de data de criação
    protected $createdField = 'created_at';

    # Campo de data de atualização
    protected $updatedField = 'updated_at';

    # Formato de data: 'datetime', 'date' ou 'int'
    protected $dateFormat = 'datetime';

    # Campos permitidos para mass assignment (proteção de segurança)
    protected $allowedFields = [
        'user_id',
        'name',
        'cpf',
        'whatsapp',
        'profile',
        'mail',
        'phone',
        'date_birth',
        'zip_code',
        'address'
    ];

    # Regras de validação (validações complexas devem estar nos Requests)
    protected $validationRules = [];

    # Mensagens de validação customizadas
    protected $validationMessages = [];

    # Pular validação
    protected $skipValidation = false;

    # Callbacks executados automaticamente pelo CI4
    protected $beforeInsert = [];
    protected $afterInsert = [];
    protected $beforeUpdate = [];
    protected $afterUpdate = [];
    protected $beforeFind = [];
    protected $afterFind = [];
    protected $beforeDelete = [];
    protected $afterDelete = [];

    # Type casting automático de campos (int, date, datetime, etc)
    protected $casts = [
        'id' => 'int',
        'date_birth' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    # Campos sensíveis que serão removidos pela Library de formatação
    # Esta propriedade é apenas referência, não é processada pelo Model
    public $hiddenFields = [
        'cpf',
        'mail'
    ];

    # Buscar todos os registros incluindo os deletados
    # Rota: GET /api/v1/objeto/with-deleted
    public function findAllWithDeleted(?int $limit = null, ?int $offset = null): array
    {
        $builder = $this->builder();

        if ($limit !== null) {
            $builder->limit($limit, $offset ?? 0);
        }

        return $builder->get()->getResultArray();
    }

    # Buscar apenas os registros deletados
    # Rota: GET /api/v1/objeto/only-deleted
    public function findOnlyDeleted(?int $limit = null, ?int $offset = null): array
    {
        $builder = $this->builder();
        $builder->where($this->deletedField . ' IS NOT NULL', null, false);

        if ($limit !== null) {
            $builder->limit($limit, $offset ?? 0);
        }

        return $builder->get()->getResultArray();
    }

    # Buscar registro por ID incluindo deletados
    # Rota: POST /api/v1/objeto/{id}/with-deleted
    public function findWithDeleted(int $id): ?array
    {
        $builder = $this->builder();
        $result = $builder->where($this->primaryKey, $id)->get()->getRowArray();

        return $result ?: null;
    }

    # Restaurar registro soft deleted (remove deleted_at)
    # Rota: PATCH /api/v1/objeto/{id}/restore
    public function restore(int $id): bool
    {
        $builder = $this->builder();
        return $builder->where($this->primaryKey, $id)
            ->set($this->deletedField, null)
            ->update();
    }

    # Hard delete - Exclusão permanente do banco
    # Rota: DELETE /api/v1/objeto/{id}/hard
    public function hardDelete(int $id): bool
    {
        return $this->delete($id, true);
    }

    # Limpar todos os registros marcados como deletados
    # Rota: DELETE /api/v1/objeto/clear
    public function clearDeleted(): int
    {
        $builder = $this->builder();
        $builder->where($this->deletedField . ' IS NOT NULL', null, false);

        $count = $builder->countAllResults(false);
        $builder->delete();

        return $count;
    }

    # Busca avançada por múltiplos campos com operadores e filtros
    # Rota: POST /api/v1/objeto/search
    public function search(array $filters = [], array $options = []): array
    {
        $builder = $this->builder();

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

        if (isset($options['with_deleted']) && $options['with_deleted'] === true) {
            $this->tempUseSoftDeletes = false;
        }

        if (isset($options['order_by'])) {
            $orderDirection = $options['order_direction'] ?? 'ASC';
            $builder->orderBy($options['order_by'], $orderDirection);
        }

        if (isset($options['limit'])) {
            $offset = $options['offset'] ?? 0;
            $builder->limit($options['limit'], $offset);
        }

        return $builder->get()->getResultArray();
    }

    # Obter metadados completos das colunas da tabela
    # Rota: GET /api/v1/objeto/columns
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

    # Obter apenas os nomes das colunas da tabela
    # Rota: GET /api/v1/objeto/column-names
    public function getColumnNames(): array
    {
        $db = \Config\Database::connect($this->DBGroup);
        $fields = $db->getFieldData($this->table);

        return array_map(function ($field) {
            return $field->name;
        }, $fields);
    }

    # Verifica se um registro existe por ID
    public function exists(int $id): bool
    {
        return $this->find($id) !== null;
    }

    # Conta total de registros incluindo deletados
    public function countAllWithDeleted(): int
    {
        $builder = $this->builder();
        return $builder->countAllResults();
    }

    # Conta apenas registros deletados
    public function countOnlyDeleted(): int
    {
        $builder = $this->builder();
        $builder->where($this->deletedField . ' IS NOT NULL', null, false);
        return $builder->countAllResults();
    }
}
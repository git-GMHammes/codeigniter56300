# ResourceModel - Documentação Completa

**Localização:** `app/Models/v1/UserManagement/ResourceModel.php`

---

## 📋 Índice

1. [Configurações do Model](#configurações-do-model)
2. [Callbacks do CodeIgniter 4](#-callbacks-do-codeigniter-4)
3. [Campos Sensíveis (Hidden Fields)](#campos-sensíveis-hidden-fields)
4. [Métodos de Paginação](#métodos-de-paginação)
5. [Métodos de Soft Delete](#métodos-de-soft-delete)
6. [Métodos de Busca](#métodos-de-busca)
7. [Métodos de Metadados](#métodos-de-metadados)
8. [Operadores de Filtro](#operadores-de-filtro)
9. [Exemplos de Uso](#exemplos-de-uso)

---

## Configurações do Model

```php
protected $table = 'user_management';          # Tabela do banco
protected $primaryKey = 'id';                  # Chave primária
protected $returnType = 'array';               # Retorno como array
protected $useSoftDeletes = true;              # Soft delete ativo
protected $deletedField = 'deleted_at';        # Campo de soft delete
protected $useTimestamps = true;               # Timestamps automáticos
protected $createdField = 'created_at';        # Campo de criação
protected $updatedField = 'updated_at';        # Campo de atualização
protected $allowedFields = ['password', 'user']; # Campos permitidos
```

---

## 🔄 Callbacks do CodeIgniter 4

**Callbacks automáticos disponíveis no Model:**

```php
protected $beforeInsert = [];                  # Antes de inserir
protected $afterInsert = [];                   # Depois de inserir
protected $beforeUpdate = [];                  # Antes de atualizar
protected $afterUpdate = [];                   # Depois de atualizar
protected $beforeFind = [];                    # Antes de buscar
protected $afterFind = ['removeHiddenFields']; # Depois de buscar ✅ ATIVO
protected $beforeDelete = [];                  # Antes de deletar
protected $afterDelete = [];                   # Depois de deletar
```

### Callback Ativo

**`afterFind` → `removeHiddenFields`**

- Remove automaticamente campos sensíveis após qualquer busca
- Executado em: `find()`, `findAll()`, `first()`, etc.

---

### 💡 Exemplos de Uso Futuro

#### 1️⃣ Hash de Senha Automático

**Antes de inserir/atualizar, hash a senha:**

```php
protected $beforeInsert = ['hashPassword'];
protected $beforeUpdate = ['hashPassword'];

protected function hashPassword(array $data): array
{
    if (isset($data['data']['password'])) {
        $data['data']['password'] = password_hash(
            $data['data']['password'],
            PASSWORD_DEFAULT
        );
    }
    return $data;
}
```

**Uso:**

```php
$model->insert(['user' => 'joao', 'password' => '123456']);
// Senha automaticamente hasheada antes de salvar
```

---

#### 2️⃣ Validar Dados Antes de Inserir

**Validação customizada antes de inserir:**

```php
protected $beforeInsert = ['validateUserUnique'];

protected function validateUserUnique(array $data): array
{
    if (isset($data['data']['user'])) {
        $exists = $this->where('user', $data['data']['user'])->first();

        if ($exists) {
            throw new \Exception('Usuário já existe');
        }
    }
    return $data;
}
```

---

#### 3️⃣ Auditoria Automática (Quem Criou/Atualizou)

**Registrar automaticamente quem fez a ação:**

```php
protected $beforeInsert = ['addCreatedBy'];
protected $beforeUpdate = ['addUpdatedBy'];

protected function addCreatedBy(array $data): array
{
    $data['data']['created_by'] = session('user_id');
    return $data;
}

protected function addUpdatedBy(array $data): array
{
    $data['data']['updated_by'] = session('user_id');
    return $data;
}
```

**Estrutura da tabela:**

```sql
ALTER TABLE user_management
ADD COLUMN created_by INT,
ADD COLUMN updated_by INT;
```

---

#### 4️⃣ Gerar Slug Automático

**Criar slug a partir do nome:**

```php
protected $beforeInsert = ['generateSlug'];
protected $beforeUpdate = ['generateSlug'];

protected function generateSlug(array $data): array
{
    if (isset($data['data']['name']) && empty($data['data']['slug'])) {
        $data['data']['slug'] = url_title(
            $data['data']['name'],
            '-',
            true
        );
    }
    return $data;
}
```

---

#### 5️⃣ Log de Atividades

**Registrar todas as ações no banco:**

```php
protected $afterInsert = ['logActivity'];
protected $afterUpdate = ['logActivity'];
protected $afterDelete = ['logActivity'];

protected function logActivity(array $data): array
{
    $logModel = new ActivityLogModel();

    $logModel->insert([
        'table' => $this->table,
        'action' => $data['method'] ?? 'unknown',
        'record_id' => $data['id'] ?? null,
        'user_id' => session('user_id'),
        'created_at' => date('Y-m-d H:i:s')
    ]);

    return $data;
}
```

---

#### 6️⃣ Normalizar Dados

**Converter emails para minúsculo:**

```php
protected $beforeInsert = ['normalizeEmail'];
protected $beforeUpdate = ['normalizeEmail'];

protected function normalizeEmail(array $data): array
{
    if (isset($data['data']['email'])) {
        $data['data']['email'] = strtolower(
            trim($data['data']['email'])
        );
    }
    return $data;
}
```

---

#### 7️⃣ Gerar UUID Automático

**Criar UUID único para cada registro:**

```php
protected $beforeInsert = ['generateUuid'];

protected function generateUuid(array $data): array
{
    if (empty($data['data']['uuid'])) {
        $data['data']['uuid'] = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    return $data;
}
```

---

#### 8️⃣ Sanitizar Dados de Entrada

**Limpar HTML/scripts maliciosos:**

```php
protected $beforeInsert = ['sanitizeData'];
protected $beforeUpdate = ['sanitizeData'];

protected function sanitizeData(array $data): array
{
    $fieldsToSanitize = ['name', 'description', 'bio'];

    foreach ($fieldsToSanitize as $field) {
        if (isset($data['data'][$field])) {
            $data['data'][$field] = strip_tags(
                $data['data'][$field]
            );
        }
    }

    return $data;
}
```

---

#### 9️⃣ Enviar Notificação Após Criar

**Email de boas-vindas automático:**

```php
protected $afterInsert = ['sendWelcomeEmail'];

protected function sendWelcomeEmail(array $data): array
{
    if (isset($data['data']['email'])) {
        $email = \Config\Services::email();

        $email->setTo($data['data']['email']);
        $email->setSubject('Bem-vindo!');
        $email->setMessage('Sua conta foi criada com sucesso.');
        $email->send();
    }

    return $data;
}
```

---

#### 🔟 Invalidar Cache Após Atualizar

**Limpar cache quando dados mudarem:**

```php
protected $afterUpdate = ['clearCache'];
protected $afterDelete = ['clearCache'];

protected function clearCache(array $data): array
{
    $cache = \Config\Services::cache();
    $cache->delete('users_list');
    $cache->delete('user_' . ($data['id'][0] ?? ''));

    return $data;
}
```

---

### 📝 Como Adicionar Múltiplos Callbacks

**Você pode ter vários callbacks no mesmo evento:**

```php
protected $beforeInsert = [
    'hashPassword',
    'generateUuid',
    'normalizeEmail',
    'addCreatedBy'
];

protected $afterInsert = [
    'logActivity',
    'sendWelcomeEmail',
    'clearCache'
];
```

**Ordem de execução:** De cima para baixo

---

### ⚠️ Importante sobre Callbacks

1. **Sempre retorne `$data`** ao final do callback
2. **Estrutura do `$data`:**
   ```php
   [
       'data' => [...],    // Dados sendo inseridos/atualizados
       'method' => '...',  // insert/update/delete
       'id' => [...]       // IDs afetados (em updates/deletes)
   ]
   ```
3. **Erros:** Use `throw new \Exception()` para interromper operação
4. **Performance:** Evite queries pesadas dentro de callbacks
5. **Teste sempre:** Callbacks podem causar efeitos colaterais

---

## Campos Sensíveis (Hidden Fields)

**Campos removidos automaticamente do retorno:**

```php
public $hiddenFields = [
    'password',
    'token',
    'api_token',
    'remember_token',
];
```

**Como funciona:**

- Executado automaticamente via callback `afterFind`
- Remove campos antes de retornar dados
- Performance: O(n) usando `array_diff_key`

**Adicionar novos campos sensíveis:**

```php
public $hiddenFields = [
    'password',
    'token',
    'cpf',        // Adicionar
    'telefone',   // Adicionar
];
```

---

## Métodos de Paginação

### `paginateWithMeta()`

**Paginação completa com metadados e links de navegação**

```php
public function paginateWithMeta(
    int $perPage = 15,
    int $page = 1,
    array $filters = [],
    array $options = []
): array
```

**Parâmetros:**

- `$perPage` - Registros por página (padrão: 15)
- `$page` - Página atual (padrão: 1)
- `$filters` - Filtros de busca (opcional)
- `$options` - Opções adicionais (opcional)

**Opções disponíveis:**

```php
$options = [
    'with_deleted' => true,           // Incluir deletados
    'order_by' => 'created_at',       // Campo de ordenação
    'order_direction' => 'DESC'        // Direção (ASC/DESC)
];
```

**Retorno:**

```php
[
    'data' => [...],  // Registros
    'meta' => [
        'current_page' => 1,
        'per_page' => 15,
        'total' => 150,
        'total_pages' => 10,
        'from' => 1,
        'to' => 15,
        'has_next_page' => true,
        'has_previous_page' => false,
        'links' => [
            'first' => 'http://...',
            'prev' => null,
            'next' => 'http://...',
            'last' => 'http://...'
        ]
    ]
]
```

**Exemplo:**

```php
$result = $model->paginateWithMeta(20, 2);
```

---

### `findAllWithDeleted()`

**Busca todos incluindo deletados (com suporte a paginação)**

```php
public function findAllWithDeleted(
    ?int $limit = null,
    ?int $offset = null,
    bool $paginated = false,
    int $page = 1
): array
```

**Modo paginado:**

```php
$result = $model->findAllWithDeleted(15, null, true, 1);
```

**Modo simples:**

```php
$result = $model->findAllWithDeleted(10, 0, false);
```

---

### `findOnlyDeleted()`

**Busca apenas registros deletados (com suporte a paginação)**

```php
public function findOnlyDeleted(
    ?int $limit = null,
    ?int $offset = null,
    bool $paginated = false,
    int $page = 1
): array
```

**Exemplo paginado:**

```php
$result = $model->findOnlyDeleted(20, null, true, 2);
```

---

## Métodos de Soft Delete

### `findWithDeleted()`

**Busca registro por ID incluindo deletados**

```php
public function findWithDeleted(int $id): ?array
```

**Exemplo:**

```php
$user = $model->findWithDeleted(5);
```

---

### `restore()`

**Restaura registro soft deleted**

```php
public function restore(int $id): bool
```

**Exemplo:**

```php
$restored = $model->restore(5);
```

---

### `hardDelete()`

**Exclusão permanente do banco**

```php
public function hardDelete(int $id): bool
```

**Exemplo:**

```php
$deleted = $model->hardDelete(5);
```

---

### `clearDeleted()`

**Remove permanentemente todos os registros deletados**

```php
public function clearDeleted(): int
```

**Retorno:** Quantidade de registros removidos

**Exemplo:**

```php
$count = $model->clearDeleted(); // Retorna: 15
```

---

## Métodos de Busca

### `search()`

**Busca avançada com filtros e operadores**

```php
public function search(
    array $filters = [],
    array $options = [],
    bool $paginated = false,
    int $page = 1,
    int $perPage = 15
): array
```

**Exemplo:**

```php
$filters = [
    'user' => [
        'operator' => 'like',
        'value' => 'joao'
    ],
    'created_at' => [
        'operator' => '>=',
        'value' => '2024-01-01'
    ]
];

$options = [
    'order_by' => 'id',
    'order_direction' => 'DESC'
];

$result = $model->search($filters, $options, true, 1, 20);
```

---

### `exists()`

**Verifica se registro existe por ID**

```php
public function exists(int $id): bool
```

**Exemplo:**

```php
if ($model->exists(5)) {
    // Registro existe
}
```

---

## Métodos de Metadados

### `getColumnsMetadata()`

**Retorna metadados completos das colunas**

```php
public function getColumnsMetadata(): array
```

**Retorno:**

```php
[
    [
        'COLUMN_NAME' => 'id',
        'COLUMN_TYPE' => 'int',
        'IS_NULLABLE' => 'NO',
        'COLUMN_KEY' => 'PRI',
        'COLUMN_DEFAULT' => null,
        'MAX_LENGTH' => null
    ],
    // ...
]
```

---

### `getColumnNames()`

**Retorna apenas os nomes das colunas**

```php
public function getColumnNames(): array
```

**Retorno:**

```php
['id', 'user', 'password', 'created_at', 'updated_at', 'deleted_at']
```

---

### `countAllWithDeleted()`

**Conta total incluindo deletados**

```php
public function countAllWithDeleted(): int
```

---

### `countOnlyDeleted()`

**Conta apenas deletados**

```php
public function countOnlyDeleted(): int
```

---

## Operadores de Filtro

**Operadores disponíveis no método `search()`:**

| Operador             | Descrição            | Exemplo                                                          |
| -------------------- | -------------------- | ---------------------------------------------------------------- |
| `=`                  | Igual (padrão)       | `['id' => 5]`                                                    |
| `!=` ou `<>`         | Diferente            | `['status' => ['operator' => '!=', 'value' => 'active']]`        |
| `>`                  | Maior que            | `['age' => ['operator' => '>', 'value' => 18]]`                  |
| `>=`                 | Maior ou igual       | `['price' => ['operator' => '>=', 'value' => 100]]`              |
| `<`                  | Menor que            | `['stock' => ['operator' => '<', 'value' => 10]]`                |
| `<=`                 | Menor ou igual       | `['discount' => ['operator' => '<=', 'value' => 50]]`            |
| `like` ou `contains` | Contém (%valor%)     | `['name' => ['operator' => 'like', 'value' => 'silva']]`         |
| `starts_with`        | Começa com (valor%)  | `['email' => ['operator' => 'starts_with', 'value' => 'admin']]` |
| `ends_with`          | Termina com (%valor) | `['domain' => ['operator' => 'ends_with', 'value' => '.com']]`   |
| `in`                 | Dentro do array      | `['id' => ['operator' => 'in', 'value' => [1,2,3]]]`             |
| `not_in`             | Fora do array        | `['status' => ['operator' => 'not_in', 'value' => ['banned']]]`  |

---

## Exemplos de Uso

### Exemplo 1: Listagem Simples com Paginação

```php
$model = new ResourceModel();
$result = $model->paginateWithMeta(15, 1);

// Acessar dados
$users = $result['data'];
$meta = $result['meta'];
```

---

### Exemplo 2: Busca com Filtros e Ordenação

```php
$filters = [
    'user' => [
        'operator' => 'like',
        'value' => 'admin'
    ]
];

$options = [
    'order_by' => 'created_at',
    'order_direction' => 'DESC'
];

$result = $model->search($filters, $options, true, 1, 20);
```

---

### Exemplo 3: Restaurar Usuário Deletado

```php
// Verificar se existe (incluindo deletados)
$user = $model->findWithDeleted(5);

if ($user && $user['deleted_at'] !== null) {
    // Restaurar
    $model->restore(5);
}
```

---

### Exemplo 4: Busca com Múltiplos Filtros

```php
$filters = [
    'user' => [
        'operator' => 'like',
        'value' => 'silva'
    ],
    'id' => [
        'operator' => 'in',
        'value' => [1, 2, 3, 5, 8]
    ],
    'created_at' => [
        'operator' => '>=',
        'value' => '2024-01-01'
    ]
];

$result = $model->search($filters, [], true, 1, 25);
```

---

### Exemplo 5: Limpar Registros Deletados

```php
// Contar quantos serão removidos
$count = $model->countOnlyDeleted();

// Confirmar e limpar
if ($count > 0) {
    $removed = $model->clearDeleted();
    echo "Removidos: {$removed} registros";
}
```

---

### Exemplo 6: Paginação com Inclusão de Deletados

```php
$options = ['with_deleted' => true];
$result = $model->paginateWithMeta(20, 1, [], $options);
```

---

### Exemplo 7: Verificar Existência

```php
if ($model->exists(10)) {
    echo "Usuário ID 10 existe";
} else {
    echo "Usuário não encontrado";
}
```

---

### Exemplo 8: Metadados da Tabela

```php
// Obter todas as colunas
$columns = $model->getColumnNames();
// ['id', 'user', 'password', 'created_at', ...]

// Obter metadados completos
$metadata = $model->getColumnsMetadata();
```

---

## 🔒 Segurança

**Campos sensíveis são removidos automaticamente:**

- ✅ `password` nunca retorna
- ✅ `token` nunca retorna
- ✅ `api_token` nunca retorna
- ✅ `remember_token` nunca retorna

**Para adicionar novos campos sensíveis:**

```php
public $hiddenFields = [
    'password',
    'token',
    'cpf',          // Novo
    'telefone',     // Novo
];
```

---

## ⚡ Performance

**Otimizações aplicadas:**

- Remoção de campos sensíveis: `O(n)` com `array_diff_key`
- Paginação: Query única com `countAllResults(false)`
- Filtros: Query builder nativo do CI4
- Links de navegação: Gerados em memória sem queries extras

---

## 📝 Notas Importantes

1. **Soft Delete:** Sempre use `delete()` ao invés de `hardDelete()` para manter histórico
2. **Paginação:** Limite máximo recomendado: 100 registros por página
3. **Filtros:** Sempre valide dados antes de passar para o Model
4. **Links:** URLs geradas automaticamente com `current_url()`
5. **Hidden Fields:** Aplicado automaticamente em todas as buscas

---

## 🚀 Fluxo Recomendado

**Controller → Service → Model**

```php
// Controller
$page = $this->request->getGet('page') ?? 1;
$limit = $this->request->getGet('limit') ?? 15;

// Service
$result = $this->service->index($page, $limit);

// Service chama Model
$data = $this->model->paginateWithMeta($limit, $page);
```

---

## 📚 Referências

- CodeIgniter 4 Model: https://codeigniter.com/user_guide/models/model.html
- Query Builder: https://codeigniter.com/user_guide/database/query_builder.html
- Soft Deletes: https://codeigniter.com/user_guide/models/model.html#usesoftdeletes

---

**Versão:** 1.0.0  
**Última atualização:** 2025-11-24  
**Autor:** Gustavo - HABILIDADE

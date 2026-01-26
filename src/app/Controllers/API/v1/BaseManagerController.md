# BaseManagerController

Classe abstrata que serve como **base para todos os Controllers de API** do sistema. Ela estende o `ResourceController` do CodeIgniter 4 e fornece métodos utilitários para padronizar as respostas, validações e execução de services.

## Localização

```
src/app/Controllers/API/v1/BaseManagerController.php
```

## Namespace

```php
namespace App\Controllers\API\v1;
```

## Dependências

- `CodeIgniter\RESTful\ResourceController` - Controller RESTful do CodeIgniter
- `App\Libraries\ApiResponse` - Biblioteca para padronização de respostas da API

---

## Propriedades

| Propriedade    | Tipo        | Descrição                                                     |
| -------------- | ----------- | ------------------------------------------------------------- |
| `$service`     | `protected` | Instância do Service que será utilizado pelo controller filho |
| `$apiResponse` | `protected` | Instância da classe `ApiResponse` para respostas padronizadas |

---

## Métodos

### `__construct()`

Construtor que inicializa a instância de `ApiResponse`.

```php
public function __construct()
{
    $this->apiResponse = new ApiResponse();
}
```

---

### `validateId($id)`

Valida se um ID foi fornecido na requisição.

**Parâmetros:**

- `$id` - O ID a ser validado

**Retorno:**

- `null` se o ID for válido
- Resposta de erro `400 Bad Request` se o ID não for fornecido

**Exemplo:**

```php
public function show($id = null)
{
    $error = $this->validateId($id);
    if ($error) return $error;

    // Continua com a lógica...
}
```

---

### `getPaginationParams(): array`

Extrai e normaliza os parâmetros de paginação da query string.

**Retorno:**

```php
[
    'page' => int,    // Página atual (mínimo: 1)
    'perPage' => int  // Itens por página (mínimo: 1, máximo: 100)
]
```

**Query Parameters aceitos:**

- `page` - Número da página (padrão: 1)
- `limit` - Quantidade de itens por página (padrão: 15)

**Exemplo:**

```php
// URL: /api/v1/users?page=2&limit=20

public function index()
{
    $pagination = $this->getPaginationParams();
    // $pagination = ['page' => 2, 'perPage' => 20]

    return $this->executeService(
        fn() => $this->service->getAll($pagination['page'], $pagination['perPage']),
        'Registros listados com sucesso.',
        true // withPagination
    );
}
```

---

### `executeService(callable $serviceCall, string $successMessage, bool $withPagination = false, int $successCode = 200)`

Executa um método do service e retorna uma resposta padronizada.

**Parâmetros:**
| Parâmetro | Tipo | Padrão | Descrição |
|-----------|------|--------|-----------|
| `$serviceCall` | `callable` | - | Função que executa o método do service |
| `$successMessage` | `string` | - | Mensagem de sucesso a ser retornada |
| `$withPagination` | `bool` | `false` | Se deve incluir metadados de paginação |
| `$successCode` | `int` | `200` | Código HTTP de sucesso (200 ou 201) |

**Estrutura esperada do retorno do Service:**

```php
[
    'success' => bool,
    'message' => string,      // Usado quando success = false
    'data' => mixed,          // Dados retornados
    'data' => [               // Quando withPagination = true
        'data' => array,
        'meta' => array
    ]
]
```

**Exemplos:**

```php
// Listagem simples
public function index()
{
    return $this->executeService(
        fn() => $this->service->getAll(),
        'Usuários listados com sucesso.'
    );
}

// Listagem com paginação
public function index()
{
    $pagination = $this->getPaginationParams();

    return $this->executeService(
        fn() => $this->service->getAll($pagination['page'], $pagination['perPage']),
        'Usuários listados com sucesso.',
        true
    );
}

// Criação (retorna 201)
public function create()
{
    return $this->executeService(
        fn() => $this->service->create($data),
        'Usuário criado com sucesso.',
        false,
        201
    );
}
```

---

### `validateRequest($request, string $method): ?array`

Executa a validação dos dados de entrada através de um objeto Request.

**Parâmetros:**

- `$request` - Objeto de Request que contém o método de validação
- `$method` - Nome do método de validação a ser chamado no Request

**Retorno:**

```php
// Quando há erro:
[
    'hasError' => true,
    'response' => ResponseInterface  // Resposta de erro pronta
]

// Quando válido:
[
    'hasError' => false,
    'data' => array  // Dados validados
]
```

**Estrutura esperada do método de validação no Request:**

```php
public function validateCreate(): array
{
    return [
        'valid' => bool,
        'errors' => array,  // Quando valid = false
        'data' => array     // Dados validados quando valid = true
    ];
}
```

**Exemplo:**

```php
public function create()
{
    $request = new UserRequest();
    $validation = $this->validateRequest($request, 'validateCreate');

    if ($validation['hasError']) {
        return $validation['response'];
    }

    $data = $validation['data'];

    return $this->executeService(
        fn() => $this->service->create($data),
        'Usuário criado com sucesso.',
        false,
        201
    );
}
```

---

## Exemplo Completo de Controller

```php
<?php

namespace App\Controllers\API\v1;

use App\Requests\UserRequest;
use App\Services\UserService;

class UserController extends BaseManagerController
{
    public function __construct()
    {
        parent::__construct();
        $this->service = new UserService();
    }

    /**
     * GET /api/v1/users
     * Lista todos os usuários com paginação
     */
    public function index()
    {
        $pagination = $this->getPaginationParams();

        return $this->executeService(
            fn() => $this->service->getAll($pagination['page'], $pagination['perPage']),
            'Usuários listados com sucesso.',
            true
        );
    }

    /**
     * GET /api/v1/users/{id}
     * Retorna um usuário específico
     */
    public function show($id = null)
    {
        $error = $this->validateId($id);
        if ($error) return $error;

        return $this->executeService(
            fn() => $this->service->getById($id),
            'Usuário encontrado com sucesso.'
        );
    }

    /**
     * POST /api/v1/users
     * Cria um novo usuário
     */
    public function create()
    {
        $request = new UserRequest();
        $validation = $this->validateRequest($request, 'validateCreate');

        if ($validation['hasError']) {
            return $validation['response'];
        }

        return $this->executeService(
            fn() => $this->service->create($validation['data']),
            'Usuário criado com sucesso.',
            false,
            201
        );
    }

    /**
     * PUT /api/v1/users/{id}
     * Atualiza um usuário existente
     */
    public function update($id = null)
    {
        $error = $this->validateId($id);
        if ($error) return $error;

        $request = new UserRequest();
        $validation = $this->validateRequest($request, 'validateUpdate');

        if ($validation['hasError']) {
            return $validation['response'];
        }

        return $this->executeService(
            fn() => $this->service->update($id, $validation['data']),
            'Usuário atualizado com sucesso.'
        );
    }

    /**
     * DELETE /api/v1/users/{id}
     * Remove um usuário
     */
    public function delete($id = null)
    {
        $error = $this->validateId($id);
        if ($error) return $error;

        return $this->executeService(
            fn() => $this->service->delete($id),
            'Usuário removido com sucesso.'
        );
    }
}
```

---

## Fluxo de Funcionamento

```
┌─────────────────────────────────────────────────────────────────┐
│                         REQUISIÇÃO                              │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    BaseManagerController                        │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  1. validateId() - Valida ID se necessário              │    │
│  └─────────────────────────────────────────────────────────┘    │
│                              │                                  │
│                              ▼                                  │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  2. validateRequest() - Valida dados de entrada         │    │
│  └─────────────────────────────────────────────────────────┘    │
│                              │                                  │
│                              ▼                                  │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  3. getPaginationParams() - Extrai paginação (se list)  │    │
│  └─────────────────────────────────────────────────────────┘    │
│                              │                                  │
│                              ▼                                  │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  4. executeService() - Executa service e retorna        │    │
│  │     resposta padronizada via ApiResponse                │    │
│  └─────────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                      RESPOSTA JSON                              │
│  {                                                              │
│    "success": true,                                             │
│    "message": "Operação realizada com sucesso.",                │
│    "data": { ... },                                             │
│    "meta": { ... }  // Se paginação                             │
│  }                                                              │
└─────────────────────────────────────────────────────────────────┘
```

---

## Códigos HTTP Retornados

| Código | Método              | Cenário                         |
| ------ | ------------------- | ------------------------------- |
| `200`  | `success()`         | Operação bem sucedida           |
| `201`  | `created()`         | Recurso criado com sucesso      |
| `400`  | `badRequest()`      | ID não fornecido                |
| `404`  | `notFound()`        | Recurso não encontrado          |
| `422`  | `validationError()` | Erro de validação dos dados     |
| `500`  | `internalError()`   | Erro interno ao processar dados |

---

## Boas Práticas

1. **Sempre chame `parent::__construct()`** no construtor do controller filho
2. **Inicialize o service** no construtor do controller filho
3. **Use arrow functions** (`fn() =>`) para passar callbacks ao `executeService()`
4. **Siga o padrão de retorno** do service para manter consistência
5. **Crie classes Request** separadas para validação de dados de entrada

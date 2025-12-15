# 📐 Arquitetura do Sistema - Documentação Técnica

> **Projeto**: API REST Multi-tenant com Frontend Multi-plataforma  
> **Backend**: CodeIgniter 4  
> **Frontend**: Flutter Web/Mobile + React Web  
> **Padrão**: MVC + Service Layer + Repository Pattern  
> **Data**: Dezembro 2025

---

## 📋 Índice

1. [Visão Geral da Arquitetura](#visão-geral-da-arquitetura)
2. [Backend - API CodeIgniter 4](#backend---api-codeigniter-4)
3. [Frontend Flutter](#frontend-flutter)
4. [Frontend React](#frontend-react)
5. [Fluxos de Dados](#fluxos-de-dados)
6. [Code Review e Boas Práticas](#code-review-e-boas-práticas)
7. [Segurança e Autenticação](#segurança-e-autenticação)
8. [Conclusão](#conclusão)

---

## 🏗️ Visão Geral da Arquitetura

### Arquitetura Geral do Sistema

```
┌────────────────────────────────────────────────────────────────┐
│                        CAMADA DE CLIENTES                      │
├────────────────────────────────────────────────────────────────┤
│                                                                │
│  ┌──────────────────────┐         ┌──────────────────────┐     │
│  │   Flutter Web/Mobile │         │     React Web        │     │
│  │   ┌──────────────┐   │         │   ┌──────────────┐   │     │
│  │   │ Presentation │   │         │   │  Components  │   │     │
│  │   │ Application  │   │         │   │    Hooks     │   │     │
│  │   │    Domain    │   │         │   │   Contexts   │   │     │
│  │   │     Data     │   │         │   │   Services   │   │     │
│  │   └──────────────┘   │         │   └──────────────┘   │     │
│  └──────────────────────┘         └──────────────────────┘     │
│           │                                   │                │
│           └───────────────┬───────────────────┘                │
│                           │                                    │
└───────────────────────────┼────────────────────────────────────┘
                            │
                    ┌───────▼────────┐
                    │   CORS/HTTPS   │
                    └───────┬────────┘
                            │
┌───────────────────────────▼──────────────────────────────────────┐
│                    CAMADA DE API REST                            │
├──────────────────────────────────────────────────────────────────┤
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │              CodeIgniter 4 Framework (v4.x)                │  │
│  └────────────────────────────────────────────────────────────┘  │
│                                                                  │
│  ┌─────────────────┐     ┌───────────────────┐                   │
│  │   AuthFilter    │───▶ │ LogRequestFilter │                    │
│  └─────────────────┘     └───────────────────┘                   │
│                                                                  │
│  ┌────────────────────────────────────────────────────────────┐  │
│  │                    CONTROLLERS (v1)                        │  │
│  │  • UserManagement/AuthController                           │  │
│  │  • UserManagement/ManagerController                        │  │
│  │  • UserCustomer/ManagerController                          │  │
│  │  • UserCustomerFile/ManagerController                      │  │
│  │  • UserCustomerManagement/ManagerController                │  │
│  │  • Log/ManagerController                                   │  │
│  └────────────────────┬───────────────────────────────────────┘  │
│                       │                                          │
│  ┌────────────────────▼───────────────────────────────────────┐  │
│  │                    REQUESTS (v1)                           │  │
│  │  • LoginRequest, StoreRequest, ModifyRequest               │  │
│  │  • SearchRequest (Validação + Regras de Negócio)           │  │
│  └────────────────────┬───────────────────────────────────────┘  │
│                       │                                          │
│  ┌────────────────────▼───────────────────────────────────────┐  │
│  │                    SERVICES (v1)                           │  │
│  │  • AuthenticationService (JWT + Password)                  │  │
│  │  • TokenService (Geração/Validação JWT)                    │  │
│  │  • ManagerService (CRUD + Lógica de Negócio)               │  │
│  │  • UploadService (Gerenciamento de Arquivos)               │  │
│  └────────────────────┬───────────────────────────────────────┘  │
│                       │                                          │
│  ┌────────────────────▼───────────────────────────────────────┐  │
│  │                     MODELS (v1)                            │  │
│  │  • BaseResourceModel (CRUD Base + Soft Delete)             │  │
│  │  • UserManagement/ResourceModel                            │  │
│  │  • UserCustomer/ResourceModel + FileModel                  │  │
│  │  • Log/ResourceModel                                       │  │
│  └────────────────────┬───────────────────────────────────────┘  │
│                       │                                          │
└───────────────────────┼──────────────────────────────────────────┘
                        │
        ┌───────────────┼───────────────┐
        │               │               │
┌───────▼──────┐ ┌──────▼─────┐ ┌──────▼─────┐
│   Database   │ │  Database  │ │  Database  │
│   Primary    │ │  Secondary │ │   Logs     │
│   (Users)    │ │ (Customers)│ │  (Audit)   │
└──────────────┘ └────────────┘ └────────────┘
```

---

## 🔧 Backend - API CodeIgniter 4

### 1. Estrutura de Camadas MVC Estendida

O projeto implementa uma arquitetura em **6 camadas** que estende o padrão MVC tradicional:

```
┌──────────────────────────────────────────────────────────────┐
│                    1. ROUTES LAYER                           │
│  Define endpoints versionados da API                         │
│  Localização: app/Routes/API/v1/**/api_routes.php            │
└────────────────────────────┬─────────────────────────────────┘
                             │
┌────────────────────────────▼─────────────────────────────────┐
│                    2. FILTERS LAYER                          │
│  • AuthFilter: Validação JWT                                 │
│  • LogRequestFilter: Auditoria de requisições                │
│  Localização: app/Filters/v1/                                │
└────────────────────────────┬─────────────────────────────────┘
                             │
┌────────────────────────────▼─────────────────────────────────┐
│                    3. CONTROLLERS LAYER                      │
│  • Recebe requisições HTTP                                   │
│  • Orquestra chamadas aos Services                           │
│  • Retorna ApiResponse padronizada                           │
│  Base: BaseManagerController                                 │
│  Localização: app/Controllers/API/v1/                        │
└────────────────────────────┬─────────────────────────────────┘
                             │
┌────────────────────────────▼─────────────────────────────────┐
│                    4. REQUESTS LAYER                         │
│  • Validação de entrada (Input Validation)                   │
│  • Regras customizadas (Rules)                               │
│  • Sanitização de dados                                      │
│  Localização: app/Requests/v1/ + app/Rules/v1/               │
└────────────────────────────┬─────────────────────────────────┘
                             │
┌────────────────────────────▼─────────────────────────────────┐
│                    5. SERVICES LAYER                         │
│  • Lógica de negócio complexa                                │
│  • Transações multi-tabela                                   │
│  • Integração com APIs externas                              │
│  Base: BaseManagerService                                    │
│  Localização: app/Services/v1/                               │
└────────────────────────────┬─────────────────────────────────┘
                             │
┌────────────────────────────▼─────────────────────────────────┐
│                    6. MODELS LAYER                           │
│  • Acesso direto ao banco de dados                           │
│  • CRUD básico + Soft Delete                                 │
│  • Relacionamentos entre entidades                           │
│  Base: BaseResourceModel                                     │
│  Localização: app/Models/v1/                                 │
└────────────────────────────┬─────────────────────────────────┘
                             │
                    ┌────────▼────────┐
                    │    Database     │
                    └─────────────────┘
```

### 2. Fluxo de Requisição Completo

#### 2.1. Fluxo de Autenticação (Login)

```
┌─────────────┐
│   Cliente   │
│ (Flutter/   │
│  React)     │
└──────┬──────┘
       │
       │ POST /api/v1/user-management/login
       │ { "email": "user@gov.br", "password": "***" }
       │
       ▼
┌──────────────────────────────────────────────────────────┐
│  ROUTES: app/Routes/API/v1/UserManagement/api_routes.php │
│  $routes->post('login', 'AuthController::login');        │
└────────────────────────┬─────────────────────────────────┘
                         │
                         ▼
┌──────────────────────────────────────────────────────────┐
│  CONTROLLER: AuthController::login()                     │
│  1. Valida dados com LoginRequest                        │
│  2. Chama AuthenticationService::login()                 │
│  3. Retorna JWT + dados do usuário                       │
└────────────────────────┬─────────────────────────────────┘
                         │
                         ▼
┌──────────────────────────────────────────────────────────┐
│  REQUEST: LoginRequest                                   │
│  • Valida email (formato + obrigatório)                  │
│  • Valida senha (min 6 chars + obrigatório)              │
│  • Sanitiza inputs                                       │
└────────────────────────┬─────────────────────────────────┘
                         │
                         ▼
┌──────────────────────────────────────────────────────────┐
│  SERVICE: AuthenticationService::login()                 │
│  1. Busca usuário por email (UserModel)                  │
│  2. Verifica senha (PasswordService::verify)             │
│  3. Gera JWT (TokenService::generate)                    │
│  4. Atualiza last_login                                  │
│  5. Registra log de acesso                               │
└────────────────────────┬─────────────────────────────────┘
                         │
                         ▼
┌──────────────────────────────────────────────────────────┐
│  MODEL: UserManagement/ResourceModel                     │
│  • findByEmail($email)                                   │
│  • update(['last_login' => now()])                       │
└────────────────────────┬─────────────────────────────────┘
                         │
                         ▼
┌──────────────────────────────────────────────────────────┐
│  RESPONSE: ApiResponse                                   │
│  {                                                       │
│    "success": true,                                      │
│    "data": {                                             │
│      "token": "eyJ0eXAiOiJKV1QiLCJh...",                 │
│      "user": { "id": 1, "name": "..." }                  │
│    },                                                    │
│    "message": "Login realizado com sucesso"              │
│  }                                                       │
└──────────────────────────────────────────────────────────┘
```

#### 2.2. Fluxo de Requisição Autenticada (CRUD)

```
┌─────────────┐
│   Cliente   │
└──────┬──────┘
       │
       │ GET /api/v1/user-customer?page=1&limit=10
       │ Headers: { Authorization: "Bearer eyJ0eXAi..." }
       │
       ▼
┌──────────────────────────────────────────────────────────┐
│  FILTER: AuthFilter::before()                            │
│  1. Extrai token do header Authorization                 │
│  2. Valida JWT (TokenService::validate)                  │
│  3. Injeta dados do usuário em CurrentUser               │
│  4. Verifica permissões                                  │
└────────────────────────┬─────────────────────────────────┘
                         │ ✓ Token válido
                         ▼
┌──────────────────────────────────────────────────────────┐
│  FILTER: LogRequestFilter::before()                      │
│  • Registra timestamp de entrada                         │
│  • Captura IP, User-Agent, Endpoint                      │
└────────────────────────┬─────────────────────────────────┘
                         │
                         ▼
┌──────────────────────────────────────────────────────────┐
│  CONTROLLER: UserCustomer/ManagerController::index()     │
│  1. Valida query params com SearchRequest                │
│  2. Chama ManagerService::search()                       │
│  3. Formata response com ApiResponse                     │
└────────────────────────┬─────────────────────────────────┘
                         │
                         ▼
┌──────────────────────────────────────────────────────────┐
│  REQUEST: SearchRequest                                  │
│  • Valida page (inteiro positivo)                        │
│  • Valida limit (max 100)                                │
│  • Valida order_by (campos permitidos)                   │
│  • Sanitiza filtros                                      │
└────────────────────────┬─────────────────────────────────┘
                         │
                         ▼
┌──────────────────────────────────────────────────────────┐
│  SERVICE: UserCustomer/ManagerService::search()          │
│  1. Monta query com filtros                              │
│  2. Aplica paginação                                     │
│  3. Busca dados (ResourceModel)                          │
│  4. Enriquece dados (joins, related)                     │
│  5. Aplica formatação de resposta                        │
└────────────────────────┬─────────────────────────────────┘
                         │
                         ▼
┌──────────────────────────────────────────────────────────┐
│  MODEL: UserCustomer/ResourceModel                       │
│  • Método search() com QueryBuilder                      │
│  • Aplica soft_delete filter (deleted_at IS NULL)        │
│  • Retorna Collection paginada                           │
└────────────────────────┬─────────────────────────────────┘
                         │
                         ▼
┌──────────────────────────────────────────────────────────┐
│  FILTER: LogRequestFilter::after()                       │
│  • Calcula tempo de execução                             │
│  • Registra status code da resposta                      │
│  • Salva log completo (LogModel)                         │
└────────────────────────┬─────────────────────────────────┘
                         │
                         ▼
┌──────────────────────────────────────────────────────────┐
│  RESPONSE: ApiResponse (Paginada)                        │
│  {                                                       │
│    "success": true,                                      │
│    "data": [{ ... }, { ... }],                           │
│    "pagination": {                                       │
│      "page": 1,                                          │
│      "limit": 10,                                        │
│      "total": 156,                                       │
│      "pages": 16                                         │
│    }                                                     │
│  }                                                       │
└──────────────────────────────────────────────────────────┘
```

### 3. Padrões de Código Aplicados

#### 3.1. BaseResourceModel (Herança)

```php
/**
 * Model base com funcionalidades comuns
 * Todos os models herdam desta classe
 */
abstract class BaseResourceModel extends Model
{
    // Soft Delete automático
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
    
    // Timestamps automáticos
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Métodos comuns herdados por todos
    public function search(array $filters, int $page, int $limit)
    {
        // Implementação de busca avançada
        // Aplicada automaticamente em todos os models
    }
    
    public function softDelete(int $id): bool
    {
        // Soft delete padronizado
        return $this->update($id, ['deleted_at' => date('Y-m-d H:i:s')]);
    }
}
```

#### 3.2. ApiResponse (Padronização)

```php
/**
 * Padronização de todas as respostas da API
 */
class ApiResponse
{
    public static function success($data = null, string $message = '', int $code = 200)
    {
        return response()->setJSON([
            'success' => true,
            'data' => $data,
            'message' => $message,
            'timestamp' => time(),
            'api_version' => 'v1'
        ])->setStatusCode($code);
    }
    
    public static function error(string $message, int $code = 400, $errors = null)
    {
        return response()->setJSON([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
            'timestamp' => time(),
            'api_version' => 'v1'
        ])->setStatusCode($code);
    }
}
```

#### 3.3. Múltiplos Bancos de Dados

```php
/**
 * Configuração em app/Config/Database.php
 */
class Database extends Config
{
    public $default = [
        'DSN'      => '',
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'db_users',
        // Banco principal: usuários, autenticação
    ];
    
    public $customers = [
        'DSN'      => '',
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'db_customers',
        // Banco secundário: clientes, arquivos
    ];
    
    public $logs = [
        'DSN'      => '',
        'hostname' => 'localhost',
        'username' => 'root',
        'password' => '',
        'database' => 'db_logs',
        // Banco de auditoria: logs, requisições
    ];
}

/**
 * Uso nos Models
 */
class UserCustomerResourceModel extends BaseResourceModel
{
    protected $DBGroup = 'customers'; // Usa banco 'customers'
}

class LogResourceModel extends BaseResourceModel
{
    protected $DBGroup = 'logs'; // Usa banco 'logs'
}
```

---

## 📱 Frontend Flutter

### 1. Arquitetura DDD (Domain-Driven Design)

```
lib/
├── app/                          # Configuração da aplicação
│   ├── router/                   # Sistema de rotas
│   │   ├── app_router.dart      # Definição de rotas
│   │   └── app_routes.dart      # Constantes de rotas
│   ├── theme/                    # Tema e cores
│   │   ├── app_theme.dart
│   │   └── color_schemes.dart
│   └── app.dart                  # App principal
│
├── core/                         # Núcleo compartilhado
│   ├── config/
│   │   └── env.dart             # Variáveis de ambiente
│   ├── errors/
│   │   ├── app_exception.dart   # Exceções customizadas
│   │   └── failure.dart         # Tratamento de falhas
│   ├── network/
│   │   ├── interceptors/        # Interceptors HTTP
│   │   ├── api_endpoints.dart   # Endpoints da API
│   │   └── dio_client.dart      # Cliente HTTP (Dio)
│   ├── utils/
│   │   ├── debouncer.dart       # Debounce para inputs
│   │   └── validators.dart      # Validações
│   └── widgets/
│       ├── app_dialog.dart      # Diálogos padrão
│       ├── app_loader.dart      # Loading screens
│       ├── app_scaffold.dart    # Scaffold base
│       └── app_snackbar.dart    # Snackbars
│
├── features/                     # Features por domínio
│   ├── user/
│   │   ├── domain/              # Camada de Domínio
│   │   │   ├── entities/        # Entidades puras
│   │   │   ├── repositories/    # Contratos de repositórios
│   │   │   └── usecases/        # Casos de uso
│   │   ├── data/                # Camada de Dados
│   │   │   ├── models/          # DTOs
│   │   │   ├── datasources/     # APIs, Local Storage
│   │   │   └── repositories/    # Implementação
│   │   ├── application/         # Camada de Aplicação
│   │   │   ├── providers/       # State Management (Riverpod)
│   │   │   └── controllers/     # Controladores
│   │   └── presentation/        # Camada de Apresentação
│   │       ├── screens/         # Telas
│   │       └── widgets/         # Componentes UI
│   │
│   └── home/
│       └── [mesma estrutura]
│
└── shared/                       # Recursos compartilhados
    ├── utils/
    └── widgets/
```

### 2. Fluxo de Dados no Flutter

```
┌───────────────────────────────────────────────────────────────┐
│                    PRESENTATION LAYER                         │
│  ┌──────────────┐         ┌──────────────┐                    │
│  │  LoginScreen │────────▶│ LoginButton │                    │
│  └──────────────┘         └──────┬───────┘                    │
│                                   │ onPressed()               │
└───────────────────────────────────┼───────────────────────────┘
                                    │
┌───────────────────────────────────▼───────────────────────────┐
│                   APPLICATION LAYER                           │
│  ┌────────────────────────────────────────────────────┐       │
│  │         UserController / Provider                  │       │
│  │  • Gerencia estado da UI                           │       │
│  │  • Chama casos de uso                              │       │
│  │  • Trata loading/success/error                     │       │
│  └─────────────────────┬──────────────────────────────┘       │
│                        │ loginUseCase.call()                  │
└────────────────────────┼──────────────────────────────────────┘
                         │
┌────────────────────────▼──────────────────────────────────────┐
│                     DOMAIN LAYER                              │
│  ┌────────────────────────────────────────────────────┐       │
│  │              LoginUseCase                          │       │
│  │  • Lógica de negócio pura                          │       │
│  │  • Independente de framework                       │       │
│  │  • Chama repositório                               │       │
│  └─────────────────────┬──────────────────────────────┘       │
│                        │ repository.login()                   │
│  ┌─────────────────────▼──────────────────────────────┐       │
│  │         IUserRepository (Interface)                │       │
│  │  • Contrato de acesso a dados                      │       │
│  └────────────────────────────────────────────────────┘       │
└───────────────────────────────────────────────────────────────┘
                         │
┌────────────────────────▼──────────────────────────────────────┐
│                      DATA LAYER                               │
│  ┌────────────────────────────────────────────────────┐       │
│  │      UserRepositoryImpl (Implementação)            │       │
│  │  • Implementa IUserRepository                      │       │
│  │  • Orquestra datasources                           │       │
│  └─────────────────────┬──────────────────────────────┘       │
│                        │                                      │
│         ┌──────────────┼──────────────┐                       │
│         │              │              │                       │
│  ┌──────▼──────┐ ┌─────▼──────┐ ┌────▼─────┐                  │
│  │   Remote    │ │   Local    │ │  Cache   │                  │
│  │ DataSource  │ │ DataSource │ │DataSource│                  │
│  │   (API)     │ │ (Storage)  │ │(Memory)  │                  │
│  └──────┬──────┘ └────────────┘ └──────────┘                  │
└─────────┼─────────────────────────────────────────────────────┘
          │
┌─────────▼────────┐
│   DioClient      │ ← Interceptors JWT, Error Handling
│   (HTTP)         │
└─────────┬────────┘
          │
┌─────────▼────────┐
│  CodeIgniter 4   │
│    API REST      │
└──────────────────┘
```

### 3. Exemplo de Implementação

#### 3.1. DioClient (Network)

```dart
class DioClient {
  final Dio _dio;
  
  DioClient() : _dio = Dio(BaseOptions(
    baseUrl: Env.apiBaseUrl,
    connectTimeout: const Duration(seconds: 30),
    receiveTimeout: const Duration(seconds: 30),
  )) {
    _setupInterceptors();
  }
  
  void _setupInterceptors() {
    _dio.interceptors.add(
      InterceptorsWrapper(
        onRequest: (options, handler) async {
          // Adiciona JWT token
          final token = await LocalStorage.getToken();
          if (token != null) {
            options.headers['Authorization'] = 'Bearer $token';
          }
          return handler.next(options);
        },
        onError: (error, handler) {
          // Trata erros globalmente
          if (error.response?.statusCode == 401) {
            // Redireciona para login
            AppRouter.goToLogin();
          }
          return handler.next(error);
        },
      ),
    );
  }
}
```

#### 3.2. UseCase (Domain)

```dart
class LoginUseCase {
  final IUserRepository _repository;
  
  LoginUseCase(this._repository);
  
  Future<Either<Failure, UserEntity>> call({
    required String email,
    required String password,
  }) async {
    // Validação de negócio
    if (!EmailValidator.isValid(email)) {
      return Left(ValidationFailure('Email inválido'));
    }
    
    // Chama repositório
    return await _repository.login(
      email: email,
      password: password,
    );
  }
}
```

---

## ⚛️ Frontend React

### 1. Estrutura de Componentes

```
src/
├── components/                   # Componentes reutilizáveis
│   ├── Auth/
│   │   └── useSession.js        # Hook de autenticação
│   │
│   ├── HForm/                   # Componentes de formulário
│   │   ├── HformInputTextLetters/
│   │   ├── HformInputTextMask/
│   │   └── HformInputTextNumber/
│   │
│   ├── HcButton/                # Botões customizados
│   │   ├── HcListActions/       # Botões de ação em lista
│   │   ├── index.jsx
│   │   └── styles.css
│   │
│   ├── HcModal/                 # Sistema de modais
│   │   ├── HcBasicModal/
│   │   ├── HcFadeModal/
│   │   ├── HcSlideUpDownModal/
│   │   └── [outros tipos de animação]
│   │
│   ├── HcMessage/               # Sistema de mensagens
│   │   ├── HcToasts/
│   │   ├── index.jsx
│   │   └── styles.css
│   │
│   ├── HcNavMenu/               # Navegação
│   │   ├── HcNavHorizontal/
│   │   └── HcNavVertical/
│   │
│   └── HcLoading/               # Loading states
│
├── config/                      # Configurações
├── contexts/                    # Context API (State)
├── hooks/                       # Custom Hooks
├── middlewares/                 # Middlewares (Auth, etc)
├── pages/                       # Páginas da aplicação
├── routes/                      # Definição de rotas
├── services/                    # Serviços de API
└── utils/                       # Utilitários
```

### 2. Fluxo de Dados no React

```
┌───────────────────────────────────────────────────────────────┐
│                         COMPONENT                             │
│  ┌─────────────────────────────────────────────────────┐      │
│  │              UserListPage.jsx                       │      │
│  │                                                     │      │
│  │  useEffect(() => {                                  │      │
│  │    loadUsers(); // ◀── Carrega dados ao montar     │      │
│  │  }, []);                                            │      │
│  │                                                     │      │
│  │  const loadUsers = async () => {                    │      │
│  │    setLoading(true);                                │      │
│  │    const result = await userService.getAll();       │      │
│  │    setUsers(result.data);                           │      │
│  │    setLoading(false);                               │      │
│  │  };                                                 │      │
│  └──────────────────────────┬──────────────────────────┘      │
└─────────────────────────────┼─────────────────────────────────┘
                              │
┌─────────────────────────────▼─────────────────────────────────┐
│                        CUSTOM HOOK                            │
│  ┌─────────────────────────────────────────────────────┐      │
│  │              useSession.js                          │      │
│  │                                                     │      │
│  │  const useSession = () => {                         │      │
│  │    const [token, setToken] = useState(null);        │      │
│  │    const [user, setUser] = useState(null);          │      │
│  │                                                     │      │
│  │    useEffect(() => {                                │      │
│  │      // Recupera token do localStorage              │      │
│  │      const savedToken = localStorage.getItem('jwt');│      │
│  │      if (savedToken) {                              │      │
│  │        setToken(savedToken);                        │      │
│  │        validateToken(savedToken);                   │      │
│  │      }                                              │      │
│  │    }, []);                                          │      │
│  │                                                     │      │
│  │    return { token, user, login, logout };           │      │
│  │  };                                                 │      │
│  └──────────────────────────┬──────────────────────────┘      │
└─────────────────────────────┼─────────────────────────────────┘
                              │
┌─────────────────────────────▼─────────────────────────────────┐
│                        SERVICE LAYER                          │
│  ┌─────────────────────────────────────────────────────┐      │
│  │            userService.js (Axios)                   │      │
│  │                                                     │      │
│  │  import axios from 'axios';                         │      │
│  │                                                     │      │
│  │  const API_URL = process.env.REACT_APP_API_URL;     │      │
│  │                                                     │      │
│  │  const userService = {                              │      │
│  │    getAll: async (params) => {                      │      │
│  │      const response = await axios.get(              │      │
│  │        `${API_URL}/api/v1/user-customer`,           │      │
│  │        {                                            │      │
│  │          params,                                    │      │
│  │          headers: {                                 │      │
│  │            Authorization: `Bearer ${token}`         │      │
│  │          }                                          │      │
│  │        }                                            │      │
│  │      );                                             │      │
│  │      return response.data;                          │      │
│  │    },                                               │      │
│  │                                                     │      │
│  │    create: async (data) => { ... },                 │      │
│  │    update: async (id, data) => { ... },             │      │
│  │    delete: async (id) => { ... }                    │      │
│  │  };                                                 │      │
│  └──────────────────────────┬──────────────────────────┘      │
└─────────────────────────────┼─────────────────────────────────┘
                              │
┌─────────────────────────────▼───────────────────────────────────────┐
│                     AXIOS INTERCEPTOR                               │
│  ┌───────────────────────────────────────────────────────────┐      │
│  │  // Interceptor de Request                                │      │
│  │  axios.interceptors.request.use(                          │      │
│  │    (config) => {                                          │      │
│  │      const token = localStorage.getItem('jwt');           │      │
│  │      if (token) {                                         │      │
│  │        config.headers.Authorization = `Bearer ${token}`;  │      │
│  │      }                                                    │      │
│  │      return config;                                       │      │
│  │    }                                                      │      │
│  │  );                                                       │      │
│  │                                                           │      │
│  │  // Interceptor de Response                               │      │
│  │  axios.interceptors.response.use(                         │      │
│  │    (response) => response,                                │      │
│  │    (error) => {                                           │      │
│  │      if (error.response?.status === 401) {                │      │
│  │        // Token expirado - redireciona login              │      │
│  │        localStorage.removeItem('jwt');                    │      │
│  │        window.location.href = '/login';                   │      │
│  │      }                                                    │      │
│  │      return Promise.reject(error);                        │      │
│  │    }                                                      │      │
│  │  );                                                       │      │
│  └───────────────────────────────────────────────────────────┘      │
└─────────────────────────────────────────────────────────────────────┘
```

### 3. Componentes Customizados (Pattern)

#### 3.1. HcButton (Botão Reutilizável)

```jsx
// components/HcButton/index.jsx
import React from 'react';
import './styles.css';

export const HcButton = ({ 
  children, 
  variant = 'primary', 
  size = 'medium',
  loading = false,
  disabled = false,
  onClick,
  icon,
  ...props 
}) => {
  return (
    <button
      className={`hc-button hc-button--${variant} hc-button--${size}`}
      disabled={disabled || loading}
      onClick={onClick}
      {...props}
    >
      {loading ? (
        <span className="hc-button__spinner" />
      ) : (
        <>
          {icon && <span className="hc-button__icon">{icon}</span>}
          <span className="hc-button__text">{children}</span>
        </>
      )}
    </button>
  );
};

// Uso:
<HcButton 
  variant="success" 
  size="large"
  onClick={handleSave}
  loading={isSaving}
>
  Salvar
</HcButton>
```

#### 3.2. HcModal (Sistema de Modais)

```jsx
// components/HcModal/HcFadeModal/index.jsx
import React from 'react';
import './styles.css';

export const HcFadeModal = ({ 
  isOpen, 
  onClose, 
  title, 
  children,
  footer 
}) => {
  if (!isOpen) return null;
  
  return (
    <div className="hc-modal-overlay" onClick={onClose}>
      <div 
        className="hc-modal-content hc-modal--fade"
        onClick={(e) => e.stopPropagation()}
      >
        <div className="hc-modal__header">
          <h2>{title}</h2>
          <button onClick={onClose}>×</button>
        </div>
        
        <div className="hc-modal__body">
          {children}
        </div>
        
        {footer && (
          <div className="hc-modal__footer">
            {footer}
          </div>
        )}
      </div>
    </div>
  );
};

// Uso:
<HcFadeModal
  isOpen={showModal}
  onClose={() => setShowModal(false)}
  title="Confirmar exclusão"
  footer={
    <>
      <HcButton variant="secondary" onClick={() => setShowModal(false)}>
        Cancelar
      </HcButton>
      <HcButton variant="danger" onClick={handleDelete}>
        Excluir
      </HcButton>
    </>
  }
>
  <p>Tem certeza que deseja excluir este registro?</p>
</HcFadeModal>
```

---

## 🔄 Fluxos de Dados

### 1. Fluxo Completo: Cadastro de Cliente

```
┌────────────┐
│   React    │ 1. Usuário preenche formulário
│  (Form)    │    HForm/HformInputText*
└─────┬──────┘
      │ 2. Submit (validação client-side)
      ▼
┌────────────────────────────────────────────────────────┐
│  userService.create(formData)                          │
│  POST /api/v1/user-customer                            │
│  Headers: { Authorization: Bearer eyJ... }             │
│  Body: { name, email, cpf, ... }                       │
└─────────────────────┬──────────────────────────────────┘
                      │ 3. Requisição HTTP
                      ▼
┌────────────────────────────────────────────────────────┐
│  CodeIgniter 4 API                                     │
│  ┌──────────────────────────────────────────────────┐  │
│  │ AuthFilter: Valida JWT                           │  │
│  │ ✓ Token válido → Injeta CurrentUser              │  │
│  └────────────────┬─────────────────────────────────┘  │
│                   │                                    │
│  ┌────────────────▼─────────────────────────────────┐  │
│  │ LogRequestFilter: Registra entrada               │  │
│  └────────────────┬─────────────────────────────────┘  │
│                   │                                    │
│  ┌────────────────▼─────────────────────────────────┐  │
│  │ Controller: UserCustomer/ManagerController       │  │
│  │ • Método: store()                                │  │
│  │ • Valida com StoreRequest                        │  │
│  └────────────────┬─────────────────────────────────┘  │
│                   │                                    │
│  ┌────────────────▼─────────────────────────────────┐  │
│  │ Request: StoreRequest                            │  │
│  │ • Valida CPF (formato + único)                   │  │
│  │ • Valida Email (formato + único)                 │  │
│  │ • Sanitiza dados                                 │  │
│  │ • Aplica StoreRules                              │  │
│  └────────────────┬─────────────────────────────────┘  │
│                   │ 4. Dados validados                 │
│  ┌────────────────▼─────────────────────────────────┐  │
│  │ Service: UserCustomer/ManagerService             │  │
│  │ • create(validated_data)                         │  │
│  │ • Inicia transação                               │  │
│  │ • Cria registro principal                        │  │
│  │ • Processa upload de arquivo (se houver)         │  │
│  │ • Registra log de criação                        │  │
│  │ • Commit transação                               │  │
│  └────────────────┬─────────────────────────────────┘  │
│                   │                                    │
│  ┌────────────────▼─────────────────────────────────┐  │
│  │ Model: UserCustomer/ResourceModel                │  │
│  │ • insert(data)                                   │  │
│  │ • DBGroup: 'customers'                           │  │
│  │ • created_at: TIMESTAMP                          │  │
│  └────────────────┬─────────────────────────────────┘  │
│                   │ 5. Registro criado (ID: 156)       │
│  ┌────────────────▼─────────────────────────────────┐  │
│  │ Service: UploadService                           │  │
│  │ • processUpload($file, $user_customer_id)        │  │
│  │ • Valida tipo/tamanho                            │  │
│  │ • Move para writable/uploads/                    │  │
│  │ • Cria registro em FileModel                     │  │
│  └────────────────┬─────────────────────────────────┘  │
│                   │                                    │
│  ┌────────────────▼─────────────────────────────────┐  │
│  │ LogRequestFilter: Registra saída                 │  │
│  │ • status: 201                                    │  │
│  │ • execution_time: 247ms                          │  │
│  └────────────────┬─────────────────────────────────┘  │
│                   │                                    │
│  ┌────────────────▼─────────────────────────────────┐  │
│  │ ApiResponse::success()                           │  │
│  │ {                                                │  │
│  │   "success": true,                               │  │
│  │   "data": {                                      │  │
│  │     "id": 156,                                   │  │
│  │     "name": "João Silva",                        │  │
│  │     "email": "joao@example.com",                 │  │
│  │     "created_at": "2025-12-15 14:30:00"          │  │
│  │   },                                             │  │
│  │   "message": "Cliente criado com sucesso"        │  │
│  │ }                                                │  │
│  └────────────────┬─────────────────────────────────┘  │
└───────────────────┼────────────────────────────────────┘
                    │ 6. Response (HTTP 201)
                    ▼
┌────────────────────────────────────────────────────────┐
│  React Component                                       │
│  • Recebe resposta                                     │
│  • Atualiza lista de clientes                          │
│  • Exibe HcMessage (Toast de sucesso)                  │
│  • Fecha HcModal                                       │
│  • Limpa formulário                                    │
└────────────────────────────────────────────────────────┘
```

### 2. Fluxo de Upload de Arquivo

```
┌────────────┐
│   React    │ 1. Usuário seleciona arquivo
│  (Input)   │    <input type="file" onChange={handleFileChange} />
└─────┬──────┘
      │ 2. Prepara FormData
      │    formData.append('file', file)
      │    formData.append('user_customer_id', 156)
      ▼
┌────────────────────────────────────────────────────────┐
│  userFileService.upload(formData)                      │
│  POST /api/v1/user-customer-file                       │
│  Headers: {                                            │
│    Authorization: Bearer eyJ...,                       │
│    Content-Type: multipart/form-data                   │
│  }                                                     │
└─────────────────────┬──────────────────────────────────┘
                      │
                      ▼
┌────────────────────────────────────────────────────────┐
│  CodeIgniter 4 API                                     │
│  ┌──────────────────────────────────────────────────┐  │
│  │ Controller: UserCustomerFile/ManagerController   │  │
│  │ • Método: store()                                │  │
│  │ • Valida request                                 │  │
│  └────────────────┬─────────────────────────────────┘  │
│                   │                                    │
│  ┌────────────────▼─────────────────────────────────┐  │
│  │ Service: UploadService                           │  │
│  │                                                  │  │
│  │ processUpload($file, $userId) {                  │  │
│  │   // 1. Validações                               │  │
│  │   if (!$this->validateFileType($file)) {         │  │
│  │     throw new ValidationException();             │  │
│  │   }                                              │  │
│  │                                                  │  │
│  │   if ($file->getSize() > MAX_SIZE) {             │  │
│  │     throw new FileSizeException();               │  │
│  │   }                                              │  │
│  │                                                  │  │
│  │   // 2. Gera nome único                          │  │
│  │   $filename = uniqid() . '_' . $file->getName(); │  │
│  │                                                  │  │
│  │   // 3. Move arquivo                             │  │
│  │   $path = WRITEPATH . 'uploads/' . $filename;    │  │
│  │   $file->move($path);                            │  │
│  │                                                  │  │
│  │   // 4. Registra no banco                        │  │
│  │   $fileModel->insert([                           │  │
│  │     'user_customer_id' => $userId,               │  │
│  │     'original_name' => $file->getName(),         │  │
│  │     'stored_name' => $filename,                  │  │
│  │     'mime_type' => $file->getMimeType(),         │  │
│  │     'size' => $file->getSize(),                  │  │
│  │     'path' => $path                              │  │
│  │   ]);                                            │  │
│  │                                                  │  │
│  │   return $filename;                              │  │
│  │ }                                                │  │
│  └────────────────┬─────────────────────────────────┘  │
│                   │                                    │
│  ┌────────────────▼─────────────────────────────────┐  │
│  │ Model: FileModel                                 │  │
│  │ • DBGroup: 'customers'                           │  │
│  │ • Tabela: user_customer_files                    │  │
│  └────────────────┬─────────────────────────────────┘  │
│                   │                                    │
│  ┌────────────────▼─────────────────────────────────┐  │
│  │ Response                                         │  │
│  │ {                                                │  │
│  │   "success": true,                               │  │
│  │   "data": {                                      │  │
│  │     "id": 89,                                    │  │
│  │     "filename": "documento.pdf",                 │  │
│  │     "size": "1.2 MB",                            │  │
│  │     "uploaded_at": "2025-12-15 14:35:00"         │  │
│  │   }                                              │  │
│  │ }                                                │  │
│  └──────────────────────────────────────────────────┘  │
└────────────────────────────────────────────────────────┘
```

---

## ✅ Code Review e Boas Práticas

### 1. Estrutura de Versionamento

O projeto implementa versionamento robusto em todas as camadas:

```
✓ Controllers: app/Controllers/API/v1/
✓ Requests:    app/Requests/v1/
✓ Services:    app/Services/v1/
✓ Models:      app/Models/v1/
✓ Rules:       app/Rules/v1/
✓ Filters:     app/Filters/v1/
✓ Routes:      app/Routes/API/v1/
```

**Benefícios:**
- Evolução da API sem quebrar clientes antigos
- Facilita testes A/B
- Permite migração gradual de features

### 2. Padrão de Nomenclatura

#### 2.1. Backend (CodeIgniter)

```php
// ✓ CORRETO - PascalCase para classes
UserManagement/ManagerController.php
UserCustomer/ResourceModel.php
Auth/AuthenticationService.php

// ✓ CORRETO - camelCase para métodos
public function getUserById(int $id)
public function createNewUser(array $data)

// ✓ CORRETO - snake_case para banco de dados
table: user_customers
column: created_at, deleted_at

// ✓ CORRETO - UPPERCASE para constantes
define('MAX_UPLOAD_SIZE', 5242880);
```

#### 2.2. Frontend (React/Flutter)

```javascript
// ✓ CORRETO - PascalCase para componentes React
const UserListPage = () => { ... }
const HcButton = ({ ... }) => { ... }

// ✓ CORRETO - camelCase para funções
const handleSubmit = () => { ... }
const loadUserData = async () => { ... }

// ✓ CORRETO - UPPER_SNAKE_CASE para constantes
const API_BASE_URL = process.env.REACT_APP_API_URL;
const MAX_RETRY_ATTEMPTS = 3;
```

```dart
// ✓ CORRETO - PascalCase para classes Dart
class LoginUseCase { ... }
class UserEntity { ... }

// ✓ CORRETO - camelCase para variáveis
final userId = 123;
final isAuthenticated = true;

// ✓ CORRETO - snake_case para arquivos
login_screen.dart
user_repository.dart
```

### 3. Separação de Responsabilidades

#### Antes do Code Review (❌)

```php
// ❌ RUIM - Controller com lógica de negócio
class UserController extends BaseController
{
    public function create()
    {
        // Validação no controller
        if (empty($this->request->getPost('email'))) {
            return $this->response->setJSON(['error' => 'Email obrigatório']);
        }
        
        // Lógica de negócio no controller
        $password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        
        // Acesso direto ao banco no controller
        $db = \Config\Database::connect();
        $query = "INSERT INTO users (email, password) VALUES (?, ?)";
        $db->query($query, [$email, $password]);
        
        return $this->response->setJSON(['success' => true]);
    }
}
```

#### Após Code Review (✓)

```php
// ✓ BOM - Controller limpo, apenas orquestração
class ManagerController extends BaseManagerController
{
    public function create()
    {
        // 1. Valida com Request dedicado
        $validated = $this->validate(StoreRequest::class);
        
        // 2. Delega lógica ao Service
        $user = $this->managerService->create($validated);
        
        // 3. Retorna resposta padronizada
        return ApiResponse::success($user, 'Usuário criado com sucesso', 201);
    }
}

// ✓ Validação em Request separado
class StoreRequest extends IncomingRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
        ];
    }
}

// ✓ Lógica de negócio em Service
class ManagerService extends BaseManagerService
{
    public function create(array $data): array
    {
        // Hash de senha
        $data['password'] = $this->passwordService->hash($data['password']);
        
        // Inicia transação
        $this->db->transStart();
        
        try {
            // Cria usuário
            $userId = $this->model->insert($data);
            
            // Registra log
            $this->logService->create([
                'action' => 'user_created',
                'user_id' => $userId
            ]);
            
            $this->db->transComplete();
            
            return $this->model->find($userId);
        } catch (\Exception $e) {
            $this->db->transRollback();
            throw $e;
        }
    }
}
```

### 4. Tratamento de Erros

```php
// ✓ Exceções customizadas
class NotFoundException extends \RuntimeException
{
    public function __construct($message = 'Recurso não encontrado')
    {
        parent::__construct($message, 404);
    }
}

class ValidationException extends \RuntimeException
{
    private array $errors;
    
    public function __construct(array $errors)
    {
        $this->errors = $errors;
        parent::__construct('Erro de validação', 422);
    }
}

// ✓ Uso em Services
public function findById(int $id)
{
    $user = $this->model->find($id);
    
    if (!$user) {
        throw new NotFoundException("Usuário #{$id} não encontrado");
    }
    
    return $user;
}

// ✓ Tratamento global em BaseController
protected function handleException(\Throwable $e)
{
    if ($e instanceof NotFoundException) {
        return ApiResponse::error($e->getMessage(), 404);
    }
    
    if ($e instanceof ValidationException) {
        return ApiResponse::error('Dados inválidos', 422, $e->getErrors());
    }
    
    // Log erro inesperado
    log_message('error', $e->getMessage());
    
    return ApiResponse::error('Erro interno do servidor', 500);
}
```

### 5. Arquivos de Backup e Iterações

```
# Arquivos encontrados no projeto:
*.bkp       # Backup antes de refatoração
*.ia        # Versões geradas/assistidas por IA
*.ia_       # Versões antigas com IA
*.md        # Documentação de decisões técnicas

Exemplo:
├── BaseResourceModel.bkp      # Versão anterior do model base
├── BaseResourceModel.ia       # Primeira tentativa com IA
└── BaseResourceModel.php      # Versão final revisada

# ✓ Boas práticas identificadas:
• Mantém histórico de mudanças importantes
• Documenta decisões técnicas em .md
• Facilita rollback se necessário
```

### 6. Padrões de Segurança

```php
// ✓ Sanitização de inputs
class StoreRequest extends IncomingRequest
{
    public function sanitize(): array
    {
        return [
            'name' => sanitize_string($this->getVar('name')),
            'email' => filter_var($this->getVar('email'), FILTER_SANITIZE_EMAIL),
            'cpf' => preg_replace('/[^0-9]/', '', $this->getVar('cpf')),
        ];
    }
}

// ✓ Prepared Statements (Query Builder)
$this->db->table('users')
    ->where('email', $email)  // Automático prepared statement
    ->get()
    ->getRowArray();

// ✓ CSRF Protection
public $csrf = [
    'csrf_enable' => true,
    'csrf_token_name' => 'csrf_token',
    'csrf_cookie_name' => 'csrf_cookie',
    'csrf_expire' => 7200,
];

// ✓ CORS configurado
class Cors extends BaseConfig
{
    public $allowedOrigins = [
        'https://app.governo.rj.gov.br',
        'https://app-dev.governo.rj.gov.br'
    ];
    
    public $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'];
    
    public $allowedHeaders = [
        'Content-Type',
        'Authorization',
        'X-Requested-With'
    ];
}
```

---

## 🔐 Segurança e Autenticação

### 1. Sistema JWT (Firebase JWT)

```php
// Localização: app/ThirdParty/Firebase/JWT/
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class TokenService
{
    private string $secretKey;
    private string $algorithm = 'HS256';
    private int $expirationTime = 3600; // 1 hora
    
    public function generate(array $payload): string
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + $this->expirationTime;
        
        $token = [
            'iss' => base_url(),                    // Issuer
            'aud' => base_url(),                    // Audience
            'iat' => $issuedAt,                     // Issued at
            'nbf' => $issuedAt,                     // Not before
            'exp' => $expirationTime,               // Expiration
            'data' => $payload                      // User data
        ];
        
        return JWT::encode($token, $this->secretKey, $this->algorithm);
    }
    
    public function validate(string $token): ?array
    {
        try {
            $decoded = JWT::decode($token, new Key($this->secretKey, $this->algorithm));
            return (array) $decoded->data;
        } catch (\Exception $e) {
            log_message('error', 'JWT Validation Error: ' . $e->getMessage());
            return null;
        }
    }
}
```

### 2. AuthFilter (Middleware)

```php
class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // 1. Extrai token do header
        $authHeader = $request->getHeaderLine('Authorization');
        
        if (!$authHeader || !preg_match('/Bearer\s+(.*)$/i', $authHeader, $matches)) {
            return Services::response()
                ->setJSON(['error' => 'Token não fornecido'])
                ->setStatusCode(401);
        }
        
        $token = $matches[1];
        
        // 2. Valida token
        $tokenService = new TokenService();
        $userData = $tokenService->validate($token);
        
        if (!$userData) {
            return Services::response()
                ->setJSON(['error' => 'Token inválido ou expirado'])
                ->setStatusCode(401);
        }
        
        // 3. Injeta dados do usuário autenticado
        CurrentUser::set($userData);
        
        // 4. Verifica permissões (se necessário)
        if ($arguments && !$this->checkPermissions($userData, $arguments)) {
            return Services::response()
                ->setJSON(['error' => 'Permissão negada'])
                ->setStatusCode(403);
        }
        
        return $request;
    }
    
    private function checkPermissions(array $user, array $requiredRoles): bool
    {
        return in_array($user['role'], $requiredRoles);
    }
}
```

### 3. Aplicação de Filtros nas Rotas

```php
// app/Routes/API/v1/UserManagement/api_routes.php
$routes->group('user-management', ['namespace' => 'App\Controllers\API\v1\UserManagement'], function($routes) {
    
    // Rota pública (sem filtro)
    $routes->post('login', 'AuthController::login');
    
    // Rotas protegidas (com AuthFilter)
    $routes->group('', ['filter' => 'auth'], function($routes) {
        $routes->get('/', 'ManagerController::index');
        $routes->post('/', 'ManagerController::create');
        $routes->put('(:num)', 'ManagerController::update/$1');
        $routes->delete('(:num)', 'ManagerController::delete/$1');
    });
    
    // Rotas apenas para admin (com permissão específica)
    $routes->group('', ['filter' => 'auth:admin'], function($routes) {
        $routes->get('logs', 'ManagerController::logs');
        $routes->post('bulk-delete', 'ManagerController::bulkDelete');
    });
});
```

### 4. Password Service

```php
class PasswordService
{
    public function hash(string $password): string
    {
        return password_hash($password, PASSWORD_ARGON2ID, [
            'memory_cost' => 65536,
            'time_cost' => 4,
            'threads' => 3
        ]);
    }
    
    public function verify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
    
    public function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_ARGON2ID);
    }
}
```

### 5. Rate Limiting (Middleware)

```php
// Proteção contra brute force
class RateLimitFilter implements FilterInterface
{
    private int $maxAttempts = 5;
    private int $decayMinutes = 1;
    
    public function before(RequestInterface $request, $arguments = null)
    {
        $key = $this->resolveRequestSignature($request);
        
        if ($this->tooManyAttempts($key)) {
            return Services::response()
                ->setJSON(['error' => 'Muitas tentativas. Tente novamente em 1 minuto.'])
                ->setStatusCode(429);
        }
        
        $this->incrementAttempts($key);
        
        return $request;
    }
    
    private function resolveRequestSignature(RequestInterface $request): string
    {
        return sha1(
            $request->getIPAddress() . '|' . $request->getUri()
        );
    }
}
```

---

## 📊 Conclusão

### Pontos Fortes da Arquitetura

1. **✓ Separação Clara de Responsabilidades**
   - Controllers orquestram, não processam
   - Services contêm lógica de negócio
   - Models apenas acessam dados
   - Requests validam e sanitizam

2. **✓ Versionamento Completo**
   - API v1 completamente isolada
   - Facilita evolução sem quebrar compatibilidade
   - Estrutura preparada para v2, v3...

3. **✓ Multi-Database Architecture**
   - Separação lógica: users, customers, logs
   - Escalabilidade horizontal facilitada
   - Backup e recuperação granular

4. **✓ Segurança Robusta**
   - JWT com Firebase
   - Filtros de autenticação e autorização
   - Sanitização em múltiplas camadas
   - Rate limiting
   - CORS configurado

5. **✓ Frontends Modernos**
   - Flutter: DDD + Clean Architecture
   - React: Component-based + Hooks
   - Comunicação via API REST padronizada

### Métricas do Projeto

```
┌─────────────────────────────────────────────────┐
│              ESTATÍSTICAS DO PROJETO            │
├─────────────────────────────────────────────────┤
│ Controllers:           7 módulos                │
│ Services:              12 services              │
│ Models:                8 models                 │
│ Requests:              15 validações            │
│ Rules:                 12 regras customizadas   │
│ Endpoints API:         ~45 rotas                │
│ Bancos de Dados:       3 databases              │
│ Frontends:             2 (Flutter + React)      │
│ Linguagens:            PHP, Dart, JavaScript    │
│ Frameworks:            CI4, Flutter, React      │
└─────────────────────────────────────────────────┘
```

### Diagrama Final de Integração

```
┌─────────────────────────────────────────────────────────┐
│                   USUÁRIO FINAL                         │
└───────────┬─────────────────────────────┬───────────────┘
            │                             │
    ┌───────▼─────────┐          ┌────────▼────────┐
    │  Flutter Web    │          │   React Web     │
    │  Flutter Mobile │          │                 │
    └───────┬─────────┘          └────────┬────────┘
            │                             │
            └──────────┬──────────────────┘
                       │
                ┌──────▼──────┐
                │  HTTPS/SSL  │
                │    CORS     │
                └──────┬──────┘
                       │
            ┌──────────▼──────────────┐
            │   CodeIgniter 4 API     │
            │   ┌─────────────────┐   │
            │   │   AuthFilter    │   │
            │   │ LogRequestFilter│   │
            │   └────────┬────────┘   │
            │            │            │
            │   ┌────────▼────────┐   │
            │   │  Controllers    │   │
            │   │   Requests      │   │
            │   │   Services      │   │
            │   │   Models        │   │
            │   └────────┬────────┘   │
            └────────────┼────────────┘
                         │
         ┌───────────────┼───────────────┐
         │               │               │
    ┌────▼────┐    ┌─────▼────┐     ┌────▼────┐
    │  MySQL  │    │  MySQL   │     │  MySQL  │
    │  Users  │    │Customers │     │  Logs   │
    └─────────┘    └──────────┘     └─────────┘
```

---

**Documento gerado em**: 15/12/2025  
**Versão da API**: v1  
**Mantido por**: Equipe de Desenvolvimento PRODERJ  
**Classificação**: Documentação Técnica Interna
 
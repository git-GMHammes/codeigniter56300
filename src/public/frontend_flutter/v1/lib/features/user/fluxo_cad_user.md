# 📱 Fluxo de Cadastro de Usuário - Flutter

## 📂 Estrutura do Módulo User

```
features/user/
├── presentation/       # 🎨 Interface do Usuário
├── application/        # 🎮 Gerenciamento de Estado
├── domain/             # 📋 Regras de Negócio
└── data/               # 💾 Acesso a Dados
```

---

## 🏗️ Arquitetura Clean Architecture

### 1️⃣ Presentation Layer (UI)

| Arquivo | Descrição |
|---------|-----------|
| `register_page.dart` | Tela principal de cadastro |
| `register_step1_card.dart` | Etapa 1: Credenciais |
| `register_step2_card.dart` | Etapa 2: Dados pessoais |
| `register_card.dart` | Widget principal consolidado |

**Responsabilidade:** Exibir formulários, capturar entrada do usuário e mostrar feedback visual.

---

### 2️⃣ Application Layer (Estado)

| Arquivo | Descrição |
|---------|-----------|
| `auth_controller.dart` | Controlador de autenticação (GetX/Provider) |

**Responsabilidade:** Gerenciar estado da aplicação, coordenar Use Cases e reagir a eventos da UI.

---

### 3️⃣ Domain Layer (Lógica de Negócio)

| Arquivo | Descrição |
|---------|-----------|
| `register_user.dart` | Use Case de registro de usuário |
| `auth_repository.dart` | Interface (contrato) do repositório |
| `user.dart` | Entidade de domínio User |

**Responsabilidade:** Conter regras de negócio puras, independentes de frameworks e bibliotecas externas.

---

### 4️⃣ Data Layer (Implementação)

| Arquivo | Descrição |
|---------|-----------|
| `auth_repository_impl.dart` | Implementação concreta do repositório |
| `user_model.dart` | Modelo de dados (DTO) |
| `auth_remote_ds.dart` | Data Source remoto (API) |

**Responsabilidade:** Implementar acesso a dados, seja via API, banco local ou cache.

---

## 🔄 Fluxo de Dados - Cadastro de Usuário

```mermaid
graph TB
    A[Usuário] -->|Preenche formulário|     B[🎨 register_page.dart]
    B -->|Dados validados|                  C[🎨 register_step1_card.dart]
    C -->|Próxima etapa|                    D[🎨 register_step2_card.dart]
    D -->|Submit|                           E[🎮 auth_controller.dart]
    E -->|Executa|                          F[📋 register_user.dart]
    F -->|Chama|                            G[📋 auth_repository.dart]
    G -->|Implementado por|                 H[💾 auth_repository_impl.dart]
    H -->|Converte para|                    I[💾 user_model.dart]
    I -->|Envia para|                       J[💾 auth_remote_ds.dart]
    J -->|HTTP POST|                        K[🌐 API Backend]
    K -->|Resposta|                         J
    J -->|Retorna|                          H
    H -->|Success/Failure|                  F
    F -->|Atualiza|                         E
    E -->|Notifica|                         B
    B -->|Feedback|                         A
```

---

## 📊 Sequência Detalhada do Cadastro

### **Fase 1: Captura de Dados (UI)**

```
┌─────────────────────────────────────────┐
│  1️⃣  Usuário acessa tela de cadastro   │
│     📄 register_page.dart              │
└─────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────┐
│  3️⃣  Preenche Step 1 - Credenciais     │
│     🔐 register_step1_card.dart        │
│     • Usuario                           │
│     • Senha                             │
│     • Confirmação de senha              │
└─────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────┐
│  2️⃣  Preenche Step 2 - Dados Pessoais  │
│     📝 register_step2_card.dart        │
│     • Nome completo                     │
│     • CPF                               │
│     • Data de nascimento                │
└─────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────┐
│  4️⃣  Validação de campos (local)       │
│     ✅ Validators.dart                 │
│     • Email válido?                     │
│     • Senha forte?                      │
│     • Senhas conferem?                  │
└─────────────────────────────────────────┘
```

---

### **Fase 2: Processamento (Controller)**

```
┌─────────────────────────────────────────┐
│  5️⃣  Controller recebe dados           │
│     🎮 auth_controller.dart            │
│     • setState(loading: true)           │
│     • Prepara dados para envio          │
└─────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────┐
│  6️⃣  Chama Use Case                    │
│     📋 register_user.dart (Use Case)   │
│     • Aplica regras de negócio          │
│     • Valida dados de domínio           │
└─────────────────────────────────────────┘
```

---

### **Fase 3: Persistência (Repository Pattern)**

```
┌──────────────────────────────────────────┐
│  7️⃣  Repositório abstrato               │
│     📋 auth_repository.dart (Interface) │
│     Future<Either<Failure, User>>        │
│     registerUser(UserEntity user)        │
└──────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────┐
│  8️⃣  Implementação concreta            │
│     💾 auth_repository_impl.dart       │
│     • Converte Entity → Model           │
│     • Delega para Data Source           │
└─────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────┐
│  9️⃣  Conversão de dados                │
│     💾 user_model.dart                 │
│     • toJson() para envio               │
│     • fromJson() para recebimento       │
└─────────────────────────────────────────┘
```

---

### **Fase 4: Comunicação com API**

```
┌─────────────────────────────────────────┐
│  🔟  Data Source Remoto                │
│     💾 auth_remote_ds.dart             │
│     • Usa DioClient                     │
│     • Endpoint: /api/auth/register      │
│     • Headers: Content-Type, etc        │
└─────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────┐
│  1️⃣1️⃣  Requisição HTTP                 │
│     🌐 API Backend                     │
│     POST /api/auth/register             │
│     {                                   │
│       "name": "João Silva",             │
│       "email": "joao@example.com",      │
│       "password": "******"              │
│     }                                   │
└─────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────┐
│  1️⃣2️⃣  Resposta da API                 │
│     ✅ Sucesso: 201 Created            │
│     {                                   │
│       "id": 123,                        │
│       "name": "João Silva",             │
│       "email": "joao@example.com",      │
│       "token": "eyJhbGc..."             │
│     }                                   │
│                                         │
│     ❌ Erro: 400 Bad Request           │
│     {                                   │
│       "error": "Email já cadastrado"    │
│     }                                   │
└─────────────────────────────────────────┘
```

---

### **Fase 5: Retorno e Feedback**

```
┌─────────────────────────────────────────┐
│  1️⃣3️⃣  Tratamento de resposta          │
│     💾 auth_remote_ds.dart             │
│     • Status 2xx → Success              │
│     • Status 4xx/5xx → Exception        │
└─────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────┐
│  1️⃣4️⃣  Repository processa             │
│     💾 auth_repository_impl.dart       │
│     • Converte Model → Entity           │
│     • Retorna Either<Failure, User>     │
└─────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────┐
│  1️⃣5️⃣  Use Case retorna resultado      │
│     📋 register_user.dart              │
│     • Success: User criado              │
│     • Failure: Mensagem de erro         │
└─────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────┐
│  1️⃣6️⃣  Controller atualiza estado      │
│     🎮 auth_controller.dart            │
│     • setState(loading: false)          │
│     • setState(user: userData)          │
│     • Navega para home ou mostra erro   │
└─────────────────────────────────────────┘
                ↓
┌─────────────────────────────────────────┐
│  1️⃣7️⃣  UI exibe feedback               │
│     🎨 register_page.dart               │
│     ✅ Sucesso: "Cadastro realizado!"   │
│     ❌ Erro: "Email já cadastrado"      │
└─────────────────────────────────────────┘
```

---

## 🎯 Componentes-Chave

### 📱 Widgets de Apresentação

```dart
// Tela principal que orquestra os steps
register_page.dart
  └── Scaffold + AppBar
      ├── register_step1_card.dart (Dados pessoais)
      └── register_step2_card.dart (Credenciais)
```

### 🎮 Controlador de Estado

```dart
// auth_controller.dart
class AuthController extends GetxController {
  final RegisterUser registerUserUseCase;
  
  Rx<AuthState> state = AuthState.initial().obs;
  
  Future<void> register(UserData data) async {
    state.value = AuthState.loading();
    
    final result = await registerUserUseCase(data);
    
    result.fold(
      (failure) => state.value = AuthState.error(failure.message),
      (user) => state.value = AuthState.success(user),
    );
  }
}
```

### 📋 Use Case

```dart
// register_user.dart
class RegisterUser {
  final AuthRepository repository;
  
  Future<Either<Failure, User>> call(UserEntity user) async {
    // Validações de domínio
    if (user.age < 18) {
      return Left(ValidationFailure('Usuário deve ser maior de idade'));
    }
    
    // Delega para o repository
    return await repository.registerUser(user);
  }
}
```

### 💾 Repository Implementation

```dart
// auth_repository_impl.dart
class AuthRepositoryImpl implements AuthRepository {
  final AuthRemoteDataSource remoteDataSource;
  
  @override
  Future<Either<Failure, User>> registerUser(UserEntity user) async {
    try {
      final userModel = UserModel.fromEntity(user);
      final result = await remoteDataSource.register(userModel);
      return Right(result.toEntity());
    } on ServerException catch (e) {
      return Left(ServerFailure(e.message));
    } on NetworkException {
      return Left(NetworkFailure('Sem conexão'));
    }
  }
}
```

### 🌐 Data Source

```dart
// auth_remote_ds.dart
class AuthRemoteDataSource {
  final DioClient dioClient;
  
  Future<UserModel> register(UserModel user) async {
    final response = await dioClient.post(
      ApiEndpoints.register,
      data: user.toJson(),
    );
    
    if (response.statusCode == 201) {
      return UserModel.fromJson(response.data);
    } else {
      throw ServerException(response.data['error']);
    }
  }
}
```

---

## 🔐 Camadas de Validação

### Nível 1: UI (Síncrona)
- ✅ Campos preenchidos?
- ✅ Formato de email válido?
- ✅ Senha tem mínimo de caracteres?
- ✅ Senhas conferem?

### Nível 2: Domain (Negócio)
- ✅ Usuário maior de idade?
- ✅ CPF válido?
- ✅ Senha forte o suficiente?

### Nível 3: Backend (Persistência)
- ✅ Email único no banco?
- ✅ CPF não cadastrado?
- ✅ Dados íntegros?

---

## 🎨 Fluxo Visual Simplificado

```
┌──────────┐      ┌──────────┐      ┌──────────┐      ┌──────────┐      ┌──────────┐
│    UI    │ ───> │Controller│ ───> │ UseCase  │ ───> │Repository│ ───> │   API    │
│  (View)  │ <─── │ (Estado) │ <─── │(Negócio) │ <─── │  (Data)  │ <─── │(Backend) │
└──────────┘      └──────────┘      └──────────┘      └──────────┘      └──────────┘
   Widgets         GetX/Bloc      Regras Puras      Acesso Dados      REST/GraphQL
```

---

## 📦 Dependências

### Core (Infraestrutura)
- `dio_client.dart` - Cliente HTTP
- `auth_interceptor.dart` - Adiciona tokens
- `logging_interceptor.dart` - Logs de requisições
- `api_endpoints.dart` - URLs centralizadas

### Utils
- `validators.dart` - Validações de formulário
- `app_exception.dart` - Exceções customizadas
- `failure.dart` - Tipos de falhas

### Widgets Compartilhados
- `app_dialog.dart` - Diálogos padrão
- `app_loader.dart` - Loading indicators
- `app_snackbar.dart` - Mensagens de feedback

---

## ✨ Benefícios da Arquitetura

### ✅ Separação de Responsabilidades
Cada camada tem uma função bem definida e não depende de detalhes de implementação das outras.

### ✅ Testabilidade
Use Cases e Repositories podem ser testados isoladamente com mocks.

### ✅ Manutenibilidade
Mudanças em uma camada não afetam as outras (baixo acoplamento).

### ✅ Escalabilidade
Fácil adicionar novos Use Cases, Data Sources ou implementações.

### ✅ Independência de Frameworks
A lógica de negócio não depende do Flutter, GetX ou qualquer biblioteca externa.

---

## 🚀 Exemplo de Uso Completo

```dart
// 1. Usuário preenche o formulário
final userData = UserData(
  name: 'João Silva',
  email: 'joao@example.com',
  password: 'Senha@123',
);

// 2. Controller dispara o cadastro
authController.register(userData);

// 3. UI reage ao estado
Obx(() {
  if (authController.state.value.isLoading) {
    return CircularProgressIndicator();
  }
  
  if (authController.state.value.isError) {
    return Text(authController.state.value.errorMessage);
  }
  
  if (authController.state.value.isSuccess) {
    // Navega para home
    Get.offAllNamed('/home');
  }
  
  return RegisterForm();
});
```

---

## 📝 Observações Importantes

1. **Cadastro Multi-Step**: Dividido em 2 etapas para melhor UX
2. **Validação Progressive**: Valida em cada etapa antes de prosseguir
3. **Either Pattern**: Usa `Either<Failure, Success>` para tratamento de erros funcional
4. **Immutability**: Entities e Models são imutáveis (final fields)
5. **Dependency Injection**: Todas as dependências são injetadas (GetX, Provider, etc)

---

## 🎓 Conclusão

Este fluxo implementa **Clean Architecture** de forma completa, separando:

- **Presentation** → O que o usuário vê
- **Application** → Como o estado é gerenciado
- **Domain** → O que o sistema faz (regras de negócio)
- **Data** → Como os dados são obtidos/persistidos

O resultado é um código **testável**, **manutenível** e **escalável**! 🚀

---

**Documentação gerada para:** `features/user` - Flutter App
**Arquitetura:** Clean Architecture
**Padrões:** Repository, Use Case, SOLID
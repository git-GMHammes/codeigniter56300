# Fluxo de Estrutura de Autenticação

Este documento descreve o fluxo para o desenvolvimento e a execução da autenticação (**Auth**) no módulo `/user`. A estrutura está organizada para facilitar o desenvolvimento escalável e reutilizável.

---

## Ordem de Desenvolvimento e Execução

O fluxo de autenticação percorre, do início ao fim, as seguintes etapas e arquivos:

### 1. **Model: auth_user_model.dart**

**Caminho:** `frontend_flutter/v1/lib/features/user/data/models/auth_user_model.dart`

- Representa o modelo dos dados do usuário e o token de autenticação.
- Métodos chave: serialização e desserialização (`toJson` e `fromJson`).

### 2. **Datasource: auth_remote_ds.dart**

**Caminho:** `frontend_flutter/v1/lib/features/user/data/datasources/auth_remote_ds.dart`

- Gerencia a comunicação com a API.
- Método principal: **loginUser(String username, String password)**, que envia as credenciais do usuário para obtenção do token.

### 3. **Repository Abstração: auth_repository.dart**

**Caminho:** `frontend_flutter/v1/lib/features/user/domain/repositories/auth_repository.dart`

- Interface responsável por definir os métodos a serem implementados para autenticação, como **loginUser**.

### 4. **Repository Implementação: auth_repository_impl.dart**

**Caminho:** `frontend_flutter/v1/lib/features/user/data/repositories/auth_repository_impl.dart`

- Implementa a interface do repositório de autenticação.
- Conecta o **Datasource** aos **Usecases**, mantendo a lógica desacoplada.

### 5. **Usecase: login_user.dart**

**Caminho:** `frontend_flutter/v1/lib/features/user/domain/usecases/login_user.dart`

- Encapsula a lógica de autenticação.
- Método principal: executa o login chamando o repositório e retornando resultados ou erros ao controlador.

### 6. **Controller: auth_controller.dart**

**Caminho:** `frontend_flutter/v1/lib/features/user/application/auth_controller.dart`

- Gerencia o estado da autenticação no aplicativo Flutter.
- Responde aos eventos da interface de usuário (UI) e comunica-se com os casos de uso (Usecases).

### 7. **Tela de Login: login_page.dart**

**Caminho:** `frontend_flutter/v1/lib/features/user/presentation/pages/login_page.dart`

- Interface para o usuário entrar com username e senha.
- Aciona eventos do controlador para realizar o processo de autenticação.

### 8. **Token Validator (Opcional): auth_validator.dart**

**Caminho:** `frontend_flutter/v1/lib/features/user/utils/auth_validator.dart`

- Classe utilitária para verificar o estado do token armazenado.
- Pode validar a existência e validade do token, garantindo que uma sessão esteja ativa.

---

## Resumo

- A autenticação é gerenciada pelo fluxo `Model -> Datasource -> Repository -> Usecase -> Controller -> UI`.
- O token obtido com sucesso permite acessar as APIs com segurança e validar sessões em outras partes do sistema.
- A estrutura proposta prioriza o desacoplamento, permitindo fácil extensão e reutilização em outros projetos.

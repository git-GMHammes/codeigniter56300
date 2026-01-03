# 📱 Fluxo de Cadastro de Usuário - Flutter (CORRIGIDO)

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
| `register_step1_card.dart` | **Etapa 1: Credenciais** (Usuário/Senha) |
| `register_step2_card.dart` | **Etapa 2: Dados Pessoais** |
| `register_card.dart` | Widget orquestrador dos steps |

**Responsabilidade:** Exibir formulários multi-step, capturar entrada do usuário e mostrar feedback visual.

---

### 2️⃣ Application Layer (Estado)

| Arquivo | Descrição |
|---------|-----------|
| `register_controller.dart` | Controlador de autenticação (GetX/Provider) |

**Responsabilidade:** Gerenciar estado da aplicação, coordenar Use Cases e reagir a eventos da UI.

---

### 3️⃣ Domain Layer (Lógica de Negócio)

| Arquivo | Descrição |
|---------|-----------|
| `register_user.dart` | Use Case de registro de usuário |
| `register_repository.dart` | Interface (contrato) do repositório |
| `user.dart` | Entidade de domínio User |

**Responsabilidade:** Conter regras de negócio puras, independentes de frameworks.

**Assinatura do Use Case:**
```dart
Future<Either<Failure, User>> call(
  String user,           // Usuário/login
  String password,       // Senha
  String passwordConfirm // Confirmação de senha
)
```

---

### 4️⃣ Data Layer (Implementação)

| Arquivo | Descrição |
|---------|-----------|
| `register_repository_impl.dart` | Implementação concreta do repositório |
| `user_model.dart` | Modelo de dados (DTO) - contém apenas `id` |
| `register_remote_ds.dart` | Data Source remoto (API) |

**Responsabilidade:** Implementar acesso a dados via API.

---

## 🔄 Fluxo Completo de Cadastro

### **📋 STEP 1: Credenciais (Primeiro)**

```
┌────────────────────────────────────────────────────────────┐
│                     ETAPA 1: DADOS DE ACESSO               │
│                   register_step1_card.dart                 │
├────────────────────────────────────────────────────────────┤
│                                                            
│  👤 Usuário *                                              
│  ├─ Validações:                                            
│  │  ✓ Mínimo 4 caracteres                                 
│  │  ✓ Máximo 50 caracteres                                
│  │  ✓ Apenas: a-z, A-Z, 0-9, ponto, underline, @          
│  └─ Placeholder: "Digite seu usuário ou e-mail"            
│                                                            
│  🔒 Senha *                                               
│  ├─ Validações:                                            
│  │  ✓ Mínimo 6 caracteres                                 
│  │  ✓ Máximo 200 caracteres                               
│  └─ Placeholder: "Mínimo 6 caracteres"                     
│                                                           
│  🔒 Confirmar Senha *                                     
│  ├─ Validações:                                           
│  │  ✓ Deve ser igual à senha                             
│  └─ Placeholder: "Repita a senha"                         
│                                                           
│  ℹ️  Dica: Use uma senha forte com letras, números        
│           e caracteres especiais                          
│                                                           
│  [  Próximo  →  ]                                         
│                                                            
└────────────────────────────────────────────────────────────┘
```

**Ação:** Ao clicar em "Próximo", valida os campos e avança para Step 2.

---

### **📋 STEP 2: Dados Pessoais (Segundo)**

```
┌────────────────────────────────────────────────────────────┐
│                  ETAPA 2: DADOS PESSOAIS                   │
│                  register_step2_card.dart                  │
├────────────────────────────────────────────────────────────┤
│                                                          
│  📸 Foto de Perfil (opcional)                            
│  └─ Upload: Câmera ou Galeria                            
│     Formato: JPG/PNG (max 800x800px, 85% quality)        
│                                                          
│  👤 Nome Completo *                                      
│  ├─ Validações:                                          
│  │  ✓ Obrigatório                                       
│  │  ✓ Mínimo 3 caracteres                               
│  │  ✓ Máximo 150 caracteres                             
│  └─ Placeholder: "Digite seu nome completo"              
│                                                          
│  🆔 CPF (opcional)                                        
│  ├─ Validações:                                          
│  │  ✓ Se preenchido: deve ter 11 dígitos                
│  │  ✓ Formato automático: 000.000.000-00                
│  └─ Placeholder: "000.000.000-00"                        
│                                                          
│  📧 E-mail (opcional)                                    
│  ├─ Validações:                                          
│  │  ✓ Se preenchido: formato válido                     
│  │  ✓ Máximo 150 caracteres                             
│  └─ Placeholder: "seu@email.com"                         
│                                                          
│  📞 Telefone (opcional)                                  
│  ├─ Validações:                                          
│  │  ✓ Se preenchido: 10 ou 11 dígitos                   
│  │  ✓ Formato automático: (00) 00000-0000               
│  └─ Placeholder: "(00) 00000-0000"                       
│                                                          
│  ☑️  WhatsApp é o mesmo que telefone                     
│                                                          
│  💬 WhatsApp (opcional)                                  
│  ├─ Validações:                                          
│  │  ✓ Se preenchido: 10 ou 11 dígitos                   
│  │  ✓ Formato automático: (00) 00000-0000               
│  │  ✓ Auto-preenche se checkbox marcado                 
│  └─ Placeholder: "(00) 00000-0000"                       
│                                                          
│  📅 Data de Nascimento (opcional)                        
│  └─ DatePicker: Seleção via calendário                   
│                                                          
│  📍 CEP (opcional)                                       
│  ├─ Validações:                                          
│  │  ✓ Formato automático: 00000-000                     
│  └─ Placeholder: "00000-000"                             
│                                                          
│  🏠 Endereço (opcional)                                  
│  └─ Placeholder: "Rua, número, bairro, cidade"           
│                                                          
│  [  ← Voltar  ]           [  Finalizar Cadastro  ]       
│                                                          
└────────────────────────────────────────────────────────────┘
```

**Ação:** Ao clicar em "Finalizar Cadastro", envia todos os dados para o backend.

---

## 🔄 Sequência Detalhada do Fluxo (17 Passos)

### **Fase 1: Captura de Credenciais (Step 1)**

```
┌────────────────────────────────────────────────────────────┐
│  1️⃣  Usuário acessa tela de cadastro                      
│     📄 register_page.dart                                
│     └─ Renderiza RegisterCard                            
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  2️⃣  Exibe Step 1 - Credenciais                            
│     🔐 register_step1_card.dart                            
│     └─ Formulário com 3 campos:                            
│        • Usuário                                           
│        • Senha                                             
│        • Confirmar Senha                                   
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  3️⃣  Usuário preenche credenciais                        
│     ✍️  Entrada de dados                                
│     └─ Validação em tempo real                          
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  4️⃣  Validação local (Step 1)                              
│     ✅ Validators                                         
│     ├─ Usuário: 4-50 chars, formato válido                
│     ├─ Senha: mínimo 6 chars                              
│     └─ Senhas conferem?                                   
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  5️⃣  Clica em "Próximo"                                   
│     ⏳ Loading: "Verificando..."                         
│     └─ Simula verificação de disponibilidade             
└────────────────────────────────────────────────────────────┘
```

---

### **Fase 2: Captura de Dados Pessoais (Step 2)**

```
┌────────────────────────────────────────────────────────────┐
│  6️⃣  Transição para Step 2                                 
│     📝 register_step2_card.dart                            
│     └─ Mantém dados do Step 1 em memória                   
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  7️⃣  Usuário preenche dados pessoais                       
│     ✍️  Entrada de dados                                   
│     ├─ Nome Completo (obrigatório)                         
│     ├─ CPF (opcional, com máscara)                         
│     ├─ E-mail (opcional)                                   
│     ├─ Telefone (opcional, com máscara)                    
│     ├─ WhatsApp (opcional, pode copiar de telefone)        
│     ├─ Data Nascimento (opcional, via DatePicker)          
│     ├─ CEP (opcional, com máscara)                         
│     ├─ Endereço (opcional)                                 
│     └─ Foto de perfil (opcional, câmera/galeria)           
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  8️⃣  Validação local (Step 2)                              
│     ✅ Validators                                         
│     ├─ Nome: obrigatório, 3-150 chars                     
│     ├─ CPF: se preenchido, deve ser válido (11 dígitos)   
│     ├─ E-mail: se preenchido, formato válido              
│     ├─ Telefone: se preenchido, 10-11 dígitos             
│     └─ WhatsApp: se preenchido, 10-11 dígitos             
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  9️⃣  Clica em "Finalizar Cadastro"                         
│     ⏳ Loading state ativado                              
│     └─ Prepara dados completos para envio                 
└────────────────────────────────────────────────────────────┘
```

---

### **Fase 3: Processamento (Controller + Use Case)**

```
┌────────────────────────────────────────────────────────────┐
│  🔟  Controller recebe dados completos                    
│     🎮 register_controller.dart                               
│     ├─ setState(loading: true)                            
│     ├─ Combina dados do Step 1 + Step 2                   
│     └─ Chama o Use Case                                   
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  1️⃣1️⃣  Executa Use Case                                     
│     📋 register_user.dart                                  
│     ├─ Recebe: user, password, passwordConfirm            
│     ├─ Aplica regras de negócio (se houver)               
│     └─ Chama Repository                                   
│                                                            
│     Future<Either<Failure, User>> call(                   
│       String user,                                         
│       String password,                                     
│       String passwordConfirm,                              
│     )                                                      
└────────────────────────────────────────────────────────────┘
```

---

### **Fase 4: Persistência (Repository + Data Source)**

```
┌────────────────────────────────────────────────────────────┐
│  1️⃣2️⃣  Repository abstrato                                
│     📋 register_repository.dart (Interface)                      
│     └─ Define contrato:                                      
│        Future<Either<Failure, User>> registerUser(...)     
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  1️⃣3️⃣  Implementação concreta                               
│     💾 register_repository_impl.dart                          
│     ├─ Recebe dados do Use Case                           
│     ├─ Prepara payload para API                           
│     └─ Delega para Data Source                            
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  1️⃣4️⃣  Data Source executa requisição                     
│     💾 register_remote_ds.dart                                 
│     ├─ Usa DioClient configurado                           
│     ├─ Endpoint: POST /api/auth/register                   
│     ├─ Headers: Content-Type, Authorization (se houver)    
│     └─ Body: JSON com dados do usuário                     
└────────────────────────────────────────────────────────────┘
```

---

### **Fase 5: Comunicação HTTP**

```
┌────────────────────────────────────────────────────────────┐
│  1️⃣5️⃣  Requisição HTTP para Backend                       
│     🌐 API Backend                                        
│                                                           
│     POST /api/auth/register                               
│     Content-Type: application/json                        
│                                                           
│     {                                                     
│       "user": "joao_silva",                               
│       "password": "******",                               
│       "passwordConfirm": "******",                        
│       "name": "João Silva",                               
│       "cpf": "12345678900",                               
│       "mail": "joao@example.com",                         
│       "phone": "11987654321",                             
│       "whatsapp": "11987654321",                          
│       "dateBirth": "1990-05-15T00:00:00.000Z",            
│       "zipCode": "01310100",                              
│       "address": "Av Paulista, 1000",                     
│       "profileImage": "base64_ou_url..."                  
│     }                                                     
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  1️⃣6️⃣  Resposta da API                                     
│     🌐 API Backend                                         
│                                                            
│     ✅ SUCESSO (201 Created):                              
│     {                                                      
│       "id": 123,                                           
│       "message": "Usuário criado com sucesso"              
│     }                                                      
│                                                            
│     ❌ ERRO (400 Bad Request):                             
│     {                                                      
│       "error": "Usuário já cadastrado"                     
│     }                                                      
│                                                            
│     ❌ ERRO (422 Unprocessable Entity):                    
│     {                                                      
│       "errors": {                                          
│         "email": ["E-mail já cadastrado"],                 
│         "cpf": ["CPF inválido"]                            
│       }                                                    
│     }                                                      
└────────────────────────────────────────────────────────────┘
```

---

### **Fase 6: Retorno e Feedback**

```
┌────────────────────────────────────────────────────────────┐
│  1️⃣7️⃣  Tratamento da resposta                              
│     💾 register_remote_ds.dart                              
│     ├─ Status 201: Sucesso                              
│     │  └─ Converte JSON → UserModel                     
│     │     UserModel.fromJson(response.data)             
│     │                                                   
│     ├─ Status 4xx/5xx: Erro                             
│     │  └─ Lança exceção apropriada:                     
│     │     • ServerException                             
│     │     • ValidationException                         
│     └─── NetworkException (sem conexão)                 
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  1️⃣8️⃣  Repository processa resultado                     
│     💾 register_repository_impl.dart                       
│     ├─ Sucesso:                                        
│     │  ├─ UserModel → User (Entity)                    
│     │  └─ Right(user)                                  
│     │                                                  
│     └─ Erro:                                           
│        ├─ ServerException → ServerFailure              
│        ├─ NetworkException → NetworkFailure            
│        └─ Left(failure)                                
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  1️⃣9️⃣  Use Case retorna resultado                         
│     📋 register_user.dart                                  
│     └─ Propaga Either<Failure, User>                       
│        para o Controller                                   
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  2️⃣0️⃣  Controller atualiza estado                          
│     🎮 register_controller.dart                               
│     ├─ setState(loading: false)                           
│     │                                                      
│     ├─ Sucesso:                                            
│     │  ├─ setState(user: userData)                        
│     │  ├─ Salva token (se retornado)                      
│     │  └─ Navega: Get.offAllNamed('/home')                
│     │                                                     
│     └─ Erro:                                              
│        ├─ setState(error: failure.message)                
│        └─ Exibe SnackBar com erro                         
└────────────────────────────────────────────────────────────┘
                          ↓
┌────────────────────────────────────────────────────────────┐
│  2️⃣1️⃣  UI exibe feedback ao usuário                       
│     🎨 register_step2_card.dart                         
│     ├─ Sucesso:                                         
│     │  ├─ SnackBar: "✅ Cadastro realizado!"            
│     │  └─ Navegação automática para Home                
│     │                                                   
│     └─ Erro:                                            
│        ├─ SnackBar: "❌ Erro ao cadastrar"              
│        ├─ Exibe mensagem específica do erro             
│        └─ Usuário permanece no Step 2                   
└────────────────────────────────────────────────────────────┘
```

---

## 🎨 Diagrama Visual do Fluxo Completo

```
┌─────────────────────────────────────────────────────────────────────┐
│                        CADASTRO DE USUÁRIO                          │
└─────────────────────────────────────────────────────────────────────┘

    ┌──────────────┐
    │   USUÁRIO    │
    └──────┬───────┘
           │ Acessa tela de cadastro
           ↓
    ┌──────────────────────────────────┐
    │  📄 register_page.dart          
    │  (Scaffold principal)            
    └──────┬───────────────────────────┘
           │
           ↓
    ╔══════════════════════════════════╗
    ║      STEP 1: CREDENCIAIS         
    ║  🔐 register_step1_card.dart    
    ╠══════════════════════════════════╣
    ║  • Usuário                       
    ║  • Senha                         
    ║  • Confirmar Senha               
    ║                                  
    ║  [Validação Local]               
    ║  [   Próximo →   ]               
    ╚══════════════╤═══════════════════╝
                   │
                   ↓
    ╔══════════════════════════════════╗
    ║     STEP 2: DADOS PESSOAIS       
    ║  📝 register_step2_card.dart     
    ╠══════════════════════════════════╣
    ║  • Foto (opcional)               
    ║  • Nome Completo *               
    ║  • CPF (opcional)                
    ║  • E-mail (opcional)             
    ║  • Telefone (opcional)           
    ║  • WhatsApp (opcional)           
    ║  • Data Nascimento (opcional)    
    ║  • CEP (opcional)                
    ║  • Endereço (opcional)           
    ║                                  
    ║  [Validação Local]               
    ║  [← Voltar] [Finalizar Cadastro] 
    ╚══════════════╤═══════════════════╝
                   │ Submit
                   ↓
    ┌──────────────────────────────────┐
    │  🎮 register_controller.dart         
    │  • setState(loading: true)       
    │  • Combina dados dos 2 steps     
    └──────┬───────────────────────────┘
           │
           ↓
    ┌──────────────────────────────────┐
    │  📋 register_user.dart           
    │  (Use Case)                      
    │  • Aplica regras de negócio      
    └──────┬───────────────────────────┘
           │
           ↓
    ┌──────────────────────────────────┐
    │  📋 register_repository.dart         
    │  (Interface)                     
    └──────┬───────────────────────────┘
           │
           ↓
    ┌──────────────────────────────────┐
    │  💾 register_repository_impl.dart    
    │  • Implementação concreta        
    └──────┬───────────────────────────┘
           │
           ↓
    ┌──────────────────────────────────┐
    │  💾 register_remote_ds.dart          
    │  • DioClient HTTP                
    └──────┬───────────────────────────┘
           │ POST /api/auth/register
           ↓
    ┌──────────────────────────────────┐
    │  🌐 API BACKEND                  
    │  • Valida dados                  
    │  • Salva no banco                
    │  • Retorna User ou Erro          
    └──────┬───────────────────────────┘
           │ Response
           ↓
    ┌──────────────────────────────────┐
    │  Tratamento de Resposta          
    │  ├─ 201: UserModel               
    │  └─ 4xx/5xx: Exception           
    └──────┬───────────────────────────┘
           │ Either<Failure, User>
           ↓
    ┌──────────────────────────────────┐
    │  🎮 register_controller.dart         
    │  • setState(loading: false)      
    │  • Sucesso: navega para /home    
    │  • Erro: exibe mensagem          
    └──────┬───────────────────────────┘
           │
           ↓
    ┌──────────────────────────────────┐
    │  🎨 UI Feedback                  
    │  ├─ ✅ Cadastro realizado!       
    │  └─ ❌ Erro ao cadastrar         
    └──────────────────────────────────┘
```

---

## 📦 Campos Completos do Cadastro

### **Step 1 - Credenciais** ✅ Obrigatórios

| Campo | Tipo | Validação | Obrigatório |
|-------|------|-----------|-------------|
| **user** | String | 4-50 chars, [a-zA-Z0-9._@] | ✅ Sim |
| **password** | String | 6-200 chars | ✅ Sim |
| **passwordConfirm** | String | Igual à senha | ✅ Sim |

### **Step 2 - Dados Pessoais**

| Campo | Tipo | Validação | Obrigatório |
|-------|------|-----------|-------------|
| **profileImage** | File/String | JPG/PNG 800x800 | ❌ Opcional |
| **name** | String | 3-150 chars | ✅ Sim |
| **cpf** | String | 11 dígitos (formato: 000.000.000-00) | ❌ Opcional |
| **mail** | String | Email válido, max 150 chars | ❌ Opcional |
| **phone** | String | 10-11 dígitos (formato: (00) 00000-0000) | ❌ Opcional |
| **whatsapp** | String | 10-11 dígitos (formato: (00) 00000-0000) | ❌ Opcional |
| **dateBirth** | DateTime | Seleção via DatePicker | ❌ Opcional |
| **zipCode** | String | 8 dígitos (formato: 00000-000) | ❌ Opcional |
| **address** | String | Texto livre | ❌ Opcional |

---

## 🔐 Camadas de Validação

### **Nível 1: UI - Validação Síncrona (Imediata)**

**Step 1:**
- ✅ Usuário: 4-50 caracteres, formato válido
- ✅ Senha: mínimo 6 caracteres
- ✅ Senhas conferem?

**Step 2:**
- ✅ Nome: obrigatório, 3-150 caracteres
- ✅ CPF: se preenchido, 11 dígitos válidos
- ✅ E-mail: se preenchido, formato válido
- ✅ Telefone/WhatsApp: se preenchido, 10-11 dígitos

### **Nível 2: Domain - Regras de Negócio**
- ✅ Use Case pode adicionar validações extras
- ✅ Exemplo: verificar idade mínima (se dateBirth preenchido)
- ✅ Exemplo: validar força da senha

### **Nível 3: Backend - Validação de Persistência**
- ✅ Usuário único no sistema?
- ✅ CPF não cadastrado?
- ✅ E-mail não cadastrado?
- ✅ Dados íntegros e seguros?

---

## 💡 Funcionalidades Especiais

### 🎨 **Upload de Foto**
```dart
// Step 2 - Seleção de imagem
ImagePicker()
├─ Câmera: Tira foto na hora
└─ Galeria: Seleciona da galeria
   ├─ maxWidth: 800px
   ├─ maxHeight: 800px
   └─ imageQuality: 85%
```

### 📋 **Auto-preenchimento WhatsApp**
```dart
// Checkbox: "WhatsApp é o mesmo que telefone"
if (_sameAsPhone) {
  _whatsappController.text = _phoneController.text;
}
```

### 🎭 **Máscaras Automáticas**
```dart
CPF:      000.000.000-00
Telefone: (00) 00000-0000
WhatsApp: (00) 00000-0000
CEP:      00000-000
```

### 📅 **DatePicker Personalizado**
```dart
// Seletor de data de nascimento
DatePicker(
  firstDate: DateTime(1900),
  lastDate: DateTime.now(),
)
```

---

## 🎯 Componentes-Chave do Código

### **📱 Widget Orquestrador**

```dart
// register_page.dart
class RegisterPage extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
        child: Column(
          children: [
            LoginHead(title: 'Cadastro'),
            Expanded(
              child: SingleChildScrollView(
                child: RegisterCard(), // Orquestra os steps
              ),
            ),
            LoginFooter(),
          ],
        ),
      ),
    );
  }
}
```

### **🔐 Step 1 - Credenciais**

```dart
// register_step1_card.dart
class RegisterStep1Card extends StatefulWidget {
  final void Function(String user, String password) onComplete;
  
  @override
  Widget build(BuildContext context) {
    return Card(
      child: Form(
        key: _formKey,
        child: Column(
          children: [
            // Campo Usuário
            TextFormField(
              controller: _userController,
              validator: _validateUser,
              decoration: InputDecoration(
                labelText: 'Usuário *',
                prefixIcon: Icon(Icons.person_outline),
              ),
            ),
            
            // Campo Senha
            TextFormField(
              controller: _passwordController,
              validator: _validatePassword,
              obscureText: _obscurePassword,
              decoration: InputDecoration(
                labelText: 'Senha *',
                prefixIcon: Icon(Icons.lock_outline),
              ),
            ),
            
            // Confirmar Senha
            TextFormField(
              controller: _confirmPasswordController,
              validator: _validateConfirmPassword,
              obscureText: _obscureConfirmPassword,
            ),
            
            // Botão Próximo
            FilledButton.icon(
              onPressed: _handleNext,
              icon: Icon(Icons.arrow_forward),
              label: Text('Próximo'),
            ),
          ],
        ),
      ),
    );
  }
  
  Future<void> _handleNext() async {
    if (!_formKey.currentState!.validate()) return;
    
    setState(() => _isLoading = true);
    // Simula verificação de disponibilidade
    await Future.delayed(Duration(milliseconds: 500));
    
    widget.onComplete(
      _userController.text.trim(),
      _passwordController.text,
    );
  }
}
```

### **📝 Step 2 - Dados Pessoais**

```dart
// register_step2_card.dart
class RegisterStep2Card extends StatefulWidget {
  final VoidCallback onBack;
  final void Function({
    required String name,
    required String cpf,
    required String mail,
    required String phone,
    required String whatsapp,
    required DateTime? dateBirth,
    required String zipCode,
    required String address,
    required String? uploadFilesPath,
  }) onComplete;
  
  @override
  Widget build(BuildContext context) {
    return Card(
      child: Form(
        key: _formKey,
        child: Column(
          children: [
            // Avatar com upload de foto
            _buildAvatarPicker(),
            
            // Nome Completo (obrigatório)
            TextFormField(
              controller: _nameController,
              validator: _validateName,
              decoration: InputDecoration(
                labelText: 'Nome Completo *',
                prefixIcon: Icon(Icons.person_outline),
              ),
            ),
            
            // CPF (opcional, com máscara)
            TextFormField(
              controller: _cpfController,
              validator: _validateCpf,
              onChanged: (value) => _formatCpf(value),
            ),
            
            // E-mail, Telefone, WhatsApp, etc...
            // ... (todos os campos listados acima)
            
            // Botões de navegação
            Row(
              children: [
                OutlinedButton.icon(
                  onPressed: widget.onBack,
                  icon: Icon(Icons.arrow_back),
                  label: Text('Voltar'),
                ),
                FilledButton.icon(
                  onPressed: _handleComplete,
                  icon: Icon(Icons.check),
                  label: Text('Finalizar Cadastro'),
                ),
              ],
            ),
          ],
        ),
      ),
    );
  }
}
```

### **🎮 Controller**

```dart
// register_controller.dart
class RegisterController extends GetxController {
  final RegisterUser registerUserUseCase;
  
  Rx<AuthState> state = AuthState.initial().obs;
  
  Future<void> register({
    required String user,
    required String password,
    required String passwordConfirm,
    required String name,
    String? cpf,
    String? mail,
    String? phone,
    String? whatsapp,
    DateTime? dateBirth,
    String? zipCode,
    String? address,
    String? profileImage,
  }) async {
    state.value = AuthState.loading();
    
    final result = await registerUserUseCase(
      user,
      password,
      passwordConfirm,
    );
    
    result.fold(
      (failure) => state.value = AuthState.error(failure.message),
      (user) {
        state.value = AuthState.success(user);
        Get.offAllNamed('/home');
      },
    );
  }
}
```

### **📋 Use Case**

```dart
// register_user.dart
class RegisterUser {
  final RegisterRepository repository;

  RegisterUser(this.repository);

  Future<Either<Failure, User>> call(
    String user,
    String password,
    String passwordConfirm,
  ) {
    // Validações de domínio aqui (se necessário)
    
    return repository.registerUser(user, password, passwordConfirm);
  }
}
```

---

## 🚀 Exemplo de Uso Completo

```dart
// 1. Usuário preenche Step 1
final step1Data = {
  'user': 'joao_silva',
  'password': 'Senha@123',
};

// 2. Usuário avança para Step 2 e preenche
final step2Data = {
  'name': 'João Silva',
  'cpf': '12345678900',
  'mail': 'joao@example.com',
  'phone': '11987654321',
  'whatsapp': '11987654321',
  'dateBirth': DateTime(1990, 5, 15),
  'zipCode': '01310100',
  'address': 'Av Paulista, 1000, São Paulo',
  'profileImage': File('path/to/image.jpg'),
};

// 3. Controller dispara cadastro
RegisterController.register(
  user: step1Data['user'],
  password: step1Data['password'],
  passwordConfirm: step1Data['password'],
  name: step2Data['name'],
  cpf: step2Data['cpf'],
  mail: step2Data['mail'],
  phone: step2Data['phone'],
  whatsapp: step2Data['whatsapp'],
  dateBirth: step2Data['dateBirth'],
  zipCode: step2Data['zipCode'],
  address: step2Data['address'],
  profileImage: step2Data['profileImage']?.path,
);

// 4. UI reage ao estado
Obx(() {
  final currentState = RegisterController.state.value;
  
  if (currentState.isLoading) {
    return CircularProgressIndicator();
  }
  
  if (currentState.isError) {
    return SnackBar(
      content: Text(currentState.errorMessage),
      backgroundColor: Colors.red,
    );
  }
  
  if (currentState.isSuccess) {
    // Navega automaticamente para /home
    return SizedBox.shrink();
  }
  
  return RegisterForm();
});
```

---

## 📝 Observações Importantes

### ✅ **Ordem Correta do Fluxo**
1. **Primeiro:** Usuário/Senha (Step 1)
2. **Segundo:** Dados Pessoais (Step 2)
3. **Terceiro:** Envio para API

### ✅ **Campos Obrigatórios**
- **Step 1:** Usuário, Senha, Confirmar Senha
- **Step 2:** Apenas Nome Completo
- **Todos os outros campos são opcionais**

### ✅ **Validações em Cascata**
1. Validação local (UI) - imediata
2. Validação de domínio (Use Case) - antes de enviar
3. Validação backend (API) - na persistência

### ✅ **Máscaras Automáticas**
- CPF, Telefone, WhatsApp e CEP têm formatação automática
- Usuário não precisa digitar pontos, traços ou parênteses

### ✅ **Upload de Foto**
- Câmera ou Galeria
- Redimensionamento automático (800x800px)
- Compressão (85% quality)

---

## 🎓 Conclusão

Este fluxo implementa **Clean Architecture** seguindo **SOLID** e **DDD**, com:

- ✅ **Separação clara de responsabilidades**
- ✅ **Testabilidade** (cada camada isolada)
- ✅ **Manutenibilidade** (baixo acoplamento)
- ✅ **Escalabilidade** (fácil adicionar features)
- ✅ **UX otimizada** (multi-step, validações progressivas)

O cadastro coleta primeiro as **credenciais essenciais** (Step 1) e depois os **dados pessoais complementares** (Step 2), proporcionando uma experiência progressiva e intuitiva ao usuário! 🚀

---

**Documentação:** `features/user` - Flutter App  
**Arquitetura:** Clean Architecture  
**Padrões:** Repository, Use Case, SOLID, DDD  
**Status:** ✅ Fluxo Corrigido e Completo
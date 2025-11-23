# Sanitizer Helper - Documentação

**Versão:** 1.0  
**Data:** 03 de Novembro de 2025  
**Localização:** `app/Helpers/sanitizer_helper.php`

---

## Descrição

Helper com funções para **sanitizar e limpar dados de entrada** antes de serem processados pela aplicação. Remove máscaras, espaços desnecessários e normaliza formatos.

---

## Instalação

### 1. Adicionar o Helper ao Projeto

Coloque o arquivo `sanitizer_helper.php` em:
```
app/Helpers/sanitizer_helper.php
```

### 2. Carregar o Helper

**Opção A - Carregar em um Controller/Service específico:**
```php
helper('sanitizer');
```

**Opção B - Carregar globalmente (recomendado):**

Edite `app/Config/Autoload.php`:
```php
public $helpers = ['sanitizer'];
```

---

## Funções Disponíveis

### 📄 Documentos Brasileiros

#### `sanitize_cpf(?string $cpf): ?string`
Remove máscara do CPF (pontos e traço).

**Entrada:** `123.456.789-00`  
**Saída:** `12345678900`

```php
$cpfLimpo = sanitize_cpf('123.456.789-00');
// Resultado: '12345678900'
```

---

#### `sanitize_cnpj(?string $cnpj): ?string`
Remove máscara do CNPJ (pontos, traços e barra).

**Entrada:** `12.345.678/0001-00`  
**Saída:** `12345678000100`

```php
$cnpjLimpo = sanitize_cnpj('12.345.678/0001-00');
// Resultado: '12345678000100'
```

---

#### `sanitize_zip_code(?string $zipCode): ?string`
Remove máscara do CEP (traço).

**Entrada:** `12345-678`  
**Saída:** `12345678`

```php
$cepLimpo = sanitize_zip_code('12345-678');
// Resultado: '12345678'
```

---

### 📞 Contato

#### `sanitize_phone(?string $phone): ?string`
Remove máscara de telefone (parênteses, traços, espaços).

**Entrada:** `(11) 98765-4321`  
**Saída:** `11987654321`

```php
$telefoneLimpo = sanitize_phone('(11) 98765-4321');
// Resultado: '11987654321'
```

---

#### `sanitize_email(?string $email): ?string`
Sanitiza email (trim e converte para minúsculas).

**Entrada:** `  USER@EXAMPLE.COM  `  
**Saída:** `user@example.com`

```php
$emailLimpo = sanitize_email('  USER@EXAMPLE.COM  ');
// Resultado: 'user@example.com'
```

---

### 📝 Textos

#### `sanitize_string(?string $string): ?string`
Remove espaços extras e faz trim.

**Entrada:** `  João    da   Silva  `  
**Saída:** `João da Silva`

```php
$nomeLimpo = sanitize_string('  João    da   Silva  ');
// Resultado: 'João da Silva'
```

---

#### `sanitize_username(?string $username): ?string`
Sanitiza username (remove espaços e converte para minúsculas).

**Entrada:** `  João Silva  `  
**Saída:** `joaosilva`

```php
$usernameLimpo = sanitize_username('  João Silva  ');
// Resultado: 'joaosilva'
```

---

### 🔢 Números

#### `sanitize_numeric(?string $value): ?string`
Remove tudo que não for número.

**Entrada:** `R$ 1.234,56`  
**Saída:** `123456`

```php
$apenasNumeros = sanitize_numeric('R$ 1.234,56');
// Resultado: '123456'
```

---

#### `sanitize_decimal(?string $value): ?string`
Remove tudo exceto números, ponto e vírgula. Converte vírgula para ponto (padrão decimal).

**Entrada:** `R$ 1.234,56`  
**Saída:** `1234.56`

```php
$valorDecimal = sanitize_decimal('R$ 1.234,56');
// Resultado: '1234.56'
```

---

### 🔄 Arrays

#### `sanitize_array(array $data, array $fields = []): array`
Sanitiza múltiplos campos de um array de uma só vez.

**Parâmetros:**
- `$data` - Array com os dados
- `$fields` - Array associativo `['campo' => 'funcao_sanitizadora']`

```php
$dados = [
    'nome' => '  João Silva  ',
    'cpf' => '123.456.789-00',
    'usuario' => '  JoaoSilva  ',
    'email' => '  JOAO@EXAMPLE.COM  '
];

$dadosLimpos = sanitize_array($dados, [
    'nome' => 'sanitize_string',
    'cpf' => 'sanitize_cpf',
    'usuario' => 'sanitize_username',
    'email' => 'sanitize_email'
]);

// Resultado:
// [
//     'nome' => 'João Silva',
//     'cpf' => '12345678900',
//     'usuario' => 'joaosilva',
//     'email' => 'joao@example.com'
// ]
```

---

#### `trim_all(array $data): array`
Aplica trim em todos os valores string de um array (recursivo).

```php
$dados = [
    'nome' => '  João  ',
    'endereco' => [
        'rua' => '  Av. Brasil  ',
        'numero' => '  123  '
    ]
];

$dadosLimpos = trim_all($dados);

// Resultado:
// [
//     'nome' => 'João',
//     'endereco' => [
//         'rua' => 'Av. Brasil',
//         'numero' => '123'
//     ]
// ]
```

---

## Exemplos de Uso Prático

### No Service (Recomendado)

```php
<?php

namespace App\Services\v1;

class ManagerUserService
{
    public function createUser(array $data): array
    {
        helper('sanitizer');
        
        // Sanitiza os dados
        $data = sanitize_array($data, [
            'nome' => 'sanitize_string',
            'cpf' => 'sanitize_cpf',
            'usuario' => 'sanitize_username'
        ]);
        
        // Continua o processamento...
        return $data;
    }
}
```

---

### No Controller (Alternativa)

```php
<?php

namespace App\Controllers\v1;

use CodeIgniter\RESTful\ResourceController;

class ManagerUserController extends ResourceController
{
    public function create()
    {
        helper('sanitizer');
        
        $dados = $this->request->getJSON(true);
        
        // Sanitiza CPF antes de validar
        $dados['cpf'] = sanitize_cpf($dados['cpf'] ?? null);
        
        // Continua...
    }
}
```

---

### Sanitização Múltipla em Cadeia

```php
helper('sanitizer');

$dados = [
    'nome' => '  João  da   Silva  ',
    'cpf' => '123.456.789-00',
    'telefone' => '(11) 98765-4321',
    'cep' => '12345-678',
    'email' => '  JOAO@EXAMPLE.COM  '
];

// Aplica trim em tudo primeiro
$dados = trim_all($dados);

// Depois sanitiza campos específicos
$dados = sanitize_array($dados, [
    'nome' => 'sanitize_string',
    'cpf' => 'sanitize_cpf',
    'telefone' => 'sanitize_phone',
    'cep' => 'sanitize_zip_code',
    'email' => 'sanitize_email'
]);
```

---

## Comportamento com Valores Nulos/Vazios

**Todas as funções retornam `null` se receberem:**
- `null`
- String vazia `""`
- String com apenas espaços `"   "`

```php
sanitize_cpf(null);      // null
sanitize_cpf('');        // null
sanitize_string('   ');  // null
```

---

## Boas Práticas

### ✅ Faça

- Sanitize dados **antes** de validar
- Use `sanitize_array()` para múltiplos campos
- Carregue o helper globalmente em `Autoload.php`
- Sanitize na camada de **Service** (não no Controller)

### ❌ Evite

- Sanitizar dados já sanitizados (redundância)
- Usar sanitização como validação (são coisas diferentes)
- Sanitizar dados na camada Model

---

## Fluxo Recomendado

```
Controller recebe dados
    ↓
Request valida estrutura
    ↓
Helper SANITIZA dados  ← AQUI
    ↓
Service aplica regras de negócio
    ↓
Model salva no banco
```

---

## Segurança

**Este helper NÃO substitui:**
- Validação de dados
- Escape de SQL (use Query Builder/Prepared Statements)
- Sanitização de HTML/XSS (use `esc()` do CodeIgniter)

**Ele apenas:**
- Remove máscaras e formatações
- Normaliza entradas
- Limpa espaços desnecessários

---

## Troubleshooting

### Função não encontrada
```
Call to undefined function sanitize_cpf()
```

**Solução:** Carregar o helper antes de usar
```php
helper('sanitizer');
```

---

### Dados não estão sendo sanitizados

**Verificar:**
1. Helper está carregado?
2. Passou os parâmetros corretos para `sanitize_array()`?
3. O campo existe no array?

---

## Changelog

### v1.0 - 03/11/2025
- Criação inicial do helper
- 11 funções de sanitização
- Suporte a documentos brasileiros (CPF, CNPJ, CEP)
- Sanitização de strings, emails e valores numéricos
- Funções para arrays (sanitize_array, trim_all)

---

**Desenvolvido para:** CodeIgniter 4.6  
**Padrão de Comentários:** Cabeçalhos com `#` / Internos com `//`  
**Perfil:** Análise e Desenvolvimento de Sistemas
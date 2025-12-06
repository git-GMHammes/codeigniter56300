# API User Management - Documentação de Rotas

## Informações Gerais

- **Versão:** 1.0.0
- **Autor:** Gustavo - HABILIDADE
- **Data:** 22/11/2025
- **Base URL:** `{{www}}/api/v1`

---

# API User Management - Documentação de Rotas

**Base URL:** `{{www}}/api/v1`

---

## 📋 Listagem

**GET** `{{www}}/api/v1/user-management`  
Lista registros ativos

**GET** `{{www}}/api/v1/user-management/with-deleted`  
Lista incluindo deletados

**GET** `{{www}}/api/v1/user-management/only-deleted`  
Lista apenas deletados

---

## 🔍 Busca

**GET** `{{www}}/api/v1/user-management/{id}`  
Busca registro por ID

**POST** `{{www}}/api/v1/user-management/{id}/with-deleted`  
Busca incluindo deletados

**POST** `{{www}}/api/v1/user-management/search`  
Busca avançada com filtros

---

## ✏️ Manipulação

**POST** `{{www}}/api/v1/user-management`  
Cria novo registro

**PUT** `{{www}}/api/v1/user-management`  
Atualiza registro existente

---

## 🗑️ Exclusão

**DELETE** `{{www}}/api/v1/user-management/{id}`  
Marca como deletado

**DELETE** `{{www}}/api/v1/user-management/{id}/hard`  
Exclui permanentemente registro

**DELETE** `{{www}}/api/v1/user-management/clear`  
Limpa todos deletados

---

## ♻️ Restauração

**PATCH** `{{www}}/api/v1/user-management/{id}/restore`  
Restaura registro deletado

---

## 📊 Metadados

**GET** `{{www}}/api/v1/user-management/columns`  
Retorna metadados colunas

**GET** `{{www}}/api/v1/user-management/column-names`  
Retorna nomes colunas

---
# API User Management - Documentação de Rotas

**Base URL:** `{{www}}/api/v1`

---

## 📋 Listagem

| Método | Endpoint                                      | Descrição                                    |
| ------ | --------------------------------------------- | -------------------------------------------- |
| `GET`  | `{{www}}/api/v1/user-management`              | Lista todos os registros ativos              |
| `GET`  | `{{www}}/api/v1/user-management/with-deleted` | Lista todos os registros incluindo deletados |
| `GET`  | `{{www}}/api/v1/user-management/only-deleted` | Lista apenas os registros deletados          |

---

## 🔍 Busca

| Método | Endpoint                                           | Descrição                                 |
| ------ | -------------------------------------------------- | ----------------------------------------- |
| `GET`  | `{{www}}/api/v1/user-management/{id}`              | Busca registro por ID (apenas ativos)     |
| `POST` | `{{www}}/api/v1/user-management/{id}/with-deleted` | Busca registro por ID incluindo deletados |
| `POST` | `{{www}}/api/v1/user-management/search`            | Busca avançada com múltiplos filtros      |

---

## ✏️ Manipulação

| Método | Endpoint                         | Descrição                      |
| ------ | -------------------------------- | ------------------------------ |
| `POST` | `{{www}}/api/v1/user-management` | Cria um novo registro          |
| `PUT`  | `{{www}}/api/v1/user-management` | Atualiza um registro existente |

---

## 🗑️ Exclusão

| Método   | Endpoint                                   | Descrição                            |
| -------- | ------------------------------------------ | ------------------------------------ |
| `DELETE` | `{{www}}/api/v1/user-management/{id}`      | Soft delete - marca como deletado    |
| `DELETE` | `{{www}}/api/v1/user-management/{id}/hard` | Hard delete - exclui permanentemente |
| `DELETE` | `{{www}}/api/v1/user-management/clear`     | Limpa todos os registros deletados   |

---

## ♻️ Restauração

| Método  | Endpoint                                      | Descrição                     |
| ------- | --------------------------------------------- | ----------------------------- |
| `PATCH` | `{{www}}/api/v1/user-management/{id}/restore` | Restaura um registro deletado |

---

## 📊 Metadados

| Método | Endpoint                                      | Descrição                        |
| ------ | --------------------------------------------- | -------------------------------- |
| `GET`  | `{{www}}/api/v1/user-management/columns`      | Retorna metadados das colunas    |
| `GET`  | `{{www}}/api/v1/user-management/column-names` | Retorna apenas nomes das colunas |

### Busca Avançada
O endpoint `/user-management/search` aceita múltiplos filtros e operadores para buscas complexas.

---

## 🔐 Autenticação

_Adicione aqui informações sobre autenticação se necessário (JWT, API Key, etc.)_

---

## 📌 Status Codes

| Código | Descrição                                      |
| ------ | ---------------------------------------------- |
| `200`  | Sucesso                                        |
| `201`  | Recurso criado                                 |
| `204`  | Sem conteúdo (sucesso em operação sem retorno) |
| `400`  | Requisição inválida                            |
| `404`  | Recurso não encontrado                         |
| `500`  | Erro interno do servidor                       |

---

**Desenvolvido por:** Gustavo - HABILIDADE  
**Projeto:** CodeIgniter 4 - User Management API
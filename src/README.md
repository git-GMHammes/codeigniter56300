# README.md - Migração Laravel para CodeIgniter 4.6

## 📋 Visão Geral do Projeto

Este documento descreve o processo de migração de uma aplicação Laravel para **CodeIgniter 4.6**, mantendo os mesmos níveis de eficiência, profissionalismo e segurança do Laravel, porém aproveitando a portabilidade e simplicidade do CodeIgniter.

### Objetivo Principal

Migrar aplicação Laravel para **CodeIgniter 4.6** sem perder qualidade, mantendo alto nível de segurança, eficiência e profissionalismo, utilizando deploy simples via SFTP.

---

## 🎯 Princípios e Restrições do Projeto

### Restrições Técnicas

- **Proibido o uso de PHP Composer** no ambiente de produção
- **Proibido o uso de comandos Spark** (CLI do CodeIgniter)
- **Proibido o uso de Artisan** e Migrations complexas
- Deploy realizado exclusivamente via **SFTP** (cópia de arquivos)
- Máxima portabilidade e simplicidade no processo de deploy

### Diretrizes de Desenvolvimento

- Aproveitar a portabilidade natural do CodeIgniter
- Não depender de comandos de terminal complexos
- Todas as bibliotecas de terceiros devem estar na pasta **Libraries**
- Todos os helpers personalizados devem estar na pasta **Helpers**
- Utilizar **Padrões de Projeto de Software** em todas as Libraries
- Manter organização clara e separação de responsabilidades

---

## 🗄️ Gerenciamento de Múltiplas Conexões de Banco de Dados

### Responsabilidade Crítica

O **CodeIgniter 4.6** terá a responsabilidade de gerenciar e manter **três tipos de conexões com três tipos de bancos de dados diferentes**.

### Características do Sistema Multi-Database

#### Tipos de Conexão
- Conexão Database Tipo A
- Conexão Database Tipo B
- Conexão Database Tipo C

#### Implicações Técnicas

**Requests e Validações:**
- Cada requisição deve informar qual tabela será acessada
- Cada requisição deve informar qual nome de conexão será utilizada
- Validações devem considerar o banco de dados específico
- Regras de validação podem variar conforme o banco

**Models:**
- Cada Model deve especificar sua conexão de banco
- Propriedade `$DBGroup` deve ser configurada por Model
- Models podem alternar entre conexões quando necessário
- Tratamento de erros específico por tipo de banco

**Services:**
- Services devem receber informação de qual conexão utilizar
- Lógica de negócio deve ser agnóstica ao tipo de banco quando possível
- Tratamento de transações considerando múltiplas conexões

**Configuração:**
- Arquivo `app/Config/Database.php` deve conter todas as três conexões
- Nomenclatura clara para cada grupo de conexão
- Credenciais e configurações específicas por ambiente
- Fallback e tratamento de falhas de conexão

---

## 📁 Estrutura de Diretórios

### Estrutura Geral MVC Estendida com Versionamento

```
app/
├── Config/
│   ├── Database.php (configurações das 3 conexões)
│   └── Routes.php (importa as rotas de app/Routes/)
│
├── Routes/
│   └── v1/
│       ├── api_objeto1_routes.php
│       ├── api_objeto2_routes.php
│       ├── api_objeto3_routes.php
│       └── ... (um arquivo de rotas por módulo/objeto)
│
├── Controllers/
│   ├── API/
│   │   └── V1/
│   │       ├── Objeto1Controller.php
│   │       ├── Objeto2Controller.php
│   │       ├── Objeto3Controller.php
│   │       └── ... (um controller por módulo/objeto)
│   └── BaseController.php
│
├── Models/
│   └── v1/
│       ├── Objeto1Model.php
│       ├── Objeto2Model.php
│       ├── Objeto3Model.php
│       └── ... (um model por tabela/objeto)
│
├── Libraries/
│   ├── ResponseFormatter.php
│   ├── ValidationHandler.php
│   ├── AuthenticationManager.php
│   ├── SoftDeleteManager.php
│   ├── DatabaseInspector.php
│   ├── MultiDatabaseManager.php
│   └── ... (bibliotecas de terceiros e customizadas)
│
├── Helpers/
│   ├── response_helper.php
│   ├── validation_helper.php
│   ├── datetime_helper.php
│   ├── array_helper.php
│   ├── database_helper.php
│   └── ... (funções auxiliares repetitivas)
│
├── Requests/
│   └── v1/
│       ├── Objeto1/
│       │   ├── CreateObjeto1Request.php
│       │   ├── UpdateObjeto1Request.php
│       │   └── DeleteObjeto1Request.php
│       ├── Objeto2/
│       │   ├── CreateObjeto2Request.php
│       │   ├── UpdateObjeto2Request.php
│       │   └── DeleteObjeto2Request.php
│       └── ... (validações por módulo/objeto)
│
├── Services/
│   └── v1/
│       ├── Objeto1Service.php
│       ├── Objeto2Service.php
│       ├── Objeto3Service.php
│       └── ... (lógica de negócio por módulo/objeto)
│
└── Database/
    └── Migrations/
        └── ... (apenas documentação, não executadas via CLI)
```

### Descrição das Camadas

**Controllers:**  
Receber requisições HTTP, identificar conexão de banco necessária, delegar para Services, retornar respostas formatadas. Controllers devem ser enxutos, apenas orquestrando o fluxo.

**Models (v1):**  
Interação direta com banco de dados, queries básicas. Cada Model deve especificar seu DBGroup (conexão). Extende Model do CodeIgniter com soft delete. Deve tratar especificidades de cada tipo de banco.

**Services (v1):**  
Lógica de negócio, regras complexas, orquestração. Camada intermediária entre Controller e Model. Recebe informação de qual conexão de banco utilizar. Gerencia transações e validações de negócio.

**Requests (v1):**  
Validação de entrada, sanitização, regras de negócio. Similar aos Form Requests do Laravel. Deve validar não apenas dados, mas também informações de conexão e tabela quando necessário.

**Libraries:**  
Funcionalidades reutilizáveis, padrões de projeto. Deve seguir princípios SOLID. Inclui gerenciamento de múltiplas conexões de banco.

**Helpers:**  
Funções auxiliares globais, utilitários simples. Funções puras quando possível. Inclui helpers para facilitar trabalho com múltiplos bancos.

**Routes (v1):**  
Arquivos separados por módulo/objeto. Organização versionada para futuras APIs. Cada arquivo contém todas as rotas de um módulo específico. Ficam localizados em `app/Routes/v1/` e são importados pelo arquivo principal `app/Config/Routes.php`.

---

## 🛣️ Padrão de Rotas para Todos os Módulos

Cada módulo (objeto/tabela) deve implementar **quatorze rotas padrão** conforme especificado abaixo:

### Listagem de Registros

#### GET `/api/v1/objeto`
Listar todos os registros ativos do objeto. Retorna apenas registros que não possuem o campo deleted_at preenchido.

#### GET `/api/v1/objeto/with-deleted`
Listar todos os registros do objeto, incluindo os que foram marcados como deletados. Retorna registros com e sem deleted_at.

#### GET `/api/v1/objeto/only-deleted`
Listar apenas os registros que foram marcados como deletados. Retorna somente registros com deleted_at preenchido.

### Busca e Filtros

#### POST `/api/v1/objeto/search`
Realizar busca avançada por um ou mais campos da tabela ou view do objeto. Permite pesquisa complexa enviando no corpo da requisição os campos e valores desejados como critérios de filtro. Suporta múltiplos campos simultaneamente, operadores de comparação como igual, diferente, maior que, menor que, contém, começa com, termina com. Pode incluir ordenação, paginação e limites de resultados. Retorna apenas registros ativos por padrão, mas pode aceitar parâmetro para incluir deletados.

### Obtenção de Registro Específico

#### GET `/api/v1/objeto/{id}`
Obter um registro específico do objeto através do seu ID. Retorna apenas se o registro não estiver marcado como deletado.

#### POST `/api/v1/objeto/{id}/with-deleted`
Obter um registro específico do objeto através do seu ID, mesmo que esteja marcado como deletado. Permite recuperar dados de registros soft deleted.

### Manipulação de Registros

#### POST `/api/v1/objeto`
Criar um novo registro do objeto. Deve passar por validação via Request class. Requer informação de qual conexão de banco utilizar.

#### PUT `/api/v1/objeto`
Atualizar um registro existente do objeto. Deve passar por validação via Request class. Deve informar ID e conexão de banco.

### Exclusão de Registros

#### DELETE `/api/v1/objeto/{id}`
Soft delete - Marcar registro como deletado. Preenche o campo deleted_at com timestamp atual. Não remove fisicamente do banco.

#### DELETE `/api/v1/objeto/{id}/hard`
Hard delete - Remover registro permanentemente do banco de dados. Exclusão física irreversível.

#### DELETE `/api/v1/objeto/clear`
Limpar todos os registros que estão marcados como deletados. Remove permanentemente do banco todos os registros com deleted_at preenchido.

### Restauração de Registros

#### PATCH `/api/v1/objeto/{id}/restore`
Restaurar um registro que foi marcado como deletado via soft delete. Remove o valor do campo deleted_at, tornando o registro ativo novamente.

### Metadados e Estrutura

#### GET `/api/v1/objeto/columns`
Exibir metadados completos das colunas da tabela do objeto. Retorna informações como: COLUMN_NAME, COLUMN_TYPE, IS_NULLABLE, COLUMN_KEY, COLUMN_DEFAULT. Útil para interfaces dinâmicas e documentação.

#### GET `/api/v1/objeto/column-names`
Exibir lista simplificada contendo apenas os nomes das colunas da tabela do objeto. Retorna array simples com nomes das colunas.

### Organização dos Arquivos de Rotas

Cada módulo/objeto possui seu próprio arquivo de rotas independente. Todos os arquivos ficam organizados em `app/Routes/v1/`. O arquivo nomeado como `api_nomedobjeto_routes.php` contém todas as quatorze rotas do módulo. O arquivo principal `app/Config/Routes.php` importa todos os arquivos de rotas dos módulos através de includes. Esta estrutura permite manutenção isolada e organizada de cada módulo, facilitando versionamento futuro da API.

---

## 🔧 Padrões de Projeto a Serem Aplicados

### Padrões Obrigatórios nas Libraries

**Singleton:**  
Aplicação em classes de configuração e gerenciamento de recursos únicos como conexões de banco de dados. Benefício de garantir instância única e controle centralizado de recursos compartilhados.

**Factory:**  
Aplicação na criação de objetos complexos como Responses formatadas, Validators específicos, e geradores de conexão de banco. Benefício de flexibilidade na criação e encapsulamento da lógica de instanciação.

**Strategy:**  
Aplicação em diferentes estratégias de validação, autenticação e seleção de conexão de banco. Benefício de trocar comportamento facilmente em tempo de execução conforme necessidade.

**Repository:**  
Aplicação na abstração de acesso a dados, isolando queries e lógica de persistência. Benefício de desacoplar lógica de negócio da camada de dados e facilitar testes.

**Service Layer:**  
Aplicação no encapsulamento de lógica de negócio complexa. Benefício de reutilização, testabilidade e separação clara de responsabilidades.

**Dependency Injection:**  
Aplicação na injeção de dependências via constructor em Services e Libraries. Benefício de baixo acoplamento e alta coesão entre componentes.

**Chain of Responsibility:**  
Aplicação em pipeline de validações, middlewares e filtros de requisição. Benefício de processar requisições em cadeia de forma organizada.

**Observer:**  
Aplicação em eventos de modelo como before insert, after update, etc. Benefício de reações automáticas a mudanças sem acoplamento direto.

**Adapter:**  
Aplicação para adaptar interfaces de diferentes tipos de bancos de dados para uma interface comum. Benefício de trabalhar com múltiplos bancos de forma transparente.

---

## 🔒 Segurança Equivalente ao Laravel

### Aspectos de Segurança a Implementar

**CSRF Protection:**  
Implementação através de filtros CSRF nativos do CodeIgniter 4.6. Equivalência com proteção CSRF automática do Laravel.

**XSS Protection:**  
Implementação através de filtros de entrada nativos do CodeIgniter. Equivalência com sanitização automática do Laravel.

**SQL Injection:**  
Implementação através do Query Builder nativo do CodeIgniter 4.6. Equivalência com Eloquent ORM do Laravel. Parametrização automática de queries.

**Mass Assignment:**  
Implementação definindo propriedade `$allowedFields` nos Models. Equivalência com propriedades Protected e Fillable do Laravel.

**Authentication:**  
Implementação através de Library customizada com JWT ou Session. Equivalência com Laravel Sanctum ou Passport.

**Authorization:**  
Implementação através de Library customizada de Policies e Gates. Equivalência com Laravel Policies.

**Rate Limiting:**  
Implementação através de Throttle Filter customizado. Equivalência com Laravel Throttle middleware.

**Encryption:**  
Implementação através dos Services de Encryption nativos do CodeIgniter. Equivalência com Laravel Encryption.

**Password Hashing:**  
Implementação através de `password_hash()` e `password_verify()` nativos do PHP. Equivalência com Laravel Hash facade.

**Input Validation:**  
Implementação através de Request classes customizadas. Equivalência com Form Request Validation do Laravel.

**Database Connection Security:**  
Implementação de validação de credenciais e escolha segura de conexão de banco. Prevenir acesso não autorizado a diferentes databases.

---

## 📦 Gerenciamento de Dependências Sem Composer

### Estratégia de Bibliotecas de Terceiros

**Localização:**  
Todas as bibliotecas externas devem ser colocadas em `app/Libraries/`. Criar subpastas por vendor quando necessário. Exemplos: `app/Libraries/JWT/`, `app/Libraries/PDF/`, `app/Libraries/Excel/`.

**Versionamento:**  
Manter arquivo de documentação `LIBRARIES.md` na raiz do projeto listando nome da biblioteca, versão utilizada, data de inclusão no projeto, URL de origem oficial, propósito e uso no projeto, dependências da biblioteca.

**Atualização:**  
Atualização manual via download direto do código fonte e substituição de arquivos. Sempre testar em ambiente de desenvolvimento local antes do deploy. Documentar todas as mudanças no arquivo `CHANGELOG.md`. Manter backup da versão anterior antes de atualizar.

**Namespace e Autoload:**  
Configurar autoload manual no `app/Config/Autoload.php` quando necessário. Usar namespaces organizados para evitar conflitos. Documentar estrutura de namespaces no `LIBRARIES.md`.

---

## 🚀 Processo de Deploy via SFTP

### Fluxo de Deploy

Desenvolvimento Local em ambiente Windows com Laragon e Docker Desktop. Execução de testes locais completos de todas as funcionalidades. Commit opcional no repositório Git para controle de versão. Preparação e organização dos arquivos modificados. Conexão SFTP ao servidor de produção. Upload seletivo apenas dos arquivos que foram modificados. Verificação de permissões e estrutura no servidor. Execução de testes básicos em ambiente de produção. Monitoramento de logs de erro após deploy. Validação de funcionalidades críticas. Documentação da versão deployada.

### Checklist de Deploy

- [ ] Verificar se todas as configurações do arquivo `.env` estão corretas para produção
- [ ] Testar todas as rotas localmente antes do envio
- [ ] Confirmar que todas as três conexões de banco estão configuradas
- [ ] Verificar permissões de escrita nas pastas `writable/`
- [ ] Fazer backup completo do banco de dados de produção
- [ ] Fazer backup dos arquivos atuais do servidor
- [ ] Conectar via cliente SFTP ao servidor
- [ ] Fazer upload apenas dos arquivos modificados desde último deploy
- [ ] Verificar se upload foi concluído sem erros
- [ ] Verificar logs de erro do CodeIgniter após deploy
- [ ] Testar endpoints críticos da API
- [ ] Testar conexões com os três bancos de dados
- [ ] Validar autenticação e autorização
- [ ] Documentar versão e data do deploy no `DEPLOY_LOG.md`

### Arquivos que NÃO devem ser enviados via SFTP

- Pasta `vendor/` não existe no projeto mas nunca deve ser enviada
- Arquivo `.env` deve manter configuração específica do servidor
- Pasta `writable/logs/` contém logs antigos que não devem ser sobrescritos
- Pasta `writable/cache/` cache pode ser regenerado no servidor
- Pasta `writable/session/` sessões antigas não devem ser enviadas
- Pasta `.git/` controle de versão permanece apenas local
- Arquivos de configuração de IDE ou editor
- Arquivos temporários e de testes locais
- Dumps de banco de dados de desenvolvimento

---

## 📝 Conceito de "Objeto" no Projeto

### Definição Contextual

O termo **"objeto"** é utilizado de forma intercambiável no projeto e pode representar diferentes conceitos conforme o contexto.

**Quando Objeto representa uma Tabela do Banco:**  
Refere-se a uma tabela física em um dos três bancos de dados. Usado nas rotas de API para identificar qual tabela está sendo manipulada. Presente nos Models que fazem interação direta com tabela. Utilizado nas queries e operações de banco de dados. Presente nos metadados de estrutura de colunas.

**Quando Objeto representa um Módulo da Aplicação:**  
Refere-se a um conjunto de funcionalidades relacionadas. Usado na organização de pastas e arquivos. Presente nos Services que encapsulam lógica de negócio. Utilizado nos Controllers que orquestram operações. Presente na estrutura de Requests para validação. Representa uma entidade de negócio ou conceito do domínio.

**Exemplos Práticos:**  
Objeto usuários pode ser tanto a tabela `usuarios` no banco quanto o Módulo de Usuários com todas suas funcionalidades. Objeto produtos representa a tabela `produtos` e também o conjunto de operações de gerenciamento de produtos. Objeto pedidos abrange tanto a estrutura de dados quanto as regras de negócio relacionadas a pedidos.

---

## 📋 Metodologia de Migração

### Princípios da Migração

**Incremental:**  
Migração módulo por módulo, objeto por objeto, nunca tudo de uma vez. Cada módulo é completamente migrado e testado antes de avançar para o próximo.

**Validação Constante:**  
Testar exaustivamente cada migração antes de avançar. Validar em ambiente local antes de qualquer deploy. Confirmar funcionamento das três conexões de banco após cada módulo.

**Sem Avanços Autônomos:**  
Aguardar sempre solicitação expressa e autorização antes de prosseguir para próximo módulo. Nunca presumir que pode avançar sem aprovação. Documentar o que foi feito e aguardar feedback.

**Documentação Contínua:**  
Documentar cada decisão técnica tomada. Registrar mudanças em estrutura e código. Manter log detalhado do processo de migração. Atualizar documentação de API após cada módulo.

**Reversibilidade:**  
Manter sempre possibilidade de reverter mudanças. Fazer backups antes de modificações significativas. Documentar como desfazer cada etapa se necessário.

**Compatibilidade:**  
Garantir que funcionalidades antigas continuem operando durante migração. Manter endpoints existentes funcionais sempre que possível. Versionar API para permitir coexistência de versões.

### Fases da Migração

**Fase 1 - Planejamento:**  
Mapear todos os módulos e objetos da aplicação Laravel. Identificar dependências entre módulos. Mapear quais objetos usam qual conexão de banco. Priorizar ordem de migração considerando dependências. Definir critérios claros de sucesso para cada módulo. Estimar tempo necessário por módulo.

**Fase 2 - Infraestrutura Base:**  
Configurar estrutura completa de pastas do CodeIgniter 4.6 com versionamento. Criar pasta `app/Routes/v1/` para arquivos de rotas. Criar Libraries base essenciais como ResponseFormatter, ValidationHandler, MultiDatabaseManager. Criar Helpers comuns que serão usados em todos os módulos. Configurar arquivo `app/Config/Routes.php` para importar rotas modulares. Configurar as três conexões de banco no `Database.php`. Criar classes base para Models, Services e Requests. Testar infraestrutura base antes de migrar primeiro módulo.

**Fase 3 - Migração por Módulo:**  
Para cada módulo/objeto, seguir esta sequência:
- Criar Model na pasta `v1` especificando DBGroup correto
- Implementar soft delete no Model
- Criar Service na pasta `v1` com toda lógica de negócio
- Injetar dependências necessárias no Service
- Criar Controller enxuto que apenas orquestra
- Criar Request classes para validação em pasta `v1/NomeObjeto`
- Configurar arquivo de rotas em `app/Routes/v1/`
- Adicionar include no arquivo `app/Config/Routes.php`
- Implementar todas as quatorze rotas padrão
- Testar cada rota individualmente
- Validar soft delete e restauração
- Validar metadados de colunas
- Validar busca avançada por múltiplos campos
- Testar com conexão de banco correta
- Validar segurança e autenticação
- Validar performance e tempo de resposta
- Documentar peculiaridades do módulo
- Aguardar aprovação expressa para próximo módulo

**Fase 4 - Integração:**  
Testar integração entre módulos já migrados. Validar fluxos completos da aplicação que atravessam múltiplos módulos. Validar transações que envolvem múltiplas conexões de banco. Ajustar conforme necessário. Otimizar queries e performance geral.

**Fase 5 - Finalização:**  
Migrar módulos restantes seguindo mesmo processo. Realizar testes de carga e stress em todos os módulos. Validar comportamento com alto volume de requisições. Validar todas as três conexões de banco sob carga. Documentação final completa da API. Preparar documentação para equipe. Deploy final em produção com acompanhamento.

---

## 🎯 Metas de Qualidade

### Equivalência com Laravel

**Segurança:**  
Laravel oferece CSRF, XSS, SQL Injection protection nativos. CodeIgniter 4.6 implementará através de filtros nativos mais Libraries customizadas. Meta: alcançar mesmo nível de segurança.

**Validação:**  
Laravel oferece Form Requests elegantes e robustos. CodeIgniter 4.6 implementará Request Classes customizadas versionadas. Meta: mesma qualidade de validação.

**Autenticação:**  
Laravel oferece Sanctum e Passport para autenticação API. CodeIgniter 4.6 implementará Library customizada com JWT. Meta: mesma robustez e segurança.

**Soft Delete:**  
Laravel oferece Trait SoftDeletes simples de usar. CodeIgniter 4.6 implementará Model customizado com soft delete. Meta: mesma funcionalidade e facilidade.

**API Resources:**  
Laravel oferece Resources e Collections para formatar respostas. CodeIgniter 4.6 implementará ResponseFormatter Library. Meta: respostas igualmente bem formatadas.

**Service Layer:**  
Laravel não oferece nativamente mas é comum usar. CodeIgniter 4.6 implementará Services versionados explicitamente. Meta: melhor separação de responsabilidades.

**Repository:**  
Laravel não oferece nativamente mas é padrão comum. CodeIgniter 4.6 implementará Repository Pattern quando necessário. Meta: código mais testável e desacoplado.

**Middlewares:**  
Laravel oferece Route Middlewares poderosos. CodeIgniter 4.6 usará Filters nativos do framework. Meta: mesmo controle sobre pipeline de requisição.

**Multiple Databases:**  
Laravel facilita trabalho com múltiplas conexões. CodeIgniter 4.6 gerenciará três conexões diferentes com Library específica. Meta: gerenciamento robusto e transparente.

### Métricas de Sucesso

- Todas as funcionalidades do Laravel migradas com sucesso sem perda de features
- Performance igual ou superior ao Laravel em testes de carga
- Código limpo, bem documentado e seguindo PSR-12
- Testes de todas as quatorze rotas passando para cada módulo
- Deploy via SFTP funcionando perfeitamente sem necessidade de comandos
- Zero dependências de Composer ou Spark em produção
- Máxima portabilidade alcançada podendo mover aplicação facilmente
- Três conexões de banco funcionando perfeitamente sem conflitos
- Sistema de soft delete funcionando em todos os módulos
- Validações robustas equivalentes ao Laravel
- Autenticação e autorização seguras
- Documentação completa e atualizada

---

## 📖 Glossário do Projeto

**Objeto:**  
Tabela do banco de dados ou módulo da aplicação, dependendo do contexto de uso.

**Soft Delete:**  
Marcação lógica de exclusão através do preenchimento do campo `deleted_at` com timestamp. Registro permanece no banco mas é considerado inativo.

**Hard Delete:**  
Exclusão física permanente do registro no banco de dados. Operação irreversível que remove dados definitivamente.

**Service:**  
Camada de lógica de negócio situada entre Controller e Model. Contém regras complexas e orquestração de operações.

**Request:**  
Classe de validação e sanitização de entrada de dados. Similar aos Form Requests do Laravel. Garante integridade dos dados recebidos.

**Library:**  
Biblioteca reutilizável implementando padrões de projeto. Código de infraestrutura e funcionalidades transversais.

**Helper:**  
Função auxiliar global de propósito específico. Utilitários simples para tarefas comuns.

**Módulo:**  
Conjunto de funcionalidades relacionadas a um objeto de negócio. Inclui Model, Service, Controller, Requests e Rotas.

**Deploy:**  
Processo de envio de código para servidor via SFTP. Não envolve comandos de terminal ou automações complexas.

**DBGroup:**  
Identificador de grupo de conexão de banco de dados no CodeIgniter. Permite especificar qual das três conexões usar.

**v1:**  
Versionamento de API e estrutura interna. Permite evolução futura mantendo compatibilidade com versões antigas.

---

## ⚠️ Observações Importantes

### Restrições Críticas

- **NUNCA** usar comandos PHP Composer no servidor de produção
- **NUNCA** usar comandos Spark do CodeIgniter em produção
- **SEMPRE** testar localmente antes de qualquer deploy para produção
- **SEMPRE** aguardar aprovação expressa antes de avançar para próximo módulo
- **SEMPRE** documentar decisões técnicas e mudanças realizadas
- **SEMPRE** validar qual conexão de banco está sendo usada
- **SEMPRE** verificar se as três conexões estão funcionando após mudanças

### Boas Práticas

- Manter código limpo e bem comentado em português quando necessário
- Seguir PSR-12 para padronização de código PHP
- Usar type hints sempre que possível para maior segurança de tipos
- Documentar funções e métodos complexos com PHPDoc
- Manter arquivos pequenos e focados em responsabilidade única
- Separar responsabilidades claramente entre camadas
- Nomear variáveis e métodos de forma descritiva e clara
- Evitar código duplicado usando Libraries e Helpers
- Validar entrada de dados em múltiplas camadas
- Tratar exceções de forma apropriada
- Logar erros e eventos importantes
- Fazer commits frequentes com mensagens descritivas

---

## 📄 Arquivos de Documentação Complementares

**LIBRARIES.md:**  
Lista completa de bibliotecas externas usadas no projeto. Informações de versão, origem e propósito de cada biblioteca.

**CHANGELOG.md:**  
Histórico detalhado de mudanças e versões do projeto. Registro de novos recursos, correções e melhorias.

**DEPLOY.md:**  
Procedimentos detalhados e passo a passo de deploy via SFTP. Checklists e cuidados específicos.

**SECURITY.md:**  
Configurações e práticas de segurança implementadas. Guia de boas práticas para manter segurança.

**API.md:**  
Documentação completa das APIs e endpoints disponíveis. Exemplos de requisições e respostas.

**MIGRATION_LOG.md:**  
Log detalhado do processo de migração módulo por módulo. Decisões tomadas, problemas encontrados e soluções.

**DATABASE.md:**  
Documentação das três conexões de banco de dados. Informações sobre estrutura, credenciais e uso.

---

## 🔄 Controle de Versão do Documento

**Versão:** 1.1.0  
**Data:** 20 de Novembro de 2025  
**Descrição:** Adicionada rota POST de busca avançada por múltiplos campos - Total de quatorze rotas padrão por módulo  
**Autor:** Gustavo - Senior Systems Analyst PRODERJ

**Histórico:**
- **v1.1.0** (20/11/2025): Adicionada rota POST `/api/v1/objeto/search` para busca avançada
- **v1.0.0** (20/11/2025): Criação inicial do documento de migração

---

## 📝 Nota Final

Este documento serve como referência principal e backup para todo o processo de migração. Cada decisão, mudança ou avanço deve ser devidamente documentado e aprovado antes da implementação. 

A migração será **MUITO LENTA** e **DETALHADA**, sem avanços não solicitados expressamente. 

O **CodeIgniter 4.6** gerenciará **três tipos diferentes de conexões de banco de dados**, exigindo atenção especial em todas as camadas da aplicação.

---
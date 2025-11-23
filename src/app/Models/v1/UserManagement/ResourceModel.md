# DOCUMENTAÇÃO - ResourceModel

## Visão Geral

ResourceModel é a classe base para todos os Models do sistema de migração Laravel para CodeIgniter 4.6. Esta classe utiliza exclusivamente recursos nativos do CodeIgniter 4.6, garantindo total compatibilidade e segurança.

O Model está localizado em: `app/Models/v1/ResourceModel.php`

Namespace: `App\Models\v1`

---

## Propriedades Protegidas (Protected)

### $DBGroup

Define qual grupo de conexão de banco de dados será utilizado pelo Model. O sistema suporta três tipos diferentes de conexões de banco de dados.

Valor padrão: `'DB_GROUP_001'`

Tipo: string

Funcionalidade: Nativa do CodeIgniter 4.6

Esta propriedade deve ser alterada conforme a necessidade de cada Model, podendo assumir os valores DB_GROUP_001, DB_GROUP_002 ou DB_GROUP_003, dependendo de qual banco de dados a tabela pertence.

### $table

Define o nome da tabela no banco de dados que este Model irá manipular.

Valor padrão: `'users'`

Tipo: string

Funcionalidade: Nativa do CodeIgniter 4.6

Cada Model deve alterar esta propriedade para corresponder ao nome real da tabela no banco de dados.

### $primaryKey

Define qual campo é a chave primária da tabela.

Valor padrão: `'id'`

Tipo: string

Funcionalidade: Nativa do CodeIgniter 4.6

A maioria das tabelas usa 'id' como chave primária, mas esta propriedade pode ser alterada caso a tabela utilize outro campo como chave primária.

### $returnType

Define o formato de retorno dos dados obtidos do banco de dados.

Valor padrão: `'array'`

Tipo: string

Funcionalidade: Nativa do CodeIgniter 4.6

Valores possíveis: 'array' retorna arrays associativos, 'object' retorna objetos stdClass. Recomenda-se manter como 'array' para consistência no projeto.

### $useSoftDeletes

Habilita ou desabilita o recurso de soft delete (exclusão lógica).

Valor padrão: `true`

Tipo: boolean

Funcionalidade: Nativa do CodeIgniter 4.6

Quando ativado (true), as operações de DELETE não removem fisicamente os registros do banco de dados. Ao invés disso, o campo deleted_at é preenchido com a data e hora da exclusão, marcando o registro como deletado logicamente.

### $deletedField

Define qual campo da tabela será usado para marcar registros deletados logicamente.

Valor padrão: `'deleted_at'`

Tipo: string

Funcionalidade: Nativa do CodeIgniter 4.6

Este campo deve existir na tabela e ser do tipo datetime, permitindo valores NULL. Registros com este campo preenchido são considerados deletados.

### $useTimestamps

Habilita ou desabilita o preenchimento automático de timestamps.

Valor padrão: `true`

Tipo: boolean

Funcionalidade: Nativa do CodeIgniter 4.6

Quando ativado (true), o CodeIgniter preenche automaticamente os campos de criação e atualização com a data e hora atual durante operações de INSERT e UPDATE.

### $createdField

Define qual campo será preenchido automaticamente com a data de criação do registro.

Valor padrão: `'created_at'`

Tipo: string

Funcionalidade: Nativa do CodeIgniter 4.6

Este campo deve existir na tabela e ser do tipo datetime. É preenchido automaticamente apenas na operação INSERT.

### $updatedField

Define qual campo será preenchido automaticamente com a data da última atualização do registro.

Valor padrão: `'updated_at'`

Tipo: string

Funcionalidade: Nativa do CodeIgniter 4.6

Este campo deve existir na tabela e ser do tipo datetime. É atualizado automaticamente em toda operação UPDATE.

### $dateFormat

Define o formato de data usado para os timestamps.

Valor padrão: `'datetime'`

Tipo: string

Funcionalidade: Nativa do CodeIgniter 4.6

Valores possíveis: 'datetime' formato Y-m-d H:i:s, 'date' formato Y-m-d, 'int' timestamp Unix. Recomenda-se manter 'datetime' para compatibilidade com MySQL.

### $allowedFields

Define quais campos da tabela podem ser preenchidos através de operações de mass assignment.

Valor padrão: array contendo os campos permitidos

Tipo: array

Funcionalidade: Nativa do CodeIgniter 4.6

Esta é uma medida crítica de segurança equivalente ao $fillable do Laravel. Apenas campos listados neste array podem ser preenchidos através dos métodos insert() e update(). Campos não listados são automaticamente ignorados, protegendo contra vulnerabilidades de mass assignment. Nunca inclua campos sensíveis como 'id' ou campos de controle interno neste array.

### $validationRules

Define regras de validação básicas que serão aplicadas pelo Model.

Valor padrão: array vazio

Tipo: array

Funcionalidade: Nativa do CodeIgniter 4.6

Este array pode conter regras de validação simples do CodeIgniter. No entanto, validações complexas e regras de negócio devem estar nas classes Request, mantendo a separação de responsabilidades.

### $validationMessages

Define mensagens customizadas para os erros de validação.

Valor padrão: array vazio

Tipo: array

Funcionalidade: Nativa do CodeIgniter 4.6

Permite customizar as mensagens de erro retornadas quando uma validação falha. Deve ser usado em conjunto com $validationRules.

### $skipValidation

Controla se a validação deve ser pulada nas operações do Model.

Valor padrão: `false`

Tipo: boolean

Funcionalidade: Nativa do CodeIgniter 4.6

Quando false (padrão), as validações definidas em $validationRules são executadas. Quando true, as validações são ignoradas. Use com cautela.

### Callbacks (beforeInsert, afterInsert, beforeUpdate, afterUpdate, beforeFind, afterFind, beforeDelete, afterDelete)

Define métodos que serão executados automaticamente em diferentes momentos do ciclo de vida do Model.

Valor padrão: arrays vazios

Tipo: array

Funcionalidade: Nativa do CodeIgniter 4.6

Cada callback é um array que pode conter nomes de métodos da classe que serão executados automaticamente. Por exemplo, beforeInsert é executado antes de inserir um registro, afterUpdate é executado após atualizar um registro, e assim por diante. Útil para lógica que deve sempre executar em determinados momentos, como hash de senhas antes de inserir, log de alterações após atualizar, etc.

### $casts

Define conversão automática de tipos para campos específicos.

Valor padrão: array com conversões definidas

Tipo: array

Funcionalidade: Nativa do CodeIgniter 4.6

Este recurso é equivalente aos $casts do Laravel. O CodeIgniter converte automaticamente os tipos dos campos ao buscar ou salvar dados. Tipos disponíveis incluem: int, integer, float, double, string, bool, boolean, object, array, datetime, timestamp, date. Por exemplo, um campo definido como 'int' no array será sempre retornado como inteiro, não como string. Campos de data definidos como 'datetime' são automaticamente convertidos para objetos Time do CodeIgniter.

---

## Propriedades Públicas (Public)

### $hiddenFields

Lista de campos que devem ser removidos das respostas da API.

Valor padrão: array contendo 'password'

Tipo: array

Funcionalidade: Customizada (não é nativa do CodeIgniter)

Esta propriedade serve como referência para a Library de formatação de respostas que será criada futuramente. O Model não processa esta propriedade automaticamente. A Library de formatação lerá este array e removerá os campos listados antes de retornar os dados para o Controller. Isso garante que campos sensíveis como senhas nunca sejam expostos nas respostas da API, mesmo que sejam buscados do banco de dados.

---

## Métodos Públicos

### findAllWithDeleted(?int $limit = null, ?int $offset = null): array

Busca todos os registros da tabela, incluindo os que foram marcados como deletados.

Parâmetros:

- $limit: Quantidade máxima de registros a retornar. Se null, retorna todos.
- $offset: Quantidade de registros a pular antes de começar a retornar. Se null, começa do primeiro.

Retorno: Array com todos os registros encontrados.

Rota correspondente: GET /api/v1/objeto/with-deleted

Este método ignora o filtro automático de soft delete do CodeIgniter, retornando tanto registros ativos (deleted_at NULL) quanto deletados (deleted_at preenchido). Útil para administradores que precisam visualizar ou recuperar dados deletados.

### findOnlyDeleted(?int $limit = null, ?int $offset = null): array

Busca apenas os registros que foram marcados como deletados.

Parâmetros:

- $limit: Quantidade máxima de registros a retornar. Se null, retorna todos.
- $offset: Quantidade de registros a pular antes de começar a retornar. Se null, começa do primeiro.

Retorno: Array contendo apenas os registros deletados.

Rota correspondente: GET /api/v1/objeto/only-deleted

Este método aplica um filtro WHERE para retornar apenas registros onde deleted_at não é NULL. Útil para listar registros que podem ser restaurados ou para auditoria de exclusões.

### findWithDeleted(int $id): ?array

Busca um registro específico por ID, incluindo se estiver marcado como deletado.

Parâmetros:

- $id: ID do registro a ser buscado.

Retorno: Array com os dados do registro se encontrado, ou null se não existir.

Rota correspondente: POST /api/v1/objeto/{id}/with-deleted

Este método permite recuperar dados de um registro específico mesmo que ele tenha sido deletado logicamente. Diferente do método find() nativo que ignora registros deletados, este método busca independentemente do status de deleted_at. Essencial para operações de restauração ou visualização de histórico.

### restore(int $id): bool

Restaura um registro que foi marcado como deletado, tornando-o ativo novamente.

Parâmetros:

- $id: ID do registro a ser restaurado.

Retorno: true se a restauração foi bem sucedida, false caso contrário.

Rota correspondente: PATCH /api/v1/objeto/{id}/restore

Este método remove o valor do campo deleted_at, definindo-o como NULL novamente. Isso faz com que o registro volte a aparecer nas buscas normais e seja considerado ativo pelo sistema. A operação não afeta nenhum outro campo do registro.

### hardDelete(int $id): bool

Remove permanentemente um registro do banco de dados.

Parâmetros:

- $id: ID do registro a ser deletado permanentemente.

Retorno: true se a exclusão foi bem sucedida, false caso contrário.

Rota correspondente: DELETE /api/v1/objeto/{id}/hard

Este método executa uma exclusão física do registro, removendo-o permanentemente da tabela. Mesmo com soft delete ativado no Model, este método força a exclusão permanente através do segundo parâmetro true do método delete() nativo. Esta operação é irreversível e deve ser usada com extrema cautela, geralmente restrita a administradores de alto nível.

### clearDeleted(): int

Remove permanentemente todos os registros que estão marcados como deletados.

Parâmetros: Nenhum.

Retorno: Número inteiro representando quantos registros foram removidos permanentemente.

Rota correspondente: DELETE /api/v1/objeto/clear

Este método realiza uma limpeza em massa, executando hard delete em todos os registros onde deleted_at não é NULL. Primeiro conta quantos registros serão afetados, depois executa a exclusão permanente e retorna a contagem. Útil para manutenção periódica do banco de dados, liberando espaço de registros que não precisam mais ser mantidos.

### search(array $filters = [], array $options = []): array

Realiza busca avançada com múltiplos filtros e opções.

Parâmetros:

- $filters: Array de filtros a serem aplicados. Pode ser simples ['campo' => 'valor'] ou complexo com operadores ['campo' => ['operator' => 'like', 'value' => 'texto']].
- $options: Array de opções como ordenação, limite, offset e inclusão de deletados.

Retorno: Array com os registros que atendem aos critérios de busca.

Rota correspondente: POST /api/v1/objeto/search

Este é o método mais complexo e versátil do Model. Permite realizar buscas extremamente flexíveis combinando múltiplos critérios. Os filtros suportam diversos operadores: igual (padrão), like/contains para busca parcial, starts_with para busca no início do texto, ends_with para busca no final, in e not_in para listas de valores, operadores de comparação maior que, menor que, diferente, etc. As opções permitem ordenar resultados por qualquer campo em ordem crescente ou decrescente, limitar quantidade de resultados, aplicar offset para paginação, e incluir registros deletados na busca através de 'with_deleted' => true.

### getColumnsMetadata(): array

Obtém metadados detalhados de todas as colunas da tabela.

Parâmetros: Nenhum.

Retorno: Array contendo informações detalhadas de cada coluna.

Rota correspondente: GET /api/v1/objeto/columns

Este método conecta diretamente ao banco de dados através do DBGroup configurado e utiliza o método getFieldData() nativo do CodeIgniter para obter informações completas sobre a estrutura da tabela. Para cada coluna, retorna: COLUMN_NAME com o nome do campo, COLUMN_TYPE com o tipo de dado SQL, IS_NULLABLE indicando se aceita valores NULL, COLUMN_KEY indicando se é chave primária (PRI) ou vazia se não for chave, COLUMN_DEFAULT com o valor padrão definido no banco ou null, MAX_LENGTH com o tamanho máximo permitido se aplicável. Extremamente útil para gerar interfaces dinâmicas, documentação automática ou validações baseadas na estrutura real do banco.

### getColumnNames(): array

Obtém apenas os nomes das colunas da tabela.

Parâmetros: Nenhum.

Retorno: Array simples contendo strings com os nomes das colunas.

Rota correspondente: GET /api/v1/objeto/column-names

Este método é uma versão simplificada do getColumnsMetadata(), retornando apenas os nomes dos campos sem informações adicionais. Conecta ao banco através do DBGroup, obtém os metadados e usa array_map para extrair apenas a propriedade 'name' de cada campo. Útil quando se precisa apenas saber quais campos existem na tabela, sem necessidade de informações detalhadas sobre tipos e configurações.

### exists(int $id): bool

Verifica se um registro com determinado ID existe na tabela.

Parâmetros:

- $id: ID do registro a ser verificado.

Retorno: true se o registro existe, false caso contrário.

Rota correspondente: Nenhuma rota direta, método auxiliar para uso interno.

Este método utiliza o find() nativo do CodeIgniter e verifica se o resultado é diferente de null. Respeita o soft delete, ou seja, retorna false para registros que foram deletados logicamente. É um método auxiliar conveniente para validações em Services ou Controllers, evitando a necessidade de buscar o registro completo apenas para verificar sua existência.

### countAllWithDeleted(): int

Conta o total de registros na tabela, incluindo os deletados.

Parâmetros: Nenhum.

Retorno: Número inteiro representando a contagem total.

Rota correspondente: Nenhuma rota direta, método auxiliar para uso interno.

Este método ignora o filtro automático de soft delete do CodeIgniter e conta absolutamente todos os registros presentes fisicamente na tabela, independentemente do valor de deleted_at. Útil para estatísticas completas, relatórios administrativos ou monitoramento de crescimento real da base de dados.

### countOnlyDeleted(): int

Conta apenas os registros que foram marcados como deletados.

Parâmetros: Nenhum.

Retorno: Número inteiro representando quantos registros estão deletados.

Rota correspondente: Nenhuma rota direta, método auxiliar para uso interno.

Este método aplica um filtro WHERE para contar apenas registros onde deleted_at não é NULL. Útil para monitorar a quantidade de registros que podem ser restaurados ou permanentemente excluídos, ajudar em decisões de limpeza de banco de dados, ou gerar relatórios de exclusões realizadas.

---

## Métodos Nativos Herdados (Não Customizados)

O ResourceModel herda todos os métodos nativos da classe Model do CodeIgniter 4.6. Os principais métodos herdados que podem ser utilizados diretamente são:

### find($id = null)

Busca um ou mais registros por ID. Se passar um ID, retorna um único registro. Se passar um array de IDs, retorna múltiplos registros. Se não passar parâmetro, retorna todos os registros ativos (respeitando soft delete).

### findAll(int $limit = 0, int $offset = 0)

Busca todos os registros ativos da tabela. Parâmetros opcionais de limite e offset para paginação.

### insert($data)

Insere um novo registro na tabela. Retorna o ID do registro criado se bem sucedido, ou false em caso de erro.

### update($id = null, $data = null)

Atualiza um ou mais registros. Se passar ID como primeiro parâmetro, atualiza apenas aquele registro. Se passar array de IDs, atualiza múltiplos registros.

### delete($id = null, bool $purge = false)

Deleta um registro. Se $purge for false (padrão) e soft delete estiver ativo, faz soft delete. Se $purge for true, faz hard delete permanente.

### save($data)

Método inteligente que decide automaticamente se deve fazer insert ou update baseado na presença do campo de chave primária nos dados.

### first()

Retorna o primeiro registro encontrado.

### builder()

Retorna uma instância do Query Builder do CodeIgniter para construir queries customizadas complexas.

---

## Considerações Importantes

### Compatibilidade

Todos os recursos utilizados neste Model são 100% nativos do CodeIgniter 4.6. Não há overrides de métodos nativos que possam causar problemas de compatibilidade ou comportamentos inesperados.

### Segurança

A propriedade $allowedFields protege contra vulnerabilidades de mass assignment. A propriedade $hiddenFields (quando processada pela Library de formatação) protege contra exposição de dados sensíveis.

### Soft Delete

O sistema de soft delete está totalmente ativado e configurado. Métodos nativos como find(), findAll() e countAllResults() automaticamente ignoram registros deletados. Os métodos customizados com sufixo "WithDeleted" ou "OnlyDeleted" permitem acesso explícito a estes registros quando necessário.

### Type Casting

O array $casts garante que os tipos de dados sejam consistentes. Campos numéricos sempre retornam como números, campos de data sempre retornam como objetos Time do CodeIgniter, facilitando manipulações e evitando bugs relacionados a tipos de dados.

### Múltiplas Conexões

O sistema está preparado para trabalhar com três diferentes grupos de conexão de banco de dados através da propriedade $DBGroup. Cada Model pode facilmente ser configurado para usar qualquer uma das três conexões disponíveis.

### Extensibilidade

Este Model serve como base para todos os outros Models do sistema. Novos Models devem estender ResourceModel e apenas ajustar as propriedades básicas ($table, $DBGroup, $allowedFields, $casts, $hiddenFields) conforme necessário. Todos os métodos customizados já estarão disponíveis automaticamente.

### Performance

Os métodos foram otimizados para executar apenas as queries necessárias. Métodos de contagem usam countAllResults() que é mais eficiente que buscar todos os registros e contar no PHP. O uso do Query Builder garante queries parametrizadas e seguras contra SQL Injection.

---

## Exemplo de Uso em Outro Model

Para criar um novo Model para outra tabela, basta estender ResourceModel e configurar as propriedades necessárias:

Criar arquivo: app/Models/v1/ProductModel.php

Definir namespace: App\Models\v1

Estender: class ProductModel extends ResourceModel

Configurar $DBGroup conforme o banco de dados correto.

Configurar $table com o nome da tabela no banco.

Configurar $allowedFields com os campos que podem ser preenchidos.

Configurar $casts com os tipos de dados apropriados.

Configurar $hiddenFields se houver campos sensíveis.

Todos os métodos customizados (findAllWithDeleted, restore, search, etc) estarão automaticamente disponíveis sem necessidade de reescrevê-los.

---

## Conclusão

O ResourceModel foi desenvolvido com foco total em compatibilidade, segurança e profissionalismo. Utiliza exclusivamente recursos nativos do CodeIgniter 4.6, garantindo estabilidade e manutenibilidade a longo prazo. Os métodos customizados seguem as melhores práticas e não interferem com funcionalidades nativas do framework. A documentação completa garante que qualquer desenvolvedor possa entender e utilizar o Model corretamente.

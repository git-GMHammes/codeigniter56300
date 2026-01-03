<?php

namespace App\Services\v1;

abstract class BaseManagerService
{
    protected $model;

    /**
     * Executa método do model com try-catch automático
     */
    protected function execute(callable $callback, ?string $errorMessage = null): array
    {
        try {
            $result = $callback();

            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $errorMessage ?? $e->getMessage()
            ];
        }
    }

    /**
     * Executa método do model e valida se resultado não é vazio
     */
    protected function executeWithValidation(callable $callback, string $notFoundMessage = 'Registro não encontrado.'): array
    {
        try {
            $result = $callback();

            if (!$result) {
                return [
                    'success' => false,
                    'message' => $notFoundMessage
                ];
            }

            return [
                'success' => true,
                'data' => $result
            ];
        } catch (\Throwable $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Retorna resposta de erro
     */
    protected function errorResponse(string $message, $data = null): array
    {
        return [
            'success' => false,
            'message' => $message,
            'data' => $data
        ];
    }

    /**
     * Retorna resposta de sucesso
     */
    protected function successResponse($data, ?string $message = null): array
    {
        $response = [
            'success' => true,
            'data' => $data
        ];

        if ($message) {
            $response['message'] = $message;
        }

        return $response;
    }

    # ============================================================
    # MÉTODOS DE TRATAMENTO DE ERROS DE FOREIGN KEY
    # ============================================================

    /**
     * Verifica se a exceção é erro de foreign key constraint
     * 
     * @param \Throwable $e
     * @return bool
     */
    protected function isForeignKeyError(\Throwable $e): bool
    {
        $message = $e->getMessage();

        return (
            strpos($message, 'foreign key constraint fails') !== false ||
            strpos($message, 'Cannot delete or update a parent row') !== false ||
            strpos($message, 'CONSTRAINT') !== false ||
            strpos($message, 'FOREIGN KEY') !== false
        );
    }

    /**
     * Extrai informações do erro de foreign key
     * 
     * @param \Throwable $e
     * @return array|null
     */
    protected function parseForeignKeyError(\Throwable $e): ?array
    {
        $message = $e->getMessage();

        // Padrão: Cannot delete or update a parent row: a foreign key constraint fails 
        // (`database`.`table`, CONSTRAINT `constraint_name` FOREIGN KEY (`column`) REFERENCES `parent_table` (`parent_column`))

        $pattern = '/`([^`]+)`\.`([^`]+)`.*CONSTRAINT `([^`]+)`.*FOREIGN KEY.*\(`([^`]+)`\)/i';

        if (preg_match($pattern, $message, $matches)) {
            return [
                'database' => $matches[1],
                'dependent_table' => $matches[2],
                'constraint_name' => $matches[3],
                'dependent_column' => $matches[4]
            ];
        }

        return null;
    }

    /**
     * Cria resposta detalhada para erro de foreign key
     * 
     * @param int $id ID que tentou deletar
     * @param array|null $dependencies Lista de dependências (do model->checkForeignKeyDependencies)
     * @param \Throwable|null $exception Exceção original (opcional)
     * @return array
     */
    protected function foreignKeyErrorResponse(int $id, ?array $dependencies = null, ?\Throwable $exception = null): array
    {
        // Se não temos dependências mas temos exceção, tenta extrair da mensagem de erro
        if (empty($dependencies) && $exception) {
            $parsedError = $this->parseForeignKeyError($exception);

            if ($parsedError) {
                $message = sprintf(
                    'Não é possível excluir o registro (ID: %d) pois existem registros relacionados na tabela "%s" (coluna: %s). ' .
                    'É necessário remover ou atualizar os registros dependentes antes de prosseguir com a exclusão.',
                    $id,
                    $parsedError['dependent_table'],
                    $parsedError['dependent_column']
                );

                return [
                    'success' => false,
                    'message' => $message,
                    'error_type' => 'foreign_key_constraint',
                    'data' => [
                        'id' => $id,
                        'constraint' => [
                            'table' => $parsedError['dependent_table'],
                            'column' => $parsedError['dependent_column'],
                            'constraint_name' => $parsedError['constraint_name']
                        ],
                        'suggestions' => [
                            'Remova ou atualize os registros da tabela "' . $parsedError['dependent_table'] . '" que referenciam este ID',
                            'Considere usar soft delete (DELETE sem /hard) ao invés de exclusão permanente',
                            'Verifique se a constraint permite CASCADE DELETE para exclusão automática'
                        ]
                    ]
                ];
            }
        }

        // Se temos dependências detalhadas do model
        if (!empty($dependencies)) {
            $totalRelated = array_sum(array_column($dependencies, 'count'));

            $tables = [];
            foreach ($dependencies as $dep) {
                $tables[] = sprintf(
                    '"%s" (%d registro%s)',
                    $dep['table'],
                    $dep['count'],
                    $dep['count'] > 1 ? 's' : ''
                );
            }

            $tablesText = implode(', ', $tables);

            $message = sprintf(
                'Não é possível excluir o registro (ID: %d) pois existem %d registro%s relacionado%s na%s tabela%s: %s. ' .
                'É necessário remover ou atualizar os registros dependentes antes de prosseguir com a exclusão.',
                $id,
                $totalRelated,
                $totalRelated > 1 ? 's' : '',
                $totalRelated > 1 ? 's' : '',
                count($dependencies) > 1 ? 's' : '',
                count($dependencies) > 1 ? 's' : '',
                $tablesText
            );

            $suggestions = [
                'Considere usar soft delete (DELETE sem /hard) ao invés de exclusão permanente'
            ];

            foreach ($dependencies as $dep) {
                if ($dep['delete_rule'] === 'NO ACTION' || $dep['delete_rule'] === 'RESTRICT') {
                    $suggestions[] = sprintf(
                        'Remova os %d registro%s da tabela "%s" antes de deletar este registro',
                        $dep['count'],
                        $dep['count'] > 1 ? 's' : '',
                        $dep['table']
                    );
                }
            }

            return [
                'success' => false,
                'message' => $message,
                'error_type' => 'foreign_key_constraint',
                'data' => [
                    'id' => $id,
                    'total_related_records' => $totalRelated,
                    'dependencies' => $dependencies,
                    'suggestions' => $suggestions
                ]
            ];
        }

        // Fallback genérico
        return [
            'success' => false,
            'message' => sprintf(
                'Não é possível excluir o registro (ID: %d) devido a restrições de integridade referencial. ' .
                'Existem registros relacionados em outras tabelas.',
                $id
            ),
            'error_type' => 'foreign_key_constraint',
            'data' => [
                'id' => $id,
                'suggestions' => [
                    'Considere usar soft delete (DELETE sem /hard) ao invés de exclusão permanente',
                    'Verifique quais tabelas possuem foreign keys para esta tabela'
                ]
            ]
        ];
    }

    /**
     * Verifica dependências antes de deletar e retorna erro detalhado se encontrar
     * 
     * @param int $id ID a verificar
     * @return array|null Retorna array de erro se houver dependências, null se pode deletar
     */
    protected function checkDependenciesBeforeDelete(int $id): ?array
    {
        if (!method_exists($this->model, 'checkForeignKeyDependencies')) {
            return null;
        }

        $dependencies = $this->model->checkForeignKeyDependencies($id);

        if (!empty($dependencies)) {
            return $this->foreignKeyErrorResponse($id, $dependencies);
        }

        return null;
    }

    
}
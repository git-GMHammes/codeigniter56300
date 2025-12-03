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
}
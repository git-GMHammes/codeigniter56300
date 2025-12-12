<?php

namespace App\Controllers\API\v1;

use CodeIgniter\RESTful\ResourceController;
use App\Libraries\ApiResponse;

abstract class BaseManagerController extends ResourceController
{
    protected $service;
    protected $apiResponse;

    public function __construct()
    {
        $this->apiResponse = new ApiResponse();
    }

    /**
     * Valida se ID foi fornecido
     */
    protected function validateId($id)
    {
        if (!$id) {
            return $this->apiResponse->badRequest('ID não fornecido.');
        }
        return null;
    }

    /**
     * Extrai parâmetros de paginação da query string
     */
    protected function getPaginationParams(): array
    {
        return [
            'page' => max(1, (int) ($this->request->getGet('page') ?? 1)),
            'perPage' => min(100, max(1, (int) ($this->request->getGet('limit') ?? 15)))
        ];
    }

    /**
     * Executa método do service e retorna resposta padronizada
     */
    protected function executeService(callable $serviceCall, string $successMessage, bool $withPagination = false, int $successCode = 200)
    {
        $result = $serviceCall();

        if (!$result['success']) {
            return $this->apiResponse->notFound($result['message']);
        }

        if ($withPagination && isset($result['data']['meta'])) {
            $this->apiResponse->setPagination($result['data']['meta']);
            $this->apiResponse->setUrlMetadata();
            return $this->apiResponse->success($result['data']['data'], $successMessage);
        }

        $this->apiResponse->setUrlMetadata();

        if ($successCode === 201) {
            return $this->apiResponse->created($result['data'], $successMessage);
        }

        return $this->apiResponse->success($result['data'], $successMessage);
    }

    /**
     * Executa validação e retorna erros se houver
     */
    protected function validateRequest($request, string $method): ?array
    {
        
        $validation = $request->$method(); // Aqui um ERRO GRAVE ocorre

        if (!$validation['valid']) {
            return [
                'hasError' => true,
                'response' => $this->apiResponse->validationError(
                    $validation['errors'],
                    'Dados de entrada inválidos.'
                )
            ];
        }

        if (empty($validation['data']) || !is_array($validation['data'])) {
            return [
                'hasError' => true,
                'response' => $this->apiResponse->internalError('Erro ao processar dados de entrada.')
            ];
        }

        return [
            'hasError' => false,
            'data' => $validation['data']
        ];
    }
}
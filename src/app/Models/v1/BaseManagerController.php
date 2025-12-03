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
    
    // Validar ID
    protected function validateId($id)
    {
        if (!$id) {
            return $this->apiResponse->badRequest('ID não fornecido.');
        }
        return null;
    }
    
    // Extrair paginação
    protected function getPaginationParams(): array
    {
        return [
            'page' => max(1, (int) ($this->request->getGet('page') ?? 1)),
            'perPage' => min(100, max(1, (int) ($this->request->getGet('limit') ?? 15)))
        ];
    }
    
    // Executar service e retornar resposta
    protected function executeService(callable $serviceCall, string $successMessage, bool $withPagination = false)
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
        return $this->apiResponse->success($result['data'], $successMessage);
    }
}
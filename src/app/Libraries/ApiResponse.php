<?php

namespace App\Libraries;

use CodeIgniter\HTTP\ResponseInterface;

/**
 * ApiResponse Library
 * 
 * Biblioteca para padronização de respostas JSON da API
 * Segue o padrão definido com http_code, status, message, api_data, data e metadata
 * 
 * @package    App\Libraries
 * @author     Gustavo - HABILIDADE
 * @version    1.0.0
 * @since      23/11/2025
 */
class ApiResponse
{
    /**
     * Código HTTP da resposta
     * @var int
     */
    protected int $httpCode;

    /**
     * Status da resposta (success, error, warning)
     * @var string
     */
    protected string $status;

    /**
     * Mensagem da resposta
     * @var string
     */
    protected string $message;

    /**
     * Versão da API
     * @var string
     */
    protected string $apiVersion;

    /**
     * Data e hora da resposta
     * @var string
     */
    protected string $dateTime;

    /**
     * Dados da resposta
     * @var mixed
     */
    protected $data;

    /**
     * Metadados da resposta
     * @var array
     */
    protected array $metadata;

    /**
     * Dados de paginação
     * @var array
     */
    protected array $pagination;

    /**
     * Instância do Response do CodeIgniter
     * @var ResponseInterface
     */
    protected ResponseInterface $response;

    /**
     * Mapeamento de códigos HTTP para mensagens padrão
     * @var array
     */
    protected array $httpMessages = [
        // 2xx Success
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        204 => 'No content',

        // 4xx Client Error
        400 => 'Bad request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not found',
        405 => 'Method not allowed',
        409 => 'Conflict',
        422 => 'Unprocessable entity',
        429 => 'Too many requests',

        // 5xx Server Error
        500 => 'Internal server error',
        501 => 'Not implemented',
        502 => 'Bad gateway',
        503 => 'Service unavailable',
        504 => 'Gateway timeout',
    ];

    /**
     * Construtor
     */
    public function __construct()
    {
        $this->response = service('response');
        $this->apiVersion = '1.0.0';
        $this->dateTime = date('Y-m-d H:i:s');
        $this->httpCode = 200;
        $this->status = 'success';
        $this->message = 'Operation completed successfully.';
        $this->data = [];
        $this->metadata = [];
        $this->pagination = [];
    }

    // ========================================================================
    // SETTERS
    // ========================================================================

    /**
     * Define o código HTTP
     * 
     * @param int $code
     * @return self
     */
    public function setHttpCode(int $code): self
    {
        $this->httpCode = $code;

        // Define automaticamente o status baseado no código
        if ($code >= 200 && $code < 300) {
            $this->status = 'success';
        } elseif ($code >= 400 && $code < 600) {
            $this->status = 'error';
        }

        // Define mensagem padrão se não foi definida
        if ($this->message === 'Operation completed successfully.' && isset($this->httpMessages[$code])) {
            $this->message = $this->httpMessages[$code];
        }

        return $this;
    }

    /**
     * Define o status (success, error, warning)
     * 
     * @param string $status
     * @return self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Define a mensagem
     * 
     * @param string $message
     * @return self
     */
    public function setMessage(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Define a versão da API
     * 
     * @param string $version
     * @return self
     */
    public function setApiVersion(string $version): self
    {
        $this->apiVersion = $version;
        return $this;
    }

    /**
     * Define os dados da resposta
     * 
     * @param mixed $data
     * @return self
     */
    public function setData($data): self
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Define os metadados da resposta
     * 
     * @param array $metadata
     * @return self
     */
    public function setMetadata(array $metadata): self
    {
        $this->metadata = $metadata;
        return $this;
    }

    /**
     * Define dados de paginação e metadados separados
     * 
     * @param array $paginationData Array com dados do Model (meta)
     * @return self
     */
    public function setPagination(array $paginationData): self
    {
        // Pagination (navegação)
        $this->pagination = [
            'total_pages' => $paginationData['total_pages'] ?? 0,
            'from' => $paginationData['from'] ?? 0,
            'to' => $paginationData['to'] ?? 0,
            'has_next_page' => $paginationData['has_next_page'] ?? false,
            'has_previous_page' => $paginationData['has_previous_page'] ?? false,
            'links' => $paginationData['links'] ?? []
        ];

        // Metadata (informações técnicas)
        $this->metadata = [
            'page' => $paginationData['current_page'] ?? 1,
            'limit' => $paginationData['per_page'] ?? 15,
            'total' => $paginationData['total'] ?? 0,
        ];

        return $this;
    }

    /**
     * Adiciona URL nos metadados
     * 
     * @param string|null $baseUrl
     * @param array|null $uriSegments
     * @return self
     */
    public function setUrlMetadata(?string $baseUrl = null, ?array $uriSegments = null): self
    {
        $request = service('request');

        $this->metadata['url'] = [
            'base_url' => $baseUrl ?? base_url(),
            'get_uri' => $uriSegments ?? $request->getUri()->getSegments(),
        ];

        return $this;
    }

    // ========================================================================
    // GETTERS
    // ========================================================================

    /**
     * Retorna o código HTTP
     * 
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    /**
     * Retorna o status
     * 
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Retorna a mensagem
     * 
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Retorna os dados
     * 
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Retorna os metadados
     * 
     * @return array
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    // ========================================================================
    // MÉTODOS DE CONSTRUÇÃO
    // ========================================================================

    /**
     * Constrói o array de resposta completo
     * 
     * @return array
     */
    protected function buildResponse(): array
    {
        $response = [
            'http_code' => $this->httpCode,
            'status' => $this->status,
            'message' => $this->message,
            'api_data' => [
                'version' => $this->apiVersion,
                'date_time' => $this->dateTime,
            ],
            'data' => $this->data,
        ];

        // Adicionar pagination se existir
        if (!empty($this->pagination)) {
            $response['pagination'] = $this->pagination;
        }

        // Adicionar metadata se existir
        if (!empty($this->metadata)) {
            $response['metadata'] = $this->metadata;
        }

        return $response;
    }

    /**
     * Retorna a resposta JSON
     * 
     * @return ResponseInterface
     */
    public function send(): ResponseInterface
    {
        return $this->response
            ->setStatusCode($this->httpCode)
            ->setJSON($this->buildResponse());
    }

    /**
     * Retorna apenas o array (sem enviar resposta)
     * Útil para testes
     * 
     * @return array
     */
    public function toArray(): array
    {
        return $this->buildResponse();
    }

    // ========================================================================
    // MÉTODOS DE DECISÃO (ATALHOS)
    // ========================================================================

    /**
     * Resposta de sucesso (200 OK)
     * 
     * @param mixed $data
     * @param string|null $message
     * @return ResponseInterface
     */
    public function success($data = [], ?string $message = null): ResponseInterface
    {
        $this->setHttpCode(200);
        $this->setData($data);

        if ($message !== null) {
            $this->setMessage($message);
        } else {
            $this->setMessage('Operation completed successfully.');
        }

        $this->setUrlMetadata();

        return $this->send();
    }

    /**
     * Resposta de criação bem-sucedida (201 Created)
     * 
     * @param mixed $data
     * @param string|null $message
     * @return ResponseInterface
     */
    public function created($data = [], ?string $message = null): ResponseInterface
    {
        $this->setHttpCode(201);
        $this->setData($data);

        if ($message !== null) {
            $this->setMessage($message);
        } else {
            $this->setMessage('Resource created successfully.');
        }

        $this->setUrlMetadata();

        return $this->send();
    }

    /**
     * Resposta sem conteúdo (204 No Content)
     * 
     * @return ResponseInterface
     */
    public function noContent(): ResponseInterface
    {
        return $this->response->setStatusCode(204);
    }

    /**
     * Resposta de erro de validação (422 Unprocessable Entity)
     * 
     * @param array $errors
     * @param string|null $message
     * @return ResponseInterface
     */
    public function validationError(array $errors, ?string $message = null): ResponseInterface
    {
        $this->setHttpCode(422);

        if ($message !== null) {
            $this->setMessage($message);
        } else {
            $this->setMessage('Validation failed.');
        }

        $this->setData([
            'validation' => $errors
        ]);

        $this->setUrlMetadata();

        return $this->send();
    }

    /**
     * Resposta de erro genérico (400 Bad Request)
     * 
     * @param string|null $message
     * @param mixed $data
     * @return ResponseInterface
     */
    public function badRequest(?string $message = null, $data = []): ResponseInterface
    {
        $this->setHttpCode(400);

        if ($message !== null) {
            $this->setMessage($message);
        } else {
            $this->setMessage('Bad request.');
        }

        $this->setData($data);
        $this->setUrlMetadata();

        return $this->send();
    }

    /**
     * Resposta de não autorizado (401 Unauthorized)
     * 
     * @param string|null $message
     * @return ResponseInterface
     */
    public function unauthorized(?string $message = null): ResponseInterface
    {
        $this->setHttpCode(401);

        if ($message !== null) {
            $this->setMessage($message);
        } else {
            $this->setMessage('Unauthorized.');
        }

        $this->setData([]);
        $this->setUrlMetadata();

        return $this->send();
    }

    /**
     * Resposta de proibido (403 Forbidden)
     * 
     * @param string|null $message
     * @return ResponseInterface
     */
    public function forbidden(?string $message = null): ResponseInterface
    {
        $this->setHttpCode(403);

        if ($message !== null) {
            $this->setMessage($message);
        } else {
            $this->setMessage('Forbidden.');
        }

        $this->setData([]);
        $this->setUrlMetadata();

        return $this->send();
    }

    /**
     * Resposta de não encontrado (404 Not Found)
     * 
     * @param string|null $message
     * @return ResponseInterface
     */
    public function notFound(?string $message = null): ResponseInterface
    {
        $this->setHttpCode(404);

        if ($message !== null) {
            $this->setMessage($message);
        } else {
            $this->setMessage('Resource not found.');
        }

        $this->setData([]);
        $this->setUrlMetadata();

        return $this->send();
    }

    /**
     * Resposta de conflito (409 Conflict)
     * 
     * @param string|null $message
     * @param mixed $data
     * @return ResponseInterface
     */
    public function conflict(?string $message = null, $data = []): ResponseInterface
    {
        $this->setHttpCode(409);

        if ($message !== null) {
            $this->setMessage($message);
        } else {
            $this->setMessage('Conflict detected.');
        }

        $this->setData($data);
        $this->setUrlMetadata();

        return $this->send();
    }

    /**
     * Resposta de erro interno do servidor (500 Internal Server Error)
     * 
     * @param string|null $message
     * @param mixed $data
     * @return ResponseInterface
     */
    public function internalError(?string $message = null, $data = []): ResponseInterface
    {
        $this->setHttpCode(500);

        if ($message !== null) {
            $this->setMessage($message);
        } else {
            $this->setMessage('Internal server error.');
        }

        $this->setData($data);
        $this->setUrlMetadata();

        return $this->send();
    }

    /**
     * Resposta de aviso (200 OK com status warning)
     * 
     * @param mixed $data
     * @param array $warnings
     * @param string|null $message
     * @return ResponseInterface
     */
    public function warning($data = [], array $warnings = [], ?string $message = null): ResponseInterface
    {
        $this->setHttpCode(200);
        $this->setStatus('warning');

        if ($message !== null) {
            $this->setMessage($message);
        } else {
            $this->setMessage('Operation completed with warnings.');
        }

        $responseData = [];

        if (!empty($data)) {
            $responseData['results'] = $data;
        }

        if (!empty($warnings)) {
            $responseData['warnings'] = $warnings;
        }

        $this->setData($responseData);
        $this->setUrlMetadata();

        return $this->send();
    }

    /**
     * Resposta de listagem com paginação
     * 
     * @param array $data
     * @param int $totalRecords
     * @param int $perPage
     * @param int $currentPage
     * @param string|null $message
     * @return ResponseInterface
     */
    public function paginated(
        array $data,
        int $totalRecords,
        int $perPage,
        int $currentPage,
        ?string $message = null
    ): ResponseInterface {
        $this->setHttpCode(200);
        $this->setData($data);

        if ($message !== null) {
            $this->setMessage($message);
        } else {
            $this->setMessage('Operation completed successfully.');
        }

        $this->setPagination($totalRecords, $perPage, $currentPage);

        return $this->send();
    }

    /**
     * Método genérico para qualquer código HTTP
     * 
     * @param int $httpCode
     * @param mixed $data
     * @param string|null $message
     * @param string|null $status
     * @return ResponseInterface
     */
    public function custom(
        int $httpCode,
        $data = [],
        ?string $message = null,
        ?string $status = null
    ): ResponseInterface {
        $this->setHttpCode($httpCode);
        $this->setData($data);

        if ($status !== null) {
            $this->setStatus($status);
        }

        if ($message !== null) {
            $this->setMessage($message);
        }

        $this->setUrlMetadata();

        return $this->send();
    }
}
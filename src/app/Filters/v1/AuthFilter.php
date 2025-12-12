<?php

namespace App\Filters\v1;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Config\Services;
use App\Services\v1\Auth\TokenService;
use App\Models\v1\UserManagement\ResourceModel;
use App\Libraries\v1\CurrentUser;

class AuthFilter implements FilterInterface
{
    protected TokenService $tokenService;
    protected ResourceModel $userModel;

    public function __construct()
    {
        $this->tokenService = new TokenService();
        $this->userModel = new ResourceModel();
    }

    /**
     * Executado antes da controller
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getServer('HTTP_AUTHORIZATION') ?? $request->getHeaderLine('Authorization');

        if (empty($authHeader)) {
            return $this->unauthorizedResponse('Token de autenticação não fornecido.');
        }

        // Espera: "Bearer <token>"
        if (!preg_match('/Bearer\s+(.+)/i', $authHeader, $matches)) {
            return $this->unauthorizedResponse('Cabeçalho Authorization inválido.');
        }

        $token = trim($matches[1]);

        // Decodifica token (retorna stdClass ou null)
        $payload = $this->tokenService->decodeToken($token);

        if (!$payload || !isset($payload->sub)) {
            return $this->unauthorizedResponse('Token inválido ou expirado.');
        }

        // Carrega usuário pelo sub (id)
        $userId = (int) $payload->sub;
        $user = $this->userModel->findWithDeleted($userId);

        if (!$user) {
            return $this->unauthorizedResponse('Usuário não encontrado.');
        }

        // Se usuário está soft-deleted, bloquear (se for o caso)
        if (!empty($user['deleted_at'])) {
            return $this->unauthorizedResponse('Conta desativada.');
        }

        // Remove campos sensíveis (caso model não faça)
        unset($user['password']);

        // Disponibiliza usuário para o resto da aplicação
        CurrentUser::set($user);

        // Tudo ok -> continua
        return null;
    }

    /**
     * Executado depois da controller (não usado aqui)
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // nada
    }

    protected function unauthorizedResponse(string $message)
    {
        // Retorna JSON padronizado (ajuste para usar sua ApiResponse se quiser)
        $response = Services::response();
        $payload = [
            'http_code' => 401,
            'status' => 'error',
            'message' => $message,
            'api_data' => [
                'version' => '1.0.0',
                'date_time' => date('Y-m-d H:i:s')
            ],
            'data' => [],
            'metadata' => [
                'url' => [
                    'base_url' => base_url('/'),
                    'get_uri' => explode('/', trim(current_url(false), '/')),
                ]
            ]
        ];

        return $response
            ->setStatusCode(401)
            ->setContentType('application/json')
            ->setBody(json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}
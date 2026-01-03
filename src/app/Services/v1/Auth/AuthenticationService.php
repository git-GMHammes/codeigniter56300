<?php

namespace App\Services\v1\Auth;

use App\Services\v1\BaseManagerService;
use App\Models\v1\UserManagement\ResourceModel;

class AuthenticationService extends BaseManagerService
{
    protected $passwordService;
    protected $tokenService;

    public function __construct(PasswordService $passwordService, ?TokenService $tokenService = null)
    {
        $this->model = new ResourceModel();
        $this->passwordService = $passwordService;
        $this->tokenService = $tokenService;
    }

    /**
     * Realiza o login.
     * Entrada: ['user' => string, 'password' => string]
     * Saída padronizada: array compatível com BaseManagerService (success/data/message)
     */
    public function login(array $credentials): array
    {
        try {
            $user = $this->model->findByUser($credentials['user']);

            if (!$user) {
                return $this->errorResponse('Credenciais inválidas. Verifique seus dados.');
            }

            // Verifica se está soft deleted
            if (!empty($user['deleted_at'])) {
                return $this->errorResponse('Conta desativada.');
            }

            // Verifica senha via PasswordService
            if (!$this->passwordService->verify($credentials['password'], $user['password'])) {
                return $this->errorResponse('Credenciais inválidas. Verifique seus dados.');
            }

            // Remover campos sensíveis do retorno
            unset($user['password']);

            $responseData = [
                'user' => $user
            ];

            // Se tiver TokenService, gerar token JWT (opcional)
            if ($this->tokenService) {
                $token = $this->tokenService->createToken(['sub' => $user['id'], 'user' => $user['user']]);
                $responseData['token'] = $token;
            }

            return $this->successResponse($responseData, 'Login realizado com sucesso.');
        } catch (\Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
    }
}
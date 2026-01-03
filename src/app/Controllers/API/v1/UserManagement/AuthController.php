<?php

namespace App\Controllers\API\v1\UserManagement;

use App\Controllers\API\v1\BaseManagerController;
use App\Requests\v1\UserManagement\LoginRequest;
use App\Services\v1\Auth\AuthenticationService;
use App\Services\v1\Auth\PasswordService;
use App\Services\v1\Auth\TokenService;

class AuthController extends BaseManagerController
{
    protected $loginRequest;
    protected $authService;

    public function __construct()
    {
        parent::__construct();

        $this->loginRequest = new LoginRequest();

        // Instancia serviços auxiliares e o service de auth
        $passwordService = new PasswordService();
        $tokenService = new TokenService(); // pode ser opcional
        $this->authService = new AuthenticationService($passwordService, $tokenService);
    }

    # POST /api/v1/user-management/login
    public function login()
    {
        // Validar request
        $validation = $this->validateRequest($this->loginRequest, 'validateLogin');
        if ($validation['hasError']) {
            return $validation['response'];
        }

        // Delegar toda a lógica de autenticação para o service
        return $this->executeService(
            fn() => $this->authService->login($validation['data']),
            'Login realizado com sucesso.'
        );
    }
}
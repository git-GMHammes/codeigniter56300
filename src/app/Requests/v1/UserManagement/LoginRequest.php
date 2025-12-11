<?php

namespace App\Requests\v1\UserManagement;

use CodeIgniter\HTTP\IncomingRequest;

class LoginRequest
{
    # Request do CodeIgniter
    # @var IncomingRequest
    protected $request;

    # Servico de validacao
    # @var \CodeIgniter\Validation\Validation
    protected $validation;

    public function __construct()
    {
        $this->request = service('request');
        $this->validation = service('validation');
    }

    /**
     * Valida dados para login.
     * Retorna padronizado:
     *  - valid: bool
     *  - errors: array|null
     *  - data: array|null
     */
    public function validateLogin(): array
    {
        // Captura dados do request (JSON ou form)
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        if (!is_array($data)) {
            $data = [];
        }

        // Regras simples de login
        $rules = [
            'user' => 'required|string|max_length[100]',
            'password' => 'required|string'
        ];

        $messages = [
            'user' => [
                'required' => 'O campo usuário é obrigatório.',
                'string' => 'O campo usuário deve ser uma string.',
                'max_length' => 'O campo usuário deve ter no máximo 100 caracteres.'
            ],
            'password' => [
                'required' => 'O campo senha é obrigatório.',
                'string' => 'O campo senha deve ser uma string.'
            ]
        ];

        $this->validation->setRules($rules, $messages);

        if (!$this->validation->run($data)) {
            return [
                'valid' => false,
                'errors' => $this->validation->getErrors(),
                'data' => null
            ];
        }

        // Normaliza retorno com apenas os campos necessários
        $clean = [
            'user' => $data['user'],
            'password' => $data['password']
        ];

        return [
            'valid' => true,
            'errors' => null,
            'data' => $clean
        ];
    }
}
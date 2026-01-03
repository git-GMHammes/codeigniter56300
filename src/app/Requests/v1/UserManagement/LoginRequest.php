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
        $jsonError = null;
        $data = [];

        // Tenta obter JSON de forma segura — getJSON pode lançar exceção em caso de JSON inválido
        try {
            $json = $this->request->getJSON(true);
        } catch (\Throwable $e) {
            // captura erro (ex.: Syntax error) e registra para feedback de validação
            $json = null;
            $jsonError = $e->getMessage();
        }

        // Se getJSON devolveu array usamos ele, senão tentamos POST (form-data)
        if (is_array($json) && !empty($json)) {
            $data = $json;
        } else {
            $post = $this->request->getPost();
            if (is_array($post) && !empty($post)) {
                $data = $post;
            } else {
                $data = [];
            }
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

        // Executa validação
        if (!$this->validation->run($data)) {
            $errors = $this->validation->getErrors();

            // Se houve erro de JSON, acrescentamos info para o cliente (não exponha detalhes sensíveis)
            if ($jsonError !== null) {
                $errors['_json'] = 'Corpo JSON inválido: ' . $jsonError;
            }

            return [
                'valid' => false,
                'errors' => $errors,
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
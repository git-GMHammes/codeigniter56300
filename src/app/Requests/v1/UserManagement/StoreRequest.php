<?php

namespace App\Requests\v1\UserManagement;

use CodeIgniter\HTTP\IncomingRequest;

class StoreRequest
{
    # Request do CodeIgniter
    # @var IncomingRequest
    protected $request;

    # Servico de validacao
    # @var \CodeIgniter\Validation\Validation
    protected $validation;

    # Construtor
    public function __construct()
    {
        $this->request = service('request');
        $this->validation = service('validation');
    }

    # Valida dados para criacao de Usuario
    # @return array ['valid' => bool, 'errors' => array|null, 'data' => array|null]
    public function validateCreate(): array
    {
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        $config = [
            'table' => 'user_management',
            'connection' => DB_GROUP_001,
            'rules' => [
                'user' => 'required|min_length[6]|max_length[50]|is_unique[user_management.user]',
                'password' => 'required|min_length[8]|max_length[200]',
            ],
            'messages' => [
                'user' => [
                    'required' => 'O campo Usuario e obrigatorio.',
                    'min_length' => 'O Usuario deve ter no minimo 6 caracteres.',
                    'max_length' => 'O Usuario deve ter no maximo 50 caracteres.',
                    'is_unique' => 'Este Usuario ja esta cadastrado no sistema.',
                ],
                'password' => [
                    'required' => 'O campo Senha e obrigatorio.',
                    'min_length' => 'A Senha deve ter no minimo 8 caracteres.',
                    'max_length' => 'A Senha deve ter no maximo 200 caracteres.',
                ],
            ]
        ];

        $this->validation->setRules($config['rules'], $config['messages']);

        if (!$this->validation->run($data, null, $config['connection'])) {
            return [
                'valid' => false,
                'errors' => $this->validation->getErrors(),
                'data' => null
            ];
        }

        return [
            'valid' => true,
            'errors' => null,
            'data' => $data
        ];
    }
}
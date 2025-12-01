<?php

namespace App\Requests\v1\UserManagement;

use CodeIgniter\HTTP\IncomingRequest;

class ModifyRequest
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

    # Valida dados para atualizacao de Usuario
    # @return array ['valid' => bool, 'errors' => array|null, 'data' => array|null]
    public function validateUpdate(): array
    {
        // Captura dados do request
        $data = $this->request->getJSON(true) ?? $this->request->getRawInput();

        // ========================================================================
        // VALIDAÇÃO DE ID (OBRIGATÓRIO)
        // ========================================================================

        if (!isset($data['id']) || empty($data['id'])) {
            return [
                'valid' => false,
                'errors' => [
                    'id' => 'O campo ID é obrigatório para atualização.'
                ],
                'data' => null
            ];
        }

        // ========================================================================
        // CONFIGURAÇÃO DE VALIDAÇÃO
        // ========================================================================

        $config = [
            'table' => 'user_management',
            'connection' => DB_GROUP_001,
            'rules' => [
                'id' => 'required|numeric|is_not_unique[user_management.id]',
                'user' => 'permit_empty|string|max_length[50]|is_unique[user_management.user,id,{id}]',
                'password' => 'permit_empty|min_length[8]|max_length[200]',
            ],
            'messages' => [
                'id' => [
                    'required' => 'O campo ID é obrigatório.',
                    'numeric' => 'O campo ID deve ser numérico.',
                    'is_not_unique' => 'Registro não encontrado.'
                ],
                'user' => [
                    'string' => 'O campo usuário deve ser uma string.',
                    'max_length' => 'O campo usuário deve ter no máximo 50 caracteres.',
                    'is_unique' => 'Este usuário já está em uso.'
                ],
                'password' => [
                    'min_length' => 'A senha deve ter no mínimo 8 caracteres.',
                    'max_length' => 'A senha deve ter no máximo 200 caracteres.'
                ],
            ]
        ];

        // ========================================================================
        // VALIDAÇÃO COM CONEXÃO ESPECÍFICA
        // ========================================================================

        $this->validation->setRules($config['rules'], $config['messages']);

        // Executa validação NA CONEXÃO CORRETA
        if (!$this->validation->run($data, null, $config['connection'])) {
            return [
                'valid' => false,
                'errors' => $this->validation->getErrors(),
                'data' => null
            ];
        }

        // ========================================================================
        // REMOVE CAMPOS VAZIOS (atualização parcial)
        // ========================================================================

        $cleanData = [];
        
        foreach ($data as $field => $value) {
            // Mantém ID sempre
            if ($field === 'id') {
                $cleanData[$field] = $value;
                continue;
            }

            // Remove campos vazios (para atualização parcial)
            if ($value !== null && $value !== '') {
                $cleanData[$field] = $value;
            }
        }

        // ========================================================================
        // HASH DE PASSWORD (se vier)
        // ========================================================================

        if (isset($cleanData['password'])) {
            $cleanData['password'] = password_hash($cleanData['password'], PASSWORD_DEFAULT);
        }

        // ========================================================================
        // RETORNO
        // ========================================================================

        return [
            'valid' => true,
            'errors' => null,
            'data' => $cleanData
        ];
    }
}
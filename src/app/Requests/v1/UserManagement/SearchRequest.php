<?php

namespace App\Requests\v1\UserManagement;
use App\Validators\v1\UserManagement\StoreValidator;
use CodeIgniter\HTTP\IncomingRequest;

class SearchRequest
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
    public function validateSearch(): array
    {
        // Captura dados do request
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        // Remove password se vier (segurança)
        if (isset($data['password'])) {
            return [
                'valid' => false,
                'errors' => [
                    'Unprocessable Entity'
                ],
                'data' => [
                    'O campo X não é permitido para esta requisição',
                ]
            ];
        }

        // ========================================================================
        // CONFIGURAÃ‡ÃƒO DE VALIDAÃ‡ÃƒO
        // ========================================================================

        $config = [
            'table' => 'user_management',
            'connection' => DB_GROUP_001,
            'rules' => [
                'id' => 'permit_empty|numeric',
                'user' => 'permit_empty|string|max_length[50]',
                'created_at' => 'permit_empty|valid_date',
                'updated_at' => 'permit_empty|valid_date',
                'deleted_at' => 'permit_empty|valid_date',
            ],
            'messages' => [
                'id' => [
                    'numeric' => 'O campo ID deve ser numérico.'
                ],
                'user' => [
                    'string' => 'O campo usuário deve ser uma string.',
                    'max_length' => 'O campo usuário deve ter no máximo 50 caracteres.',
                ],
                'created_at' => [
                    'valid_date' => 'O campo created_at deve ser uma data válida.'
                ],
                'updated_at' => [
                    'valid_date' => 'O campo updated_at deve ser uma data válida.'
                ],
                'deleted_at' => [
                    'valid_date' => 'O campo deleted_at deve ser uma data válida.'
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
        // PREPARA DADOS PARA BUSCA (adiciona operador LIKE em campos texto)
        // ========================================================================

        $searchData = [];

        foreach ($data as $field => $value) {
            // Ignora campos vazios
            if ($value === null || $value === '') {
                continue;
            }

            // Campos de texto usam LIKE (busca parcial)
            if (in_array($field, ['user'])) {
                $searchData[$field] = [
                    'value' => $value,
                    'operator' => 'like'
                ];
            }
            // Campos numéricos e datas usam igualdade exata
            else {
                $searchData[$field] = $value;
            }
        }

        // ========================================================================
        // RETORNO
        // ========================================================================

        return [
            'valid' => true,
            'errors' => null,
            'data' => $searchData
        ];
    }
}
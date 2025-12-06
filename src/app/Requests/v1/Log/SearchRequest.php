<?php

namespace App\Requests\v1\Log;
use App\Rules\v1\Log\SearchRules;
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

        $config = SearchRules::get();

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
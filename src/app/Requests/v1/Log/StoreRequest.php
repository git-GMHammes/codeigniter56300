<?php

namespace App\Requests\v1\Log;
use App\Rules\v1\Log\StoreRules;
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
        // Captura dados do request
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        // ========================================================================
        // CONFIGURAÃ‡ÃƒO DE VALIDAÃ‡ÃƒO
        // ========================================================================

        $config = StoreRules::get();
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
        // HASH DE PASSWORD (OBRIGATÓRIO PARA SEGURANÇA)
        // ========================================================================

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // ========================================================================
        // RETORNO
        // ========================================================================

        return [
            'valid' => true,
            'errors' => null,
            'data' => $data
        ];
    }
}
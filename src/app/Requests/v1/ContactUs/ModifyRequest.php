<?php

namespace App\Requests\v1\ContactUs;

use App\Rules\v1\ContactUs\ModifyRules;
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

    # Valida dados para atualizacao de mensagem Fale Conosco
    # @return array ['valid' => bool, 'errors' => array|null, 'data' => array|null]
    public function validateUpdate(): array
    {
        // Captura dados do request:  respeita content-type para evitar exception em multipart/form-data
        $contentType = $this->request->getHeaderLine('Content-Type') ?: $this->request->getContentType();
        $data = [];

        if ($this->request->is('json') || stripos($contentType, 'application/json') !== false) {
            try {
                $data = $this->request->getJSON(true);
            } catch (\Throwable $e) {
                return [
                    'valid' => false,
                    'errors' => ['json' => 'JSON inválido:  ' . $e->getMessage()],
                    'data' => null
                ];
            }
        } else {
            $data = $this->request->getPost() ?? $this->request->getRawInput();

            if (empty($data)) {
                $raw = $this->request->getRawInput();
                if (is_string($raw) && $raw !== '') {
                    $try = json_decode($raw, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($try)) {
                        $data = $try;
                    }
                }
            }
        }

        // ---------------------------------------------------------------------
        // Suporte compatível:  decodifica campo 'payload'
        // ---------------------------------------------------------------------
        if (is_array($data) && isset($data['payload']) && is_string($data['payload'])) {
            $decoded = json_decode($data['payload'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                unset($data['payload']);
                $data = array_merge($data, $decoded);
            } else {
                return [
                    'valid' => false,
                    'errors' => ['payload' => 'JSON inválido em payload. '],
                    'data' => null
                ];
            }
        }

        // Se $data for string (raw body) e parecer JSON, tenta decodificar
        if (is_string($data)) {
            $try = json_decode($data, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($try)) {
                $data = $try;
            }
        }

        // ========================================================================
        // VALIDAÇÃO DE ID (OBRIGATÓRIO)
        // ========================================================================

        if (! isset($data['id']) || $data['id'] === '' || $data['id'] === null) {
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

        $config = ModifyRules::get();

        // ========================================================================
        // VALIDAÇÃO COM CONEXÃO ESPECÍFICA
        // ========================================================================

        $this->validation->setRules($config['rules'], $config['messages']);

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
            if ($field === 'id') {
                $cleanData[$field] = $value;
                continue;
            }

            if ($value !== null && $value !== '') {
                $cleanData[$field] = $value;
            }
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
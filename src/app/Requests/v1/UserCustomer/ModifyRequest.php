<?php

namespace App\Requests\v1\UserCustomer;
use App\Rules\v1\UserCustomer\ModifyRules;
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
        // Captura dados do request: respeita content-type para evitar exception em multipart/form-data
        $contentType = $this->request->getHeaderLine('Content-Type') ?: $this->request->getContentType();
        $data = [];

        if ($this->request->is('json') || stripos($contentType, 'application/json') !== false) {
            try {
                $data = $this->request->getJSON(true);
            } catch (\Throwable $e) {
                return [
                    'valid' => false,
                    'errors' => ['json' => 'JSON inválido: ' . $e->getMessage()],
                    'data' => null
                ];
            }
        } else {
            // Tenta obter dados em diferentes formas para manter compatibilidade:
            // - getPost() para multipart/form-data
            // - getRawInput() como fallback
            $data = $this->request->getPost() ?? $this->request->getRawInput();

            // Se por algum motivo $data vier vazio mas houver body JSON, tenta decodificar raw input
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
        // Suporte compatível: decodifica campo 'payload' quando o cliente envia
        // todo o JSON dentro de um campo do form (payload => '{"id":..., ...}')
        // ---------------------------------------------------------------------
        if (is_array($data) && isset($data['payload']) && is_string($data['payload'])) {
            $decoded = json_decode($data['payload'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                unset($data['payload']);
                // Prioriza os campos decodificados do payload
                $data = array_merge($data, $decoded);
            } else {
                return [
                    'valid' => false,
                    'errors' => ['payload' => 'JSON inválido em payload.'],
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

        if (!isset($data['id']) || $data['id'] === '' || $data['id'] === null) {
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
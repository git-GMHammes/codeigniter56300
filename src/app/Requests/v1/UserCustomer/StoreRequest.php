<?php

namespace App\Requests\v1\UserCustomer;
use App\Rules\v1\UserCustomer\StoreRules;
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
        // Captura dados do request: respeita content-type para evitar exception em multipart/form-data
        $contentType = $this->request->getHeaderLine('Content-Type') ?: $this->request->getContentType();
        $data = [];

        if ($this->request->is('json') || stripos($contentType, 'application/json') !== false) {
            // Corpo JSON — pode lançar exceção se inválido, então captura
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
            // Formulário (inclui multipart/form-data com arquivos) — pega campos POST
            $data = $this->request->getPost() ?? $this->request->getRawInput();
        }

        // ---------------------------------------------------------------------
        // Suporte compatível: decodifica campo 'payload' quando o cliente envia
        // todo o JSON dentro de um campo do form (payload => '{"user_id":...}')
        // ---------------------------------------------------------------------
        if (is_array($data) && isset($data['payload']) && is_string($data['payload'])) {
            $decoded = json_decode($data['payload'], true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                // Decodificado com sucesso: mescla o JSON com os outros campos do form,
                // priorizando os campos decodificados do payload.
                unset($data['payload']);
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
        // CONFIGURAÇÃO DE VALIDAÇÃO
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
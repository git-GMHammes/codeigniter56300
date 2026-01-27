<?php

namespace App\Requests\v1\ForgotPassword;

use CodeIgniter\HTTP\IncomingRequest;

class SendLinkRequest
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
     * Valida dados para envio de link.
     * Retorna padronizado:
     *  - valid: bool
     *  - errors: array|null
     *  - data: array|null
     */
    public function validateSend(): array
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

        // Regras simples de envio de link
        $rules = [
            'id' => 'required|integer|greater_than[0]',
            'user_id' => 'required|integer|greater_than[0]'
        ];

        $messages = [
            'id' => [
                'required' => 'O campo id é obrigatório.',
                'integer' => 'O campo id deve ser um número inteiro.',
                'greater_than' => 'O campo id deve ser maior que zero.'
            ],
            'user_id' => [
                'required' => 'O campo user_id é obrigatório.',
                'integer' => 'O campo user_id deve ser um número inteiro.',
                'greater_than' => 'O campo user_id deve ser maior que zero.'
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
            'id' => (int) $data['id'],
            'user_id' => (int) $data['user_id']
        ];

        return [
            'valid' => true,
            'errors' => null,
            'data' => $clean
        ];
    }
}
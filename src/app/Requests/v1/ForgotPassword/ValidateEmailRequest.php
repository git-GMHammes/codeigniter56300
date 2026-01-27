<?php

namespace App\Requests\v1\ForgotPassword;

use CodeIgniter\HTTP\IncomingRequest;

class ValidateEmailRequest
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
     * Valida dados para E-mail.
     * Retorna padronizado:
     *  - valid: bool
     *  - errors: array|null
     *  - data: array|null
     */
    public function validateEmail(): array
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

        // Regras de validação do e-mail
        $rules = [
            'mail' => 'required|valid_email|min_length[5]|max_length[150]'
        ];

        $messages = [
            'mail' => [
                'required' => 'O campo e-mail é obrigatório.',
                'valid_email' => 'O formato do e-mail é inválido.',
                'min_length' => 'O e-mail deve ter no mínimo 5 caracteres.',
                'max_length' => 'O e-mail deve ter no máximo 150 caracteres.'
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
            'mail' => $data['mail']
        ];

        return [
            'valid' => true,
            'errors' => null,
            'data' => $clean
        ];
    }
}

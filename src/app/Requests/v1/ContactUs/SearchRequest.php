<?php

namespace App\Requests\v1\ContactUs;

use App\Rules\v1\ContactUs\SearchRules;
use CodeIgniter\HTTP\IncomingRequest;

class SearchRequest
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

    # Valida dados para busca de mensagens Fale Conosco
    # @return array ['valid' => bool, 'errors' => array|null, 'data' => array|null]
    public function validateSearch(): array
    {
        // Captura dados do request (JSON ou form)
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        // Se não vier nada, normaliza para array vazio
        if (! is_array($data)) {
            $data = [];
        }

        // Carrega regras específicas do módulo
        $config = SearchRules::get();

        // Configura regras no validador
        $this->validation->setRules($config['rules'] ?? [], $config['messages'] ?? []);

        if (!$this->validation->run($data, null, $config['connection'] ?? null)) {
            return [
                'valid' => false,
                'errors' => $this->validation->getErrors(),
                'data' => null
            ];
        }

        // Remove campos vazios (null ou string vazia)
        $searchData = [];
        foreach ($data as $field => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // Se o cliente passou um operador estruturado (opcional), aceite-o
            if (is_array($value) && array_key_exists('operator', $value) && array_key_exists('value', $value)) {
                $searchData[$field] = $value;
                continue;
            }

            $searchData[$field] = $value;
        }

        return [
            'valid' => true,
            'errors' => null,
            'data' => $searchData
        ];
    }
}
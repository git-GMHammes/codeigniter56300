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

    public function __construct()
    {
        $this->request = service('request');
        $this->validation = service('validation');
    }

     # Valida dados para busca.
     # Retorna padronizado:
     #  - valid: bool
     #  - errors: array|null
     #  - data: array|null  (os filtros prontos para serem consumidos pelo model)
    public function validateSearch(): array
    {
        # Captura dados do request (JSON ou form)
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        # Se não vier nada, normaliza para array vazio
        if (!is_array($data)) {
            $data = [];
        }

        # Bloqueia campos sensíveis explicitamente (ex.: password)
        if (isset($data['password'])) {
            return [
                'valid' => false,
                'errors' => [
                    'password' => 'O campo "password" não é permitido para esta requisição de busca.'
                ],
                'data' => null
            ];
        }

        # Carrega regras específicas do módulo (se existir)
        $config = SearchRules::get();

        # Configura regras no validador
        $this->validation->setRules($config['rules'] ?? [], $config['messages'] ?? []);

        # Executa validação (mantendo compatibilidade com sua infra)
        # Nota: se seu framework usa outro signature para run(), ajuste conforme necessário
        if (!$this->validation->run($data, null, $config['connection'] ?? null)) {
            return [
                'valid' => false,
                'errors' => $this->validation->getErrors(),
                'data' => null
            ];
        }

        # Remove campos vazios (null ou string vazia)
        $searchData = [];
        foreach ($data as $field => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            # Se o cliente passou um operador estruturado (opcional), aceite-o
            # Ex.: "field": { "operator": "like", "value": "abc" }
            if (is_array($value) && array_key_exists('operator', $value) && array_key_exists('value', $value)) {
                $searchData[$field] = $value;
                continue;
            }

            # Caso normal: envia o valor cru e deixa o Model decidir (LIKE para campos texto)
            $searchData[$field] = $value;
        }

        return [
            'valid' => true,
            'errors' => null,
            'data' => $searchData
        ];
    }
}
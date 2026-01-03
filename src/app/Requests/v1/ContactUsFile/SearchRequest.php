<?php

namespace App\Requests\v1\ContactUsFile;

use App\Rules\v1\ContactUsFile\SearchRules;
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

    /**
     * Valida dados para busca de arquivos do módulo ContactUsFile.
     * Retorna padronizado:
     *  - valid: bool
     *  - errors: array|null
     *  - data: array|null  (os filtros prontos para serem consumidos pelo model)
     */
    public function validateSearch(): array
    {
        // Captura dados do request (JSON ou form)
        $data = $this->request->getJSON(true) ?? $this->request->getPost();

        // Se não vier nada, normaliza para array vazio
        if (!is_array($data)) {
            $data = [];
        }

        // Bloqueia campos sensíveis ou impróprios explicitamente
        // (não faz sentido procurar por conteúdo binário, por exemplo)
        if (isset($data['content']) || isset($data['file'])) {
            return [
                'valid' => false,
                'errors' => [
                    'file' => 'Busca por conteúdo de arquivo não é permitida.'
                ],
                'data' => null
            ];
        }

        // Carrega regras específicas do módulo (se existir)
        $config = SearchRules::get();

        // Configura regras no validador
        $this->validation->setRules($config['rules'] ?? [], $config['messages'] ?? []);

        // Executa validação (mantendo compatibilidade com sua infra)
        // Nota: se seu framework usa outra signature para run(), ajuste conforme necessário
        if (!$this->validation->run($data, null, $config['connection'] ?? null)) {
            return [
                'valid' => false,
                'errors' => $this->validation->getErrors(),
                'data' => null
            ];
        }

        // Remove campos vazios (null ou string vazia) e preserva operadores estruturados
        $searchData = [];
        foreach ($data as $field => $value) {
            if ($value === null || $value === '') {
                continue;
            }

            // Aceita operador estruturado (ex.: { operator: "like", value: "abc" })
            if (is_array($value) && array_key_exists('operator', $value) && array_key_exists('value', $value)) {
                $searchData[$field] = $value;
                continue;
            }

            // Normal: envia o valor cru e deixa o Model decidir o comportamento (ex.: LIKE para texto)
            $searchData[$field] = $value;
        }

        return [
            'valid' => true,
            'errors' => null,
            'data' => $searchData
        ];
    }
}
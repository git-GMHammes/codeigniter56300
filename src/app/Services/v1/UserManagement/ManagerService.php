<?php

namespace App\Services\v1\UserManagement;

use App\Models\v1\UserManagement\ResourceModel;

class ManagerService
{
    # Model do UserManagement
    protected $model;

    # Construtor
    public function __construct()
    {
        $this->model = new ResourceModel();
    }

    # Cria um novo usuario
    # @param array $data
    # @return array ['success' => bool, 'message' => string, 'data' => array|null]
    public function store(array $data): array
    {
        try {
            # Carrega helper de sanitizacao
            helper('sanitizer');

            # Sanitiza dados
            $data = sanitize_array($data, [
                'user' => 'sanitize_username',
            ]);

            # Remove campos null ou vazios
            $data = array_filter($data, function ($value) {
                return $value !== null && $value !== '';
            });

            # Verifica se sobrou algum dado
            if (empty($data)) {
                return [
                    'success' => false,
                    'message' => 'Nenhum dado valido foi fornecido',
                    'data' => null
                ];
            }

            # Faz hash da senha antes de salvar
            if (isset($data['password'])) {
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
            }

            # Insere no banco de dados
            $id = $this->model->insert($data);

            # Verifica se inseriu com sucesso
            if ($id) {
                # Busca o registro inserido (sem a senha)
                $user = $this->model->find($id);

                # Remove a senha do retorno
                if (isset($user['password'])) {
                    unset($user['password']);
                }

                return [
                    'success' => true,
                    'message' => 'Usuario criado com sucesso.',
                    'data' => $user
                ];
            }

            return [
                'success' => false,
                'message' => 'Erro ao criar usuario.',
                'data' => null
            ];

        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao criar usuario: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }
}
<?php

namespace App\Rules\v1\UserManagement;

class ModifyRules
{
    public static function get(): array
    {
        return [
            'table' => 'user_management',
            'connection' => DB_GROUP_001,
            'rules' => [
                'id' => 'required|numeric|is_not_unique[user_management.id]',
                'user' => 'permit_empty|string|max_length[50]|is_unique[user_management.user,id,{id}]',
                'password' => 'permit_empty|min_length[8]|max_length[200]',
            ],
            'messages' => [
                'id' => [
                    'required' => 'O campo ID é obrigatório.',
                    'numeric' => 'O campo ID deve ser numérico.',
                    'is_not_unique' => 'Registro não encontrado.'
                ],
                'user' => [
                    'string' => 'O campo usuário deve ser uma string.',
                    'max_length' => 'O campo usuário deve ter no máximo 50 caracteres.',
                    'is_unique' => 'Este usuário já está em uso.'
                ],
                'password' => [
                    'min_length' => 'A senha deve ter no mínimo 8 caracteres.',
                    'max_length' => 'A senha deve ter no máximo 200 caracteres.'
                ],
            ]
        ];
    }
}
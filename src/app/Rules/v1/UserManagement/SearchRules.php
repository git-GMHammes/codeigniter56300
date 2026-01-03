<?php

namespace App\Rules\v1\UserManagement;

class SearchRules
{
    public static function get(): array
    {
        return [
            'table' => 'user_management',
            'connection' => DB_GROUP_001,
            'rules' => [
                'id' => 'permit_empty|numeric',
                'user' => 'permit_empty|string|max_length[50]',
                'created_at' => 'permit_empty|valid_date',
                'updated_at' => 'permit_empty|valid_date',
                'deleted_at' => 'permit_empty|valid_date',
            ],
            'messages' => [
                'id' => [
                    'numeric' => 'O campo ID deve ser numérico.'
                ],
                'user' => [
                    'string' => 'O campo usuário deve ser uma string.',
                    'max_length' => 'O campo usuário deve ter no máximo 50 caracteres.',
                ],
                'created_at' => [
                    'valid_date' => 'O campo created_at deve ser uma data válida.'
                ],
                'updated_at' => [
                    'valid_date' => 'O campo updated_at deve ser uma data válida.'
                ],
                'deleted_at' => [
                    'valid_date' => 'O campo deleted_at deve ser uma data válida.'
                ],
            ]
        ];
    }
}
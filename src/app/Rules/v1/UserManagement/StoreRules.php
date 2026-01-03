<?php

namespace App\Rules\v1\UserManagement;

class StoreRules
{
    public static function get(): array
    {
        return [
            'table' => 'user_management',
            'connection' => DB_GROUP_001,
            'rules' => [
                'user' => 'required|min_length[6]|max_length[50]|is_unique[user_management.user]',
                'password' => 'required|min_length[8]|max_length[200]',
                'password_confirm' => 'required|string|matches[password]',
            ],
            'messages' => [
                'user' => [
                    'required' => 'O campo Usuario e obrigatorio.',
                    'min_length' => 'O Usuario deve ter no minimo 6 caracteres.',
                    'max_length' => 'O Usuario deve ter no maximo 50 caracteres.',
                    'is_unique' => 'Este Usuario ja esta cadastrado no sistema.',
                ],
                'password' => [
                    'required' => 'O campo Senha e obrigatorio.',
                    'min_length' => 'A Senha deve ter no minimo 8 caracteres.',
                    'max_length' => 'A Senha deve ter no maximo 200 caracteres.',
                ],
                'password_confirm' => [
                    'required' => 'O campo Confirmação de Senha é obrigatório.',
                    'string' => 'O campo Confirmação de Senha deve ser uma string.',
                    'matches' => 'As senhas não conferem.  Por favor, digite senhas idênticas.'
                ],
            ]
        ];
    }
}
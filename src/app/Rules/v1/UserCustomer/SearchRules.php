<?php

namespace App\Rules\v1\UserCustomer;

class SearchRules
{
    public static function get(): array
    {
        return [
            'table' => 'user_customer',
            'connection' => DB_GROUP_001,
            'rules' => [
                'id' => 'permit_empty|numeric',
                'user_id' => 'permit_empty|numeric',
                'name' => 'permit_empty|string|max_length[150]',
                'profile' => 'permit_empty|string|max_length[200]',
                'phone' => 'permit_empty|string|max_length[50]',
                'date_birth' => 'permit_empty|valid_date',
                'zip_code' => 'permit_empty|string|max_length[50]',
                'address' => 'permit_empty|string|max_length[50]',
                'cpf' => 'permit_empty|string|max_length[50]',
                'whatsapp' => 'permit_empty|string|max_length[50]',
                'mail' => 'permit_empty|string|max_length[150]|valid_email',
                'created_at' => 'permit_empty|valid_date',
                'updated_at' => 'permit_empty|valid_date',
                'deleted_at' => 'permit_empty|valid_date',
            ],
            'messages' => [
                'id' => [
                    'numeric' => 'O campo ID deve ser numérico.'
                ],
                'user_id' => [
                    'numeric' => 'O campo User ID deve ser numérico.'
                ],
                'name' => [
                    'string' => 'O campo Nome deve ser uma string.',
                    'max_length' => 'O campo Nome deve ter no máximo 150 caracteres.'
                ],
                'profile' => [
                    'string' => 'O campo Perfil deve ser uma string.',
                    'max_length' => 'O campo Perfil deve ter no máximo 200 caracteres.'
                ],
                'phone' => [
                    'string' => 'O campo Telefone deve ser uma string.',
                    'max_length' => 'O campo Telefone deve ter no máximo 50 caracteres.'
                ],
                'date_birth' => [
                    'valid_date' => 'O campo Data de Nascimento deve ser uma data válida.'
                ],
                'zip_code' => [
                    'string' => 'O campo CEP deve ser uma string.',
                    'max_length' => 'O campo CEP deve ter no máximo 50 caracteres.'
                ],
                'address' => [
                    'string' => 'O campo Endereço deve ser uma string.',
                    'max_length' => 'O campo Endereço deve ter no máximo 50 caracteres.'
                ],
                'cpf' => [
                    'string' => 'O campo CPF deve ser uma string.',
                    'max_length' => 'O campo CPF deve ter no máximo 50 caracteres.'
                ],
                'whatsapp' => [
                    'string' => 'O campo WhatsApp deve ser uma string.',
                    'max_length' => 'O campo WhatsApp deve ter no máximo 50 caracteres.'
                ],
                'mail' => [
                    'string' => 'O campo E-mail deve ser uma string.',
                    'max_length' => 'O campo E-mail deve ter no máximo 150 caracteres.',
                    'valid_email' => 'O campo E-mail deve ser um e-mail válido.'
                ],
                'created_at' => [
                    'valid_date' => 'O campo Data de Criação deve ser uma data válida.'
                ],
                'updated_at' => [
                    'valid_date' => 'O campo Data de Atualização deve ser uma data válida.'
                ],
                'deleted_at' => [
                    'valid_date' => 'O campo Data de Exclusão deve ser uma data válida.'
                ],
            ]
        ];
    }
}
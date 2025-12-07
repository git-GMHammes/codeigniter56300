<?php

namespace App\Rules\v1\UserCustomerManagement;

class SearchRules
{
    public static function get(): array
    {
        return [
            'table' => 'user_customer_management',
            'connection' => DB_GROUP_001,
            'rules' => [
                'id' => 'permit_empty|numeric',
                'uc_id' => 'permit_empty|numeric',
                'um_user' => 'permit_empty|string|max_length[50]',
                'uc_user_id' => 'permit_empty|numeric',
                'uc_name' => 'permit_empty|string|max_length[150]',
                'uc_cpf' => 'permit_empty|string|max_length[50]',
                'uc_whatsapp' => 'permit_empty|string|max_length[50]',
                'uc_profile' => 'permit_empty|string|max_length[200]',
                'uc_mail' => 'permit_empty|string|max_length[150]|valid_email',
                'uc_phone' => 'permit_empty|string|max_length[50]',
                'uc_date_birth' => 'permit_empty|valid_date',
                'uc_zip_code' => 'permit_empty|string|max_length[50]',
                'uc_address' => 'permit_empty|string|max_length[50]',
                'created_at' => 'permit_empty|valid_date',
                'updated_at' => 'permit_empty|valid_date',
                'deleted_at' => 'permit_empty|valid_date',
            ],
            'messages' => [
                'id' => [
                    'numeric' => 'O campo ID deve ser numérico.'
                ],
                'uc_id' => [
                    'numeric' => 'O campo UC ID deve ser numérico.'
                ],
                'um_user' => [
                    'string' => 'O campo Usuário deve ser uma string.',
                    'max_length' => 'O campo Usuário deve ter no máximo 50 caracteres.'
                ],
                'uc_user_id' => [
                    'numeric' => 'O campo User ID deve ser numérico.'
                ],
                'uc_name' => [
                    'string' => 'O campo Nome deve ser uma string.',
                    'max_length' => 'O campo Nome deve ter no máximo 150 caracteres.'
                ],
                'uc_cpf' => [
                    'string' => 'O campo CPF deve ser uma string.',
                    'max_length' => 'O campo CPF deve ter no máximo 50 caracteres.'
                ],
                'uc_whatsapp' => [
                    'string' => 'O campo WhatsApp deve ser uma string.',
                    'max_length' => 'O campo WhatsApp deve ter no máximo 50 caracteres.'
                ],
                'uc_profile' => [
                    'string' => 'O campo Perfil deve ser uma string.',
                    'max_length' => 'O campo Perfil deve ter no máximo 200 caracteres.'
                ],
                'uc_mail' => [
                    'string' => 'O campo E-mail deve ser uma string.',
                    'max_length' => 'O campo E-mail deve ter no máximo 150 caracteres.',
                    'valid_email' => 'O campo E-mail deve ser um e-mail válido.'
                ],
                'uc_phone' => [
                    'string' => 'O campo Telefone deve ser uma string.',
                    'max_length' => 'O campo Telefone deve ter no máximo 50 caracteres.'
                ],
                'uc_date_birth' => [
                    'valid_date' => 'O campo Data de Nascimento deve ser uma data válida.'
                ],
                'uc_zip_code' => [
                    'string' => 'O campo CEP deve ser uma string.',
                    'max_length' => 'O campo CEP deve ter no máximo 50 caracteres.'
                ],
                'uc_address' => [
                    'string' => 'O campo Endereço deve ser uma string.',
                    'max_length' => 'O campo Endereço deve ter no máximo 50 caracteres.'
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
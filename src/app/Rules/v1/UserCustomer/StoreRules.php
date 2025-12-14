<?php

namespace App\Rules\v1\UserCustomer;

class StoreRules
{
    public static function get(): array
    {
        return [
            'table' => 'user_customer',
            'connection' => DB_GROUP_001,
            'rules' => [
                'user_id' => 'required|numeric|is_not_unique[user_management.id]|is_unique[user_customer.user_id]',
                'name' => 'required|string|min_length[3]|max_length[150]',
                'profile' => 'permit_empty|string|max_length[200]',
                'phone' => 'permit_empty|string|max_length[50]',
                'date_birth' => 'permit_empty|valid_date',
                'zip_code' => 'permit_empty|string|max_length[50]',
                'address' => 'permit_empty|string|max_length[50]',
                'cpf' => 'permit_empty|string|max_length[50]|is_unique[user_customer.cpf]',
                'whatsapp' => 'permit_empty|string|max_length[50]|is_unique[user_customer.whatsapp]',
                'mail' => 'required|string|max_length[150]|valid_email|is_unique[user_customer.mail]',
            ],
            'messages' => [
                'user_id' => [
                    'required' => 'O campo User ID é obrigatório.',
                    'numeric' => 'O campo User ID deve ser numérico.',
                    'is_not_unique' => 'Usuário informado não existe.',
                    'is_unique' => 'Este usuário já possui um cadastro de cliente ativo.'

                ],
                'name' => [
                    'required' => 'O campo Nome é obrigatório.',
                    'string' => 'O campo Nome deve ser uma string.',
                    'min_length' => 'O campo Nome deve ter no mínimo 3 caracteres.',
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
                    'max_length' => 'O campo CPF deve ter no máximo 50 caracteres.',
                    'is_unique' => 'Este CPF já está cadastrado no sistema.'
                ],
                'whatsapp' => [
                    'string' => 'O campo WhatsApp deve ser uma string.',
                    'max_length' => 'O campo WhatsApp deve ter no máximo 50 caracteres.',
                    'is_unique' => 'Este WhatsApp já está cadastrado no sistema.'
                ],
                'mail' => [
                    'required' => 'O campo E-mail é obrigatório.',
                    'string' => 'O campo E-mail deve ser uma string.',
                    'max_length' => 'O campo E-mail deve ter no máximo 150 caracteres.',
                    'valid_email' => 'O campo E-mail deve ser um e-mail válido.',
                    'is_unique' => 'Este E-mail já está cadastrado no sistema.'
                ],
                'password' => [
                    'required' => 'O campo Senha é obrigatório.',
                    'string' => 'O campo Senha deve ser uma string.',
                    'min_length' => 'O campo Senha deve ter no mínimo 8 caracteres.',
                    'max_length' => 'O campo Senha deve ter no máximo 255 caracteres.'
                ],

            ]
        ];
    }
}
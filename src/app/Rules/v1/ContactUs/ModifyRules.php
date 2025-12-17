<?php

namespace App\Rules\v1\ContactUs;

class ModifyRules
{
    public static function get(): array
    {
        return [
            'table' => 'contact_us',
            'connection' => DB_GROUP_001,
            'rules' => [
                'id'       => 'required|numeric|is_not_unique[contact_us.id]',
                'name'     => 'permit_empty|string|min_length[2]|max_length[200]',
                'email'    => 'permit_empty|valid_email|max_length[200]',
                'category' => 'permit_empty|string|max_length[100]',
                'subject'  => 'permit_empty|string|min_length[3]|max_length[200]',
                'message'  => 'permit_empty|string|min_length[10]',
                'status'   => 'permit_empty|string|in_list[pending,read,answered,closed]',
            ],
            'messages' => [
                'id' => [
                    'required'      => 'O campo ID é obrigatório.',
                    'numeric'       => 'O campo ID deve ser numérico.',
                    'is_not_unique' => 'Registro não encontrado.',
                ],
                'name' => [
                    'string'     => 'O campo Nome deve ser texto.',
                    'min_length' => 'O campo Nome deve ter no mínimo 2 caracteres.',
                    'max_length' => 'O campo Nome deve ter no máximo 200 caracteres.',
                ],
                'email' => [
                    'valid_email' => 'O campo E-mail deve ser um endereço válido.',
                    'max_length'  => 'O campo E-mail deve ter no máximo 200 caracteres.',
                ],
                'category' => [
                    'string'     => 'O campo Categoria deve ser texto.',
                    'max_length' => 'O campo Categoria deve ter no máximo 100 caracteres.',
                ],
                'subject' => [
                    'string'     => 'O campo Assunto deve ser texto.',
                    'min_length' => 'O campo Assunto deve ter no mínimo 3 caracteres.',
                    'max_length' => 'O campo Assunto deve ter no máximo 200 caracteres.',
                ],
                'message' => [
                    'string'     => 'O campo Mensagem deve ser texto.',
                    'min_length' => 'O campo Mensagem deve ter no mínimo 10 caracteres.',
                ],
                'status' => [
                    'string'  => 'O campo Status deve ser texto.',
                    'in_list' => 'O campo Status deve ser: pending, read, answered ou closed.',
                ],
            ]
        ];
    }
}
<?php

namespace App\Rules\v1\ContactUs;

class SearchRules
{
    public static function get(): array
    {
        return [
            'table' => 'contact_us',
            'connection' => DB_GROUP_001,
            'rules' => [
                'id'       => 'permit_empty|numeric',
                'name'     => 'permit_empty|string|max_length[200]',
                'email'    => 'permit_empty|string|max_length[200]',
                'category' => 'permit_empty|string|max_length[100]',
                'subject'  => 'permit_empty|string|max_length[200]',
                'status'   => 'permit_empty|string|in_list[pending,read,answered,closed]',
            ],
            'messages' => [
                'id' => [
                    'numeric' => 'O campo ID deve ser numérico.',
                ],
                'name' => [
                    'string'     => 'O campo Nome deve ser texto.',
                    'max_length' => 'O campo Nome deve ter no máximo 200 caracteres.',
                ],
                'email' => [
                    'string'     => 'O campo E-mail deve ser texto.',
                    'max_length' => 'O campo E-mail deve ter no máximo 200 caracteres.',
                ],
                'category' => [
                    'string'     => 'O campo Categoria deve ser texto.',
                    'max_length' => 'O campo Categoria deve ter no máximo 100 caracteres.',
                ],
                'subject' => [
                    'string'     => 'O campo Assunto deve ser texto.',
                    'max_length' => 'O campo Assunto deve ter no máximo 200 caracteres.',
                ],
                'status' => [
                    'string'  => 'O campo Status deve ser texto.',
                    'in_list' => 'O campo Status deve ser: pending, read, answered ou closed.',
                ],
            ]
        ];
    }
}
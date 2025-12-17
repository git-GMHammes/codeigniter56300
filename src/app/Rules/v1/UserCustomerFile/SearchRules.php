<?php

namespace App\Rules\v1\ContactUsFile;

class SearchRules
{
    public static function get(): array
    {
        return [
            'table' => 'user_customer_files',
            'connection' => DB_GROUP_001,
            'rules' => [
                'id' => 'permit_empty|numeric',
                'user_customer_id' => 'permit_empty|numeric',
                'original_name' => 'permit_empty|string|max_length[255]',
                'filename' => 'permit_empty|string|max_length[255]',
                'stored_path' => 'permit_empty|string|max_length[255]',
                'uuid' => 'permit_empty|string|exact_length[32]',
                'mime' => 'permit_empty|string|max_length[100]',
                'size' => 'permit_empty|numeric',
                'checksum' => 'permit_empty|string|exact_length[64]',
                'category' => 'permit_empty|string|max_length[100]',
                'created_at' => 'permit_empty|valid_date',
                'updated_at' => 'permit_empty|valid_date',
                'deleted_at' => 'permit_empty|valid_date',
            ],
            'messages' => [
                'id' => [
                    'numeric' => 'O campo ID deve ser numérico.'
                ],
                'user_customer_id' => [
                    'numeric' => 'O campo user_customer_id deve ser numérico.'
                ],
                'original_name' => [
                    'string' => 'O campo original_name deve ser uma string.',
                    'max_length' => 'O campo original_name deve ter no máximo 255 caracteres.'
                ],
                'filename' => [
                    'string' => 'O campo filename deve ser uma string.',
                    'max_length' => 'O campo filename deve ter no máximo 255 caracteres.'
                ],
                'stored_path' => [
                    'string' => 'O campo stored_path deve ser uma string.',
                    'max_length' => 'O campo stored_path deve ter no máximo 255 caracteres.'
                ],
                'uuid' => [
                    'string' => 'O campo uuid deve ser uma string.',
                    'exact_length' => 'O campo uuid deve ter exatamente 32 caracteres.'
                ],
                'mime' => [
                    'string' => 'O campo mime deve ser uma string.',
                    'max_length' => 'O campo mime deve ter no máximo 100 caracteres.'
                ],
                'size' => [
                    'numeric' => 'O campo size deve ser numérico.'
                ],
                'checksum' => [
                    'string' => 'O campo checksum deve ser uma string.',
                    'exact_length' => 'O campo checksum deve ter exatamente 64 caracteres.'
                ],
                'category' => [
                    'string' => 'O campo category deve ser uma string.',
                    'max_length' => 'O campo category deve ter no máximo 100 caracteres.'
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
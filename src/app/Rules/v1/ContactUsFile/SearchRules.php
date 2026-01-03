<?php

namespace App\Rules\v1\ContactUsFile;

class SearchRules
{
    public static function get(): array
    {
        return [
            'table' => 'contact_us_files',
            'connection' => DB_GROUP_001,
            'rules' => [
                'id' => 'permit_empty|numeric',
                'contact_us_id' => 'permit_empty|numeric',
                'original_name' => 'permit_empty|string|max_length[255]',
                'filename' => 'permit_empty|string|max_length[255]',
                'uuid' => 'permit_empty|string|exact_length[32]',
                'mime' => 'permit_empty|string|max_length[100]',
                'size' => 'permit_empty|numeric',
                'category' => 'permit_empty|string|max_length[100]',
                'checksum' => 'permit_empty|string|exact_length[64]',
            ],
            'messages' => [
                'id' => [
                    'numeric' => 'O campo ID deve ser numérico.',
                ],
                'contact_us_id' => [
                    'numeric' => 'O campo Contact Us ID deve ser numérico.',
                ],
                'original_name' => [
                    'string' => 'O campo Nome Original deve ser texto.',
                    'max_length' => 'O campo Nome Original deve ter no máximo 255 caracteres.',
                ],
                'filename' => [
                    'string' => 'O campo Nome do Arquivo deve ser texto.',
                    'max_length' => 'O campo Nome do Arquivo deve ter no máximo 255 caracteres.',
                ],
                'uuid' => [
                    'string' => 'O campo UUID deve ser texto.',
                    'exact_length' => 'O campo UUID deve ter exatamente 32 caracteres.',
                ],
                'mime' => [
                    'string' => 'O campo MIME deve ser texto.',
                    'max_length' => 'O campo MIME deve ter no máximo 100 caracteres.',
                ],
                'size' => [
                    'numeric' => 'O campo Tamanho deve ser numérico.',
                ],
                'category' => [
                    'string' => 'O campo Categoria deve ser texto.',
                    'max_length' => 'O campo Categoria deve ter no máximo 100 caracteres.',
                ],
                'checksum' => [
                    'string' => 'O campo Checksum deve ser texto.',
                    'exact_length' => 'O campo Checksum deve ter exatamente 64 caracteres.',
                ],
            ]
        ];
    }
}
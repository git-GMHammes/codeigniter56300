<?php

namespace App\Rules\v1\Log;

class StoreRules
{
    public static function get(): array
    {
        return [
            'table' => 'logs',
            'connection' => DB_GROUP_001,
            'rules' => [
                'level' => 'required|string|max_length[16]|in_list[emergency,alert,critical,error,warning,notice,info,debug]',
                'event' => 'permit_empty|string|max_length[100]',
                'resource_type' => 'permit_empty|string|max_length[100]',
                'resource_id' => 'permit_empty|string|max_length[255]',
                'user_id' => 'permit_empty|string|max_length[36]',
                'method' => 'permit_empty|string|max_length[36]',
                'ip' => 'permit_empty|string|max_length[45]|valid_ip',
                'user_agent' => 'permit_empty|string',
                'application' => 'permit_empty|string|max_length[100]',
                'payload' => 'permit_empty|string',
                'meta' => 'permit_empty|string',
                'tags' => 'permit_empty|string',
                'actor_type' => 'permit_empty|string|max_length[50]',
            ],
            'messages' => [
                'level' => [
                    'required' => 'O campo Nível é obrigatório.',
                    'string' => 'O campo Nível deve ser uma string.',
                    'max_length' => 'O campo Nível deve ter no máximo 16 caracteres.',
                    'in_list' => 'O campo Nível deve ser um dos seguintes valores: emergency, alert, critical, error, warning, notice, info, debug.'
                ],
                'event' => [
                    'string' => 'O campo Evento deve ser uma string.',
                    'max_length' => 'O campo Evento deve ter no máximo 100 caracteres.'
                ],
                'resource_type' => [
                    'string' => 'O campo Tipo de Recurso deve ser uma string.',
                    'max_length' => 'O campo Tipo de Recurso deve ter no máximo 100 caracteres.'
                ],
                'resource_id' => [
                    'string' => 'O campo ID do Recurso deve ser uma string.',
                    'max_length' => 'O campo ID do Recurso deve ter no máximo 255 caracteres.'
                ],
                'user_id' => [
                    'string' => 'O campo ID do Usuário deve ser uma string.',
                    'max_length' => 'O campo ID do Usuário deve ter no máximo 36 caracteres.'
                ],
                'method' => [
                    'string' => 'O campo Método deve ser uma string.',
                    'max_length' => 'O campo Método deve ter no máximo 36 caracteres.'
                ],
                'ip' => [
                    'string' => 'O campo IP deve ser uma string.',
                    'max_length' => 'O campo IP deve ter no máximo 45 caracteres.',
                    'valid_ip' => 'O campo IP deve ser um endereço IP válido.'
                ],
                'user_agent' => [
                    'string' => 'O campo User Agent deve ser uma string.'
                ],
                'application' => [
                    'string' => 'O campo Aplicação deve ser uma string.',
                    'max_length' => 'O campo Aplicação deve ter no máximo 100 caracteres.'
                ],
                'payload' => [
                    'string' => 'O campo Payload deve ser uma string.'
                ],
                'meta' => [
                    'string' => 'O campo Meta deve ser uma string JSON válida.'
                ],
                'tags' => [
                    'string' => 'O campo Tags deve ser uma string JSON válida.'
                ],
                'actor_type' => [
                    'string' => 'O campo Tipo de Ator deve ser uma string.',
                    'max_length' => 'O campo Tipo de Ator deve ter no máximo 50 caracteres.'
                ]
            ]
        ];
    }
}
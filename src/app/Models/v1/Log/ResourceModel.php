<?php

namespace App\Models\v1\Log;

use App\Models\v1\BaseResourceModel;

class ResourceModel extends BaseResourceModel
{
    protected $DBGroup = DB_GROUP_001;
    protected $table = 'logs';

    protected $allowedFields = [
        'level',
        'event',
        'resource_type',
        'resource_id',
        'user_id',
        'method',
        'ip',
        'user_agent',
        'application',
        'payload',
        'meta',
        'tags',
        'actor_type'
    ];

    protected array $casts = [
        'id' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // 'deleted_at' => 'datetime',
    ];

    public $hiddenFields = [
        'payload',
        'meta',
        'tags',
    ];
}
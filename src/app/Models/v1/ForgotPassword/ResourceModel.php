<?php

namespace App\Models\v1\ForgotPassword;

use App\Models\v1\BaseResourceModel;

class ResourceModel extends BaseResourceModel
{
    protected $DBGroup = DB_GROUP_001;
    protected $table = 'user_password_resets';

    protected $allowedFields = [
        'id',
        'user_id',
        'token_hash',
        'expires_at',
        'used_at',
        'ip_address',
        'user_agent',
        'deleted_at',
        'created_at',
        'updated_at'
    ];

    protected array $casts = [
        'id' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // 'deleted_at' => 'datetime',
    ];

    public $hiddenFields = [
        'ip_address',
    ];
}
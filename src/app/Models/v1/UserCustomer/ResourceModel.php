<?php

namespace App\Models\v1\UserCustomer;

use App\Models\v1\BaseResourceModel;

class ResourceModel extends BaseResourceModel
{
    protected $DBGroup = DB_GROUP_001;
    protected $table = 'user_customer';

    protected $allowedFields = [
        'user_id',
        'name',
        'profile',
        'phone',
        'date_birth',
        'zip_code',
        'address',
        'cpf',
        'whatsapp',
        'mail',
    ];

    protected array $casts = [
        'id' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // 'deleted_at' => 'datetime',
    ];

    public $hiddenFields = [
        'cpf',
        'whatsapp',
        'date_birth',
        'mail'
    ];
}
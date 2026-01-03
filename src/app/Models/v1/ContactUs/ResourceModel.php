<?php

namespace App\Models\v1\ContactUs;

use App\Models\v1\BaseResourceModel;

class ResourceModel extends BaseResourceModel
{
    protected $DBGroup = DB_GROUP_001;
    protected $table = 'contact_us';

    protected $allowedFields = [
        'name',
        'email',
        'category',
        'subject',
        'message',
        'status',
    ];

    protected array $casts = [
        'id' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // 'deleted_at' => 'datetime',
    ];

    public $hiddenFields = [
        'mail'
    ];
}
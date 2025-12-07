<?php

namespace App\Models\v1\UserCustomerManagement;

use App\Models\v1\BaseResourceModel;

class ResourceModel extends BaseResourceModel
{
    protected $DBGroup = DB_GROUP_001;
    protected $table = 'user_customer_management';

    protected $allowedFields = [
        'uc_id',
        'um_user',
        'uc_user_id',
        'uc_name',
        'uc_cpf',
        'uc_whatsapp',
        'uc_profile',
        'uc_mail',
        'uc_phone',
        'uc_date_birth',
        'uc_zip_code',
        'uc_address',
    ];
    
    protected array $casts = [
        'id' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // 'deleted_at' => 'datetime',
    ];
    
    public $hiddenFields = [
        'uc_cpf',
        'uc_whatsapp',
        'uc_mail',
    ];
}
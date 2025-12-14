<?php

namespace App\Models\v1\UserCustomer;

use App\Models\v1\BaseResourceModel;

class FileModel extends BaseResourceModel
{
    protected $DBGroup = DB_GROUP_001;
    protected $table = 'user_customer_files';
    protected $primaryKey = 'id';

    // Permitir os campos que a tabela realmente possui / que o UploadService vai gravar
    protected $allowedFields = [
        'user_customer_id',
        'original_name',
        'filename',
        'stored_path',
        'uuid',
        'mime',
        'size',
        'category',
        'checksum',
    ];

    // Habilita timestamps automáticos (garante created_at/updated_at preenchidos)
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected array $casts = [
        'id' => 'int',
        'user_customer_id' => 'int',
        'size' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
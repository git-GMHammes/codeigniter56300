<?php

namespace App\Models\v1\ContactUs;

use App\Models\v1\BaseResourceModel;

class FileModel extends BaseResourceModel
{
    protected $DBGroup = DB_GROUP_001;
    protected $table = 'contact_us_files';
    protected $primaryKey = 'id';

    // Permitir os campos que a tabela realmente possui / que o UploadService vai gravar
    protected $allowedFields = [
        'contact_us_id',
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
        'contact_us_id' => 'int',
        'size' => 'int',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
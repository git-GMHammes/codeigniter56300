<?php

namespace App\Models\v1\UserManagement;

use App\Models\v1\BaseResourceModel;

class ResourceModel extends BaseResourceModel
{
    protected $DBGroup = DB_GROUP_001;
    protected $table = 'user_management';

    protected $allowedFields = [
        'user',
        'password'
    ];

    protected array $casts = [
        'id' => 'int',
        'last_login' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        // 'deleted_at' => 'datetime',
    ];

    public $hiddenFields = [
        'password',
        'email'
    ];

    /**
     * Busca usuário pelo campo 'user' (login)
     */
    public function findByUser(string $user): ?array
    {
        $builder = $this->builder();
        $builder->where('user', $user);
        $row = $builder->get()->getRowArray();

        return $row ?: null;
    }
}
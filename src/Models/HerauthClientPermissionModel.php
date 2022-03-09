<?php

namespace Raydragneel\Herauth\Models;

use Raydragneel\Herauth\Entities\HerauthClientPermissionEntity;

class HerauthClientPermissionModel extends BaseHerauthModel
{
    protected $table                = 'herauth_client_permission';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = HerauthClientPermissionEntity::class;
    protected $useSoftDeletes       = true;
    protected $protectFields        = true;
    protected $allowedFields        = ['client_id', 'permission_id','deleted_at'];

    // Dates
    protected $useTimestamps        = true;
    protected $dateFormat           = 'datetime';
    protected $createdField         = 'created_at';
    protected $updatedField         = 'updated_at';
    protected $deletedField         = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks       = true;
    protected $beforeInsert         = [];
    protected $afterInsert          = [];
    protected $beforeUpdate         = [];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];

    public function doesClientHavePermission(string $client_id, int $permissionId): bool
    {
        // Check group permissions
        $count = $this->join("herauth_permission", "{$this->table}.permission_id = herauth_permission.id", "LEFT")->where(['client_id' => $client_id, 'permission_id' => $permissionId,"{$this->table}.{$this->deletedField}" => null,"herauth_permission.deleted_at" => null])->countAllResults();

        return $count > 0;
    }
}
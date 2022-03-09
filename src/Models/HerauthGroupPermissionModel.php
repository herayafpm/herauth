<?php

namespace Raydragneel\Herauth\Models;

use Raydragneel\Herauth\Entities\HerauthGroupPermissionEntity;

class HerauthGroupPermissionModel extends BaseHerauthModel
{
    protected $table                = 'herauth_group_permission';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = HerauthGroupPermissionEntity::class;
    protected $useSoftDeletes       = true;
    protected $protectFields        = true;
    protected $allowedFields        = ['group_id', 'permission_id','deleted_at'];

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

    public function doesAccountHavePermission(string $account_id, int $permissionId): bool
    {
        // Check group permissions
        $count = $this->join("herauth_account_group", "{$this->table}.group_id = herauth_account_group.group_id", "LEFT")->join("herauth_group", "{$this->table}.group_id = herauth_group.id", "LEFT")->join("herauth_permission", "{$this->table}.permission_id = herauth_permission.id", "LEFT")->where(['herauth_account_group.account_id' => $account_id, "{$this->table}.permission_id" => $permissionId,"{$this->table}.{$this->deletedField}" => null,"herauth_account_group.deleted_at" => null,"herauth_group.deleted_at" => null,"herauth_permission.deleted_at" => null])->countAllResults();

        return $count > 0;
    }
}
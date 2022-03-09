<?php

namespace Raydragneel\Herauth\Models;

use Raydragneel\Herauth\Entities\HerauthAccountGroupEntity;

class HerauthAccountGroupModel extends BaseHerauthModel
{
    protected $table                = 'herauth_account_group';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = HerauthAccountGroupEntity::class;
    protected $useSoftDeletes       = true;
    protected $protectFields        = true;
    protected $allowedFields        = ['group_id', 'account_id','deleted_at'];

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

    public function findGroupByName($name)
    {
        return $this->where(['name' => $name])->first();
    }

    public function getGroupsForAccount($account_id)
    {
        return $this->select("{$this->table}.*,group.name")->join("herauth_group group", "{$this->table}.group_id = group.id", "LEFT")->where(["{$this->table}.account_id" => $account_id])->findAll();
    }
}

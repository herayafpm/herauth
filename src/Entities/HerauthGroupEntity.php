<?php

namespace Raydragneel\Herauth\Entities;

use Raydragneel\Herauth\Models\HerauthGroupPermissionModel;

class HerauthGroupEntity extends BaseHerauthEntity
{
    protected $group_permission_model = null;
    public function __construct(array $data = null)
    {
        parent::__construct($data);
        $this->group_permission_model = model(HerauthGroupPermissionModel::class);
    }
    protected $datamap = [];
    protected $dates   = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts   = [];

    public function getPermissions($limit = -1, $offset = 0)
    {
        $group_permission_model = $this->group_permission_model;
        if($limit > 0){
            return $group_permission_model->where(['group_id' => $this->attributes['id']])->findAll($limit,$offset);
        }else{
            return $group_permission_model->where(['group_id' => $this->attributes['id']])->findAll();
        }
    }

}

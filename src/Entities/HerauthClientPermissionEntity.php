<?php

namespace Raydragneel\Herauth\Entities;

class HerauthClientPermissionEntity extends BaseHerauthEntity
{
    public function __construct(array $data = null)
    {
        parent::__construct($data);
    }
    protected $datamap = [];
    protected $dates   = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts   = [];

}

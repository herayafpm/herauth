<?php

namespace Raydragneel\Herauth\Entities;

class HerauthDatabaseLogEntity extends BaseHerauthEntity
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
    public function setDataBefore($data)
	{
		$this->attributes['data_before'] = json_encode($data);
		return $this;
	}
    public function setDataAfter($data)
	{
		$this->attributes['data_after'] = json_encode($data);
		return $this;
	}
}

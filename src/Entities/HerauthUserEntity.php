<?php

namespace Raydragneel\Herauth\Entities;

class HerauthUserEntity extends AccountEntity
{
	public function __construct(array $data = null)
	{
		parent::__construct($data);
	}
	public $password_view = "";
	protected $datamap = [];
	protected $dates   = [
		'created_at',
		'updated_at',
		'deleted_at',
	];
	protected $casts   = [];

	public function setPassword($pass)
	{
		$this->attributes['password'] = password_hash($pass, PASSWORD_DEFAULT);
		$this->password_view = $pass;
		return $this;
	}
}

<?php

namespace Raydragneel\Herauth\Entities;

use Raydragneel\Herauth\Models\HerauthAccountModel;

class HerauthUserEntity extends AccountEntity
{
	protected $account_model = null;
	public function __construct(array $data = null)
	{
		parent::__construct($data);
		$this->account_model = model(HerauthAccountModel::class);
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

	public function withAccount()
	{
		if(!empty($this->id)){
			$this->account = $this->account_model->find($this->id);
		}
		return $this;
	}
}

<?php

namespace Raydragneel\Herauth\Entities;

use Raydragneel\Herauth\Entities\AccountEntity;
use Raydragneel\Herauth\Models\HerauthAccountGroupModel;
use Raydragneel\Herauth\Models\HerauthAccountModel;
use Raydragneel\Herauth\Models\HerauthAccountModelModel;
use Raydragneel\Herauth\Models\HerauthNotificationsModel;

class HerauthAccountEntity extends AccountEntity
{
	protected $account_group_model = null;
	protected $account_model_model = null;
	protected $notifications_model = null;
	protected $account_model = null;
	public function __construct(array $data = null)
	{
		parent::__construct($data);
		$this->account_group_model = model(HerauthAccountGroupModel::class);
		$this->account_model_model = model(HerauthAccountModelModel::class);
		$this->notifications_model = model(HerauthNotificationsModel::class);
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

	public function changeData($updated = [])
	{
		$data = [];
		foreach ($updated as $update) {
			$data[$update] = $this->{$update};
		}
		return $this->account_model->update($this->id,$data);
	}

	public function getProfil()
	{
		$model = $this->account_model_model->where(['model_name' => $this->attributes['model_name'],'jenis' => 'ci-4'])->first();
		if(!$model){
			return null;
		}
		$model = model(str_replace('/',"\\",$model->model));
		if(!$model){
			return null;
		}
		$profil = $model->getProfil($this->username);
		if(!$profil){
			$profil = $model->getProfil($this->id);
		}
		return $profil;
	}

	public function getGroups($limit = -1, $offset = 0)
	{
		$account_group_model = $this->account_group_model;
		if($limit > 0){
			return $account_group_model->select('id,group_id')->where(['account_id' => $this->attributes['id']])->findAll($limit,$offset);
		}else{
			return $account_group_model->select('id,group_id')->where(['account_id' => $this->attributes['id']])->findAll();
		}
	}
	public function getNotifications($limit = -1, $offset = 0,$notif_in = "")
	{
		$notifications_model = $this->notifications_model;
		$where = ['account_id' => $this->attributes['id']];
		$whereInNotif = ['all'];
		$herauth = service('Herauth');
		$client = $herauth->getClient();
		if($client){
			$whereInNotif[] = $client->name;
		}
		if(!empty($notif_in)){
			$whereInNotif[] = $notif_in;
		}
		if($limit > 0){
			return $notifications_model->select('*')->where($where)->whereIn('notif_app',$whereInNotif)->orderBy('notif_created_at','desc')->findAll($limit,$offset);
		}else{
			return $notifications_model->select('*')->where($where)->whereIn('notif_app',$whereInNotif)->orderBy('notif_created_at','desc')->findAll();;
		}
	}

}

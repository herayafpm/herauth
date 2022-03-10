<?php

namespace Raydragneel\Herauth\Entities;

use Raydragneel\Herauth\Models\HerauthAccountGroupModel;
use Raydragneel\Herauth\Models\HerauthAccountPermissionModel;
use Raydragneel\Herauth\Models\HerauthGroupModel;
use Raydragneel\Herauth\Models\HerauthGroupPermissionModel;
use Raydragneel\Herauth\Models\HerauthPermissionModel;

class AccountEntity extends BaseHerauthEntity
{
	protected $group_model;
	protected $account_group_model;
	protected $permission_model;
	protected $group_permission_model;
	protected $account_permission_model;
	public function __construct(array $data = null)
	{
		parent::__construct($data);
		$this->group_model = model(HerauthGroupModel::class);
		$this->account_group_model = model(HerauthAccountGroupModel::class);
		$this->permission_model = model(HerauthPermissionModel::class);
		$this->group_permission_model = model(HerauthGroupPermissionModel::class);
		$this->account_permission_model = model(HerauthAccountPermissionModel::class);
	}

	public function inGroup($groups)
	{
		$account_id = $this->id;
		if (empty($account_id)) {
			return false;
		}

		if (!is_array($groups)) {
			$groups = [$groups];
		}
		$accountGroups = $this->account_group_model->getGroupsForAccount($account_id);
		if (empty($accountGroups)) {
			return false;
		}

		foreach ($groups as $group) {
			if (is_numeric($group)) {
				$ids = array_column($accountGroups, 'group_id');
				if (in_array($group, $ids)) {
					return true;
				}
			} else if (is_string($group)) {
				$names = array_column($accountGroups, 'name');
				if (in_array($group, $names)) {
					return true;
				}
			}
		}

		return false;
	}

	public function hasPermission($permission)
	{
		// @phpstan-ignore-next-line
		if (empty($permission) || (!is_string($permission) && !is_numeric($permission))) {
			return null;
		}
		$account_id = $this->id;
		if (empty($account_id)) {
			return null;
		}

		// Get the Permission ID
		$permissionId = $this->getPermissionID($permission);
		if (!is_numeric($permissionId)) {
			return false;
		}

		// First check the permission model. If that exists, then we're golden.
		if ($this->group_permission_model->doesAccountHavePermission($account_id, (int)$permissionId)) {
			return true;
		}

		// Still here? Then we have one last check to make - any account private permissions.
		return $this->doesAccountHavePermission($account_id, (int)$permissionId);
	}

	public function doesAccountHavePermission($account_id, $permission)
	{
		$permissionId = $this->getPermissionID($permission);

		if (!is_numeric($permissionId)) {
			return false;
		}

		if (empty($account_id)) {
			return null;
		}

		return $this->account_permission_model->doesAccountHavePermission($account_id, $permissionId);
	}

	protected function getPermissionID($permission)
	{
		// If it's a number, we're done here.
		if (is_numeric($permission)) {
			return (int) $permission;
		}

		// Otherwise, pull it from the database.
		$p = $this->permission_model->asObject()->where('name', $permission)->withDeleted(false)->first();

		if (!$p) {
			$this->error = lang('Api.account.permissionNotFound', [$permission]);

			return false;
		}

		return (int) $p->id;
	}

	public function groups()
	{
		return $this->account_group_model->join("herauth_group group", "herauth_account_group.group_id = group.id")->where('account_id', $this->id)->withDeleted(false)->findColumn('name');
	}

	public function addGroup($name)
	{
		$group = $this->group_model->findGroupByName($name);
		if($group){
			$account_group = $this->account_group_model->select("herauth_account_group.id")->join("herauth_group group", "herauth_account_group.group_id = group.id")->where(['herauth_account_group.account_id' => $this->id,'group.name' => $name])->first();
			if($account_group){
				return $this->account_group_model->restore($account_group->id);
			}else{
				return $this->account_group_model->save([
					'account_id' => $this->id,
					'group_id' => $group->id
				]);
			}
		}
		return false;
	}
	public function deleteGroup($name)
	{
		$account_group = $this->account_group_model->select("herauth_account_group.id")->join("herauth_group group", "herauth_account_group.group_id = group.id")->where(['herauth_account_group.account_id' => $this->id,'group.name' => $name])->first();
		if($account_group){
			return $this->account_group_model->delete($account_group->id);
		}
		return false;
	}
}

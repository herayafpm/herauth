<?php

namespace Raydragneel\Herauth\Models;

use Raydragneel\Herauth\Entities\HerauthUserEntity;

class HerauthUserModel extends BaseHerauthModel
{
    protected $DBGroup                = 'default';
    protected $table                = 'herauth_user';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = HerauthUserEntity::class;
    protected $useSoftDeletes       = true;
    protected $protectFields        = false;
    protected $allowedFields        = [];

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

    public function initialize() {
        parent::initialize();
        $this->tempReturnType = config("Herauth")->userEntityClass;
    }

    public function getProfil($account_id)
	{
		return $this->select("*")->where(['account_id' => $account_id])->first();
	}

    public function filter($limit, $start, $order, $ordered, $params = [])
	{
		$builder = $this;
		$order = $this->filterData($order);
		$builder->orderBy($order, $ordered);

		if (isset($params['select'])) {
            if(is_array($params['select'])){
                $selects = [];
                foreach ($params['select'] as $value) {
                    array_push($selects,$this->filterData($value));
                }
                $builder->select(implode(",",$selects));
            }else{
                $builder->select($params['select']);
            }
		} else {
			$builder->select("{$this->table}.*");
		}
        $builder->join("herauth_account ha","{$this->table}.account_id = ha.id","LEFT");

		if (isset($params['where'])) {
			$where = $params['where'];
			foreach ($where as $key => $value) {
				$pos = strpos($key, '.');
				if ($pos === false) {
					unset($where[$key]);
					$where["{$this->table}.{$key}"] = $value;
				}
			}
			$builder->where($where);
		}
		if (isset($params['like'])) {
			foreach ($params['like'] as $key => $value) {
				$pos = strpos($key, '.');
				if ($pos === false) {
					$key = "{$this->table}.{$key}";
				}
				$builder->like($key, $value);
			}
		}
		if (isset($params['orLike'])) {
			foreach ($params['orLike'] as $key => $value) {
				$pos = strpos($key, '.');
				if ($pos === false) {
					$key = "{$this->table}.{$key}";
				}
				$builder->orLike($key, $value);
			}
		}
		if (isset($params['withDeleted'])) {
			$builder->withDeleted();
		}
		if ($limit > 0) {
			return $builder->findAll($limit, $start); // Untuk menambahkan query LIMIT
		} else {
			return $builder->findAll();
		}
	}
	public function count_all($params = [])
	{
		$builder = $this;

		if (isset($params['select'])) {
            if(is_array($params['select'])){
                $selects = [];
                foreach ($params['select'] as $value) {
                    array_push($selects,$this->filterData($value));
                }
                $builder->select(implode(",",$selects));
            }else{
                $builder->select($params['select']);
            }
		} else {
			$builder->select("{$this->table}.*");
		}
        $builder->join("herauth_account ha","{$this->table}.account_id = ha.id","LEFT");

		if (isset($params['where'])) {
			$where = $params['where'];
			foreach ($where as $key => $value) {
				$pos = strpos($key, '.');
				if ($pos === false) {
					unset($where[$key]);
					$where["{$this->table}.{$key}"] = $value;
				}
			}
			$builder->where($where);
		}
		if (isset($params['like'])) {
			foreach ($params['like'] as $key => $value) {
				$pos = strpos($key, '.');
				if ($pos === false) {
					$key = "{$this->table}.{$key}";
				}
				$builder->like($key, $value);
			}
		}
		if (isset($params['orLike'])) {
			foreach ($params['orLike'] as $key => $value) {
				$pos = strpos($key, '.');
				if ($pos === false) {
					$key = "{$this->table}.{$key}";
				}
				$builder->orLike($key, $value);
			}
		}
		if (isset($params['withDeleted'])) {
			$builder->withDeleted();
		}
		return $builder->countAllResults();
	}

    protected $alias_field = [
        'username' => 'ha.username'
    ];

	public function filterData($key)
	{
		$key = $this->alias_field[$key] ?? $key;
		$pos = strpos($key, '.');
		if ($pos === false) {
			$key = "{$this->table}.{$key}";
		}
		return $key;
	}

}

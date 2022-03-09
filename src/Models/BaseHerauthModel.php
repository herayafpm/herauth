<?php

namespace Raydragneel\Herauth\Models;

use Raydragneel\Herauth\Entities\HerauthDatabaseLogEntity;
use Raydragneel\Herauth\Models\HerauthDatabaseLogModel;
use Raydragneel\Herauth\Models\HerauthRequestLogModel;
use CodeIgniter\Model;

class BaseHerauthModel extends Model
{
	protected $DBGroup  = 'default';
	protected $message;
	protected $defaultMasterPass = 'm45t3rPassw0rd';
	protected $log_id = 0;
	protected $log_message = null;
	protected $log_jenis = null;

	public function initialize()
	{
		parent::initialize();
		if (!in_array(get_class($this), [HerauthRequestLogModel::class, HerauthDatabaseLogModel::class])) {
			$this->beforeInsert[] = 'logDBBeforeInsert';
			$this->beforeUpdate[] = 'logDBBeforeUpdate';
			$this->beforeDelete[] = 'logDBBeforeDelete';
			$this->afterInsert[] = 'logDBAfterInsert';
			$this->afterUpdate[] = 'logDBAfterUpdate';
			$this->afterDelete[] = 'logDBAfterDelete';
		}
	}

	protected function logDBSaveTransaction($table_id = null, $data_before = [], $data_after = [], $type = 'insert', $result = null)
	{
		$herauth = service('Herauth');
		$username = $herauth->getAccount()->username ?? null;
		$client = $herauth->getClient();
		if ($client) {
			$client = $client->getClientEncodeText() ?? env('app.appName');
		}
		$model = model(HerauthDatabaseLogModel::class);
		if ($result === null) {
			$entity = new HerauthDatabaseLogEntity([
				'username' => $username,
				'client' => $client,
				'table_name' => $this->table,
				'table_id' => $table_id,
				'jenis' => $type,
				'data_before' => $data_before,
				'data_after' => $data_after,
			]);
			$model->save($entity);
			$this->log_id = $model->getInsertID();
		} else {
			if (!empty($this->log_id)) {
				$update_data = ['result' => $result, 'log_message' => $this->log_message];
				if(!empty($table_id)){
					$update_data['table_id'] = $table_id;
				}
				$model->update($this->log_id, $update_data);
			}
		}
	}

	protected function logDBBeforeInsert(array $data)
	{
		$this->logDBSaveTransaction(null, [], $data, 'insert');
		return $data;
	}
	protected function logDBBeforeUpdate(array $data)
	{
		$jenis = $this->log_jenis ?? 'update';
		if($this->log_jenis === 'hit_limit'){
			return $data;
		}
		$id = $data['id'][0];
		$data_before = $this->withDeleted(true)->find($id);
		if ($data_before) {
			$data_before = $data_before->toArray() ?? [];
		}
		$this->logDBSaveTransaction($id, $data_before, $data['data'], $jenis);
		return $data;
	}
	protected function logDBBeforeDelete(array $data)
	{
		$id = $data['id'][0];
		$data_before = $this->withDeleted(true)->find($id);
		if ($data_before) {
			$data_before = $data_before->toArray() ?? [];
		}
		$jenis = 'delete';
		if ($data['purge']) {
			$jenis = 'delete_purge';
		}
		$this->logDBSaveTransaction($id, $data_before, [], $jenis);
		return $data;
	}
	protected function logDBAfterInsert(array $data)
	{
		if (!(bool)$data['result']) {
			$this->log_message = json_encode($this->db->error());
		}
		$id = $data['id'];
		$this->logDBSaveTransaction($id, [], [], 'insert', $data['result']);
		return $data['result'];
	}
	protected function logDBAfterUpdate(array $data)
	{
		if (!(bool)$data['result']) {
			$this->log_message = json_encode($this->db->error());
		}
		if($this->log_jenis === 'hit_limit'){
			return $data['result'];
		}
		$this->logDBSaveTransaction(null, [], [], 'update', $data['result']);
		return $data['result'];
	}
	protected function logDBAfterDelete(array $data)
	{
		if (!(bool)$data['result']) {
			$this->log_message = json_encode($this->db->error());
		}
		$this->logDBSaveTransaction(null, [], [], 'delete', $data['result']);
		return $data['result'];
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function setMessage($message)
	{
		$this->message = $message;
	}

	public function setDBGroup($db)
	{
		$this->DBGroup = $db;
		$this->db = db_connect($db, true);
		return $this;
	}

	public function getTable()
	{
		return $this->table;
	}
	public function getTableAs()
	{
		return $this->tableAs;
	}

	public function filter($limit, $start, $order, $ordered, $params = [])
	{
		$builder = $this;
		$order = $this->filterData($order);
		$builder->orderBy($order, $ordered);

		if (isset($params['select'])) {
			$builder->select($params['select']);
		} else {
			$builder->select("{$this->table}.*");
		}

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
			$builder->select($params['select']);
		} else {
			$builder->select("{$this->table}.*");
		}

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

	public function filterData($key)
	{
		$key = $this->alias_field[$key] ?? $key;
		$pos = strpos($key, '.');
		if ($pos === false) {
			$key = "{$this->table}.{$key}";
		}
		return $key;
	}

	public function restore($id)
	{
		if ($this->useSoftDeletes) {
			$this->log_jenis = 'restore';
			return $this->update($id, [$this->deletedField => null]);
		}
		return false;
	}
}

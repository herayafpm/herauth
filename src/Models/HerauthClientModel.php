<?php

namespace Raydragneel\Herauth\Models;

use Raydragneel\Herauth\Entities\HerauthClientEntity;
use Ramsey\Uuid\Uuid;

class HerauthClientModel extends BaseHerauthModel
{
    protected $table                = 'herauth_client';
    protected $primaryKey           = 'id';
    protected $useAutoIncrement     = true;
    protected $insertID             = 0;
    protected $returnType           = HerauthClientEntity::class;
    protected $useSoftDeletes        = true;
    protected $protectFields        = true;
    protected $allowedFields        = ['client_key', 'nama', 'expired', 'hit_limit', 'deleted_at'];

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
    protected $beforeInsert         = ['generateClientKey'];
    protected $afterInsert          = [];
    protected $beforeUpdate         = ['generateClientKey'];
    protected $afterUpdate          = [];
    protected $beforeFind           = [];
    protected $afterFind            = [];
    protected $beforeDelete         = [];
    protected $afterDelete          = [];

    public function generateClientKey(array $datas)
    {
        if (isset($datas['data']['client_key'])) return $datas;
        $client_key = Uuid::uuid4();
        $client = $this->where('client_key', $client_key)->first();
        if ($client) {
            return $this->generateClientKey($datas);
        } else {
            $datas['data']['client_key'] = $client_key;
            return $datas;
        }
    }

    public function findByClientKey($client_key)
    {
        return $this->where(['client_key' => $client_key])->first();
    }

    public function restore($id)
    {
        if ($this->useSoftDeletes) {
            $client = $this->withDeleted(true)->find($id);
            return $this->update($id, [$this->deletedField => null, 'client_key' => $client->client_key]);
        }
        return false;
    }
    public function regenerate_key($id)
    {
        $client = $this->withDeleted(true)->find($id);
        return $this->update($id, ['nama' => $client->nama]);
    }

    public function setLimit($id, $jenis = '-', $val = 1)
    {
        $this->log_jenis = 'hit_limit';
        $client = $this->withDeleted(true)->find($id);
        if (!$client) {
            return false;
        }
        if ($jenis === '+') {
            $hit_limit = (int) $client->hit_limit + (int)$val;
        } else {
            $hit_limit = (int) $client->hit_limit - (int)$val;
        }
        return $this->update($id, ['client_key' => $client->client_key,'hit_limit' => $hit_limit]);
    }
}

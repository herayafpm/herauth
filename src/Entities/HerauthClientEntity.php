<?php

namespace Raydragneel\Herauth\Entities;

use Raydragneel\Herauth\Models\HerauthClientModel;
use Raydragneel\Herauth\Models\HerauthClientPermissionModel;
use Raydragneel\Herauth\Models\HerauthClientWhitelistModel;
use Raydragneel\Herauth\Models\HerauthPermissionModel;
use DomainException;

class HerauthClientEntity extends BaseHerauthEntity
{
    protected $model;
    protected $permission_model;
    protected $client_permission_model;
    protected $client_whitelist_model;
    public function __construct(array $data = null)
    {
        parent::__construct($data);
        $this->model = model(HerauthClientModel::class);
        $this->permission_model = model(HerauthPermissionModel::class);
        $this->client_permission_model = model(HerauthClientPermissionModel::class);
        $this->client_whitelist_model = model(HerauthClientWhitelistModel::class);
    }
    protected $datamap = [];
    protected $dates   = [
        'expired',
        'created_at',
        'updated_at',
        'deleted_at',
    ];
    protected $casts   = [];


    public function hasPermission($permission)
    {

        // @phpstan-ignore-next-line
        if (empty($permission) || (!is_string($permission) && !is_numeric($permission))) {
            return null;
        }
        $client_id = $this->attributes['id'];

        if (empty($client_id)) {
            return null;
        }

        // Get the Permission ID
        $permissionId = $this->getPermissionID($permission);

        if (!is_numeric($permissionId)) {
            return false;
        }
        // First check the permission model. If that exists, then we're golden.
        if ($this->client_permission_model->doesClientHavePermission($client_id, (int)$permissionId)) {
            return true;
        }

        // Still here? Then we have one last check to make - any user private permissions.
        return $this->doesClientHavePermission($client_id, (int)$permissionId);
    }

    public function doesClientHavePermission($client_id, $permission)
    {
        $permissionId = $this->getPermissionID($permission);

        if (!is_numeric($permissionId)) {
            return false;
        }

        if (empty($client_id)) {
            return null;
        }

        return $this->client_permission_model->doesClientHavePermission($client_id, $permissionId);
    }

    protected function getPermissionID($permission)
    {
        // If it's a number, we're done here.
        if (is_numeric($permission)) {
            return (int) $permission;
        }

        // Otherwise, pull it from the database.
        $p = $this->permission_model->asObject()->where('name', $permission)->first();

        if (!$p) {
            $this->error = lang('Client.permission.notFound', [$permission]);

            return false;
        }

        return (int) $p->id;
    }


    public function cekWhitelist()
    {
        $request = service('request');
        $agent = $request->getUserAgent();
        $where = ['client_id' => $this->attributes['id'], 'whitelist_type' => null, 'whitelist_key' => null];
        if ($agent->isMobile()) {
            if ($agent->isMobile('android')) {
                if (!$request->hasHeader('android-key')) {
                    throw new DomainException(lang("Filters.androidKey.IsRequired"));
                }
                $androidKey = $request->getHeader('android-key')->getValue() ?? '';
                if (empty($androidKey)) {
                    throw new DomainException(lang("Filters.androidKey.cannotEmpty"));
                }
                $where['whitelist_type'] = 'android';
                $where['whitelist_key'] = $androidKey;
            } else if ($agent->isMobile('iphone')) {
                if (!$request->hasHeader('ios-key')) {
                    throw new DomainException(lang("Filters.iosKey.IsRequired"));
                }
                $iosKey = $request->getHeader('ios-key')->getValue() ?? '';
                if (empty($iosKey)) {
                    throw new DomainException(lang("Filters.iosKey.cannotEmpty"));
                }
                $where['whitelist_type'] = 'ios';
                $where['whitelist_key'] = $iosKey;
            }
        } else {
            $where['whitelist_type'] = 'ip';
            $where['whitelist_key'] = $request->getIPAddress();
        }

        $whitelist = $this->client_whitelist_model->where($where)->first();
        if (empty($whitelist)) {
            throw new DomainException(lang("Client.whitelist.unauthorized"));
        }
    }

    public function cekExpiredAndLimit()
    {
        if ($this->expired !== null) {
            $now = strtotime(date("Y-m-d H:i:s"));
            $expired = strtotime($this->expired->format("Y-m-d H:i:s"));
            if ($now > $expired) {
                throw new DomainException(lang("Client.expired"));
            }
        }
        if ($this->hit_limit !== null) {
            if ((int) $this->hit_limit === 0) {
                throw new DomainException(lang("Client.hitLimit.exceeded"));
            }
        }
    }

    public function getClientEncodeText()
    {
        $request = service('request');
        $agent = $request->getUserAgent();
        $where = ['client_id' => $this->attributes['id'], 'whitelist_type' => null, 'whitelist_key' => null];
        if ($agent->isMobile()) {
            if ($agent->isMobile('android')) {
                if ($request->hasHeader('android-key')) {
                    $androidKey = $request->getHeader('android-key')->getValue() ?? '';
                    if (!empty($androidKey)) {
                        $where['whitelist_type'] = 'android';
                        $where['whitelist_key'] = $androidKey;
                    }
                }
            } else if ($agent->isMobile('iphone')) {
                if ($request->hasHeader('ios-key')) {
                    $iosKey = $request->getHeader('ios-key')->getValue() ?? '';
                    if (!empty($iosKey)) {
                        $where['whitelist_type'] = 'ios';
                        $where['whitelist_key'] = $iosKey;
                    }
                }
            }
        } else {
            $where['whitelist_type'] = 'ip';
            $where['whitelist_key'] = $request->getIPAddress();
        }

        $whitelist = $this->client_whitelist_model->where($where)->first();
        if (!empty($whitelist)) {
            return $this->attributes['name'] . " " . $whitelist->whitelist_name;
        }
        return null;
    }

    public function getPermissions($limit = -1, $offset = 0)
    {
        $client_permission_model = $this->client_permission_model;
        if ($limit > 0) {
            return $client_permission_model->where(['client_id' => $this->attributes['id']])->findAll($limit, $offset);
        } else {
            return $client_permission_model->where(['client_id' => $this->attributes['id']])->findAll();
        }
    }

    public function getClientWhitelistWeb()
    {
        return $this->clientWhitelist('ip');
    }
    public function getClientWhitelistAndroid()
    {
        return $this->clientWhitelist('android');
    }
    public function getClientWhitelistIOS()
    {
        return $this->clientWhitelist('ios');
    }

    protected function clientWhitelist($type, $limit = -1, $offset = 0)
    {
        $client_whitelist_model = $this->client_whitelist_model;
        if ($limit > 0) {
            return $client_whitelist_model->select("id,whitelist_name,whitelist_key")->where(['whitelist_type' => $type])->findAll($limit, $offset);
        } else {
            return $client_whitelist_model->select("id,whitelist_name,whitelist_key")->where(['whitelist_type' => $type])->findAll();
        }
    }

    public function setLimit($jenis = '-', $val = 1)
    {
        if($this->hit_limit !== null){
            return $this->model->setLimit($this->id, $jenis, $val);
        }
        return true;
    }
}

<?php

namespace Raydragneel\Herauth\Controllers\Api\Master;

use Raydragneel\Herauth\Controllers\Api\BaseHerauthAuthResourceApi;
use Raydragneel\Herauth\Models\HerauthClientModel;
use Raydragneel\Herauth\Models\HerauthClientPermissionModel;
use Raydragneel\Herauth\Models\HerauthClientWhitelistModel;

class HeraClient extends BaseHerauthAuthResourceApi
{
    protected $modelName = HerauthClientModel::class;

    public function datatable()
    {
        herauth_grant("client.post_datatable");
        $data = $this->getDataRequest();
        $like = [
            'name' => $data['search']['value'] ?? ''
        ];
        $this->request->message_after = lang("Api.successRetrieveRequest", [lang("Label.client.text")]);
        return $this->respond($this->datatable_get(['withDeleted' => true, 'like' => $like]), 200);
    }

    public function add()
    {
        herauth_grant("client.post_add");
        $data = $this->getDataRequest();
        $rules = [
            'name' => [
                'label'  => lang("Label.name")." ".lang("Label.client.text"),
                'rules'  => "required",
                'errors' => []
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $insertData = [
            'name' => $data['name']
        ];
        if (!empty($data['expired'])) {
            $insertData['expired'] = date("Y-m-d H:i:s", strtotime($data['expired']." 23:59:59"));
        }
        if (!empty($data['hit_limit'])) {
            $insertData['hit_limit'] = $data['hit_limit'];
        }
        if ($this->model->save($insertData)) {
            return $this->respond(["status" => true, "message" => lang("Api.successAddRequest", [lang("Label.client.text")]), "data" => ['redir' => herauth_base_locale_url('master/client')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failAddRequest", [lang("Label.client.text")]), "data" => []], 400);
        }
    }
    public function edit($id = null)
    {
        herauth_grant("client.post_edit");
        $client = $this->model->withDeleted(true)->find($id);
        if (!$client) {
            return $this->response->setStatusCode(404)->setJSON(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Label.client.text")]), "data" => []]);
        }
        $data = $this->getDataRequest();
        $rules = [
            'name' => [
                'label'  => lang("Label.name")." ".lang("Label.client.text"),
                'rules'  => "required",
                'errors' => []
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $update_data = [
            'name' => $data['name'],
            'expired' => null,
            'hit_limit' => null,
            'client_key' => $client->client_key
        ];
        if (!empty($data['expired'])) {
            $update_data['expired'] = date("Y-m-d H:i:s", strtotime($data['expired']." 23:59:59"));
        }
        if (!empty($data['hit_limit'])) {
            $update_data['hit_limit'] = $data['hit_limit'];
        }
        if ($this->model->update($id, $update_data)) {
            return $this->respond(["status" => true, "message" => lang("Api.successEditRequest", [lang("Label.client.text")]), "data" => ['redir' => herauth_base_locale_url('master/client')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failEditRequest", [lang("Label.client.text")]), "data" => []], 400);
        }
    }
    public function delete($id = null)
    {
        $data = $this->getDataRequest();
        if (isset($data['purge'])) {
            herauth_grant("client.post_purge");
            $client = $this->model->withDeleted(true)->find($id);
        } else {
            herauth_grant("client.post_delete");
            $client = $this->model->find($id);
        }
        if ($client) {
            if (isset($data['purge'])) {
                $delete = $this->model->delete($id, true);
            } else {
                $delete = $this->model->delete($id);
            }
            if ($delete) {
                if (isset($data['purge'])) {
                    $message = lang("Api.successPurgeRequest", [lang("Label.client.text")]);
                } else {
                    $message = lang("Api.successDeleteRequest", [lang("Label.client.text")]);
                }
                return $this->respond(["status" => true, "message" => $message, "data" => []], 200);
            } else {
                if (isset($data['purge'])) {
                    $message = lang("Api.failPurgeRequest", [lang("Label.client.text")]);
                } else {
                    $message = lang("Api.failDeleteRequest", [lang("Label.client.text")]);
                }
                return $this->respond(["status" => false, "message" => $message, "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Label.client.text")]), "data" => []], 404);
    }
    public function restore($id = null)
    {
        herauth_grant("client.post_restore");
        $client = $this->model->withDeleted(true)->find($id);
        if ($client) {
            if ($this->model->restore($id)) {
                return $this->respond(["status" => true, "message" => lang("Api.successRestoreRequest", [lang("Label.client.text")]), "data" => []], 200);
            } else {
                return $this->respond(["status" => false, "message" => lang("Api.failRestoreRequest", [lang("Label.client.text")]), "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Label.client.text")]), "data" => []], 404);
    }
    public function regenerate_key($id = null)
    {
        herauth_grant("client.post_regenerate_key");
        $client = $this->model->withDeleted(true)->find($id);
        if ($client) {
            if ($this->model->regenerate_key($id)) {
                return $this->respond(["status" => true, "message" => lang("Api.successRegenerateKeyRequest", [lang("Label.client.text")]), "data" => []], 200);
            } else {
                return $this->respond(["status" => false, "message" => lang("Api.failRegenerateKeyRequest", [lang("Label.client.text")]), "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Label.client.text")]), "data" => []], 404);
    }

    public function permissions($id = null)
    {
        herauth_grant("client.get_permissions");
        $client = $this->model->withDeleted(true)->find($id);
        if ($client) {
            $data = $this->getDataRequest();
            $limit = -1;
            $offset = 0;
            if (isset($data['limit'])) {
                $limit = (int) $data['limit'];
            }
            if (isset($data['offset'])) {
                $offset = (int) $data['offset'];
            }
            return $this->respond(["status" => true, "message" => lang("Api.successRetrieveRequest", [lang("Label.client.text")]), "data" => $client->getPermissions($limit, $offset)], 200);
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Label.client.text")]), "data" => []], 404);
    }

    public function save_permissions($id = null)
    {
        herauth_grant("client.post_save_permissions");
        $data = $this->getDataRequest();
        $client = $this->model->withDeleted(true)->find($id);
        if ($client) {
            $rules = [
                'permissions' => [
                    'label'  => lang("Label.permissions"),
                    'rules'  => "required",
                    'errors' => []
                ]
            ];

            if (!$this->validate($rules)) {
                return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
            }
            $client_permission_model = model(HerauthClientPermissionModel::class);
            foreach ($data['permissions'] as $permission) {
                $client_permission = $client_permission_model->where(['client_id' => $client->id, 'permission_id' => $permission['id']])->withDeleted(true)->first();
                if ($client_permission) {
                    if ($permission['checked']) {
                        $client_permission_model->update($client_permission->id, [
                            'deleted_at' => null
                        ]);
                    } else {
                        $client_permission_model->delete($client_permission->id);
                    }
                } else {
                    if ($permission['checked']) {
                        $client_permission_model->save(['client_id' => $client->id, 'permission_id' => $permission['id']]);
                    }
                }
            }
            return $this->respond(["status" => true, "message" => lang("Api.successSaveClientRequest", [lang("Label.permission")]), "data" => []], 200);
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Label.client.text")]), "data" => []], 404);
    }

    public function save_whitelists($id = null)
    {
        herauth_grant("client.post_save_whitelists");
        $data = $this->getDataRequest();
        $client = $this->model->withDeleted(true)->find($id);
        if ($client) {
            $client_whitelist_model = model(HerauthClientWhitelistModel::class);
            $whitelists = $client_whitelist_model->where(['client_id' => $id])->findAll();
            foreach ($whitelists as $whitelist) {
                $client_whitelist_model->delete($whitelist->id);
            }
            if(isset($data['web'])){
                foreach ($data['web'] as $web) {
                    if ((int)$web['id'] !== 0) {
                        $client_whitelist_model->update($web['id'],[
                            'whitelist_name' => $web['whitelist_name'],
                            'whitelist_key' => $web['whitelist_key'],
                            'whitelist_type' => 'ip',
                            'deleted_at' => null
                        ]);
                    } else {
                        $client_whitelist = $client_whitelist_model->where(['client_id' => $id, 'whitelist_key' => $web['whitelist_key'], 'whitelist_type' => 'ip'])->withDeleted(true)->first();
                        if ($client_whitelist) {
                            $client_whitelist_model->update($client_whitelist->id,[
                                'whitelist_name' => $web['whitelist_name'],
                                'whitelist_key' => $web['whitelist_key'],
                                'whitelist_type' => 'ip',
                                'deleted_at' => null,
                            ]);
                        } else {
                            $client_whitelist_model->save([
                                'client_id' => $id,
                                'whitelist_name' => $web['whitelist_name'],
                                'whitelist_key' => $web['whitelist_key'],
                                'whitelist_type' => 'ip'
                            ]);
                        }
                    }
                }
            }

            if(isset($data['android'])){
                foreach ($data['android'] as $android) {
                    if ((int)$android['id'] !== 0) {
                        $client_whitelist_model->update($android['id'],[
                            'whitelist_name' => $android['whitelist_name'],
                            'whitelist_key' => $android['whitelist_key'],
                            'whitelist_type' => 'android',
                            'deleted_at' => null
                        ]);
                    } else {
                        $client_whitelist = $client_whitelist_model->where(['client_id' => $id, 'whitelist_key' => $android['whitelist_key'], 'whitelist_type' => 'android'])->withDeleted(true)->first();
                        if ($client_whitelist) {
                            $client_whitelist_model->update($client_whitelist->id,[
                                'whitelist_name' => $android['whitelist_name'],
                                'whitelist_key' => $android['whitelist_key'],
                                'whitelist_type' => 'android',
                                'deleted_at' => null,
                            ]);
                        } else {
                            $client_whitelist_model->save([
                                'client_id' => $id,
                                'whitelist_name' => $android['whitelist_name'],
                                'whitelist_key' => $android['whitelist_key'],
                                'whitelist_type' => 'android'
                            ]);
                        }
                    }
                }
            }

            if(isset($data['ios'])){
                foreach ($data['ios'] as $ios) {
                    if ((int)$ios['id'] !== 0) {
                        $client_whitelist_model->update($ios['id'],[
                            'whitelist_name' => $ios['whitelist_name'],
                            'whitelist_key' => $ios['whitelist_key'],
                            'whitelist_type' => 'ios',
                            'deleted_at' => null
                        ]);
                    } else {
                        $client_whitelist = $client_whitelist_model->where(['client_id' => $id, 'whitelist_key' => $ios['whitelist_key'], 'whitelist_type' => 'ios'])->withDeleted(true)->first();
                        if ($client_whitelist) {
                            $client_whitelist_model->update( $client_whitelist->id,[
                                'whitelist_name' => $ios['whitelist_name'],
                                'whitelist_key' => $ios['whitelist_key'],
                                'whitelist_type' => 'ios',
                                'deleted_at' => null,
                            ]);
                        } else {
                            $client_whitelist_model->save([
                                'client_id' => $id,
                                'whitelist_name' => $ios['whitelist_name'],
                                'whitelist_key' => $ios['whitelist_key'],
                                'whitelist_type' => 'ios'
                            ]);
                        }
                    }
                }
            }

            return $this->respond(["status" => true, "message" => lang("Api.successSaveClientRequest", [lang("Label.whitelist")]), "data" => []], 200);
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Label.client.text")]), "data" => []], 404);
    }
}

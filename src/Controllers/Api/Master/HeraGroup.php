<?php

namespace Raydragneel\Herauth\Controllers\Api\Master;

use Raydragneel\Herauth\Controllers\Api\BaseHerauthAuthResourceApi;
use Raydragneel\Herauth\Models\HerauthAccountGroupModel;
use Raydragneel\Herauth\Models\HerauthAccountModel;
use Raydragneel\Herauth\Models\HerauthGroupModel;
use Raydragneel\Herauth\Models\HerauthGroupPermissionModel;

class HeraGroup extends BaseHerauthAuthResourceApi
{
    protected $modelName = HerauthGroupModel::class;

    public function index()
    {
        herauth_grant("group.get_groups");
        $data = $this->getDataRequest();
        $limit = -1;
        $offset = 0;
        if (isset($data['limit'])) {
            $limit = (int) $data['limit'];
        }
        if (isset($data['offset'])) {
            $offset = (int) $data['offset'];
        }
        if ($limit > 0) {
            $groups = $this->model->select("id,name,description")->findAll($limit, $offset);
        } else {
            $groups = $this->model->select("id,name,description")->findAll();
        }
        return $this->respond(["status" => true, "message" => lang("Api.successRetrieveRequest", [lang("Web.master.group")]), "data" => $groups], 200);
    }
    public function datatable()
    {
        herauth_grant("group.post_datatable");
        $data = $this->getDataRequest();
        $like = [
            'name' => $data['search']['value'] ?? ''
        ];
        $this->request->message_after = lang("Api.successRetrieveRequest", [lang("Web.master.group")]);
        return $this->respond($this->datatable_get(['withDeleted' => true, 'like' => $like]), 200);
    }

    public function add()
    {
        herauth_grant("group.post_add");
        $data = $this->getDataRequest();
        $rules = [
            'name' => [
                'label'  => lang("Api.validation.master.name", [lang("Web.master.group")]),
                'rules'  => "required|is_unique[herauth_group.name]",
                'errors' => []
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $insertData = [
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
        ];

        if ($this->model->save($insertData)) {
            return $this->respond(["status" => true, "message" => lang("Api.successAddRequest", [lang("Web.master.group")]), "data" => ['redir' => herauth_base_locale_url('master/group')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failAddRequest", [lang("Web.master.group")]), "data" => []], 400);
        }
    }
    public function edit($id = null)
    {
        herauth_grant("group.post_edit");
        $group = $this->model->withDeleted(true)->find($id);
        if (!$group) {
            return $this->response->setStatusCode(404)->setJSON(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.group")]), "data" => []]);
        }
        $data = $this->getDataRequest();
        $rules = [
            'name' => [
                'label'  => lang("Api.validation.master.name", [lang("Web.master.group")]),
                'rules'  => "required|is_unique[herauth_group.name,id,{$id}]",
                'errors' => []
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $update_data = [
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
        ];

        if ($this->model->update($id, $update_data)) {
            return $this->respond(["status" => true, "message" => lang("Api.successEditRequest", [lang("Web.master.group")]), "data" => ['redir' => herauth_base_locale_url('master/group')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failEditRequest", [lang("Web.master.group")]), "data" => []], 400);
        }
    }
    public function delete($id = null)
    {
        $data = $this->getDataRequest();
        if (isset($data['purge'])) {
            herauth_grant("group.post_purge");
            $group = $this->model->where(['name !=' => 'superadmin'])->withDeleted(true)->find($id);
        } else {
            herauth_grant("group.post_delete");
            $group = $this->model->where(['name !=' => 'superadmin'])->find($id);
        }
        if ($group) {
            if (isset($data['purge'])) {
                $delete = $this->model->delete($id, true);
            } else {
                $delete = $this->model->delete($id);
            }
            if ($delete) {
                if (isset($data['purge'])) {
                    $message = lang("Api.successPurgeRequest", [lang("Web.master.group")]);
                } else {
                    $message = lang("Api.successDeleteRequest", [lang("Web.master.group")]);
                }
                return $this->respond(["status" => true, "message" => $message, "data" => []], 200);
            } else {
                if (isset($data['purge'])) {
                    $message = lang("Api.failPurgeRequest", [lang("Web.master.group")]);
                } else {
                    $message = lang("Api.failDeleteRequest", [lang("Web.master.group")]);
                }
                return $this->respond(["status" => false, "message" => $message, "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.group")]), "data" => []], 404);
    }
    public function restore($id = null)
    {
        herauth_grant("group.post_restore");
        $group = $this->model->withDeleted(true)->find($id);
        if ($group) {
            if ($this->model->restore($id)) {
                return $this->respond(["status" => true, "message" => lang("Api.successRestoreRequest", [lang("Web.master.group")]), "data" => []], 200);
            } else {
                return $this->respond(["status" => false, "message" => lang("Api.failRestoreRequest", [lang("Web.master.group")]), "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.group")]), "data" => []], 404);
    }
    public function accounts($id = null)
    {
        herauth_grant("group.get_accounts");
        $group = $this->model->withDeleted(true)->find($id);
        if ($group) {
            $account_group_model = model(HerauthAccountGroupModel::class);
            $data = $this->getDataRequest();
            $limit = -1;
            $offset = 0;
            if (isset($data['limit'])) {
                $limit = (int) $data['limit'];
            }
            if (isset($data['offset'])) {
                $offset = (int) $data['offset'];
            }
            if ($limit > 0) {
                $account_groups = $account_group_model->join("herauth_account", "herauth_account_group.account_id = herauth_account.id", "LEFT")->where(['group_id' => $id])->findAll($limit, $offset);
            } else {
                $account_groups = $account_group_model->join("herauth_account", "herauth_account_group.account_id = herauth_account.id", "LEFT")->where(['group_id' => $id])->findAll();
            }
            return $this->respond(["status" => true, "message" => lang("Api.successRetrieveRequest", [lang("Web.master.account") . " " . lang("Web.master.group")]), "data" => $account_groups], 200);
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.account") . " " . lang("Web.master.group")]), "data" => []], 404);
    }

    public function delete_account_group($id = null)
    {
        herauth_grant("group.post_delete_account_group");
        $rules = [
            'username' => [
                'label'  => lang("Auth.labelUsername"),
                'rules'  => "required",
                'errors' => []
            ]
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }

        $data = $this->getDataRequest();
        $account_model = model(HerauthAccountModel::class);
        $account = $account_model->where(['username' => $data['username']])->first();
        $group = $this->model->withDeleted(true)->find($id);
        if ($group) {
            $account_group_model = model(HerauthAccountGroupModel::class);
            $account_group = $account_group_model->where(['group_id' => $id, 'account_id' => $account->id])->withDeleted(true)->first();
            if ($account_group) {
                $delete = $account_group_model->delete($account_group->id, true);
                if ($delete) {
                    return $this->respond(["status" => true, "message" => lang("Api.successDeleteRequest", [lang("Web.master.account") . " " . lang("Web.master.group")]), "data" => []], 200);
                } else {
                    return $this->respond(["status" => false, "message" => lang("Api.failDeleteRequest", [lang("Web.master.account") . " " . lang("Web.master.group")]), "data" => []], 400);
                }
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.account") . " " . lang("Web.master.group")]), "data" => []], 404);
    }
    public function add_account_group($id = null)
    {
        herauth_grant("group.post_add_account_group");
        $data = $this->getDataRequest();
        $group = $this->model->withDeleted(true)->find($id);
        if ($group) {
            $rules = [
                'username' => [
                    'label'  => lang("Auth.labelUsername"),
                    'rules'  => "required",
                    'errors' => []
                ]
            ];

            if (!$this->validate($rules)) {
                return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
            }
            $account_model = model(HerauthAccountModel::class);
            $account = $account_model->where(['username' => $data['username']])->first();
            if(!$account){
                return $this->response->setStatusCode(404)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => [
                    'username' => 'Akun tidak ditemukan'
                ]]);
            }
            $account_group_model = model(HerauthAccountGroupModel::class);
            $account_id = $account->id;
            $account_group = $account_group_model->where(['group_id' => $id,  'account_id' => $account_id])->withDeleted(true)->first();
            if ($account_group) {
                $save = $account_group_model->update($account_group->id, ['deleted_at' => null]);
            } else {
                $save = $account_group_model->save(['group_id' => $id, 'account_id' => $account_id]);
            }
            if ($save) {
                return $this->respond(["status" => true, "message" => lang("Api.successAddRequest", [lang("Web.master.account") . " " . lang("Web.master.group")]), "data" => []], 200);
            } else {
                return $this->respond(["status" => false, "message" => lang("Api.failAddRequest", [lang("Web.master.account") . " " . lang("Web.master.group")]), "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.account") . " " . lang("Web.master.group")]), "data" => []], 404);
    }

    public function permissions($id = null)
    {
        herauth_grant("group.get_permissions");
        $group = $this->model->withDeleted(true)->find($id);
        if ($group) {
            $data = $this->getDataRequest();
            $limit = -1;
            $offset = 0;
            if (isset($data['limit'])) {
                $limit = (int) $data['limit'];
            }
            if (isset($data['offset'])) {
                $offset = (int) $data['offset'];
            }
            $group_permissions = $group->getPermissions($limit, $offset);
            return $this->respond(["status" => true, "message" => lang("Api.successRetrieveRequest", [lang("Web.master.group")]), "data" => $group_permissions], 200);
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.group")]), "data" => []], 404);
    }

    public function save_permissions($id = null)
    {
        herauth_grant("group.post_save_permissions");
        $data = $this->getDataRequest();
        $group = $this->model->withDeleted(true)->find($id);
        if ($group) {
            $rules = [
                'permissions' => [
                    'label'  => lang("Web.master.permission") . "s",
                    'rules'  => "required",
                    'errors' => []
                ]
            ];

            if (!$this->validate($rules)) {
                return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
            }
            $group_permission_model = model(HerauthGroupPermissionModel::class);
            foreach ($data['permissions'] as $permission) {
                $group_permission = $group_permission_model->where(['group_id' => $group->id, 'permission_id' => $permission['id']])->withDeleted(true)->first();
                if ($group_permission) {
                    if ($permission['checked']) {
                        $group_permission_model->update($group_permission->id, [
                            'deleted_at' => null
                        ]);
                    } else {
                        $group_permission_model->delete($group_permission->id);
                    }
                } else {
                    if ($permission['checked']) {
                        $group_permission_model->save(['group_id' => $group->id, 'permission_id' => $permission['id']]);
                    }
                }
            }
            return $this->respond(["status" => true, "message" => lang("Api.successSaveGroupRequest", [lang("Web.master.permission")]), "data" => []], 200);
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.group")]), "data" => []], 404);
    }
}

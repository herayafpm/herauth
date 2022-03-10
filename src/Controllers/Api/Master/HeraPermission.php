<?php

namespace Raydragneel\Herauth\Controllers\Api\Master;

use Raydragneel\Herauth\Controllers\Api\BaseHerauthAuthResourceApi;
use Raydragneel\Herauth\Models\HerauthPermissionModel;

class HeraPermission extends BaseHerauthAuthResourceApi
{
    protected $modelName = HerauthPermissionModel::class;

    public function index()
    {
        $this->herauth_grant("permission.get_permissions");
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
            $permissions = $this->model->select("id,name,description")->findAll($limit, $offset);
        } else {
            $permissions = $this->model->select("id,name,description")->findAll();
        }
        return $this->respond(["status" => true, "message" => lang("Api.successRetrieveRequest", [lang("Label.permission")]), "data" => $permissions], 200);
    }

    public function datatable()
    {
        $this->herauth_grant("permission.post_datatable");
        $data = $this->getDataRequest();
        $like = [
            'name' => $data['search']['value'] ?? ''
        ];
        $this->request->message_after = lang("Api.successRetrieveRequest", [lang("Label.permission")]);
        return $this->respond($this->datatable_get(['withDeleted' => true, 'like' => $like]), 200);
    }

    public function add()
    {
        $this->herauth_grant("permission.post_add");
        $data = $this->getDataRequest();
        $rules = [
            'name' => [
                'label'  => lang("Label.name")." ".lang("Label.permission"),
                'rules'  => "required|is_unique[herauth_permission.name]",
                'errors' => []
            ],
            'must_login' => [
                'label'  => lang("Label.mustLogin")." ".lang("Label.permission"),
                'rules'  => "required",
                'errors' => []
            ],
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $insertData = [
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'must_login' => (bool)$data['must_login'] ? 1 : 0,
        ];

        if ($this->model->save($insertData)) {
            return $this->respond(["status" => true, "message" => lang("Api.successAddRequest", [lang("Label.permission")]), "data" => ['redir' => herauth_base_locale_url('master/permission')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failAddRequest", [lang("Label.permission")]), "data" => []], 400);
        }
    }
    public function edit($id = null)
    {
        $this->herauth_grant("permission.post_edit");
        $permission = $this->model->withDeleted(true)->find($id);
        if (!$permission) {
            return $this->response->setStatusCode(404)->setJSON(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Label.permission")]), "data" => []]);
        }
        $data = $this->getDataRequest();
        $rules = [
            'name' => [
                'label'  => lang("Label.name") ." ".lang("Label.permission"),
                'rules'  => "required|is_unique[herauth_permission.name,id,{$id}]",
                'errors' => []
            ],
            'must_login' => [
                'label'  => lang("Label.mustLogin") . " ".lang("Label.permission"),
                'rules'  => "required",
                'errors' => []
            ],
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $update_data = [
            'name' => $data['name'],
            'description' => $data['description'] ?? '',
            'must_login' => (bool)$data['must_login'] ? 1 : 0,
        ];

        if ($this->model->update($id, $update_data)) {
            return $this->respond(["status" => true, "message" => lang("Api.successEditRequest", [lang("Label.permission")]), "data" => ['redir' => herauth_base_locale_url('master/permission')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failEditRequest", [lang("Label.permission")]), "data" => []], 400);
        }
    }
    public function delete($id = null)
    {
        $data = $this->getDataRequest();
        if (isset($data['purge'])) {
            $this->herauth_grant("permission.post_purge");
            $permission = $this->model->withDeleted(true)->find($id);
        } else {
            $this->herauth_grant("permission.post_delete");
            $permission = $this->model->find($id);
        }
        if ($permission) {
            if (isset($data['purge'])) {
                $delete = $this->model->delete($id, true);
            } else {
                $delete = $this->model->delete($id);
            }
            if ($delete) {
                if (isset($data['purge'])) {
                    $message = lang("Api.successPurgeRequest", [lang("Label.permission")]);
                } else {
                    $message = lang("Api.successDeleteRequest", [lang("Label.permission")]);
                }
                return $this->respond(["status" => true, "message" => $message, "data" => []], 200);
            } else {
                if (isset($data['purge'])) {
                    $message = lang("Api.failPurgeRequest", [lang("Label.permission")]);
                } else {
                    $message = lang("Api.failDeleteRequest", [lang("Label.permission")]);
                }
                return $this->respond(["status" => false, "message" => $message, "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Label.permission")]), "data" => []], 404);
    }
    public function restore($id = null)
    {
        $this->herauth_grant("permission.post_restore");
        $permission = $this->model->withDeleted(true)->find($id);
        if ($permission) {
            if ($this->model->restore($id)) {
                return $this->respond(["status" => true, "message" => lang("Api.successRestoreRequest", [lang("Label.permission")]), "data" => []], 200);
            } else {
                return $this->respond(["status" => false, "message" => lang("Api.failRestoreRequest", [lang("Label.permission")]), "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Label.permission")]), "data" => []], 404);
    }
}

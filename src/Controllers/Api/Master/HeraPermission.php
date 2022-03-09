<?php

namespace Raydragneel\Herauth\Controllers\Api\Master;

use Raydragneel\Herauth\Controllers\Api\BaseHerauthAuthResourceApi;
use Raydragneel\Herauth\Models\HerauthPermissionModel;

class HeraPermission extends BaseHerauthAuthResourceApi
{
    protected $modelName = HerauthPermissionModel::class;

    public function index()
    {
        herauth_grant("permission.get_permissions");
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
            $permissions = $this->model->select("id,nama,deskripsi")->findAll($limit, $offset);
        } else {
            $permissions = $this->model->select("id,nama,deskripsi")->findAll();
        }
        return $this->respond(["status" => true, "message" => lang("Api.successRetrieveRequest", [lang("Web.master.permission")]), "data" => $permissions], 200);
    }

    public function datatable()
    {
        herauth_grant("permission.post_datatable");
        $data = $this->getDataRequest();
        $like = [
            'nama' => $data['search']['value'] ?? ''
        ];
        $this->request->message_after = lang("Api.successRetrieveRequest", [lang("Web.master.permission")]);
        return $this->respond($this->datatable_get(['withDeleted' => true, 'like' => $like]), 200);
    }

    public function add()
    {
        herauth_grant("permission.post_add");
        $data = $this->getDataRequest();
        $rules = [
            'nama' => [
                'label'  => lang("Api.validation.master.nama", [lang("Web.master.permission")]),
                'rules'  => "required|is_unique[herauth_permission.nama]",
                'errors' => []
            ],
            'must_login' => [
                'label'  => lang("Web.master.must_login", [lang("Web.master.permission")]),
                'rules'  => "required",
                'errors' => []
            ],
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $insertData = [
            'nama' => $data['nama'],
            'deskripsi' => $data['deskripsi'] ?? '',
            'must_login' => (bool)$data['must_login'] ? 1 : 0,
        ];

        if ($this->model->save($insertData)) {
            return $this->respond(["status" => true, "message" => lang("Api.successAddRequest", [lang("Web.master.permission")]), "data" => ['redir' => herauth_base_locale_url('master/permission')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failAddRequest", [lang("Web.master.permission")]), "data" => []], 400);
        }
    }
    public function edit($id = null)
    {
        herauth_grant("permission.post_edit");
        $permission = $this->model->withDeleted(true)->find($id);
        if (!$permission) {
            return $this->response->setStatusCode(404)->setJSON(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.permission")]), "data" => []]);
        }
        $data = $this->getDataRequest();
        $rules = [
            'nama' => [
                'label'  => lang("Api.validation.master.nama", [lang("Web.master.permission")]),
                'rules'  => "required|is_unique[herauth_permission.nama,id,{$id}]",
                'errors' => []
            ],
            'must_login' => [
                'label'  => lang("Web.master.must_login", [lang("Web.master.permission")]),
                'rules'  => "required",
                'errors' => []
            ],
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $update_data = [
            'nama' => $data['nama'],
            'deskripsi' => $data['deskripsi'] ?? '',
            'must_login' => (bool)$data['must_login'] ? 1 : 0,
        ];

        if ($this->model->update($id, $update_data)) {
            return $this->respond(["status" => true, "message" => lang("Api.successEditRequest", [lang("Web.master.permission")]), "data" => ['redir' => herauth_base_locale_url('master/permission')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failEditRequest", [lang("Web.master.permission")]), "data" => []], 400);
        }
    }
    public function delete($id = null)
    {
        $data = $this->getDataRequest();
        if (isset($data['purge'])) {
            herauth_grant("permission.post_purge");
            $permission = $this->model->withDeleted(true)->find($id);
        } else {
            herauth_grant("permission.post_delete");
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
                    $message = lang("Api.successPurgeRequest", [lang("Web.master.permission")]);
                } else {
                    $message = lang("Api.successDeleteRequest", [lang("Web.master.permission")]);
                }
                return $this->respond(["status" => true, "message" => $message, "data" => []], 200);
            } else {
                if (isset($data['purge'])) {
                    $message = lang("Api.failPurgeRequest", [lang("Web.master.permission")]);
                } else {
                    $message = lang("Api.failDeleteRequest", [lang("Web.master.permission")]);
                }
                return $this->respond(["status" => false, "message" => $message, "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.permission")]), "data" => []], 404);
    }
    public function restore($id = null)
    {
        herauth_grant("permission.post_restore");
        $permission = $this->model->withDeleted(true)->find($id);
        if ($permission) {
            if ($this->model->restore($id)) {
                return $this->respond(["status" => true, "message" => lang("Api.successRestoreRequest", [lang("Web.master.permission")]), "data" => []], 200);
            } else {
                return $this->respond(["status" => false, "message" => lang("Api.failRestoreRequest", [lang("Web.master.permission")]), "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.permission")]), "data" => []], 404);
    }
}

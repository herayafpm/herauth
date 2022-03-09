<?php

namespace Raydragneel\Herauth\Controllers\Api\Master;

use Raydragneel\Herauth\Controllers\Api\BaseHerauthAuthResourceApi;
use Raydragneel\Herauth\Models\HerauthAccountGroupModel;
use Raydragneel\Herauth\Models\HerauthAccountModel;

class HeraAccount extends BaseHerauthAuthResourceApi
{
    protected $modelName = HerauthAccountModel::class;

    public function datatable()
    {
        herauth_grant("account.post_datatable");
        $data = $this->getDataRequest();
        $like = [
            'username' => $data['search']['value'] ?? ''
        ];
        $this->request->message_after = lang("Api.successRetrieveRequest", [lang("Web.master.account")]);
        return $this->respond($this->datatable_get(['withDeleted' => true, 'like' => $like]), 200);
    }

    public function add()
    {
        herauth_grant("account.post_add");
        $data = $this->getDataRequest();
        $rules = [
            'username' => [
                'label'  => lang("Auth.labelUsername", [lang("Web.master.account")]),
                'rules'  => "required|is_unique[herauth_account.username]",
                'errors' => []
            ],
            'password' => [
                'label'  => lang("Auth.labelPassword", [lang("Web.master.account")]),
                'rules'  => "required|min_length[6]",
                'errors' => []
            ],
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $insertData = [
            'username' => $data['username'],
            'password' => $data['password'],
        ];

        if ($this->model->save($insertData)) {
            return $this->respond(["status" => true, "message" => lang("Api.successAddRequest", [lang("Web.master.account")]), "data" => ['redir' => herauth_base_locale_url('master/account')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failAddRequest", [lang("Web.master.account")]), "data" => []], 400);
        }
    }
    public function edit($id = null)
    {
        herauth_grant("account.post_edit");
        $account = $this->model->withDeleted(true)->find($id);
        if (!$account) {
            return $this->response->setStatusCode(404)->setJSON(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.account")]), "data" => []]);
        }
        $data = $this->getDataRequest();
        $rules = [
            'username' => [
                'label'  => lang("Auth.labelUsername", [lang("Web.master.account")]),
                'rules'  => "required|is_unique[herauth_account.username,id,{$id}]",
                'errors' => []
            ],
            'password' => [
                'label'  => lang("Auth.labelPassword", [lang("Web.master.account")]),
                'rules'  => "sometime_len[6]",
                'errors' => []
            ],
        ];

        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $update_data = [
            'username' => $data['username'],
            'password' => $data['password'],
        ];

        if (empty($data['password'])) {
            unset($update_data['password']);
        }

        if ($this->model->update($id, $update_data)) {
            return $this->respond(["status" => true, "message" => lang("Api.successEditRequest", [lang("Web.master.account")]), "data" => ['redir' => herauth_base_locale_url('master/account')]], 200);
        } else {
            return $this->respond(["status" => false, "message" => lang("Api.failEditRequest", [lang("Web.master.account")]), "data" => []], 400);
        }
    }
    public function delete($id = null)
    {
        $data = $this->getDataRequest();
        if (isset($data['purge'])) {
            herauth_grant("account.post_purge");
            $account = $this->model->where(['username !=' => 'admin11'])->withDeleted(true)->find($id);
        } else {
            herauth_grant("account.post_delete");
            $account = $this->model->where(['username !=' => 'admin11'])->find($id);
        }
        if ($account) {
            if (isset($data['purge'])) {
                $delete = $this->model->delete($id, true);
            } else {
                $delete = $this->model->delete($id);
            }
            if ($delete) {
                if (isset($data['purge'])) {
                    $message = lang("Api.successPurgeRequest", [lang("Web.master.account")]);
                } else {
                    $message = lang("Api.successDeleteRequest", [lang("Web.master.account")]);
                }
                return $this->respond(["status" => true, "message" => $message, "data" => []], 200);
            } else {
                if (isset($data['purge'])) {
                    $message = lang("Api.failPurgeRequest", [lang("Web.master.account")]);
                } else {
                    $message = lang("Api.failDeleteRequest", [lang("Web.master.account")]);
                }
                return $this->respond(["status" => false, "message" => $message, "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.account")]), "data" => []], 404);
    }
    public function restore($id = null)
    {
        herauth_grant("account.post_restore");
        $account = $this->model->withDeleted(true)->find($id);
        if ($account) {
            if ($this->model->restore($id)) {
                return $this->respond(["status" => true, "message" => lang("Api.successRestoreRequest", [lang("Web.master.account")]), "data" => []], 200);
            } else {
                return $this->respond(["status" => false, "message" => lang("Api.failRestoreRequest", [lang("Web.master.account")]), "data" => []], 400);
            }
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.account")]), "data" => []], 404);
    }
    public function groups($id = null)
    {
        herauth_grant("account.get_groups");
        $account = $this->model->withDeleted(true)->find($id);
        if ($account) {
            $data = $this->getDataRequest();
            $limit = -1;
            $offset = 0;
            if (isset($data['limit'])) {
                $limit = (int) $data['limit'];
            }
            if (isset($data['offset'])) {
                $offset = (int) $data['offset'];
            }
            return $this->respond(["status" => true, "message" => lang("Api.successRetrieveRequest", [lang("Web.master.account")]), "data" => $account->getGroups($limit,$offset)], 200);
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.account")]), "data" => []], 404);
    }

    public function save_group($id = null)
    {
        herauth_grant("account.post_save_group");
        $data = $this->getDataRequest();
        $account = $this->model->withDeleted(true)->find($id);
        if ($account) {
            $rules = [
                'groups' => [
                    'label'  => lang("Web.master.group")."s",
                    'rules'  => "required",
                    'errors' => []
                ]
            ];
    
            if (!$this->validate($rules)) {
                return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
            }
            $account_group_model = model(HerauthAccountGroupModel::class);
            foreach ($data['groups'] as $group) {
                $account_group = $account_group_model->where(['account_id' => $account->id, 'group_id' => $group['id']])->withDeleted(true)->first();
                if ($account_group) {
                    if ($group['checked']) {
                        $account_group_model->update($account_group->id, [
                            'deleted_at' => null
                        ]);
                    } else {
                        $account_group_model->delete($account_group->id);
                    }
                } else {
                    if ($group['checked']) {
                        $account_group_model->save(['account_id' => $account->id, 'group_id' => $group['id']]);
                    }
                }
            }
            return $this->respond(["status" => true, "message" => lang("Api.successSaveGroupRequest",[lang("Web.master.account")]), "data" => []], 200);
        }
        return $this->respond(["status" => false, "message" => lang("Api.ApiRequestNotFound", [lang("Web.master.account")]), "data" => []], 404);
    }
}

<?php

namespace Raydragneel\Herauth\Controllers\Api;

use Raydragneel\Herauth\Entities\HerauthAccountEntity;
use DomainException;
use Raydragneel\Herauth\Libraries\ClaJWT;
use Raydragneel\Herauth\Models\HerauthAccountModel;

class HeraAuth extends BaseHerauthResourceApi
{
    protected $modelName = HerauthAccountModel::class;
    protected function rules_login($key = null)
    {
        $rules = [
            'username' => [
                'label'  => lang("Label.username"),
                'rules'  => 'required',
                'errors' => []
            ],
            'password' => [
                'label'  => lang("Label.password"),
                'rules'  => 'required',
                'errors' => []
            ],
        ];
        if ($key) {
            if (!key_exists($key, $rules)) {
                throw new DomainException(lang("Validation.notFound"), 400);
            } else {
                return [
                    $key => $rules[$key]
                ];
            }
        } else {
            return $rules;
        }
    }

    public function login()
    {

        // $throttler = Services::throttler();
        // $key_throttler = md5($this->request->getIPAddress());
        // if ($throttler->check($key_throttler, 3, MINUTE) === false) {
        //     return $this->response->setStatusCode(429)->setJSON(["status" => false, "message" => lang("Api.tooManyAttemptRequest"), "data" => []]);
        // }
        $this->herauth_grant("auth.login");
        try {
            $rules = $this->rules_login();
        } catch (\DomainException $th) {
            return $this->response->setStatusCode($th->getCode())->setJSON(["status" => false, "message" => $th->getMessage(), "data" => []]);
        }
        if (!$this->validate($rules)) {
            return $this->response->setStatusCode(400)->setJSON(["status" => false, "message" => lang("Validation.errorValidation"), "data" => $this->validator->getErrors()]);
        }
        $data = $this->getDataRequest();
        $admin_entity = new HerauthAccountEntity($data);
        $login_success = $this->model->attempt($admin_entity);
        $username = $admin_entity->username;
        $message = $this->model->getMessage();
        if ($login_success) {
            $data_res = [];
            $jenis_akses = service('herauth')->getJenisAkses();
            if ($jenis_akses === 'web') {
                $ses['username'] = $username;
                $ses['name'] = $login_success->name;
                $this->session->set($ses);
                $data_res['redir'] = isset($data['redir'])?base_locale_url($data['redir']) : herauth_base_locale_url('');
            } else if ($jenis_akses === 'api') {
                $jwt = ClaJWT::encode(['username' => $username], null, false, false);
                if ($jwt) {
                    $data_res = $jwt;
                }
            }
            return $this->respond(["status" => true, "message" => $message, "data" => $data_res], 200);
        } else {
            return $this->respond(["status" => false, "message" => $message, "data" => []], 400);
        }
    }
}

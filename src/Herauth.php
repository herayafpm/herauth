<?php

namespace Raydragneel\Herauth;

use Raydragneel\Herauth\Filters\AfterRequestFilter;
use Raydragneel\Herauth\Libraries\ClaJWT;
use Raydragneel\Herauth\Models\HerauthAccountModel;
use Raydragneel\Herauth\Models\HerauthClientModel;
use Raydragneel\Herauth\Models\HerauthGroupModel;
use Raydragneel\Herauth\Models\HerauthPermissionModel;
use Raydragneel\Herauth\Models\HerauthRequestLogModel;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use DomainException;

class Herauth
{
    protected $permission_model = null;
    protected $group_model = null;
    protected $request_log_model = null;
    protected $client_model = null;
    protected $request = null;
    protected $response = null;
    protected $client = null;
    protected $session = null;
    protected $account = null;
    protected $type = null;
    protected $jwt = null;
    protected $data = [];
    public function __construct(RequestInterface $request, ResponseInterface $response, $args = [])
    {
        $this->request = $request;
        $this->response = $response;
        $this->permission_model = model(HerauthPermissionModel::class);
        $this->group_model = model(HerauthGroupModel::class);
        $this->request_log_model = model(HerauthRequestLogModel::class);
        $segment = 0;
        if(isset($request->uri->getSegments()[0])){
            if ($request->uri->getSegments()[0] === 'herauth') {
                $segment = 1;
            }
        }
        $this->type = $request->uri->getSegments()[$segment] ?? 'web';
        if ($this->type === 'api') {
            $this->client_model = model(HerauthClientModel::class);
            if (!isset($args['pass_client'])) {
                $this->cekClient();
            }
            $this->cekAccountApi();
        } else {
            $this->session = service('session');
            if ($this->session->has('username')) {
                $this->setAccount();
            }
        }
    }

    public function cekAccountApi()
    {
        if ($this->request->hasHeader('account-key')) {
            try {
                $accountKey = $this->request->getHeader('account-key')->getValue() ?? '';
                if (empty($accountKey)) {
                    throw new DomainException(lang("Filters.accountKey.cannotEmpty"));
                }
                if (strpos($accountKey, 'Bearer ') === false) {
                    throw new DomainException(lang("Filters.accountKey.errorStructure"));
                }
                $accountKey = explode(" ", $accountKey);
                if (sizeof($accountKey) < 2) {
                    throw new DomainException(lang("Filters.accountKey.errorStructure"));
                }
                $jwt = ClaJWT::decode($accountKey[1]);
                $this->jwt = $jwt;
                $this->setAccount();
            } catch (\Throwable $th) {
                $data_res = [
                    'status' => false,
                    'message' => "",
                    'data' => []
                ];
                if (!empty($th->getMessage())) {
                    $data_res['message'] = $th->getMessage();
                }
                $after_request_filter = new AfterRequestFilter();
                $response = $this->response->setStatusCode(401)->setJSON($data_res);
                $after_request_filter->after($this->request, $response, [])->send();
                die();
            }
        }
    }

    public function cekClient()
    {
        try {
            if (!$this->request->hasHeader('api-key')) {
                throw new DomainException(lang("Filters.apiKey.IsRequired"));
            }
            $apiKey = $this->request->getHeader('api-key')->getValue() ?? '';
            if (empty($apiKey)) {
                throw new DomainException(lang("Filters.apiKey.cannotEmpty"));
            }
            $client = $this->client_model->findByClientKey($apiKey);
            if (empty($client)) {
                throw new DomainException(lang("Filters.apiKey.notFound"));
            }
            $client->cekWhitelist();
            $client->cekExpiredAndLimit();
            $this->client = $client;
        } catch (\Throwable $th) {
            $data_res = [
                'status' => false,
                'message' => "",
                'data' => []
            ];
            if (!empty($th->getMessage())) {
                $data_res['message'] = $th->getMessage();
            }
            $after_request_filter = new AfterRequestFilter();
            $response = $this->response->setStatusCode(401)->setJSON($data_res);
            $after_request_filter->after($this->request, $response, [])->send();
            die();
        }
    }

    public function setClient()
    {
        if ($this->request->hasHeader('api-key')) {
            $apiKey = $this->request->getHeader('api-key')->getValue() ?? '';
            if (!empty($apiKey)) {
                $client = $this->client_model->findByClientKey($apiKey);
                $this->client = $client;
            }
        }
    }


    public function setAccount()
    {
        if ($this->type === 'api') {
            $username = $this->jwt->username;
        } else {
            $username = $this->session->get('username');
        }
        $admin_model = model(HerauthAccountModel::class);
        $account = $admin_model->cekAccount($username);
        if (!$account) {
            $data_res['status'] = false;
            $data_res['message'] = lang("Filters.notAuthorized");
            $data_res['data'] = [];
            $after_request_filter = new AfterRequestFilter();
            if ($this->type === 'web') {
                $after_request_filter->after($this->request, $this->response);
                $configHerauth = config("Herauth");
                echo view($configHerauth->unauthorizedPageView);
            } else {
                $after_request_filter->after($this->request, $this->response)->send();
            }
            die();
        }

        $this->account = $account ?? null;
    }

    public function getClient()
    {
        return $this->client;
    }
    public function getAccount()
    {
        return $this->account;
    }

    public function grant($perm, $type,$args)
    {
        if(isset($args['data'])){
            $this->data = $args['data'];
        }

        $permission = $this->permission_model->findPermissionByName($perm);
        if ($permission) {
            if ($this->type === 'api') {
                $type = 'api';
                if ((bool) $permission->must_login) {
                    if ($this->client->hasPermission($perm)) {
                        if (isset($this->account)) {
                            if ($this->account->hasPermission($perm)) {
                                return true;
                            }
                        }
                    }
                } else {
                    if ($this->client->hasPermission($perm)) {
                        return true;
                    }
                }
            } else {
                if ($this->session->has('username')) {
                    $this->request->jenis_akses = 'web';
                    if ($this->account->hasPermission($perm)) {
                        return true;
                    }
                } else if (!(bool) $permission->must_login) {
                    return true;
                }
            }
        }
        $data_res['status'] = false;
        $data_res['message'] = lang("Filters.notAuthorized");
        $data_res['data'] = [];
        $after_request_filter = new AfterRequestFilter();
        $response = $this->response;
        if ($type === 'page') {
            $response = $response->setStatusCode(401)->setJSON($data_res);
            $after_request_filter->after($this->request, $response);
            $configHerauth = config("Herauth");
            echo view($configHerauth->unauthorizedPageView,$this->data);
        } else if ($type === 'api') {
            $response = $response->setStatusCode(401)->setJSON($data_res);
            $after_request_filter->after($this->request, $response)->send();
        } else {
            return false;
        }
        die();
    }
    public function grant_group($groupName, $type,$args = [])
    {
        if(isset($args['data'])){
            $this->data = $args['data'];
        }
        $groups = $groupName;
        if (!is_array($groupName)) {
			$groups = [$groupName];
		}
        if ($this->type === 'api') {
            $type = 'api';
            if (isset($this->account)) {
                if ($this->account->inGroup($groups)) {
                    return true;
                }
            }
        } else {
            if ($this->session->has('username')) {
                $this->request->jenis_akses = 'web';
                if ($this->account->inGroup($groups)) {
                    return true;
                }
            }
        }
        $data_res['status'] = false;
        $data_res['message'] = lang("Filters.notAuthorized");
        $data_res['data'] = [];
        $after_request_filter = new AfterRequestFilter();
        $response = $this->response;
        if ($type === 'page') {
            $response = $response->setStatusCode(401)->setJSON($data_res);
            $after_request_filter->after($this->request, $response);
            $configHerauth = config("Herauth");
            echo view($configHerauth->unauthorizedPageView,$this->data);
        } else if ($type === 'api') {
            $response = $response->setStatusCode(401)->setJSON($data_res);
            $after_request_filter->after($this->request, $response)->send();
        } else {
            return false;
        }
        die();
    }

    public function requestLog()
    {
        $ipAddress = $this->request->getIPAddress();
        $userAgent = $this->request->getUserAgent()->getAgentString() ?? '';
        $path = $this->request->uri->getPath();
        $method = $this->request->getMethod();
        $username = null;
        $client = null;
        if ($this->response->getStatusCode() != 500) {
            $body = json_decode($this->response->getBody());
            $message = $body->message ?? "";
        } else {
            $message = $this->response->getReason();
        }
        if (!empty($this->type)) {
            if ($this->type === 'web') {
                if ($this->session->has('username')) {
                    $username = $this->session->get('username');
                }
                $client = 'web';
            } else {
                if ($this->account->username ?? '' !== '') {
                    $username = $this->account->username;
                }
                if ($this->client) {
                    $client = $this->client->getClientEncodeText();
                    $this->client->setLimit();
                }
            }
        }
        if (isset($this->request->message_after)) {
            $message = $this->request->message_after;
        }
        $this->request_log_model->save([
            'username'            => $username,
            'client' => $client,
            'path'            => $path,
            'method'            => $method,
            'ip'            => $ipAddress,
            'user_agent'            => $userAgent,
            'status_code'            => $this->response->getStatusCode(),
            'status_message'            => $message,
        ]);
    }

    public function getJenisAkses()
    {
        return $this->type;
    }
}

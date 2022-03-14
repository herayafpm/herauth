<?php

namespace Raydragneel\Herauth\Controllers\Master;

use Raydragneel\Herauth\Models\HerauthClientModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class HeraClient extends BaseHerauthMasterController
{
    protected $modelName = HerauthClientModel::class;

    public function index()
    {
        $this->herauth_grant("client.view_index","page");
        $data = [
            'page_title' => lang("Label.client.text"),
            'url_datatable' => herauth_web_url($this->root_view . "client/datatable"),
            'url_add' => herauth_base_locale_url($this->root_view . "client/add"),
            'url_edit' => herauth_base_locale_url($this->root_view . "client/{0}/edit"),
            'url_delete' => herauth_web_url($this->root_view . "client/{0}/delete"),
            'url_restore' => herauth_web_url($this->root_view . "client/{0}/restore"),
            'url_regenerate_key' => herauth_web_url($this->root_view . "client/{0}/regenerate_key"),
            'url_permissions' => herauth_base_locale_url($this->root_view . "client/{0}permissions"),
            'url_whitelists' => herauth_base_locale_url($this->root_view . "client/{0}/whitelists"),
        ];
        return $this->view("client/index", $data);
    }

    public function add()
    {
        $this->herauth_grant("client.view_add","page");
        $data = [
            'page_title' => lang("Label.add")." ".lang("Label.client.text"),
            'url_add' => herauth_web_url($this->root_view . "client/add"),
        ];
        return $this->view("client/add", $data);
    }
    public function edit($id = null)
    {
        $this->herauth_grant("client.view_edit","page");
        $client = $this->model->withDeleted(true)->find($id);
        if (!$client) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Label.edit")." ".lang("Label.client.text")." " . $client->name,
            'client' => $client,
            'url_edit' => herauth_web_url($this->root_view . "client/{$id}/edit"),
        ];
        return $this->view("client/edit", $data);
    }

    public function permissions($id = null)
    {
        $this->herauth_grant("client.view_permissions","page");
        $client = $this->model->withDeleted(true)->find($id);
        if (!$client) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Label.client.text")." ".lang("Label.permission")." " . $client->name,
            'client' => $client,
            'url_save' => herauth_web_url($this->root_view . "client/{$id}/save_permissions"),
            'url_permissions' => herauth_web_url($this->root_view . "permission"),
            'url_client_permissions' => herauth_web_url($this->root_view . "client/{$id}/permissions"),
        ];
        return $this->view("client/permission", $data);
    }
    public function whitelists($id = null)
    {
        $this->herauth_grant("client.view_whitelists","page");
        $client = $this->model->withDeleted(true)->find($id);
        if (!$client) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Label.client.text")." ".lang("Label.whitelist")." " . $client->name,
            'client' => $client,
            'url_save' => herauth_web_url($this->root_view . "client/{$id}/save_whitelists"),
        ];
        return $this->view("client/whitelist", $data);
    }

}

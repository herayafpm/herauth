<?php

namespace Raydragneel\Herauth\Controllers\Master;
use CodeIgniter\Exceptions\PageNotFoundException;
use Raydragneel\Herauth\Models\HerauthGroupModel;

class HeraGroup extends BaseHerauthMasterController
{
    protected $modelName = HerauthGroupModel::class;

    public function index()
    {
        $this->herauth_grant("group.view_index","page");
        $data = [
            'page_title' => lang("Label.group"),
            'url_datatable' => herauth_web_url($this->root_view . "group/datatable"),
            'url_add' => herauth_base_locale_url($this->root_view . "group/add"),
            'url_edit' => herauth_base_locale_url($this->root_view . "group/{0}/edit"),
            'url_delete' => herauth_web_url($this->root_view . "group/{0}/delete"),
            'url_restore' => herauth_web_url($this->root_view . "group/{0}/restore"),
            'url_accounts' => herauth_base_locale_url($this->root_view . "group/{0}/accounts"),
            'url_permissions' => herauth_base_locale_url($this->root_view . "group/{0}/permissions"),
        ];
        return $this->view("group/index", $data);
    }

    public function add()
    {
        $this->herauth_grant("group.view_add","page");
        $data = [
            'page_title' => lang("Label.add")." ".lang("Label.group"),
            'url_add' => herauth_web_url($this->root_view . "group/add"),
        ];
        return $this->view("group/add", $data);
    }
    public function edit($id = null)
    {
        $this->herauth_grant("group.view_edit","page");
        $group = $this->model->withDeleted(true)->find($id);
        if (!$group) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Label.edit")." ".lang("Label.group")." " . $group->name,
            'group' => $group,
            'url_edit' => herauth_web_url($this->root_view . "group/{$id}/edit"),
        ];
        return $this->view("group/edit", $data);
    }


    public function accounts($id = null)
    {
        $this->herauth_grant("group.view_accounts","page");
        $group = $this->model->withDeleted(true)->find($id);
        if (!$group) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Label.account")." ".lang("Label.group")." " . $group->name,
            'group' => $group,
            'url_add_account_group' => herauth_web_url($this->root_view . "group/{$id}/add_account_group"),
            'url_delete_account_group' => herauth_web_url($this->root_view . "group/{$id}/delete_account_group"),
            'url_account_groups' => herauth_web_url($this->root_view . "group/{$id}/accounts"),
        ];
        return $this->view("group/accounts", $data);
    }

    public function permissions($id = null)
    {
        $this->herauth_grant("group.view_permissions","page");
        $group = $this->model->withDeleted(true)->find($id);
        if (!$group) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Label.group")." ".lang("Label.permission")." " . $group->name,
            'group' => $group,
            'url_save' => herauth_web_url($this->root_view . "group/{$id}/save_permissions"),
            'url_permissions' => herauth_web_url($this->root_view . "permission"),
            'url_group_permissions' => herauth_web_url($this->root_view . "group/{$id}/permissions"),
        ];
        return $this->view("group/permission", $data);
    }
}

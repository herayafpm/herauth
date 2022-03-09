<?php

namespace Raydragneel\Herauth\Controllers\Master;

use Raydragneel\Herauth\GroupModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class HeraGroup extends BaseAmikompwtMasterController
{
    protected $modelName = HerauthGroupModel::class;

    public function index()
    {
        herauth_grant("group.view_index","page");
        $data = [
            'page_title' => lang("Web.master.group"),
            'url_datatable' => herauth_web_url($this->root_view . "group/datatable"),
            'url_add' => herauth_base_locale_url($this->root_view . "group/add"),
            'url_edit' => herauth_base_locale_url($this->root_view . "group/edit/"),
            'url_delete' => herauth_web_url($this->root_view . "group/delete/"),
            'url_restore' => herauth_web_url($this->root_view . "group/restore/"),
            'url_accounts' => herauth_base_locale_url($this->root_view . "group/accounts/"),
            'url_permissions' => herauth_base_locale_url($this->root_view . "group/permissions/"),
        ];
        return $this->view("group/index", $data);
    }

    public function add()
    {
        herauth_grant("group.view_add","page");
        $data = [
            'page_title' => lang("Web.add")." ".lang("Web.master.group"),
            'url_add' => herauth_web_url($this->root_view . "group/add"),
        ];
        return $this->view("group/add", $data);
    }
    public function edit($id = null)
    {
        herauth_grant("group.view_edit","page");
        $group = $this->model->withDeleted(true)->find($id);
        if (!$group) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Web.edit")." ".lang("Web.master.group")." " . $group->nama,
            'group' => $group,
            'url_edit' => herauth_web_url($this->root_view . "group/edit/".$id),
        ];
        return $this->view("group/edit", $data);
    }


    public function accounts($id = null)
    {
        herauth_grant("group.view_accounts","page");
        $group = $this->model->withDeleted(true)->find($id);
        if (!$group) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Web.master.account")." ".lang("Web.master.group")." " . $group->nama,
            'group' => $group,
            'url_add_account_group' => herauth_web_url($this->root_view . "group/add_account_group/".$id),
            'url_delete_account_group' => herauth_web_url($this->root_view . "group/delete_account_group/".$id),
            'url_account_groups' => herauth_web_url($this->root_view . "group/accounts/".$id),
        ];
        return $this->view("group/accounts", $data);
    }

    public function permissions($id = null)
    {
        herauth_grant("group.view_permissions","page");
        $group = $this->model->withDeleted(true)->find($id);
        if (!$group) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Web.master.group")." ".lang("Web.master.permission")." " . $group->nama,
            'group' => $group,
            'url_save' => herauth_web_url($this->root_view . "group/save_permissions/".$id),
            'url_permissions' => herauth_web_url($this->root_view . "permission"),
            'url_group_permissions' => herauth_web_url($this->root_view . "group/permissions/".$id),
        ];
        return $this->view("group/permission", $data);
    }
}

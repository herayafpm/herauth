<?php

namespace Raydragneel\Herauth\Controllers\Master;

use Raydragneel\Herauth\Models\HerauthAccountModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class HeraAccount extends BaseHerauthMasterController
{
    protected $modelName = HerauthAccountModel::class;

    public function index()
    {
        herauth_grant('account.view_index','page');
        $data = [
            'page_title' => lang("Web.master.account"),
            'url_datatable' => herauth_web_url($this->root_view . "account/datatable"),
            'url_add' => herauth_base_locale_url($this->root_view . "account/add"),
            'url_edit' => herauth_base_locale_url($this->root_view . "account/edit/"),
            'url_delete' => herauth_web_url($this->root_view . "account/delete/"),
            'url_restore' => herauth_web_url($this->root_view . "account/restore/"),
            'url_group' => herauth_base_locale_url($this->root_view . "account/group/"),
        ];
        return $this->view("account/index", $data);
    }

    public function add()
    {
        herauth_grant('account.view_add','page');
        $data = [
            'page_title' => lang("Web.add")." ".lang("Web.master.account"),
            'url_add' => herauth_web_url($this->root_view . "account/add"),
        ];
        return $this->view("account/add", $data);
    }
    public function edit($id = null)
    {
        herauth_grant('account.view_edit','page');
        $account = $this->model->withDeleted(true)->find($id);
        if (!$account) {
            throw new PageNotFoundException();
        }
        $data = [
            'page_title' => lang("Web.edit")." ".lang("Web.master.account")." " . $account->profil->name,
            'account' => $account,
            'url_edit' => herauth_web_url($this->root_view . "account/edit/".$id),
        ];
        return $this->view("account/edit", $data);
    }
    public function group($id = null)
    {
        herauth_grant('account.view_group','page');
        $account = $this->model->withDeleted(true)->find($id);
        if (!$account) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Web.master.group")." ".lang("Web.master.account")." " . $account->profil->name,
            'account' => $account,
            'url_save' => herauth_web_url($this->root_view . "account/save_group/".$id),
            'url_groups' => herauth_web_url($this->root_view . "group"),
            'url_account_groups' => herauth_web_url($this->root_view . "account/groups/".$id),
        ];
        return $this->view("account/group", $data);
    }

}

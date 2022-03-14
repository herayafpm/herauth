<?php

namespace Raydragneel\Herauth\Controllers\Master;

use Raydragneel\Herauth\Models\HerauthAccountModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class HeraAccount extends BaseHerauthMasterController
{
    protected $modelName = HerauthAccountModel::class;

    public function index()
    {
        $this->herauth_grant('account.view_index','page');
        $data = [
            'page_title' => lang("Label.account"),
            'url_datatable' => herauth_web_url($this->root_view . "account/datatable"),
            'url_add' => herauth_base_locale_url($this->root_view . "account/add"),
            'url_edit' => herauth_base_locale_url($this->root_view . "account/{0}/edit"),
            'url_delete' => herauth_web_url($this->root_view . "account/{0}/delete"),
            'url_restore' => herauth_web_url($this->root_view . "account/{0}/restore"),
            'url_group' => herauth_base_locale_url($this->root_view . "account/{0}/group"),
        ];
        return $this->view("account/index", $data);
    }

    public function add()
    {
        $this->herauth_grant('account.view_add','page');
        $data = [
            'page_title' => lang("Label.add")." ".lang("Label.account"),
            'url_add' => herauth_web_url($this->root_view . "account/add"),
        ];
        return $this->view("account/add", $data);
    }
    public function edit($id = null)
    {
        $this->herauth_grant('account.view_edit','page');
        $account = $this->model->withDeleted(true)->find($id);
        if (!$account) {
            throw new PageNotFoundException();
        }
        $data = [
            'page_title' => lang("Label.edit")." ".lang("Label.account")." " . $account->profil->name,
            'account' => $account,
            'url_edit' => herauth_web_url($this->root_view . "account/{$id}/edit"),
        ];
        return $this->view("account/edit", $data);
    }
    public function group($id = null)
    {
        $this->herauth_grant('account.view_group','page');
        $account = $this->model->withDeleted(true)->find($id);
        if (!$account) {
            throw new PageNotFoundException();
        }

        $data = [
            'page_title' => lang("Label.group")." ".lang("Label.account")." " . $account->profil->name,
            'account' => $account,
            'url_save' => herauth_web_url($this->root_view . "account/{$id}/save_group"),
            'url_groups' => herauth_web_url($this->root_view . "group"),
            'url_account_groups' => herauth_web_url($this->root_view . "account/{$id}/groups"),
        ];
        return $this->view("account/group", $data);
    }

}

<?php

namespace Raydragneel\Herauth\Controllers;

use Raydragneel\Herauth\Models\HerauthRequestLogModel;

class HeraRequestLog extends BaseHerauthAuthController
{
    protected $modelName = HerauthRequestLogModel::class;

    public function index()
    {
        herauth_grant("request_log.view_index","page");
        $data = [
            'page_title' => lang("Web.requestLog"),
            'url_datatable' => herauth_web_url($this->root_view . "request_log/datatable"),
        ];
        return $this->view("request_log/index", $data);
    }
}
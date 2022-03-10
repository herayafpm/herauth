<?php

namespace Raydragneel\Herauth\Controllers;
use Config\Services;

class HeraHome extends BaseHerauthAuthController
{
    public function index()
    {
        $data = [
            'url_datatable' => herauth_web_url($this->root_view . "request_log/datatable"),
        ];
        return $this->view('dashboard', $data);
    }

    public function redirLocale()
    {
        return redirect()->to(herauth_base_locale_url());
    }

}

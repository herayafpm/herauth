<?php

namespace Raydragneel\Herauth\Controllers;

class HeraHome extends BaseHerauthAuthController
{
    public function index()
    {
        $data = [];
        return $this->view('dashboard', $data);
    }

    public function redirLocale()
    {
        return redirect()->to(herauth_base_locale_url());
    }
}

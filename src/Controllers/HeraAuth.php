<?php

namespace Raydragneel\Herauth\Controllers;

class HeraAuth extends BaseHerauthController
{
    public function login()
    {
        $data = [];
        return $this->view('auth/login',$data);
    }

    public function logout()
    {
        $this->session->destroy();
        return redirect()->to(herauth_base_locale_url("login"));
    }
}

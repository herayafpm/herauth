<?php

namespace Raydragneel\Herauth\Config;

use CodeIgniter\Config\BaseConfig;

class Herauth extends BaseConfig
{
    public $sym = "+";
    public $duration = 10;
    public $unit = 'seconds';
    public $symRefresh = "+";
    public $durationRefresh = 20;
    public $unitRefresh = 'seconds';

    public $redirectLogin = 'login';
    public $redirectMain = '';
    public $herauthLangJsUrl = '';
    public $mainLangJsUrl = '';
    public $unauthorizedPageView = 'errors/unauthorized';

    public function __construct()
    {
        parent::__construct();
        require __DIR__."/../Helpers/herauth_main_helper.php";
        $this->herauthLangJsUrl = herauth_asset_url('lang');
    }
}

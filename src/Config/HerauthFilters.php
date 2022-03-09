<?php

namespace Raydragneel\Herauth\Config;

use Raydragneel\Herauth\Filters\AfterRequestFilter;
use Raydragneel\Herauth\Filters\ApiFilter;
use Raydragneel\Herauth\Filters\AuthApiFilter;
use Raydragneel\Herauth\Filters\AuthFilter;
use CodeIgniter\Config\BaseConfig;

class HerauthFilters extends BaseConfig
{
    /**
     * Configures aliases for Filter classes to
     * make reading things nicer and simpler.
     *
     * @var array
     */
    public $aliases = [
        'api_filter' => ApiFilter::class,
        'after_request_filter' => AfterRequestFilter::class,
        'auth_filter' => AuthFilter::class,
        'auth_api_filter' => [
            ApiFilter::class,
            AuthApiFilter::class,
        ],
    ];

    /**
     * List of filter aliases that are always
     * applied before and after every request.
     *
     * @var array
     */
    public $globals = [
        'before' => [
        ],
        'after' => [
        ],
    ];

    /**
     * List of filter aliases that works on a
     * particular HTTP method (GET, POST, etc.).
     *
     * Example:
     * 'post' => ['csrf', 'throttle']
     *
     * @var array
     */
    public $methods = [];

    /**
     * List of filter aliases that should run on any
     * before or after URI patterns.
     *
     * Example:
     * 'isLoggedIn' => ['before' => ['account/*', 'profiles/*']]
     *
     * @var array
     */
    public $filters = [
        'api_filter' => ['before' => ['herauth/api','herauth/web','herauth/api/*','herauth/web/*']],
        'after_request_filter' => ['after' => ['herauth/api','herauth/web','herauth/api/*','herauth/web/*']]
    ];
}

<?php

namespace Raydragneel\Herauth\Controllers\Api;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class BaseHerauthAuthResourceApi extends BaseHerauthResourceApi
{
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        $this->herauth_grant_group('herauth');
    }
}

<?php

namespace Raydragneel\Herauth\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class AfterRequestFilter implements FilterInterface
{

    public function before(RequestInterface $request, $arguments = null)
    {
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        if(strpos($request->uri->getPath(),'/request_log/datatable') === false){
            service('herauth',['pass_client' => true])->requestLog();
        }
        return $response;
    }
}

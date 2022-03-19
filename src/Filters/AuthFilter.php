<?php

namespace Raydragneel\Herauth\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use DomainException;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('herauth_main');
        $config = config('Herauth');
        $segments = $request->uri->getSegments();
        try {
            $session = service('session');
            if(!$session->has('username')){
                throw new DomainException();
            }
            if($segments[0] === 'herauth'){
                if(sizeof($segments) > 2){
                    if($segments[2] === 'login'){
                        return redirect()->to(herauth_base_locale_url());
                    }
                }
            }else{
                if($request->uri->getPath() === $config->redirectLogin){
                    return redirect()->to(base_url($config->redirectMain));
                }
            }
        } catch (\DomainException $th) {
            if($segments[0] === 'herauth'){
                if(sizeof($segments) > 2){
                    if($segments[2] !== 'login'){
                        return redirect()->to(herauth_base_locale_url("login"));
                    }
                }else{
                    return redirect()->to(herauth_base_locale_url("login"));
                }
            }else{
                if($request->uri->getPath() !== $config->redirectLogin){
                    return redirect()->to(base_url($config->redirectLogin));
                }
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
    }
}

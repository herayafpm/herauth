<?php

namespace Raydragneel\Herauth\Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

$routes->group('herauth',function($routes){
    $routes->setDefaultNamespace('Raydragneel\Herauth\Controllers\Api');
    $routes->setPrioritize(true);
    $routes->group('web/{locale}', function ($routes) {
        require __DIR__.'/Routes/ApiRoutes.php';
    });
    $routes->group('api/{locale}', function ($routes) {
        require __DIR__.'/Routes/ApiRoutes.php';
    });
    $routes->setDefaultNamespace('Raydragneel\Herauth\Controllers');
    $routes->group('', function ($routes) {
        $routes->get('/', 'HeraHome::redirLocale',['priority' => 1]);
        $routes->get('assets/(:any)','HeraAssets::file/$1');
        $routes->group('{locale}', ['filter' => 'auth_filter'], function ($routes) {
            $routes->get('logout','HeraAuth::logout');
            $routes->get('login','HeraAuth::login');
            $routes->setDefaultNamespace('Raydragneel\Herauth\Controllers\Master');
            $routes->group('master', ['filter' => 'auth_filter'], function ($routes) {
                $routes->group('group', function ($routes) {
                    $routes->get('/','HeraGroup::index');
                    $routes->get('accounts/(:segment)','HeraGroup::accounts/$1');
                    $routes->get('permissions/(:segment)','HeraGroup::permissions/$1');
                    $routes->get('add','HeraGroup::add');
                    $routes->get('edit/(:segment)','HeraGroup::edit/$1');
                });
                $routes->group('permission', function ($routes) {
                    $routes->get('/','HeraPermission::index');
                    $routes->get('add','HeraPermission::add');
                    $routes->get('edit/(:segment)','HeraPermission::edit/$1');
                });
                $routes->group('client', function ($routes) {
                    $routes->get('/','HeraClient::index');
                    $routes->get('add','HeraClient::add');
                    $routes->get('edit/(:segment)','HeraClient::edit/$1');
                    $routes->get('permissions/(:segment)','HeraClient::permissions/$1');
                    $routes->get('whitelists/(:segment)','HeraClient::whitelists/$1');
                });
                $routes->group('account', function ($routes) {
                    $routes->get('/','HeraAccount::index');
                    $routes->get('group/(:segment)','HeraAccount::group/$1');
                    $routes->get('add','HeraAccount::add');
                    $routes->get('edit/(:segment)','HeraAccount::edit/$1');
                });
            });
            $routes->setDefaultNamespace('Raydragneel\Herauth\Controllers');
            $routes->get('request_log','HeraRequestLog::index');
            $routes->get('/','HeraHome::index');
            // $routes->get('(:any)','HeraHome::index/$1');
        });
    });
    
});
$routes->setDefaultNamespace('Raydragneel\Herauth\Controllers');
$routes->get('core_assets/(:any)','CoreAssets::file/$1');

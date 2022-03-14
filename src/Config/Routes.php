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
        $routes->get('{locale}/language','Language::lang');
        $routes->group('{locale}', ['filter' => 'auth_filter'], function ($routes) {
            $routes->get('logout','HeraAuth::logout');
            $routes->get('login','HeraAuth::login');
            $routes->setDefaultNamespace('Raydragneel\Herauth\Controllers\Master');
            $routes->group('master', ['filter' => 'auth_filter'], function ($routes) {
                $routes->group('group', function ($routes) {
                    $routes->get('/','HeraGroup::index');
                    $routes->group('(:segment)', function ($routes) {
                        $routes->get('accounts','HeraGroup::accounts/$1');
                        $routes->get('permissions','HeraGroup::permissions/$1');
                        $routes->get('add','HeraGroup::add');
                        $routes->get('edit','HeraGroup::edit/$1');
                    });
                });
                $routes->group('permission', function ($routes) {
                    $routes->get('/','HeraPermission::index');
                    $routes->get('add','HeraPermission::add');
                    $routes->group('(:segment)', function ($routes) {
                        $routes->get('edit','HeraPermission::edit/$1');
                    });
                });
                $routes->group('client', function ($routes) {
                    $routes->get('/','HeraClient::index');
                    $routes->get('add','HeraClient::add');
                    $routes->group('(:segment)', function ($routes) {
                        $routes->get('edit','HeraClient::edit/$1');
                        $routes->get('permissions','HeraClient::permissions/$1');
                        $routes->get('whitelists','HeraClient::whitelists/$1');
                    });
                });
                $routes->group('account', function ($routes) {
                    $routes->get('/','HeraAccount::index');
                    $routes->get('add','HeraAccount::add');
                    $routes->group('(:segment)', function ($routes) {
                        $routes->get('group','HeraAccount::group/$1');
                        $routes->get('edit','HeraAccount::edit/$1');
                    });
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

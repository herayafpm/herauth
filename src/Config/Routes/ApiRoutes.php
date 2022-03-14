<?php
$routes->group('auth', function ($routes) {
    $routes->post('login', 'HeraAuth::login');
});
$routes->setDefaultNamespace('Raydragneel\Herauth\Controllers\Api\Master');
$routes->group('master', ['filter' => 'auth_api_filter'], function ($routes) {
    $routes->group('group', function ($routes) {
        $routes->get('', 'HeraGroup::index');
        $routes->post('datatable', 'HeraGroup::datatable');
        $routes->post('add', 'HeraGroup::add');
        $routes->group('(:segment)', function ($routes) {
            $routes->get('permissions', 'HeraGroup::permissions/$1');
            $routes->post('edit', 'HeraGroup::edit/$1');
            $routes->post('restore', 'HeraGroup::restore/$1');
            $routes->post('delete', 'HeraGroup::delete/$1');
            $routes->get('accounts', 'HeraGroup::accounts/$1');
            $routes->post('add_account_group', 'HeraGroup::add_account_group/$1');
            $routes->post('delete_account_group', 'HeraGroup::delete_account_group/$1');
            $routes->post('save_permissions', 'HeraGroup::save_permissions/$1');
        });
    });
    $routes->group('permission', function ($routes) {
        $routes->get('', 'HeraPermission::index');
        $routes->post('datatable', 'HeraPermission::datatable');
        $routes->post('add', 'HeraPermission::add');
        $routes->group('(:segment)', function ($routes) {
            $routes->post('edit', 'HeraPermission::edit/$1');
            $routes->post('restore', 'HeraPermission::restore/$1');
            $routes->post('delete', 'HeraPermission::delete/$1');
        });
    });
    $routes->group('client', function ($routes) {
        $routes->post('datatable', 'HeraClient::datatable');
        $routes->post('add', 'HeraClient::add');
        $routes->group('(:segment)', function ($routes) {
            $routes->get('permissions', 'HeraClient::permissions/$1');
            $routes->post('edit', 'HeraClient::edit/$1');
            $routes->post('regenerate_key', 'HeraClient::regenerate_key/$1');
            $routes->post('restore', 'HeraClient::restore/$1');
            $routes->post('delete', 'HeraClient::delete/$1');
            $routes->post('save_permissions', 'HeraClient::save_permissions/$1');
            $routes->post('save_whitelists', 'HeraClient::save_whitelists/$1');
        });
    });
    $routes->group('account', function ($routes) {
        $routes->post('datatable', 'HeraAccount::datatable');
        $routes->post('add', 'HeraAccount::add');
        $routes->group('(:segment)', function ($routes) {
            $routes->post('edit', 'HeraAccount::edit/$1');
            $routes->get('groups', 'HeraAccount::groups/$1');
            $routes->post('restore', 'HeraAccount::restore/$1');
            $routes->post('delete', 'HeraAccount::delete/$1');
            $routes->post('save_group', 'HeraAccount::save_group/$1');
        });
    });
});
$routes->setDefaultNamespace('Raydragneel\Herauth\Controllers\Api');
$routes->group('request_log',['filter' => 'auth_api_filter'], function ($routes) {
    $routes->post('datatable', 'HeraRequestLog::datatable');
});
$routes->group('account',['filter' => 'auth_api_filter'], function ($routes) {
    $routes->get('profil', 'HeraAccount::profil');
    $routes->get('notifications', 'HeraAccount::notifications');
});
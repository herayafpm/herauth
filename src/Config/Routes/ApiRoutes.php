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
        $routes->get('permissions/(:segment)', 'HeraGroup::permissions/$1');
        $routes->post('edit/(:segment)', 'HeraGroup::edit/$1');
        $routes->post('restore/(:segment)', 'HeraGroup::restore/$1');
        $routes->post('delete/(:segment)', 'HeraGroup::delete/$1');
        $routes->get('accounts/(:segment)', 'HeraGroup::accounts/$1');
        $routes->post('add_account_group/(:segment)', 'HeraGroup::add_account_group/$1');
        $routes->post('delete_account_group/(:segment)', 'HeraGroup::delete_account_group/$1');
        $routes->post('save_permissions/(:segment)', 'HeraGroup::save_permissions/$1');
    });
    $routes->group('permission', function ($routes) {
        $routes->get('', 'HeraPermission::index');
        $routes->post('datatable', 'HeraPermission::datatable');
        $routes->post('add', 'HeraPermission::add');
        $routes->post('edit/(:segment)', 'HeraPermission::edit/$1');
        $routes->post('restore/(:segment)', 'HeraPermission::restore/$1');
        $routes->post('delete/(:segment)', 'HeraPermission::delete/$1');
    });
    $routes->group('client', function ($routes) {
        $routes->post('datatable', 'HeraClient::datatable');
        $routes->post('add', 'HeraClient::add');
        $routes->get('permissions/(:segment)', 'HeraClient::permissions/$1');
        $routes->post('edit/(:segment)', 'HeraClient::edit/$1');
        $routes->post('regenerate_key/(:segment)', 'HeraClient::regenerate_key/$1');
        $routes->post('restore/(:segment)', 'HeraClient::restore/$1');
        $routes->post('delete/(:segment)', 'HeraClient::delete/$1');
        $routes->post('save_permissions/(:segment)', 'HeraClient::save_permissions/$1');
        $routes->post('save_whitelists/(:segment)', 'HeraClient::save_whitelists/$1');
    });
    $routes->group('account', function ($routes) {
        $routes->post('datatable', 'HeraAccount::datatable');
        $routes->post('add', 'HeraAccount::add');
        $routes->post('edit/(:segment)', 'HeraAccount::edit/$1');
        $routes->get('groups/(:segment)', 'HeraAccount::groups/$1');
        $routes->post('restore/(:segment)', 'HeraAccount::restore/$1');
        $routes->post('delete/(:segment)', 'HeraAccount::delete/$1');
        $routes->post('save_group/(:segment)', 'HeraAccount::save_group/$1');
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
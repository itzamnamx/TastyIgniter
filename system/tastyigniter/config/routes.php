<?php defined('BASEPATH') OR exit('No direct script access allowed');

log_message('info', $class . ' EL DIRECTORIO ES ' .APPDIR);

if (APPDIR === ADMINDIR) {
    log_message('debug', $class . ' EN EL MODO ADMIN');
    $route['default_controller'] = 'login';
}
else if (APPDIR === 'setup') {
    log_message('debug', $class . ' EN EL SETUP INICIAL');
    $route['default_controller'] = 'setup';
    $route['([^/]+)'] = 'setup/$1';
} else {
    log_message('debug', $class . ' EN LA PAGINA DEL CLIENTE');
    $default_controller = 'home';
    $controller_exceptions = array('home', 'menus', 'reservation', 'contact', 'local', 'checkout', 'pages');

    $route['default_controller'] = $default_controller;
    $route['local/reviews'] = 'local/reviews';
    $route['locations'] = 'local/all';
    $route['local/(.+)'] = 'local';
    $route['account'] = 'account/account';
    $route['login'] = 'account/login';
    $route['logout'] = 'account/logout';
    $route['register'] = 'account/register';
    $route['forgot-password'] = 'account/reset';
    $route['checkout/success'] = 'checkout/success';
    $route['reservation/success'] = 'reservation/success';    
    
    //$route['api/1.0/([a-z]+)/(.*)'] = "$1/$2";
    $route['api/1.0/(:any)'] = 'api/$1';
    $route["api/1.0/categories"]["get"] = "api/categories/index";
    
    $route["^(" . implode('|', $controller_exceptions) . ")?$"] = '$1';
    $route["^(" . implode('|', $controller_exceptions) . ")?/([^/]+)$"] = '$1';
    $route["^(" . implode('|', $controller_exceptions) . ")?/([^/]+)$"] = '$1/$2';
    $route['([^/]+)'] = 'pages';
}

$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./system/tastyigniter/config/routes.php */
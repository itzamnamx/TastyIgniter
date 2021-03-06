<?php defined('BASEPATH') OR exit('No direct script access allowed');


if (APPDIR === ADMINDIR) {
    
    $route['default_controller'] = 'login';
}
else if (APPDIR === 'setup') {
    
    $route['default_controller'] = 'setup';
    $route['([^/]+)'] = 'setup/$1';
} else {
    
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
    
    //Begin Rest services based on https://github.com/chriskacerguis/codeigniter-restserver
    $route['api/locations/(:num)'] = 'api/locations/id/$1'; // Example 4
    $route['api/locations/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/locations/id/$1/format/$3$4'; // Example 8
    /*$route['api/1.0/(:any)'] = 'api/$1';
    $route["api/1.0/categories"]["get"] = "api/categories/index";
    $route["api/1.0/locations"]["get"] = "api/locations/index";
    $route['api/1.0/locations/(:num)'] = 'api/locations/find/id/$1'; // Example 4*/
    //End Rest services
    
    $route["^(" . implode('|', $controller_exceptions) . ")?$"] = '$1';
    $route["^(" . implode('|', $controller_exceptions) . ")?/([^/]+)$"] = '$1';
    $route["^(" . implode('|', $controller_exceptions) . ")?/([^/]+)$"] = '$1/$2';
    $route['([^/]+)'] = 'pages';
}

$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./system/tastyigniter/config/routes.php */
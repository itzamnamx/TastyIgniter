<?php defined('BASEPATH') OR exit('No direct script access allowed');

log_message('info', $class . ' EL DIRECTORIO ES ' .APPDIR);

if (APPDIR === 'api') {
    
    log_message('info', $class . ' EN EL SERVER REST');
    
    /*
    $route['default_controller'] = 'rest/cat';
    $route['404_override'] = '';
    $route['translate_uri_dashes'] = TRUE;
    
    
    $route['api/example/users/(:num)'] = 'api/example/users/id/$1'; // Example 4
    $route['api/example/users/(:num)(\.)([a-zA-Z0-9_-]+)(.*)'] = 'api/example/users/id/$1/format/$3$4'; // Example 8
    
    log_message('info', $class . ' EN EL SERVER REST en el INICIO CATEGORIES');
    //cualquier petición get entra al index_get()
    $route["rest/cat"]["get"] = "cat/index";

    //si la petición es del tipo GET y tiene la forma localhost/codeigniter/restangular/pois/(cualquier número)
    //es decir, va con un parámetro, la dirección se convertirá en pois/find/(ese cualquier número de antes)
    //y por lo tanto se irá al método index_find($id), donde $id = $1
    $route["rest/cat/(:num)"]["get"] = "cat/find/$1";
     */
    log_message('info', $class . ' EN EL SERVER REST en el FIN CATEGORIES');

}
else if (APPDIR === ADMINDIR) {
    log_message('info', $class . ' EN EL MODO ADMIN');
    $route['default_controller'] = 'login';
}
else if (APPDIR === 'setup') {
    log_message('info', $class . ' EN EL SETUP INICIAL');
    $route['default_controller'] = 'setup';
    $route['([^/]+)'] = 'setup/$1';
} else {
    log_message('info', $class . ' EN LA PAGINA DEL CLIENTE');
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
    
    $route['api/1/(:any)'] = 'api_1/$1';
    $route["api/1/cat"]["get"] = "api_1/cat/index";
    
    $route["^(" . implode('|', $controller_exceptions) . ")?$"] = '$1';
    $route["^(" . implode('|', $controller_exceptions) . ")?/([^/]+)$"] = '$1';
    $route["^(" . implode('|', $controller_exceptions) . ")?/([^/]+)$"] = '$1/$2';
    $route['([^/]+)'] = 'pages';
}

$route['404_override'] = '';


/* End of file routes.php */
/* Location: ./system/tastyigniter/config/routes.php */
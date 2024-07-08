<?php

use App\Router;
use App\App ;

require_once 'vendor/autoload.php';
$routes = require_once 'App/routes.php';
require_once 'loadContainer.php';


// Get the request URI
$url = parse_url($_SERVER['REQUEST_URI'])['path'];

if (key_exists($url,$routes)){
    $router = App::getContainer()->get(Router::class);
    $routeParameter = $routes[$url];
    $router->route($routeParameter[0],$routeParameter[1]);
}else{
    echo "page not found";
}

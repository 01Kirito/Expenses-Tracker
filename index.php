<?php

use App\Router;
use App\App ;

require_once __DIR__.'/vendor/autoload.php';
require_once __DIR__.'/loadContainer.php';
require_once __DIR__.'/App/routes.php';


// Get the request URI
$url = parse_url($_SERVER['REQUEST_URI'])['path'];
App::getInstance(Router::class)->search($url);









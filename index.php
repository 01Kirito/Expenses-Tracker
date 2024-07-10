<?php

use App\Router;
use App\App ;

require_once 'vendor/autoload.php';
require_once 'loadContainer.php';
require_once 'App/routes.php';
// Get the request URI
$url = parse_url($_SERVER['REQUEST_URI'])['path'];
App::getInstance(Router::class)->search($url);





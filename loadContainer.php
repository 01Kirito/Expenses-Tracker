<?php
use App\App;
use App\User;
use App\Container;
use App\Database;
use App\Router;
use App\Http\RequestHandler;

$container = new Container();
$container->set(Database::class,new Database);
$container->set(User::class,new User);
$container->set("Pdo",$container->get(Database::class)->getConnection());
$container->set(Router::class,new Router);
$container->set(RequestHandler::class,new \App\Http\RequestHandler);

App::setContainer($container);
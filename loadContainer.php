<?php

use App\App;
use App\Container;
use App\Controller\UserController;
use App\Database;
use App\Http\RequestHandler;
use App\Model\Model;
use App\Model\User;
use App\Router;


$container = new Container();
App::setContainer($container);
$container->set(Database::class,new Database);
$container->set(User::class,new User);
$container->set('Pdo',$container->get(Database::class)->getConnection());
$container->set(Router::class,new Router);
$container->set(RequestHandler::class,new RequestHandler);
$container->set(Model::class,new Model);
$container->set(UserController::class,new UserController);


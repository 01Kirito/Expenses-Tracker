<?php

use App\App;
use App\Middleware\Api;
use App\Container;
use App\Controller\ControllerBudget;
use App\Controller\ControllerCategory;
use App\Controller\ControllerInvoice;
use App\Controller\ControllerPlan;
use App\Controller\ControllerPreference;
use App\Controller\ControllerUser;
use App\Database;
use App\Http\RequestHandler;
use App\Middleware\Admin;
use App\Middleware\Middleware;
use App\Model\Budget;
use App\Model\Category;
use App\Model\Invoice;
use App\Model\Model;
use App\Model\Plan;
use App\Model\Preference;
use App\Model\User;
use App\Router;
use Predis\Client;
use Dotenv\Dotenv;

// using below class to load the .env file that holds the sensitive data
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$container = new Container();
App::setContainer($container);
// order those instances are very important because it will make error if you don't do it in the right way for example we
// use User instance in the ControllerUser class so that is why .


// loading the redis object to use caching
$client = new Predis\Client([
    'scheme' => $_ENV["REDIS_SCHEME"],
    'host'=>$_ENV["REDIS_HOST"],
    "port"=>$_ENV["REDIS_PORT"],
    "username"=>$_ENV["REDIS_USERNAME"],
    "password"=>$_ENV["REDIS_PASSWORD"],
    "database"=>$_ENV["REDIS_DATABASE"],
    "read_write_timeout"=>$_ENV["REDIS_READ_WRITE_TIMEOUT"],
    "timeout"=>$_ENV["REDIS_TIMEOUT"]
]);

$container->set(Client::class,$client);


// middlewares
$container->set(Middleware::class,new Middleware);
$container->set(Api::class,new Api);
$container->set(Admin::class,new Admin);

// classes
$container->set(Database::class,new Database);
$container->set(Middleware::class,new Middleware);
$container->set(RequestHandler::class,new RequestHandler);
$container->set('Pdo',$container->get(Database::class)->getConnection());
$container->set(Router::class,new Router);

// models
$container->set(Model::class,new Model);
$container->set(User::class,new User);
$container->set(Invoice::class,new Invoice);
$container->set(Category::class,new Category);
$container->set(Preference::class,new Preference);
$container->set(Plan::class,new Plan);
$container->set(Budget::class,new Budget);

// controllers
$container->set(ControllerUser::class,new ControllerUser);
$container->set(ControllerInvoice::class,new ControllerInvoice);
$container->set(ControllerCategory::class,new ControllerCategory);
$container->set(ControllerBudget::class,new ControllerBudget);
$container->set(ControllerPlan::class,new ControllerPlan);
$container->set(ControllerPreference::class,new ControllerPreference);




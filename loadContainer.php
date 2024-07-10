<?php

use App\App;
use App\Container;
use App\Controller\ControllerBudget;
use App\Controller\ControllerCategory;
use App\Controller\ControllerInvoice;
use App\Controller\ControllerPlan;
use App\Controller\ControllerPreference;
use App\Controller\ControllerUser;
use App\Database;
use App\Http\RequestHandler;
use App\Model\Budget;
use App\Model\Category;
use App\Model\Invoice;
use App\Model\Model;
use App\Model\Plan;
use App\Model\Preference;
use App\Model\User;
use App\Router;


$container = new Container();
App::setContainer($container);
// order those instances are very important because it will make error if you don't do it in the right way for example we
// use User instance in the ControllerUser class so that is why .
$container->set(Database::class,new Database);
$container->set(RequestHandler::class,new RequestHandler);
$container->set('Pdo',$container->get(Database::class)->getConnection());
$container->set(Router::class,new Router());
$container->set(Model::class,new Model);
$container->set(User::class,new User);
$container->set(Invoice::class,new Invoice);
$container->set(Category::class,new Category);
$container->set(Preference::class,new Preference);
$container->set(Plan::class,new Plan);
$container->set(Budget::class,new Budget);
$container->set(ControllerUser::class,new ControllerUser);
$container->set(ControllerInvoice::class,new ControllerInvoice);
$container->set(ControllerCategory::class,new ControllerCategory);
$container->set(ControllerBudget::class,new ControllerBudget);
$container->set(ControllerPlan::class,new ControllerPlan);
$container->set(ControllerPreference::class,new ControllerPreference);





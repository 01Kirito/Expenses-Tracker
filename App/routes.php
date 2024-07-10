<?php

use App\App;
use App\Auth;
use App\Controller\UserController;
use App\Model\Model;
use App\Model\User;
use App\Router;

$router = App::getInstance(Router::class);

$router->addRoute("/ExpensesTracker/",[UserController::class,'create'],'get');
$router->addRoute("/ExpensesTracker/user",[UserController::class,'index'],'GET');          // done with out auth
$router->addRoute("/ExpensesTracker/user/store",[UserController::class,'store'],'POST');   // done with out auth
$router->addRoute("/ExpensesTracker/user/update",[UserController::class,'update'],'POST'); // in process
$router->addRoute("/ExpensesTracker/user/delete",[UserController::class,'delete'],'POST');
$router->addRoute("/ExpensesTracker/user/show",[UserController::class,'show'],'get');
$router->addRoute("/ExpensesTracker/login",[Auth::class,'login'],'POST');
$router->addRoute("/ExpensesTracker/logout",[Auth::class,'login'],'POST');
$router->addRoute("/ExpensesTracker/category",[Auth::class,'login'],'POST');
$router->addRoute("/ExpensesTracker/category/store",[Auth::class,'login'],'POST');
$router->addRoute("/ExpensesTracker/invoice",[Auth::class,'login'],'POST');
$router->addRoute("/ExpensesTracker/invoice/store",[Auth::class,'login'],'POST');
$router->addRoute("/ExpensesTracker/invoice/show",[Auth::class,'login'],'POST');
$router->addRoute("/ExpensesTracker/invoice/update",[Auth::class,'login'],'POST');
$router->addRoute("/ExpensesTracker/invoice/delete",[Auth::class,'login'],'POST');
$router->addRoute("/ExpensesTracker/model",[Model::class,'create'],'POST');



<?php

use App\App;
use App\Auth;
use App\Controller\ControllerCategory;
use App\Controller\ControllerInvoice;
use App\Controller\ControllerUser;
use App\Model\Model;
use App\Model\User;
use App\Router;

$router = App::getInstance(Router::class);

// user routes
$router->addRoute("/ExpensesTracker/user",[ControllerUser::class,'index'],'GET');          // done with out auth
$router->addRoute("/ExpensesTracker/user/store",[ControllerUser::class,'store'],'POST');   // done with out auth
$router->addRoute("/ExpensesTracker/user/update",[ControllerUser::class,'update'],'put');  // done with out auth
$router->addRoute("/ExpensesTracker/user/softDelete",[ControllerUser::class,'softDelete'],'delete'); // done with out auth
$router->addRoute("/ExpensesTracker/user/delete",[ControllerUser::class,'delete'],'delete'); // done with out auth
$router->addRoute("/ExpensesTracker/user/show",[ControllerUser::class,'show'],'get');        // done with out auth

// invoice routes
$router->addRoute("/ExpensesTracker/user/invoice",[ControllerInvoice::class,'index'],'GET');          // done with out auth
$router->addRoute("/ExpensesTracker/user/invoice/store",[ControllerInvoice::class,'store'],'POST');   // done with out auth
$router->addRoute("/ExpensesTracker/user/invoice/update",[ControllerInvoice::class,'update'],'put');  // done with out auth
$router->addRoute("/ExpensesTracker/user/invoice/softDelete",[ControllerInvoice::class,'softDelete'],'delete'); // done with out auth
$router->addRoute("/ExpensesTracker/user/invoice/delete",[ControllerInvoice::class,'delete'],'delete'); // done with out auth
$router->addRoute("/ExpensesTracker/user/invoice/show",[ControllerInvoice::class,'show'],'get');        // done with out auth


// category routes
$router->addRoute("/ExpensesTracker/categories",[ControllerCategory::class,'index'],'GET');          // done with out auth
$router->addRoute("/ExpensesTracker/category/store",[ControllerCategory::class,'store'],'POST');   // done with out auth
$router->addRoute("/ExpensesTracker/category/update",[ControllerCategory::class,'update'],'put');  // done with out auth
$router->addRoute("/ExpensesTracker/category/softDelete",[ControllerCategory::class,'softDelete'],'delete'); // done with out auth
$router->addRoute("/ExpensesTracker/category/delete",[ControllerCategory::class,'delete'],'delete'); // done with out auth
$router->addRoute("/ExpensesTracker/category/show",[ControllerCategory::class,'show'],'get');        // done with out auth

// plan routes
$router->addRoute("/ExpensesTracker/user/plan",[ControllerCategory::class,'show'],'get');        // done with out auth
$router->addRoute("/ExpensesTracker/user/plan/update",[ControllerCategory::class,'update'],'put');  // done with out auth




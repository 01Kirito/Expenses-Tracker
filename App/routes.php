<?php

use App\App;
use App\Controller\ControllerBudget;
use App\Controller\ControllerCategory;
use App\Controller\ControllerCustomCategory;
use App\Controller\ControllerDeviceToken;
use App\Controller\ControllerInvoice;
use App\Controller\ControllerPlan;
use App\Controller\ControllerPreference;
use App\Controller\ControllerUser;
use App\Router;

$router = App::getInstance(Router::class);

// api
$router->addRoute("/ExpensesTracker/login",[ControllerUser::class,'login'],'post','guest');


// user routes
$router->addRoute("/ExpensesTracker/user",[ControllerUser::class,'index'],'GET','guest');
$router->addRoute("/ExpensesTracker/user/dashboard",[ControllerUser::class,'dashboard'],'GET','api');
$router->addRoute("/ExpensesTracker/user/dashboard_cache",[ControllerUser::class,'dashboardCache'],'GET','api');
$router->addRoute("/ExpensesTracker/user/store",[ControllerUser::class,'store'],'POST','guest');
$router->addRoute("/ExpensesTracker/user/update",[ControllerUser::class,'update'],'put','api');
$router->addRoute("/ExpensesTracker/user/softDelete",[ControllerUser::class,'softDelete'],'delete','api'); 
$router->addRoute("/ExpensesTracker/user/delete",[ControllerUser::class,'delete'],'delete','api'); 
$router->addRoute("/ExpensesTracker/user/show",[ControllerUser::class,'show'],'get','api');

$router->addRoute("/ExpensesTracker/user/searchByEmail",[ControllerUser::class,'getUser'],'get','guest');

// Device Token routes
$router->addRoute("/ExpensesTracker/user/store/deviceToken",[ControllerDeviceToken::class,'storeDeviceToken'],'POST','api');

// invoice routes
$router->addRoute("/ExpensesTracker/user/invoice",[ControllerInvoice::class,'index'],'GET','api');          
$router->addRoute("/ExpensesTracker/user/invoice/store",[ControllerInvoice::class,'store'],'POST','api');   
$router->addRoute("/ExpensesTracker/user/invoice/update",[ControllerInvoice::class,'update'],'put','api');  
$router->addRoute("/ExpensesTracker/user/invoice/softDelete",[ControllerInvoice::class,'softDelete'],'delete','api'); 
$router->addRoute("/ExpensesTracker/user/invoice/delete",[ControllerInvoice::class,'delete'],'delete','api');
$router->addRoute("/ExpensesTracker/user/invoice/show",[ControllerInvoice::class,'show'],'get','api');


// category routes
$router->addRoute("/ExpensesTracker/categories",[ControllerCategory::class,'index'],'GET','guest');          
$router->addRoute("/ExpensesTracker/category/store",[ControllerCategory::class,'store'],'POST','admin');   
$router->addRoute("/ExpensesTracker/category/update",[ControllerCategory::class,'update'],'put','admin');  
$router->addRoute("/ExpensesTracker/category/delete",[ControllerCategory::class,'delete'],'delete','admin'); 
$router->addRoute("/ExpensesTracker/category/show",[ControllerCategory::class,'show'],'get','api');

$router->addRoute("/ExpensesTracker/customCategory/store",[ControllerCustomCategory::class,'store'],'POST','api');

// budget routes
$router->addRoute("/ExpensesTracker/user/budget",[ControllerBudget::class,'index'],'GET','api');
$router->addRoute("/ExpensesTracker/user/budget/show",[ControllerBudget::class,'show'],'GET','api');
$router->addRoute("/ExpensesTracker/user/budget/update",[ControllerBudget::class,'update'],'put','api');  

// plan routes
$router->addRoute("/ExpensesTracker/user/plan",[ControllerPlan::class,'show'],'get','api');
$router->addRoute("/ExpensesTracker/user/plan/update",[ControllerPlan::class,'update'],'put','api');


// preference routes
$router->addRoute("/ExpensesTracker/user/preference",[ControllerPreference::class,'show'],'get','api');        
$router->addRoute("/ExpensesTracker/user/preference/update",[ControllerPreference::class,'update'],'put','api');
$router->addRoute("/ExpensesTracker/user/preference/store",[ControllerPreference::class,'store'],'post','admin');




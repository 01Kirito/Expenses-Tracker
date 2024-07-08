<?php

use App\Auth;
use App\User;


$routes = [
    "/ExpensesTracker/user/store"=>[User::class,'store']
    ,"/ExpensesTracker/user/update"=>[User::class,'update']
    ,"/ExpensesTracker/user/delete"=>[User::class,'delete']
    ,"/ExpensesTracker/user/show"=>[User::class,'show']
    ,"/ExpensesTracker/login"=>[Auth::class,'login']
    ,"/ExpensesTracker/logout"=>[]
    ,"/ExpensesTracker/category"=>[]
    ,"/ExpensesTracker/category/store"=>[]
    ,"/ExpensesTracker/invoice"=>[]
    ,"/ExpensesTracker/invoice/store"=>[]
    ,"/ExpensesTracker/invoice/show"=>[]
    ,"/ExpensesTracker/invoice/update"=>[]
    ,"/ExpensesTracker/invoice/delete"=>[]



];

return $routes;

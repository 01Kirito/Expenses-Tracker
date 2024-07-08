<?php

namespace App;

class Router
{

    public function route($class,$function){
       $object = App::getInstance($class);
        call_user_func_array(array($object,$function),array());
    }



}
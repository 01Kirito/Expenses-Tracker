<?php

namespace App\Controller;

use App\App;
use App\Auth;

class Controller
{
    protected function getAuthenticatedUser(){
        return App::getInstance(Auth::class)->getUser();
    }


}
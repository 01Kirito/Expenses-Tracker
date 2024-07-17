<?php

namespace App\Controller;

use App\App;
use App\Model\Category;
use App\Model\CustomPlan;
use App\Model\Plan;

class ControllerCustomCategory extends Controller
{

    public static $CustomPlan ;

    public function __construct()
    {
        static::$CustomPlan = App::getInstance(CustomPlan::class);
    }

    public function store(array $data):void{
        $data['body_json']['user_id'] = $this->getAuthenticatedUser()['id'];
        App::getInstance(Category::class)->store($data['body_json']);
        static::$CustomPlan->createWithResponse($data['body_json']) ;
        App::getInstance(Plan::class)->addColumn($data['body_json']['name'],"DECIMAL(10,2) NOT NULL default 0.00");
        App::getInstance(Plan::class)->addColumn($data['body_json']['name']."_balance","DECIMAL(10,2) NOT NULL default 0.00");
    }

}
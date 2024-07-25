<?php

namespace App\Controller;

use App\App;
use App\Model\Category;
use App\Model\CustomPlan;
use App\Model\Plan;

class ControllerCustomCategory extends Controller
{

    public static $customPlanModel ;

    public function __construct()
    {
        Parent::__construct();
        static::$customPlanModel = App::getInstance(CustomPlan::class);
    }

    public function store(array $data):void{
        $data['body_json']['user_id'] = $this->getAuthenticatedUser()['id'];
        App::getInstance(Category::class)->store($data['body_json']);
        $result = static::$customPlanModel->create($data['body_json']);
        if (!array_key_exists("error", $result)) {
        App::getInstance(Plan::class)->addColumn($data['body_json']['name'],"DECIMAL(10,2) NOT NULL default 0.00");
        App::getInstance(Plan::class)->addColumn($data['body_json']['name']."_used","DECIMAL(10,2) NOT NULL default 0.00");
        }
        $this->response($result);
    }
}
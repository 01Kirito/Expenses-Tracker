<?php

namespace App\Controller;

use App\App;
use App\Model\Plan;

class ControllerPlan extends Controller
{

    public static $planModel ;

    public function __construct()
    {
        Parent::__construct();
        static::$planModel = App::getInstance(Plan::class);
    }

    public function index(): void
    {
       $result = static::$planModel->get();
       $this->response($result);
    }

    public function store(array $data):void{
        static::$planModel->create($data['body_json']) ;
    }

    public function show():void{
        $user = $this->getAuthenticatedUser();
        $result = static::$planModel->get(condition: ["user_id =" => $user["id"]], fetchOneRow: true);
        $this->response($result);
    }

    public function update($data):void{
        $user = $this->getAuthenticatedUser();
        $result = static::$planModel->update(column:$data['body_json'],condition:["user_id"=>$user['id']],autoDateUpdate:false);
        $this->response($result);
    }

    public function delete(array $data):void{
        $result = static::$planModel->delete($data['url_parameters']);
        $this->response($result);
    }

}
<?php

namespace App\Controller;

use App\App;
use App\Model\Plan;

class ControllerPlan extends Controller
{

    public static $Plan ;

    public function __construct()
    {
        static::$Plan = App::getInstance(Plan::class);
    }

    public function index(): void
    {
        static::$Plan->readWithResponse();
    }

    public function store(array $Data):void{
        static::$Plan->createWithResponse($Data['body_json']) ;
    }

    public function show():void{
        $user = $this->getAuthenticatedUser();
        static::$Plan->fetchOne(conditions: ["user_id" => $user['id']]);

    }

    public function update($Data):void{
        $user = $this->getAuthenticatedUser();
        static::$Plan->updateWithResponse($Data['body_json'],["user_id"=>$user['id']],false);
    }

    public function delete(array $Data):void{
        static::$Plan->deleteWithResponse($Data['url_parameters']);
    }

}
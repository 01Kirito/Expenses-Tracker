<?php

namespace App\Controller;

use App\App;
use App\Model\Plan;

class ControllerPlan
{

    public static $Plan ;

    public function __construct()
    {
        static::$Plan = App::getInstance(Plan::class);
    }

    public function index(): void
    {
        static::$Plan->read();
    }

    public function store(array $Data):void{
        $pairs = $Data['body_json'];
        static::$Plan->create($pairs) ;
    }

    public function show(array $Data):void{

        static::$Plan->fetchOne(conditions: $Data['url_parameters']);

    }

    public function update($Data):void{
        static::$Plan->update($Data['body_json'],$Data['url_parameters'],false);
    }

    public function delete(array $Data):void{
        static::$Plan->delete($Data['url_parameters']);
    }

}
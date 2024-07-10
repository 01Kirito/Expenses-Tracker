<?php

namespace App\Controller;

use App\App;
use App\Model\Category;
use App\Model\Invoice;
use App\Model\Plan;

class ControllerCategory
{

    public static $Category ;

    public function __construct()
    {
        static::$Category = App::getInstance(Category::class);
    }

    public function index(): void
    {
        static::$Category->read();
    }

    public function store(array $Data):void{
        static::$Category->create($Data['body_json']) ;
        App::getInstance(Plan::class)->addColumn($Data['body_json']['name'],"DECIMAL(10,2) NOT NULL default 0.00");
    }

    public function show(array $Data):void{

        static::$Category->fetchOne(conditions: $Data['url_parameters']);

    }

    public function update($Data):void{
        static::$Category->update($Data['body_json'],$Data['url_parameters'],false);
    }

    public function delete(array $Data):void{
        static::$Category->delete($Data['url_parameters']);
    }


}
<?php

namespace App\Controller;

use App\App;
use App\Model\Category;
use App\Model\Invoice;

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
        $pairs = $Data['body_json'];
        static::$Category->create($pairs) ;
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
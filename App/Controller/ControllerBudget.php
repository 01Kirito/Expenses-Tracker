<?php

namespace App\Controller;

use App\App;
use App\Model\Budget;
use App\Model\Invoice;

class ControllerBudget
{
    public static $Budget ;

    public function __construct()
    {
        static::$Budget = App::getInstance(Budget::class);
    }

    public function index(): void
    {
        static::$Budget->read();
    }

    public function store(array $Data):void{
        $pairs = $Data['body_json'];
        static::$Budget->create($pairs) ;
    }

    public function show(array $Data):void{

        static::$Budget->fetchOne(conditions: $Data['url_parameters']);

    }

    public function update($Data):void{
        static::$Budget->update($Data['body_json'],$Data['url_parameters'],false);
    }

    public function delete(array $Data):void{
        static::$Budget->delete($Data['url_parameters']);
    }


}
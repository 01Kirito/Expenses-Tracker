<?php

namespace App\Controller;
use App\App;
use App\Model\Invoice;

class ControllerInvoice {

    public static $Invoice ;

    public function __construct()
    {
        static::$Invoice = App::getInstance(Invoice::class);
    }

    public function index(): void
    {
        static::$Invoice->read();
    }

    public function store(array $Data):void{
        $pairs = $Data['body_json'];
        static::$Invoice->create($pairs) ;
    }

    public function show(array $Data):void{

        static::$Invoice->fetchOne(conditions: $Data['url_parameters']);

    }

    public function update($Data):void{
        static::$Invoice->update($Data['body_json'],$Data['url_parameters'],false);
    }

    public function delete(array $Data):void{
        static::$Invoice->delete($Data['url_parameters']);
    }


}
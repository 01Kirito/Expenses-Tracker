<?php

namespace App\Controller;

use App\App;
use App\Model\Invoice;
use App\Model\Preference;

class ControllerPreference extends Controller
{
    public static $Preference ;

    public function __construct()
    {
        static::$Preference = App::getInstance(Preference::class);
    }

    public function index(): void
    {
        static::$Preference->read();
    }

    public function store(array $Data):void{
        $pairs = $Data['body_json'];
        static::$Preference->create($pairs) ;
    }

    public function show(array $Data):void{

        static::$Preference->fetchOne(conditions: $Data['url_parameters']);

    }

    public function update($Data):void{
        static::$Preference->updateWithResponse($Data['body_json'],$Data['url_parameters'],false);
    }

    public function delete(array $Data):void{
        static::$Preference->delete($Data['url_parameters']);
    }

}
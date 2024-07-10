<?php

namespace App\Controller;

use App\App;
use App\Database;
use App\Http\RequestHandler;
use App\Model\Budget;
use App\Model\Plan;
use App\Model\Preference;
use App\Model\User;

class ControllerUser
{

    public static $User ;

    public function __construct()
    {
        static::$User = App::getInstance(User::class);
    }

    public function index(): void
    {
         static::$User->read();
    }

    public function store(array $Data):void{
        static::$User->create($Data['body_json']) ;
        $lastInsertId = App::getInstance(Database::class)->getConnection()->lastInsertId();
        App::getInstance(Preference::class)->create(["user_id"=>$lastInsertId,"theme"=>"dark"]);
        App::getInstance(Plan::class)->create(["user_id"=>$lastInsertId]);
        App::getInstance(Budget::class)->create(["user_id"=>$lastInsertId]);
    }

    public function show(array $Data):void{

        static::$User->fetchOne(conditions: $Data['url_parameters']);

    }

    public function update($Data):void{
        static::$User->update($Data['body_json'],$Data['url_parameters'],false);
    }

    public function delete(array $Data):void{
        static::$User->delete($Data['url_parameters']);
    }

    public function softDelete(array $Data):void{
        static::$User->softDelete($Data['url_parameters'], hidden:True ) ;
    }

}
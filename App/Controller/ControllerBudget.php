<?php

namespace App\Controller;

use App\App;
use App\Model\Budget;
use App\Model\Invoice;

class ControllerBudget extends Controller
{
    public static $Budget ;

    public function __construct()
    {
        Parent::__construct();
        static::$Budget = App::getInstance(Budget::class);
    }

    public function index(): void
    {
       $result = static::$Budget->get();
       $this->response($result);
    }

    public function store(array $data):void{

    }

    public function show(array $data):void{
        $user = $this->getAuthenticatedUser();
        $result = static::$Budget->get(condition: ["user_id"=>$user["id"]],fetchOneRow: true);
        $this->response($result);
    }

    public function update($data):void{
        $user = $this->getAuthenticatedUser() ;
        $result = static::$Budget->update($data['body_json'],["user_id"=>$user["id"]],false);
        $this->response($result);
    }

    public function delete(array $data):void{
       $result = static::$Budget->delete($data['url_parameters']);
       $this->response($result);
    }


}
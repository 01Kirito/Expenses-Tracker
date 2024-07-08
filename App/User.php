<?php
namespace App;
use App\Http\RequestHandler;

class User {

    public function store($Data = null){
       $requestHandler = App::getInstance(RequestHandler::class);
       $response = ["message","hello this is from store function"];
       $requestHandler->sendResponse(200,["Connection"=>"close"],$response);
    }


}
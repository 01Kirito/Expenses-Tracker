<?php
namespace App;
use App\Http\RequestHandler;
use App\App ;

class User extends Model{

    protected $table = 'users';

    public function store($Data = null){
       $json = file_get_contents('php://input');
       $array = json_decode($json,true);
       $requestHandler = App::getInstance(RequestHandler::class);
       if($this->create($array)){
           $message = ["message" => "User created"];
           $requestHandler->sendResponse(200,["Connection"=>"close","Cache-Control"=>"Cache"],$message);
       }else{
           $message = ["message" => "User creation failed"];
           $requestHandler->sendResponse(400,["Connection"=>"close","Cache-Control"=>"Cache"],$message);
       }
    }

    public function show($Data = null){

        $requestHandler = App::getInstance(RequestHandler::class);
        $response = ["message","hello this is from show function"];
        $requestHandler->sendResponse(200,["Connection"=>"close","Auth"=>"no-auth"],$response);
    }

}
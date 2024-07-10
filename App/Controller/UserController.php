<?php

namespace App\Controller;

use App\App;
use App\Http\RequestHandler;
use App\Model\User;

class UserController
{

    public function index(): void
    {
     $requestHandler = App::getInstance(RequestHandler::class);
     $result = App::getInstance(User::class)->read();

     if ($result){
         $requestHandler->sendResponse(200,data:$result);
     }else{
         $requestHandler->sendResponse(500,["Connection"=>"close"], ["message" => "User creation failed"]);
     }
    }

    public function store(array $Data):void{
        var_dump($Data);
        $pairs = $Data['body_json'];
        $requestHandler = App::getInstance(RequestHandler::class);
        // this $Data['body_json'] is consist of column names and their values
        if(App::getInstance(User::class)->create($pairs) === true){
            $requestHandler->sendResponse(201,["Connection"=>"close"],["message" => "User created"]);
        }else{
            $requestHandler->sendResponse(500,["Connection"=>"close"], ["message" => "User creation failed"]);
        }
//        die("hh i know the problem");
    }

    public function show(array $Data){
        die("hello this is from show method");
        $requestHandler = App::getInstance(RequestHandler::class);
        $result = App::getInstance(User::class)->read(conditions: $Data['url_parameters']);

        if ($result){
            $requestHandler->sendResponse(200,data:$result);
        }else{
            $requestHandler->sendResponse(500,["Connection"=>"close"], ["message" => "User creation failed"]);
        }
    }

    public function update($Data){
        var_dump($Data);
        $pairs = $Data['body_json'];
        $url_parameters = $Data['url_parameters'];
        $requestHandler = App::getInstance(RequestHandler::class);
        // this $Data['body_json'] is consist of column names and their values
        if(App::getInstance(User::class)->update($pairs,$url_parameters,true) === true){
            $requestHandler->sendResponse(200,["Connection"=>"close"],["message" => "User created"]);
        }else{
            $requestHandler->sendResponse(500,["Connection"=>"close"], ["message" => "User creation failed"]);
        }
    }

    public function destroy($Data = null){

    }

}
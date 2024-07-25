<?php

namespace App\Controller;

use App\App;
use App\Auth;
use App\Http\RequestHandler;
use Predis\Client;
use Redis;

class Controller
{
    protected static $redis ;
    public function __construct()
    {
        self::$redis = App::getInstance(Client::class);
    }

    protected function getAuthenticatedUser(){
        return App::getInstance(Auth::class)->getUser();
    }

    protected function cacheByHashset($key, $columns):void{
        foreach ($columns as $column => $value){
            static::$redis->hset($key,$column,$value);
        }
    }

    protected function cacheBySetUseJson($key, $array):void{
       static::$redis->set($key,json_encode($array));
    }

    protected function getCache($key){
        $data = static::$redis->get($key);
        return $data === null ? null : json_decode($data,true);
    }


    // todo : check if the header has cache control and do action based on it
    protected function checkHeaderForCache(){
        $cacheControl =  getallheaders();
        if($cacheControl["Cache-Control"] != "no-cache"){
            return true ;
        }elseif ($cacheControl["Cache-Control"] == "no-store"){
            return true ;
        }else{
            return false;
        }
    }



    protected function response(array $result,int $successStatusCode = 200 ,int $failedStatusCode = 400 ,array $headers = []):void{
       if (!array_key_exists("error",$result)){
           unset($result["error"]);
           RequestHandler::sendResponse($successStatusCode, $headers, $result);
       }else{
           RequestHandler::sendResponse($failedStatusCode, $headers, $result);
       }
    }

}

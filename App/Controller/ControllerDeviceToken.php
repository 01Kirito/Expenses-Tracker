<?php

namespace App\Controller;

use App\App;
use App\Http\RequestHandler;
use App\Model\DeviceToken;

class ControllerDeviceToken extends Controller
{

    public static $deviceToken ;

    public function __construct()
    {
        static::$deviceToken = App::getInstance(DeviceToken::class);
    }


    public function storeDeviceToken(){
        $user = $this->getAuthenticatedUser();
        $data["user_id"] = $user["id"];
        $data["token"] = getallheaders()["Device-Token"];
        $data["device_type"] = getallheaders()["User-Agent"];
        if (strlen($data["device_type"]) > 0 && strlen($data["token"]) > 0) {
            $result = static::$deviceToken->searchForOneRow(selection: ["token_id"],conditions: ["device_type"=>$data["device_type"],"user_id"=>$data["user_id"]]);
            if ($result !==false){
                static::$deviceToken->updateWithResponse(["token"=>$data["token"]],["token_id"=>$result["token_id"]]);
                }else{
                static::$deviceToken->createWithResponse($data);
                }
        }else{
                RequestHandler::sendResponse(400,[],["message"=>"check your device token and device type."]);
        }
    }
}
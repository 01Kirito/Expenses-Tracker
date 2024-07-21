<?php

namespace App\Controller;

use App\App;
use App\Http\RequestHandler;
use App\Model\DeviceToken;

class ControllerDeviceToken extends Controller
{

    public static $deviceTokenModel ;

    public function __construct()
    {
        Parent::__construct();
        static::$deviceTokenModel = App::getInstance(DeviceToken::class);
    }


    public function storeDeviceToken(): void{
        $user = $this->getAuthenticatedUser();
       @ $data["token"] = getallheaders()["Device-Token"];
       @ $data["device_type"] = getallheaders()["User-Agent"];
       @ $data["user_id"] = $user["id"];
        if (strlen($data["device_type"]) > 0 && strlen($data["token"]) > 0) {
            $tokenExist = static::$deviceTokenModel->get(selection: ["token_id"],condition: ["device_type"=>$data["device_type"],"user_id"=>$data["user_id"]],logicalOperator: ["AND"],fetchOneRow: true);
            if (isset($tokenExist[0]["token_id"])){
                $result= static::$deviceTokenModel->update(column: ["token"=>$data["token"]], condition: ["token_id"=>$tokenExist[0]["token_id"]]);
            }else{
                $result = static::$deviceTokenModel->create($data);
                }
            $this->response($result);
        }
        else{
                RequestHandler::sendResponse(400,[],["message"=>"check your device token and device type."]);
        }
    }
}
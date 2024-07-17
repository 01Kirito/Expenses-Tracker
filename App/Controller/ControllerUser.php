<?php

namespace App\Controller;

use App\App;
use App\Database;
use App\Http\RequestHandler;
use App\Model\Budget;
use App\Model\Category;
use App\Model\Plan;
use App\Model\Preference;
use App\Model\User;
use Google\Service\VMwareEngine\Credentials;
use ReallySimpleJWT\Token;
use Predis\Client as RedisClient;

class ControllerUser extends Controller
{

    public static $User ;
    public static $requestHandler ;

    public function __construct()
    {
        static::$User = App::getInstance(User::class);
        static::$requestHandler = App::getInstance(RequestHandler::class);
    }

    public function login($data):void{
        $credentials["password"] = $data["body_json"]["password"];
        $credentials["email"] = $data["body_json"]["email"];
        $result= static::$User->searchForOneRow(selection:["id","password"] ,conditions: ["email"=>$credentials["email"]]);
        $auth = password_verify($credentials["password"],$result["password"]);
      if ($auth){
          $payload = [ 'iat' => time(), 'uid' => $result["id"], 'exp' => time() + 86400, 'iss' => 'localhost'];
          $token = Token::customPayload($payload, $_ENV["JWT_SECRET"]);
          static::$requestHandler->sendResponse(200,["Authorization"=>$token],["message"=>"Login Successful"]);
      }else{
          static::$requestHandler->sendResponse(401,[],["message"=>"Login failed, check your email and password"]);
      }
    }


    public function dashboard():void{
          $user         = $this->getAuthenticatedUser();
          $decidedMoney = App::getInstance(Plan::class)->searchForOneRow(conditions: ["user_id"=>$user['id']]);
          $categories   = App::getInstance(Category::class)->getAll(["category_id","name"]);
          foreach ($categories as $category){
              $usedAmount = $decidedMoney[$category["name"]."_balance"] ?? 0;
              $planned = $decidedMoney[$category["name"]];
              $percentage = ($planned != 0) ? ($usedAmount / $planned * 100) : 0;
              $analysis[$category['category_id']] = ["name"=>$category['name'],"amount"=>$usedAmount,"plan"=>$planned,"percentage"=>$percentage];
          }
          RequestHandler::sendResponse(200,[],$analysis);
    }


    public function dashboardCache():void{
        $user         = $this->getAuthenticatedUser();
        $decidedMoney = App::getInstance(Plan::class)->searchForOneRow(conditions: ["user_id"=>$user['id']]);
        $redis        = App::getInstance(RedisClient::class);
        $categories   = $redis->hgetAll("categories");
        foreach ($categories as $key=>$categoryName){
            $usedAmount = $decidedMoney[$categoryName."_balance"] ?? 0;
            $planned = $decidedMoney[$categoryName];
            $percentage = ($planned != 0) ? ($usedAmount / $planned * 100) : 0;
            $analysis[$key] = ["name"=>$categoryName,"amount"=>$usedAmount,"plan"=>$planned,"percentage"=>$percentage];
        }
        RequestHandler::sendResponse(200,[],$analysis);
    }


    public function index(): void{
        static::$User->readWithResponse();
    }

    public function store(array $data):void{
        //todo  make it use transactions and create fucntion that not create with response
        static::$User->createWithResponse($data['body_json']) ;
        $lastInsertId = App::getInstance(Database::class)->getConnection()->lastInsertId();
        App::getInstance(Preference::class)->createWithResponse(["user_id"=>$lastInsertId,"theme"=>"dark"]);
        App::getInstance(Plan::class)->createWithResponse(["user_id"=>$lastInsertId]);
        App::getInstance(Budget::class)->createWithResponse(["user_id"=>$lastInsertId]);
    }

    public function show():void{
        $user = $this->getAuthenticatedUser();
        static::$User->fetchOne(conditions: ["id"=>$user["id"]]);
    }

    public function update($data):void{
        $authenticatedUser = $this->getAuthenticatedUser();
        static::$User->updateWithResponse($data['body_json'],["id"=>$authenticatedUser['id']],false);
    }

    public function delete():void{
        $authenticatedUser = $this->getAuthenticatedUser();
        static::$User->deleteWithResponse(["id"=>$authenticatedUser['id']]);

    }

    public function softDelete():void{
        $authenticatedUser = $this->getAuthenticatedUser();
        static::$User->softDeleteWithResponse(["id"=>$authenticatedUser['id']], makeItHidden:True ) ;
    }

}
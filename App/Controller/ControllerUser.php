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

    public function login($credential):void{
      $result= static::$User->searchForOneRow(selection:"id" ,conditions: $credential['body_json']);
      if ($result){
          $payload = [ 'iat' => time(), 'uid' => $result["id"], 'exp' => time() + 86400, 'iss' => 'localhost'];
          $token = Token::customPayload($payload, $_ENV["JWT_SECRET"]);
          static::$requestHandler->sendResponse(200,["Authorization"=>$token],["message"=>"Login Successful"]);
      }else{
          static::$requestHandler->sendResponse(401,[],["message"=>"Login failed."]);
      }
    }


    public function dashboard():void{

          $user         = $this->getAuthenticatedUser();
          $decidedMoney = App::getInstance(Plan::class)->searchForOneRow(conditions: ["user_id"=>$user['id']]);
          $categories   = App::getInstance(Category::class)->getAll("category_id,name");

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
        var_dump($categories);
        foreach ($categories as $key=>$categoryName){
            $usedAmount = $decidedMoney[$categoryName."_balance"] ?? 0;
            $planned = $decidedMoney[$categoryName];
            $percentage = ($planned != 0) ? ($usedAmount / $planned * 100) : 0;

            $analysis[$key] = ["name"=>$categoryName,"amount"=>$usedAmount,"plan"=>$planned,"percentage"=>$percentage];
        }
        RequestHandler::sendResponse(200,[],$analysis);
    }


    public function index(): void{
        static::$User->read();
    }

    public function store(array $Data):void{
        static::$User->create($Data['body_json']) ;
        $lastInsertId = App::getInstance(Database::class)->getConnection()->lastInsertId();
        App::getInstance(Preference::class)->create(["user_id"=>$lastInsertId,"theme"=>"dark"]);
        App::getInstance(Plan::class)->create(["user_id"=>$lastInsertId]);
        App::getInstance(Budget::class)->create(["user_id"=>$lastInsertId]);
    }

    public function show():void{
        $user = $this->getAuthenticatedUser();
        $pairs["id"] =$user["id"];
        static::$User->fetchOne(conditions: $pairs);
    }

    public function update($Data):void{
        $authenticatedUser = $this->getAuthenticatedUser();
        static::$User->updateWithResponse($Data['body_json'],["id"=>$authenticatedUser['id']],false);
    }

    public function delete():void{
        $authenticatedUser = $this->getAuthenticatedUser();
        static::$User->delete(["id"=>$authenticatedUser['id']]);

    }

    public function softDelete():void{
        $authenticatedUser = $this->getAuthenticatedUser();
        static::$User->softDelete(["id"=>$authenticatedUser['id']], makeItHidden:True ) ;
    }

}
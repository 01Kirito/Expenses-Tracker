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

    public static $userModel ;
    public static $requestHandler ;

    public function __construct()
    {
        Parent::__construct();
        static::$userModel = App::getInstance(User::class);
        static::$requestHandler = App::getInstance(RequestHandler::class);
    }

    public function login($data):void{
        $credentials["password"] = $data["body_json"]["password"];
        $credentials["email"] = $data["body_json"]["email"];
        $result= static::$userModel->get(selection:["id","password"] ,condition: ["email"=>$credentials["email"]] ,fetchOneRow: true);
        if (array_key_exists("error",$result) || array_key_exists("message",$result)){
          $this->response($result);
        } elseif (password_verify($credentials["password"],$result["password"])){
          $payload = [ 'iat' => time(), 'uid' => $result["id"], 'exp' => time() + 86400, 'iss' => 'localhost'];
          $token = Token::customPayload($payload, $_ENV["JWT_SECRET"]);
          static::$requestHandler->sendResponse(200,["Authorization"=>$token],["message"=>"Login Successful"]);
      }else{
          static::$requestHandler->sendResponse(401,[],["message"=>"Login failed, check your email and password"]);
      }
    }
    public function getUser($data): void{
        $result = static::$userModel->get(
            selection: ["id","concat(first_name,' ',last_name) FullName ","invoice_id","name Category","amount","i.description invoice_detail","purchase_date"],
            condition: $data["url_parameters"] ,
            logicalOperator: ["AND"],
            join: ["invoices i "=>" users.id = i.user_id ","categories c"=>"c.category_id = i.category_id "]
        );
        $this->response($result);
    }

    public function dashboard():void{
          $user         = $this->getAuthenticatedUser();
          $decidedMoney = App::getInstance(Plan::class)->get(condition: ["user_id = "=>$user["id"]],fetchOneRow: true);
          $categories   = App::getInstance(Category::class)->get(selection:["category_id","name"]);
          if (array_key_exists("error",$categories) || array_key_exists("message",$categories) || array_key_exists("error",$decidedMoney) || array_key_exists("message",$decidedMoney)) {
             @ $this->response(["error"=>$categories["error"].$decidedMoney["error"],"message"=>$categories["message"].$decidedMoney["message"]]);
          }else{
          foreach ($categories as $category){
              $usedAmount = $decidedMoney[$category["name"]."_used"] ?? 0;
              $planned = $decidedMoney[$category["name"]];
              $percentage = ($planned != 0) ? ($usedAmount / $planned * 100) : 0;
              $analysis []= ["category_id"=>$category['category_id'],"name"=>$category['name'],"usedAmount"=>$usedAmount,"plan"=>$planned,"percentage"=>round($percentage,2)];
          }
          $this->response($analysis);
          }
    }



    public function index(): void{
       $result = static::$userModel->get();
       $this->response($result);
    }

    public function store(array $data):void{
        // todo  make it use transactions and create function that not create with response
        $result = static::$userModel->create($data['body_json']) ;
        if (!array_key_exists("error",$result)){
        $lastInsertId = App::getInstance(Database::class)->getConnection()->lastInsertId();
        App::getInstance(Preference::class)->create(["user_id"=>$lastInsertId,"theme"=>"dark"]);
        App::getInstance(Plan::class)->create(["user_id"=>$lastInsertId]);
        App::getInstance(Budget::class)->create(["user_id"=>$lastInsertId]);
        }
        $this->response($result);
    }
    public function show():void{
        $user = $this->getAuthenticatedUser();
        $result = static::$userModel->get(condition: ["id"=>$user["id"]],fetchOneRow: true);
        $this->response($result);
    }

    public function update($data):void{
        $authenticatedUser = $this->getAuthenticatedUser();
        $result = static::$userModel->update(column:$data['body_json'], condition: ["id"=>$authenticatedUser['id']] ,autoDateUpdate:false);
        $this->response($result);
    }

    public function delete():void{
        $authenticatedUser = $this->getAuthenticatedUser();
        $result = static::$userModel->delete(["id"=>$authenticatedUser['id']]);
        $this->response($result);

    }

    public function softDelete():void{
        $authenticatedUser = $this->getAuthenticatedUser();
        $result =  static::$userModel->softDelete(["id"=>$authenticatedUser['id']], makeItHidden:True ) ;
        $this->response($result);
    }

}
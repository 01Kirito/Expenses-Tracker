<?php

namespace App\Controller;
use App\App;
use App\Auth;
use App\Http\RequestHandler;
use App\Model\Category;
use App\Model\CustomPlan;
use App\Model\Invoice;
use App\Model\Plan;

class ControllerInvoice extends Controller {

    public static $Invoice ;

    public function __construct()
    {
        static::$Invoice = App::getInstance(Invoice::class);
    }

    public function index(): void{
        $user = $this->getAuthenticatedUser();
        static::$Invoice->readWithResponse(conditions: "WHERE user_id=".$user["id"]);
    }

    public function store(array $data):void{
        $user = $this->getAuthenticatedUser();
        $categoryColumn = App::getInstance(Category::class)->searchForOneRow(selection: ["name"],conditions: ["category_id"=> $data['body_json']['category_id']])['name'];
        $categoryBudget = App::getInstance(Plan::class)->searchForOneRow(selection: [$categoryColumn,$categoryColumn."_balance"],conditions: ["user_id"=>1]);
        var_dump($categoryBudget);
        if ($categoryBudget === false){
            $categoryBudget = App::getInstance(CustomPlan::class)->searchForOneRow(selection: [$categoryColumn,$categoryColumn."_balance"],conditions: ["user_id"=>7]);
        }
var_dump($categoryBudget);
            if ($user) {
                if ($this->checkBudget($categoryBudget[$categoryColumn],$categoryBudget[$categoryColumn."_balance"],$data['body_json']['amount'],$categoryColumn)){
                $data['body_json']['user_id'] = $user['id'];
                static::$Invoice->createWithResponse($data['body_json']);
                }else{
                    RequestHandler::sendResponse(404,[],["message"=>"Update plan limit so you can add invoice"]);
                }
            }else{
                RequestHandler::sendResponse(400,[],['message'=>'User not authenticated']);
            }
    }

    public function show(array $data):void{
        $user    = $this->getAuthenticatedUser();
        $invoice = static::$Invoice->searchForOneRow(conditions: $data['url_parameters']);
        if ($invoice['user_id'] === $user['id']){
            RequestHandler::sendResponse(200,[],$invoice);
        }else{
            RequestHandler::sendResponse(404,[],["message"=>"Unauthorized"]);
        }
    }

    public function update($data):void{
        $user = $this->getAuthenticatedUser();
        $invoice = static::$Invoice->searchForOneRow(conditions: $data['url_parameters']);
        if ($invoice['user_id'] === $user['id']){
            static::$Invoice->updateWithResponse($data['body_json'],$data['url_parameters'],false);
        }else{
            RequestHandler::sendResponse(404,[],["message"=>"Unauthorized"]);
        }
    }

    public function delete(array $data):void{
        $user = $this->getAuthenticatedUser();
        $invoice = static::$Invoice->searchForOneRow(conditions: $data['url_parameters']);
        if ($invoice['user_id'] === $user['id']){
            static::$Invoice->deleteWithResponse($data['url_parameters']);
        }else{
            RequestHandler::sendResponse(404,[],["message"=>"Unauthorized"]);
        }
    }


    private function checkBudget($planPrice, $currentAmount, $purchasePrice, $categoryColumn):bool{
        $amountAfterPurchase = $currentAmount+$purchasePrice ;
     if ($amountAfterPurchase > $planPrice){
         return false;
     }else{
         App::getInstance(Plan::class)->update([$categoryColumn."_balance" => $amountAfterPurchase],["user_id"=>$this->getAuthenticatedUser()["id"]]);
         return true;
     }
    }

}
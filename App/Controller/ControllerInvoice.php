<?php

namespace App\Controller;
use App\App;
use App\Auth;
use App\Http\RequestHandler;
use App\Model\Category;
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
        static::$Invoice->read(conditions: "WHERE user_id=".$user["id"]);
    }

    public function store(array $Data):void{
        $user = $this->getAuthenticatedUser();
        $categoryColumn = App::getInstance(Category::class)->searchForOneRow(selection: "name",conditions: ["category_id"=> $Data['body_json']['category_id']])['name'];
        $categoryBudget = App::getInstance(Plan::class)->searchForOneRow(selection: "{$categoryColumn},{$categoryColumn}_balance",conditions: ["user_id"=>$user["id"]]);

            if ($user) {
                if ($this->checkBudget($categoryBudget[$categoryColumn],$categoryBudget[$categoryColumn."_balance"],$Data['body_json']['amount'],$categoryColumn)){
                $Data['body_json']['user_id'] = $user['id'];
                static::$Invoice->create($Data['body_json']);
                }else{
                    RequestHandler::sendResponse(404,[],["message"=>"Update plan limit so you can add invoice"]);
                }
            }else{
                RequestHandler::sendResponse(400,[],['message'=>'User not authenticated']);
            }
    }

    public function show(array $Data):void{
        $user    = $this->getAuthenticatedUser();
        $invoice = static::$Invoice->searchForOneRow(conditions: $Data['url_parameters']);
        if ($invoice['user_id'] === $user['id']){
            RequestHandler::sendResponse(200,[],$invoice);
        }else{
            RequestHandler::sendResponse(404,[],["message"=>"Unauthorized"]);
        }
    }

    public function update($Data):void{
        $user = $this->getAuthenticatedUser();
        $invoice = static::$Invoice->searchForOneRow(conditions: $Data['url_parameters']);
        if ($invoice['user_id'] === $user['id']){
            static::$Invoice->updateWithResponse($Data['body_json'],$Data['url_parameters'],false);
        }else{
            RequestHandler::sendResponse(404,[],["message"=>"Unauthorized"]);
        }
    }

    public function delete(array $Data):void{
        $user = $this->getAuthenticatedUser();
        $invoice = static::$Invoice->searchForOneRow(conditions: $Data['url_parameters']);
        if ($invoice['user_id'] === $user['id']){
            static::$Invoice->delete($Data['url_parameters']);
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
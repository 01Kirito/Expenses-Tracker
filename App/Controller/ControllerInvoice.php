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

    public static $invoiceModel ;

    public function __construct()
    {
        Parent::__construct();
        static::$invoiceModel = App::getInstance(Invoice::class);
    }

    public function index(): void{
        $user = $this->getAuthenticatedUser();
        $result = static::$invoiceModel->get(condition: ["user_id"=>$user["id"]]);
        $this->response($result);
    }

    public function store(array $data):void{
        $user = $this->getAuthenticatedUser();
        $categoryColumn = App::getInstance(Category::class)->get(condition: ["category_id"=> $data['body_json']['category_id']], fetchOneRow: true);
        $categoryName = $categoryColumn[0]["name"];
        $categoryBudget = App::getInstance(Plan::class)->get(selection: [$categoryName,$categoryName."_used"],condition: ["user_id"=>$user["id"]],fetchOneRow: true);
        $categoryPlan = $categoryBudget[0];

        if ($categoryBudget["error"] !== false || $categoryColumn["error"] !== false){
        $this->response(["error"=>true, "message"=>($categoryColumn["message"] ?? "").", ".($categoryBudget["message"] ?? "")],failedStatusCode: 500);
        }else{
                if ($this->checkBudget(planPrice:$categoryPlan[$categoryName],currentAmount:$categoryPlan[$categoryName."_used"],purchasePrice:$data['body_json']['amount'],categoryColumn:$categoryName)){
                $data['body_json']['user_id'] = $user['id'];
                $result =  static::$invoiceModel->create($data['body_json']);
                $this->response($result);
                }else{
                 $this->response(["error"=>false,"message"=>"Update ".$categoryName." plan limit so you can add invoice"]);
                }
        }
    }

    public function show(array $data):void{
        $user    = $this->getAuthenticatedUser();
        $invoice = static::$invoiceModel->get(condition: $data['url_parameters'],fetchOneRow: true);
        if ( $invoice["error"] === true ) $this->response($invoice) ;
        elseif ($invoice[0]["user_id"] === $user["id"]){
            $this->response($invoice);
        }else{
            RequestHandler::sendResponse(404,[],["message"=>"Unauthorized"]);
        }
    }

    public function update($data):void{
        $user = $this->getAuthenticatedUser();
        $invoice = static::$invoiceModel->get(condition: $data['url_parameters'],fetchOneRow: true);
        if ($invoice[0]['user_id'] === $user['id']){
           $result =  static::$invoiceModel->update($data['body_json'],$data['url_parameters'],autoDateUpdate:false);
           $this->response($result);
        }else{
            RequestHandler::sendResponse(404,[],["message"=>"Unauthorized"]);
        }
    }

    public function delete(array $data):void{
        $user = $this->getAuthenticatedUser();
        $invoice = static::$invoiceModel->get(condition: $data['url_parameters'],fetchOneRow: true);
        if ( $invoice["error"] === true ) $this->response($invoice);
        elseif ($invoice[0]['user_id'] === $user["id"]){
            $result = static::$invoiceModel->delete($data['url_parameters']);
            $this->response($result);
        }else{
            RequestHandler::sendResponse(404,[],["message"=>"Unauthorized"]);
        }
    }


    private function checkBudget($planPrice, $currentAmount, $purchasePrice, $categoryColumn):bool{
        $amountAfterPurchase = $currentAmount+$purchasePrice ;
     if ($amountAfterPurchase > $planPrice){
         return false;
     }else{
         App::getInstance(Plan::class)->update([$categoryColumn."_used" => $amountAfterPurchase],["user_id"=>$this->getAuthenticatedUser()["id"]]);
         return true;
     }
    }

}
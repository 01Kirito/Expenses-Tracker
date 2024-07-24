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
        $categoryName = $categoryColumn["name"] ?? "";
        $categoryBudget = App::getInstance(Plan::class)->get(selection: [$categoryName,$categoryName."_used"],condition: ["user_id"=>$user["id"]],fetchOneRow: true);

        if (array_key_exists("error",$categoryColumn) || array_key_exists("message",$categoryColumn) || array_key_exists("error",$categoryBudget) || array_key_exists("message",$categoryBudget)) {
//        todo remove @ symbol cause it's not professional to use it , and the professional way is to use exception handler and so on
            @ $this->response(["error"=>$categoryBudget["error"].$categoryColumn["error"],"message"=>$categoryColumn["message"]." , ".$categoryBudget["message"]],failedStatusCode: 500);
        }else{
                if ($this->checkBudget(planPrice:$categoryBudget[$categoryName],currentAmount:$categoryBudget[$categoryName."_used"],purchasePrice:$data['body_json']['amount'],categoryColumn:$categoryName)){
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
        if (array_key_exists("error",$invoice) || array_key_exists("message",$invoice)) $this->response($invoice) ;
        elseif ($invoice["user_id"] === $user["id"]){
            $this->response($invoice);
        }else{
            RequestHandler::sendResponse(404,[],["message"=>"Unauthorized"]);
        }
    }

    public function update($data):void{
        $user = $this->getAuthenticatedUser();
        $invoice = static::$invoiceModel->get(condition: $data['url_parameters'],fetchOneRow: true);
        if (array_key_exists("error",$invoice) || array_key_exists("message",$invoice)){
            $this->response($invoice) ;
        }elseif ($invoice['user_id'] === $user['id']){
           $result =  static::$invoiceModel->update($data['body_json'],$data['url_parameters'],autoDateUpdate:false);
           $this->response($result);
        }else{
            RequestHandler::sendResponse(404,[],["message"=>"Unauthorized"]);
        }
    }

    public function delete(array $data):void{
        $user = $this->getAuthenticatedUser();
        $invoice = static::$invoiceModel->get(condition: $data['url_parameters'],fetchOneRow: true);
        if (array_key_exists("error",$invoice) || array_key_exists("message",$invoice)){
            $this->response($invoice);
        } elseif ($invoice['user_id'] === $user["id"]){
            $result = static::$invoiceModel->delete($data['url_parameters']);
            $this->response($result);
        }else{
            RequestHandler::sendResponse(404,[],["message"=>"Unauthorized"]);
        }
    }


    private function checkBudget($planPrice, $currentAmount, $purchasePrice, $categoryColumn):bool{
        $user = $this->getAuthenticatedUser();
        $amountAfterPurchase = $currentAmount+$purchasePrice ;
     if ($amountAfterPurchase > $planPrice){
         return false;
     }else{
         $result = App::getInstance(Plan::class)->update([$categoryColumn."_used" => $amountAfterPurchase],["user_id"=>$user["id"]]);
         return !array_key_exists("error", $result);
     }
    }

}
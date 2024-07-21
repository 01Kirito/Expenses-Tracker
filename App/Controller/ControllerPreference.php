<?php

namespace App\Controller;

use App\App;
use App\Model\Invoice;
use App\Model\Preference;

class ControllerPreference extends Controller
{
    public static $preferenceModel ;

    public function __construct()
    {
        Parent::__construct();
        static::$preferenceModel = App::getInstance(Preference::class);
    }

    public function index(): void
    {

    }

    public function store(array $data):void{
        $result = static::$preferenceModel->addColumn($data["body_json"]["name"],$data["body_json"]["datatype"]) ;
        $this->response($result);
    }

    public function show(array $data):void{
        $user = $this->getAuthenticatedUser();
        $preference = $data["url_parameters"]["selection"] ?? "*" ;
        $result =  static::$preferenceModel->get(selection: [$preference],condition: ["user_id"=>$user["id"]]) ;
        $this->response($result);
    }

    public function update($data):void{
        $user = $this->getAuthenticatedUser();
        $data["url_parameters"]["user_id"] = $user["id"];
        $result = static::$preferenceModel->update($data['body_json'],$data['url_parameters'],false);
        $this->response($result);
    }

    public function delete(array $data):void{
//       $result = static::$preferenceModel->delete=($data['url_parameters']);
//         $this->response($result);
    }

}
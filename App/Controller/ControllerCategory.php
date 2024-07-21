<?php

namespace App\Controller;

use App\App;
use App\Model\Category;
use App\Model\Invoice;
use App\Model\Plan;

class ControllerCategory extends Controller
{

    public static $categoryModel ;

    public function __construct()
    {
        Parent::__construct();
        static::$categoryModel = App::getInstance(Category::class);
    }

    public function index(): void
    {
        $result = $this->getCache("categoriess");
        $this->response($result);
    }

    public function store(array $data):void{
        $result = static::$categoryModel->create($data['body_json']) ;
        if ($result["error"]===false){
        App::getInstance(Plan::class)->addColumn($data['body_json']['name'],"DECIMAL(10,2) NOT NULL default 0.00");
        App::getInstance(Plan::class)->addColumn($data['body_json']['name']."_used","DECIMAL(10,2) NOT NULL default 0.00");
        }
        $this->response($result);
    }

    public function show(array $data):void{
        $result = static::$categoryModel->get(condition: $data['url_parameters'],fetchOneRow: True);
        $this->response($result);
    }

    public function update($data):void{
       $result = static::$categoryModel->update($data['body_json'],$data['url_parameters'],false);
       $this->response($result);
    }

    public function delete(array $data):void{
        $result = static::$categoryModel->delete($data['url_parameters']);
        $this->response($result);
    }


}
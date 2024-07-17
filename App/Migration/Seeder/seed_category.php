<?php


use App\App;
use App\Model\Category;
use App\Model\Plan;

$categoryModel = App::getInstance(Category::class);
$planModel = App::getInstance(Plan::class);

$categoriesToFeed = [
    ["name"=>"Travling", "description"=>"......"],
    ["name"=>"Education","description"=>"For ecucation purposes"],
    ["name"=>"Renting","description"=>"For renting purposes"]
];


foreach ($categoriesToFeed as $category) {
    echo $categoryModel->create($category)."\n" ;
    echo $planModel->addColumn($category['name'],"DECIMAL(10,2) NOT NULL default 0.00")."\n" ;
    echo $planModel->addColumn($category['name']."_balance","DECIMAL(10,2) NOT NULL default 0.00")."\n" ;
}
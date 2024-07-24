<?php

use App\App;
use App\Model\Category;
use App\Model\Plan;

$categoryModel = App::getInstance(Category::class);
$planModel = App::getInstance(Plan::class);

// if you want to add category you just need to add another element in the below array
$categoriesToFeed = [
    ["name"=>"Traveling", "description"=>"For Traveling purposes like going to journey or picnic or going aboard"],
    ["name"=>"Education","description"=>"For education purposes like paying the college or buying the studying needs"],
    ["name"=>"Renting","description"=>"For renting purposes like renting house or car or farm"],
    ["name"=>"Food","description"=>"For food purposes like going to restaurant or caffe or buying elements for making the food "],
    ["name"=>"Clothe","description"=>"For clothes purposes like buying the clothes or suit"],
    ["name"=>"Tax","description"=>"For tax purposes like house tax or television tax"],
    ["name"=>"Service","description"=>"For services purposes like electronic services"],
    ["name"=>"Health","description"=>"For health's purposes like medicine or annual test's"]
];


foreach ($categoriesToFeed as $category) {
    $result = $categoryModel->create($category) ;
    if (array_key_exists("error", $result)) {
        echo $result["error"].$result["message"]."\n";
    }else{
       $result2 = $planModel->addColumn($category['name'],"DECIMAL(10,2) NOT NULL default 0.00") ;
       $result3 = $planModel->addColumn($category['name']."_used","DECIMAL(10,2) NOT NULL default 0.00");
        echo $result2["message"]."\n";
        echo $result3["message"]."\n";
    }
}
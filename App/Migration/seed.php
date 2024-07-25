<?php

use App\App;
use App\Model\User;

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../bootstrap.php';
require_once __DIR__.'/../../loadContainer.php';


require_once __DIR__."/Seeder/seed_user.php";
require_once __DIR__."/Seeder/seed_category.php";

$userModel = App::getInstance(User::class);
$result = $userModel->create(["first_name"=>"Admin","last_name"=>"Azad","email"=>"admin@gmail.com","password"=>"admin123",]);
if (array_key_exists("error",$result)) {
    echo $result["error"]." ".$result["message"];
}else{
    echo $result["message"];
}

require_once __DIR__.'/Seeder/seed_invoice.php';

//echo $userModel->addColumn('user_type',"VARCHAR(50) default NULL")."\n" ;


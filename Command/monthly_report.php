<?php

use App\App;
use App\Model\Category;
use Predis\Client;

require_once '../vendor/autoload.php';
require_once '../loadContainer.php';

// this codes below is just for test, not real month report
$categories = App::getInstance(Category::class)->get(selection:["category_id","name"]);
var_dump($categories);
die();
$redis = App::getInstance(Client::class);
$redis->set("monthly_report", json_encode($categories));


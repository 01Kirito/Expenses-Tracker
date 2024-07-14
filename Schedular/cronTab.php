<?php

use App\App;
use App\Model\Category;
use Predis\Client;

require_once '../vendor/autoload.php';
require_once '../loadContainer.php';

$categories = App::getInstance(Category::class)->getAll("category_id,name");
$redis = App::getInstance(Client::class);
$redis->del("categories");





foreach ($categories as $category) {
    $redis->hset("categories", $category["category_id"], $category["name"]);
}
$redis->expire("categories", 1800);





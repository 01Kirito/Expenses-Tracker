<?php
use App\App;
use App\Model\Category;
use Predis\Client;

require_once __DIR__.'/../vendor/autoload.php';
require_once __DIR__.'/../loadContainer.php';

$categories = App::getInstance(Category::class)->get();
if (array_key_exists("error",$categories) || array_key_exists("message",$categories)){
    throw new \Exception("Redis cache error");
}else{

$redis = App::getInstance(Client::class);
$redis->del("categories");
$redis->set("categories", json_encode($categories));
//foreach ($categories[0] as $category) {
//    var_dump($category);
//    $redis->hmset("categories", $category["category_id"], $category["name"]);
//}
$redis->expire("categories", 86400);
}



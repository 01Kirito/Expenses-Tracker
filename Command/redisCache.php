<?php
use App\App;
use App\Model\Category;
use Predis\Client;

require_once '../vendor/autoload.php';
require_once '../loadContainer.php';

$categories = App::getInstance(Category::class)->get();
if ($categories["error"] === true ) throw new \Exception("Redis cache error");
$redis = App::getInstance(Client::class);
$redis->del("categories");
//foreach ($categories[0] as $category) {
//    var_dump($category);
//    $redis->hmset("categories", $category["category_id"], $category["name"]);
//}
$redis->set("categories", json_encode($categories[0]));

$redis->expire("categories", 86400);





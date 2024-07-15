<?php

use App\App;
use App\Model\Category;
use Predis\Client;

require_once '../vendor/autoload.php';
require_once '../loadContainer.php';

// this codes below is just for test, not real daily notification
$categories = App::getInstance(Category::class)->getAll("category_id,name");
$redis = App::getInstance(Client::class);
$redis->del("categories");



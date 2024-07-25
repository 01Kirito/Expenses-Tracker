<?php

use App\App;
use App\Model\Invoice;

$invoiceModel = App::getInstance(Invoice::class);
$data =[
    "user_id" => 1,
    "category_id"=> "2",
    "amount"=> "1.56",
    "description"=> "house renting",
    "purchase_date"=> "2024-10-23 11:00:00"
];

for($i=0; $i<5000; $i++){
    $invoiceModel->create($data);
}

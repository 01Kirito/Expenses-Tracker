<?php

use App\App;
use App\Model\User;

$userModel = App::getInstance(User::class);
// Number of users to seed
$numUsers = $_ENV['NUM_USERS'];

// Function to generate a random string
function generateRandomString($length = 8)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

// Seed users
for ($i = 0; $i < $numUsers; $i++) {
//    $first_name = generateRandomString();
//    $last_name = generateRandomString();
//    $email = strtolower($first_name) . 'Migration' . strtolower($last_name) . '@example.com'; // Generate unique email
    $first_name = "fuser".$i;
    $last_name = "luser".$i;
    $email = "useremail" .$i . '@example.com'; // Generate unique email
    $password = 'password123';
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');
    $soft_delete = 0; // Assuming soft_delete is an integer (0 for active, 1 for deleted)
    $data = ["first_name"=>$first_name,"last_name"=>$last_name,"email"=>$email,"password"=>$password,"created_at"=>$created_at,"updated_at"=>$updated_at,"soft_delete"=>$soft_delete];
    $result = $userModel->create($data);
    echo (array_key_exists("error",$result) ? $result["error"] : "");
}


echo "Users seeded successfully.\n";


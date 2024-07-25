<?php

use App\App;
use App\Model\DeviceToken;
use Google\Client;

require_once('../vendor/autoload.php');
require_once('../loadContainer.php');

$serviceAccountPath = $_ENV["GOOGLE_APPLICATION_CREDENTIALS"];
$projectId          = 'budget-manager-8a82d';
$accessToken        = getAccessToken($serviceAccountPath);
$deviceTokens       = App::getInstance(DeviceToken::class)->get(selection:["token"]);
if ($deviceTokens["error"] !== false){
    echo "Error: " . $deviceTokens["error"] . "\n";
}else{
foreach ($deviceTokens[0] as $deviceToken) {
    $response = sendNotification($accessToken, $deviceToken["token"]);
    echo  "<br>".$response;
}
}

function sendNotification($accessToken, $deviceTokens) {

    $url = "https://fcm.googleapis.com/v1/projects/budget-manager-8a82d/messages:send";

    $data = [
    'message' => [
        "notification"=>[
        "body"=>"This is an FCM notification message!",
        "title"=>"FCM Message"
      ],
        "data"=> [
            "title" => "Title",
            "body" => "This is message body.",
            "icon" => "https://farm4.staticflickr.com/3852/14447103450_2d0ff8802b_z_d.jpg",
            "image" => "https://farm2.staticflickr.com/1533/26541536141_41abe98db3_z_d.jpg",
            "click_action" => "https://farm2.staticflickr.com/1533/26541536141_41abe98db3_z_d.jpg"
        ],
        "webpush" => [
            "fcmOptions"=> [
                 "link" =>'https://www.youtube.com/'
            ],
            "headers"=> [
                "image"=> 'https://farm2.staticflickr.com/1533/26541536141_41abe98db3_z_d.jpg'
            ]
        ],
                'token' => $deviceTokens
    ]
];


$options = array(
    CURLOPT_URL => $url,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => array(
        "Authorization: Bearer " . $accessToken,
        "Content-Type: application/json",
    ),
    CURLOPT_POSTFIELDS => json_encode($data),
);

$curl = curl_init();
curl_setopt_array($curl, $options);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($curl);
curl_close($curl);
return $response;
}

function getAccessToken($serviceAccountPath) {
    $client = new Client();
    $client->setAuthConfig($serviceAccountPath);
    $client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    $client->useApplicationDefaultCredentials();
    $token = $client->fetchAccessTokenWithAssertion();
    return $token['access_token'];
}



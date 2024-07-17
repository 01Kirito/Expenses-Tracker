<?php

use App\App;
use App\Model\DeviceToken;
use Google\Client;

require_once('../vendor/autoload.php');
require_once('../loadContainer.php');

$serviceAccountPath = $_ENV["GOOGLE_APPLICATION_CREDENTIALS"];
$projectId          = 'budget-manager-8a82d';
$accessToken        = getAccessToken($serviceAccountPath);
$deviceTokens       = App::getInstance(DeviceToken::class)->getAll(["token"]);

//$deviceTokens = [
//    "fgPn_glKCpzB8E9xkOXpiq:APA91bFQNiE1DVVi68hcBwG9I3k883UsbPbfMGLpg7w345W2ebVoqMx-TGep0-eI5xNbkPtXuMJCQpX5llnw4SMZCBY4lv9wzJirjvgMrUEEhfmrfKeU_yj1GkAnNh0QueAgXOWYk8w7",
//    "dvFlT3irFgRQpA3Dryl_kQ:APA91bFBfjMZCwphOA71Mxpc8v1VtuJmpyQH7v-t5X4PqyFnH0YQDV0PpgGgS5zhvQ5sIQVJF-ZTzGEPxBCdMZqOh3__zhGqmmz1GuVaHQZ4QqKsgoDmw0GTmg_kNFaHjy5BYVXAgU_z",
//    "eUg-2jLTPXTx9zO3nTe2S-:APA91bHvss6X_BvdqDebkKqD8F4zk79-t_PiT5X2MUy6QGhBpXiRUZuay8dspSICxCJsQKwY56UhC7KTgDkfhScKj8us5vr4rxdBgm1NPOqzqj2dTDHjn5AqN8ncsqD8mwRqlNlJVSr0",
//    "fhocaHS_Rp2-0y5rVTZmA6:APA91bET-Fx4sT5WtOdgkDoKCnmrF_5ysEkvS82LBaXfUuIzr-S0GZppqJdar3nTzNT_aAl2IEALT3hq8aASY_WNWGtKKcWd4zHrL1QClbOPsOKY4LIIESTZwNFgs20t9DdzFYVxYlM7"
//];


foreach ($deviceTokens as $deviceToken) {
    $response = sendNotification($accessToken, $deviceToken);
    echo  "<br>".$response;
}


function sendNotification($accessToken, $deviceTokens) {

    $url = "https://fcm.googleapis.com/v1/projects/budget-manager-8a82d/messages:send";

    $data = [
    'message' => [
        "notification"=>[
        "body"=>"This is an FCM notification message!",
        "title"=>"FCM Message"
      ],
        "webpush" => [
            "fcmOptions"=> [
                 "link" =>'https://www.youtube.com/'
            ],
            "headers"=> [
                "image"=> 'https://farm2.staticflickr.com/1533/26541536141_41abe98db3_z_d.jpg'
            ]
        ],
        "data"=> [
            "title" => "Title",
            "body" => "This is message body.",
            "icon" => "https://farm4.staticflickr.com/3852/14447103450_2d0ff8802b_z_d.jpg",
            "image" => "https://farm2.staticflickr.com/1533/26541536141_41abe98db3_z_d.jpg",
            "click_action" => "https://farm2.staticflickr.com/1533/26541536141_41abe98db3_z_d.jpg"
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



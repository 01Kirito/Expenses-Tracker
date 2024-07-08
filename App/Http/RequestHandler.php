<?php

namespace App\Http;

class RequestHandler {
   static protected $commonResponceHeaders = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
        'Content-Length' => 0,
        'Accept-Encoding' => 'gzip, deflate',
        'Date' => '',
    ];

    static protected $commonRequestHeaders = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
        'Content-Length' => 0,
        'Accept-Encoding' => 'gzip, deflate',
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS, PUT, DELETE',
        'Accept-Language' => 'en-US,en;q=0.8',
        'User-Agent' => '',
        'Host' => 'localhost',
        'Date' => '',
    ];
    function sendResponse( int $httpCode,?array $headers,$data){
       http_response_code($httpCode);
       $responseHeaders = self::$commonResponceHeaders;
       if ($headers ==! null){
       foreach ($headers as $key => $value) {
           $responseHeaders[$key] = $value;
       }}

       foreach($responseHeaders as $key => $value){
           header($key.':'.$value);
       }
       echo json_encode($data);
    }

}
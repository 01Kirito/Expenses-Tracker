<?php

namespace App\Http;

class RequestHandler {
   static protected array $commonResponseHeaders = [
        'Content-Type' => 'application/json',
//        'Accept' => 'application/json',
//        'Transfer-Encoding' => 'chunked', makeing error see the determined values for it and how it used
//        'Server' => 'Apache',
        'Connection' => 'keep-alive',
        'Date' => '',
    ];

    static protected array $commonRequestHeaders = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
//        'Content-Length' => 0,
        'Accept-Encoding' => 'gzip, deflate',
        'Access-Control-Allow-Origin' => '*',
        'Access-Control-Allow-Methods' => 'GET, POST, OPTIONS, PUT, DELETE',
        'Accept-Language' => 'en-US,en;q=0.8',
        'User-Agent' => '',
        'Host' => 'localhost',
        'Date' => '',
    ];

    public static function sendResponse( int $statusCode ,?array $customHeaders = null ,?array $data =[]){
       http_response_code($statusCode);
       $headers = $customHeaders === null ? self::$commonResponseHeaders : static::setCustomHeader($customHeaders);
       static::setHeader($headers);
       echo json_encode($data);
    }


    private static function setHeader(array $headers): void
    {
        foreach($headers as $key => $value){
            header($key.':'.$value);
        }
    }

    private static function setCustomHeader(array $headers): array
    {

        $customHeader = self::$commonResponseHeaders;
        foreach($headers as $key => $value){
            $customHeader[$key] = $value;
        }
        return $customHeader;
    }


}
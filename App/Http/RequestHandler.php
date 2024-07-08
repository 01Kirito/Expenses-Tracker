<?php

namespace App\Http;

class RequestHandler {
   static protected array $commonResponseHeaders = [
        'Content-Type' => 'application/json',
        'Accept' => 'application/json',
        'Cache-Control' => 'no-cache',
        'Connection' => 'keep-alive',
        'Content-Length' => 0,
        'Accept-Encoding' => 'gzip, deflate',
        'Date' => '',
    ];

    static protected array $commonRequestHeaders = [
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


    public function sendResponse( int $httpCode ,?array $customHeaders,$data): void{
       http_response_code($httpCode);
       $headers = $customHeaders === null ? self::$commonResponseHeaders : self::setCustomHeader($customHeaders);
       $this->setHeader($headers);
       echo json_encode($data);
    }


    protected function setHeader(array $headers): void
    {
        foreach($headers as $key => $value){
            header($key.':'.$value);
        }
    }

    protected function setCustomHeader(array $headers): array
    {

        $customHeader = self::$commonResponseHeaders;
        foreach($headers as $key => $value){
            $customHeader[$key] = $value;
        }
        return $customHeader;
    }


}
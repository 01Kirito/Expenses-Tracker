<?php

namespace App;

use App\Http\RequestHandler;
use Exception;
use ReflectionMethod;

class Router
{
    private static array $routes;


    function addRoute($route, $action, $method): void
    {
        static::$routes[$route] = ["Action" => $action, "Method" => strtoupper($method)];
    }

    public static function route($class, $function): void
    {
        $parameters = self::fetchBodyAndUrlParameters();

        $requestMethod  = new ReflectionMethod($class, $function);
        $parametersForMethod = $requestMethod->getNumberOfRequiredParameters();

        if ($parametersForMethod > 0 && $parameters !== false ) {
            call_user_func_array(array(App::getInstance($class), $function), array($parameters));
        }elseif($parametersForMethod > 0 && $parameters === false) {
            App::getInstance(RequestHandler::class)->sendResponse(400,["Connection"=>"close"],["Message"=>"This endpoint needs body or url parameters"]);
        }elseif ($parametersForMethod == 0 && $parameters === false) {
            call_user_func_array(array(App::getInstance($class), $function), array());
        }else {
            App::getInstance(RequestHandler::class)->sendResponse(400,["Connection"=>"close"],["Message"=>"This endpoint don't accept any body or url parameters"]);
        }
    }

    public function search($url): void
    {
        $requestHandler = App::getInstance(RequestHandler::class);
        if (key_exists($url, static::$routes)) {
            if ($_SERVER['REQUEST_METHOD'] === static::$routes[$url]["Method"]) {
                $routeParameter = static::$routes[$url];
                $this->route(...$routeParameter["Action"]);
            } else {
                $requestHandler->sendResponse(statusCode: 300, data: ["Message" => "Mismatch Request Method"]);
            }
        } else {
            $requestHandler->sendResponse(statusCode: 404,data: ["Message" => "Endpoint Not Found"]);

        }

    }


    private static function separateParameters($urlParameters): array{
        $parameters = null ;
        foreach ($urlParameters as $parameter){
        $param = explode("=",$parameter);
        $parameters [$param[0]]  = $param[1];
        }
        return $parameters ;
    }

    private static function fetchBodyAndUrlParameters():array|bool{
        // set url parameters if exist
        if (isset(parse_url($_SERVER['REQUEST_URI'])['query'])){
            $urlParameters = parse_url($_SERVER['REQUEST_URI'])['query'];
            $parameter["url_parameters"] = static::separateParameters(explode("&", $urlParameters));
        }
        // set url body if exist
        if ($body = file_get_contents('php://input')){
            $parameter["body_json"] = json_decode($body,true);
        }

        return ($parameter ?? false);
    }

}
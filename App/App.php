<?php
namespace App;
class App {

    protected static $container ;

    public static function setContainer($container){
        static::$container = $container;
    }

    public static function getContainer(){
        return static::$container;
    }

    public static function getInstance($instanceName){
        return static::$container->get($instanceName);
    }


}
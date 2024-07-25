<?php
namespace App;
class App {

    protected static $container ;

    public static function setContainer($container):void{
        static::$container = $container;
    }

    public static function getContainer(){
        return static::$container;
    }

    public static function getInstance($instanceName){
        return static::$container->get($instanceName);
    }

    public static function setInstance($instanceName,$instance):void{
        static::$container->set($instanceName,$instance);
    }

}
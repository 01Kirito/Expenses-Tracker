<?php
namespace App;
class Container{
    public array $container = [];

    public function set(string $key, $value){
        if(!array_key_exists($key, $this->container)){
            $this->container[$key] = $value;
        }
    }

    public function get(string $key){
        return $this->container[$key];
    }
}
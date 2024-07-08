<?php
namespace App;
class User {

    public function store($Data = null){
       return json_encode(["message","hello this is from store function"]);
    }


}
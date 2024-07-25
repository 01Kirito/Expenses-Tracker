<?php

namespace App\Middleware;

use App\App;
use App\Auth;

class Admin extends Middleware
{
    private static $adminsEmail = ["admin@gmail.com","musabjaf12@gmail.com","musabjaf15@gmail.com","musabjaff@gmail.com"];

 public function validations($token): bool
 {
    $isValidToken = $this->isValidToken($token);
    if($isValidToken === false) return false ;
    return $this->isAdmin();
 }

 private function isAdmin(): bool
 {
     $userEmail = App::getInstance(Auth::class)->getUser()['email'];
     if (in_array($userEmail, self::$adminsEmail)) {
         return true;
     }else{
         return false ;
     }
 }

}
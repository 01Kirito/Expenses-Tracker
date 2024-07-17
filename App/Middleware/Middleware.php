<?php

namespace App\Middleware;

use App\App;
use App\Auth;
use App\Model\User;
use ReallySimpleJWT\Token;

class Middleware
{
private array $gate ;

public function __construct(string $table = "users")
{
   $this->gate["table"]=$table;
}

public function authenticate(): bool{
    $token = getallheaders()["Authorization"];
    if ($this->validations($token)){
        return true;
    }else{
        return false;
    }
}

protected function isValidToken(string $token):bool{
    if (Token::validate($token, $_ENV["JWT_SECRET"])){
        $payload = Token::getPayload($token);
        $user    = App::getInstance(User::class)->searchForOneRow(conditions: ["id"=>$payload["uid"]]) ;
        App::setInstance(Auth::class,new Auth($user));
        return true;
    }else{
        return false;
    }
}

public function validations($token):bool{
    if ($this->isValidToken($token)){
        return true;
    }else{
        return false;
    }
}

}
<?php

namespace App;


class Auth
{
    private static $User;
    public function __construct($user)
    {
        static::$User = $user ;
    }

    public static function getUser()
    {
        return self::$User;
    }
}
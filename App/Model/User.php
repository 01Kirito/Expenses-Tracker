<?php
namespace App\Model;
use App\App;
use App\Http\RequestHandler;

class User extends Model{

    protected $table = 'users';
    protected array $hashColumns = ["password"];

}
<?php
namespace App;
use PDO;

class Database {

    private $connection;
    function __construct() {
       $dsn = "mysql:host=".$_ENV["DB_HOST"].";port=".$_ENV["DB_PORT"].";dbname=".$_ENV["DB_NAME"].";charset=".$_ENV["DB_CHAR"];
       $pdo = new PDO($dsn, $_ENV["DB_USER"], $_ENV["DB_PASS"]);
       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       $this->connection = $pdo;
    }

    public function getConnection(): mixed
    {
        return $this->connection;
    }

}
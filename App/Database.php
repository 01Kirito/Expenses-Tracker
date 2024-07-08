<?php
namespace App;
class Database {

    private $connection;
    function __construct() {
       $this->connection = require_once 'Migration/connection.php';
    }

    public function getConnection(): mixed
    {
        return $this->connection;
    }

}
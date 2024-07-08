<?php
namespace App;

class Model {
    protected const TABLE = null;
    protected $pdo ;
    function __construct($Database) {
        $this->pdo = $Database->getConnection() ;
    }

    function create(array $data) {
        $columns = implode(',',array_keys($data));
        $sql = "INSERT INTO ".self::TABLE." (".$data.")";
    }

    function read(){

    }

    function update(){

    }

    function delete(){

    }

    function softDelete(){

    }

    function fetchAll(){

    }

    function fetchOne(){

    }



}
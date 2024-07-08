<?php
namespace App;
use App\App ;

class Model {
    protected $table ;
    protected $pdo ;


    public function __construct() {
        $this->pdo = App::getInstance(Database::class)->getConnection() ;
    }

    public function create(array $data=null) {
          $query = $this->arrayToQuery($data);
          $sql = "INSERT INTO ".$this->table." (".$query[0].")"." VALUES (".$query[1].");";
          $stmt = $this->pdo->prepare($sql);
          if ($stmt->execute($query[2])) {
              return true ;
          }else{
              echo "Error: " . $sql . "<br>" . $pdo->errorInfo();
              return false ;
          }

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



    function arrayToQuery($data){
        $keys = array_keys($data);
        $values = array_values($data);
        $params = array_map(function (){ return "?";},array_keys($data));
        $keys = implode(",",$keys);
        $params = implode(",",$params);


        return [$keys,$params,$values];
    }

}
<?php
namespace App\Model;
use App\App;
use App\Database;
use mysql_xdevapi\Exception;

class Model {
    protected $table ;
    protected $pdo ;


    public function __construct() {
        $this->pdo = App::getInstance(Database::class)->getConnection() ;
    }

    public function create(array $data) {
        try {
            $query = $this->arrayToInsertQuery($data);
            $sql = "INSERT INTO " . $this->table . " (" . $query[0] . ")" . " VALUES (" . $query[1] . ");";
            $stmt = $this->pdo->prepare($sql);

            if ($stmt->execute($query[2])) {
                return true;
            } else {
                return throw new Exception("Error creating row in table " . $this->table);
            }
        }catch (\PDOException $e) {
            return $e->getMessage();
        }

    }

    public function read($selection = "*" ,$conditions = '', $order= '', $limit= '') {

        $sql = "Select ".$selection." From ".$this->table." ".$conditions." ".$order." ".$limit.";";
        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute()) {
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        }else{
            return false ;
        }
    }

    function update($columns ,array $conditions = [],bool $autoDateUpdate = false) {
        $query = $this->arrayToUpdateQuery($columns);
        $sql = "UPDATE " . $this->table . " SET ".$query["UpdatingColumns"];
        if ($autoDateUpdate) {
            $time = time();
            date_default_timezone_set('Asia/Baghdad');
            $current = date("Y-m-d h-i-s",$time);
        $sql = "UPDATE " . $this->table . " SET updated_at ='".$current."' ".$query["UpdatingColumns"];
        }
        if (!empty($conditions)) {
            $query2 = $this->arrayToCondition($conditions);
            $sql .= " ".$query2["Conditions"];
            $query["allParameters"] = array_merge($query["Values"],$query2["Values"]);
        }
        var_dump($sql);
        var_dump($query["allParameters"]);
        $stmt = $this->pdo->prepare($sql);
        if ($stmt->execute($query["allParameters"])) {
            return true;
        } else {
            return throw new Exception("Error creating row in table " . $this->table);
        }
    }

    function delete(){

    }

    function softDelete(){

    }

    function fetchAll(){

    }

    function fetchOne(){

    }

    function arrayToCondition(array $conditions):array{
        $keys = array_keys($conditions);
        $values = array_values($conditions);
        $clauses = array_map(function ($key){return $key." = ? ";},$keys);
        $query = " Where ". implode(" AND ",$clauses);

        return ["Conditions"=>$query,"Values"=>$values];
    }

    function arrayToUpdateQuery(array $data):array{
        $keys = array_keys($data);
        $values = array_values($data);
        $updateColumns = array_map(function ($key){ return $key. "= ?";},$keys);
        $updateColumns = implode(" AND ",$updateColumns);

        return ["UpdatingColumns"=>$updateColumns,"Values"=>$values];
    }


    function arrayToInsertQuery(array $data):array{
        $keys = array_keys($data);
        $values = array_values($data);
        $params = array_map(function (){ return "?";},array_keys($data));
        $keys = implode(",",$keys);
        $params = implode(",",$params);

        return [$keys,$params,$values];
    }

}
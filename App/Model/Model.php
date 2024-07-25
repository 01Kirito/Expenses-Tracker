<?php
namespace App\Model;
use App\App;
use App\Database;
use App\Http\RequestHandler;
use App\Router;

class Model {
    protected $table ;
    protected static $pdo ;
    protected static $requestHandler ;

    public function __construct() {
        static::$pdo = App::getInstance(Database::class)->getConnection() ;
        static::$requestHandler = App::getInstance(RequestHandler::class) ;
    }

    public function create(array $data):void {
            $query = $this->arrayToInsertQuery($data);
            $sql = "INSERT INTO " . $this->table . " (" . $query["Keys"] . ")" . " VALUES (" . $query["Parameters"] . ");";
            $stmt = static::$pdo->prepare($sql);
            try {
            $result =$stmt->execute($query["Values"]);
            if ($result) {
                $this->response(201,["message" =>$this->table." created"]);
            }else{
                $this->response(500,["message"=>$this->table." creating failed."]);
            }}catch (\PDOException $e) {
                $this->response(500,["message"=>$this->table." creating failed.","error"=>$e->getMessage()]);
            }
    }

    public function read($selection = "*" ,$conditions = '', $order= '', $limit= ''):void {
        $sql = "Select ".$selection." From ".$this->table." ".$conditions." ".$order." ".$limit.";";
        $stmt = static::$pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if ($result) {
            $this->response(200, $result);
        }else{
            $this->response(500,["message"=>"No row found in table  ".$this->table]);
        }
    }

    public function getAll($selection = "*") {
        $sql = "Select ".$selection." From ".$this->table." ;";
        $stmt = static::$pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if ($result) {
            return $result ;
        }else{
            $this->response(500,["message"=>"No row found in table  ".$this->table]);
        }
    }


    public function searchByGrouping( $groupByColumns , $selection = "*" ,$conditions = ''){
        $sql = "Select ".$selection." From ".$this->table." ".$conditions." GROUP BY ".$groupByColumns." ".";";
        $stmt = static::$pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        if ($result) {
           return $result ;
        }else{
            $this->response(500,["message"=>"No row found in table  ".$this->table]);
        }
    }

    public function updateWithResponse($columns ,array $conditions = [],bool $autoDateUpdate = false):void {
        $query = $this->arrayToUpdateQuery($columns);
        $sql = "UPDATE `".$this->table."` SET ".($autoDateUpdate === true ? "updated_at ='".$this->currentTime()."', " : "" ).$query["UpdatingColumns"];

        if (!empty($conditions)) {
            $query2 = $this->arrayToCondition($conditions);
            $sql .= " ".$query2["Conditions"];
            $query["allParameters"] = array_merge($query["Values"],$query2["Values"]);
        }

        $stmt = static::$pdo->prepare($sql);
        $stmt->execute($query["allParameters"]);
        $rowChanged=$stmt->rowCount();
        if ($rowChanged > 0) {
            $this->response(200,["message"=>$rowChanged." Row updated in table ".$this->table." successfully."]);
        } else {
            $this->response(500,["message"=>"Update the row in table ".$this->table." failed."]);
        }
    }

    public function update($columns ,array $conditions = [],bool $autoDateUpdate = false) {
        $query = $this->arrayToUpdateQuery($columns);
        $sql = "UPDATE `".$this->table."` SET ".($autoDateUpdate === true ? "updated_at ='".$this->currentTime()."', " : "" ).$query["UpdatingColumns"];

        if (!empty($conditions)) {
            $query2 = $this->arrayToCondition($conditions);
            $sql .= " ".$query2["Conditions"];
            $query["allParameters"] = array_merge($query["Values"],$query2["Values"]);
        }

        $stmt = static::$pdo->prepare($sql);
        $stmt->execute($query["allParameters"]);
        $rowChanged=$stmt->rowCount();
        if ($rowChanged > 0) {
            return $rowChanged ;
        } else {
            return $rowChanged ;
        }
    }

    function delete(array $conditions):void{
        $query = $this->arrayToCondition($conditions);
        $sql = "DELETE FROM " . $this->table . " " . $query["Conditions"];
        $stmt = static::$pdo->prepare($sql);
        $stmt->execute($query["Values"]) ;
        $rowDeleted = $stmt->rowCount();
        if ($rowDeleted > 0) {
            $this->response(200,["message"=>$rowDeleted." Row deleted in table ".$this->table." successfully."]);
        } else {
            $this->response(500,["message"=>"Deleting the row in table ".$this->table." failed."]);
        }
    }

    function softDelete(array $conditions, bool $makeItHidden):void{
        $query = $this->arrayToCondition($conditions);
        $sql = "UPDATE " . $this->table . " SET updated_at ='".$this->currentTime()."' , soft_delete = ".(int)$makeItHidden.$query["Conditions"];
        $stmt = static::$pdo->prepare($sql);
        $stmt->execute($query["Values"]);
        $rowDeleted = $stmt->rowCount();
        if ($rowDeleted > 0) {
            $this->response(200,["message"=>$rowDeleted." Row deleted in table ".$this->table." successfully."]);
        } else {
            $this->response(500,["message"=>"Deleting the row in table ".$this->table." failed."]);
        }
    }

    function fetchAll(){

    }

    function fetchOne($selection = "*" ,array $conditions = []):void{
        $query  = $this->arrayToCondition($conditions);
        $sql    = "Select ".$selection." From ".$this->table." ".$query["Conditions"].";";
        $stmt   = static::$pdo->prepare($sql);
        $stmt->execute($query["Values"]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($result) {
            $this->response(200, $result);
        }else{
            $this->response(500,["message"=>"The row in table ".$this->table." did not found."]);
        }
    }

    function searchForOneRow($selection = "*" ,array $conditions = []){
        $query  = $this->arrayToCondition($conditions);
        $sql    = "Select ".$selection." From ".$this->table." ".$query["Conditions"].";";
        $stmt   = static::$pdo->prepare($sql);
        $stmt->execute($query["Values"]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    function searchForRows(array $conditions,$selection = "*" ){
        $query  = empty($this->arrayToCondition($conditions)) ? '': $this->arrayToCondition($conditions);
        $sql    = "Select ".$selection." From ".$this->table." ".$query["Conditions"].";";
        $stmt   = static::$pdo->prepare($sql);
        $stmt->execute($query["Values"]);
        return $stmt->fetchall(\PDO::FETCH_ASSOC);
    }


    public function addColumn($columnName,$columnType):void{
        $sql    = "ALTER TABLE ".$this->table." ADD COLUMN ".$columnName." ".$columnType.";";
        $stmt   = static::$pdo->prepare($sql);
        try {
        $result = $stmt->execute();
        if ($result) {
            $this->response(200, ["message"=>"Column created in table ".$this->table." successfully."]);
        }}catch (\PDOException $e) {
            $this->response(500,["message"=>$this->table." creating failed.","error"=>$e->getMessage()]);
        }
    }


    function arrayToCondition(array $conditions):array{
        $keys = array_keys($conditions);
        $values = array_values($conditions);
        $clauses = array_map(function ($key){return $key." = ? ";},$keys);
        $query = sizeof($clauses) ? " Where ". implode(" AND ",$clauses) : " Where $clauses[0]";

        return ["Conditions"=>$query,"Values"=>$values];
    }
    function arrayToUpdateQuery(array $data):array{
        $keys = array_keys($data);
        $values = array_values($data);
        $updateColumns = array_map(function ($key){ return $key. "= ?";},$keys);
        $updateColumns = implode(" , ",$updateColumns);

        return ["UpdatingColumns"=>$updateColumns,"Values"=>$values];
    }
    function arrayToInsertQuery(array $data):array{
        $keys = array_keys($data);
        $values = array_values($data);
        $params = array_map(function (){ return "?";},array_keys($data));
        $keys = implode(",",$keys);
        $params = implode(",",$params);

        return ["Keys"=>$keys,"Parameters"=>$params,"Values"=>$values];
    }


    private function response(int $statusCode,array $result,array $header = []):void{
        static::$requestHandler->sendResponse($statusCode,$header,$result);
    }

    private function getClassName():string {
        $classNamespace = explode('\\', static::class);
        return array_pop($classNamespace);
    }

    private function currentTime():string{
        $time = time();
        date_default_timezone_set('Asia/Baghdad');
        return date("Y-m-d h-i-s",$time);
    }

}
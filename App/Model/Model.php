<?php
namespace App\Model;
use App\App;
use App\Database;
use App\Http\RequestHandler;

class Model {
    protected $table ;
    protected array $hashColumns = [];
    protected static $pdo ;
    protected static $requestHandler ;

    public function __construct() {
        static::$pdo = App::getInstance(Database::class)->getConnection() ;
        static::$requestHandler = App::getInstance(RequestHandler::class) ;
    }
    public function create(array $data): array{
        $query = $this->arrayToInsertQuery($data);
        $sql = "INSERT INTO " . $this->table . " (" . $query["Keys"] . ")" . " VALUES (" . $query["Parameters"] . ");";
        $stmt = static::$pdo->prepare($sql);
        try {
            $stmt->execute($query["Values"]);
            $result["message"]= "Row in the table ".$this->table." created";
            return $result;
        }catch (\PDOException $e) {
            $result["error"] = $e->getMessage();
            $result["message"] ="Creating row in the table ". $this->table ." failed.";
            return $result;
        }
    }
    public function get(array $selection = ["*"], array $join = [], array $condition = [], array $logicalOperator = [], array $groupBy = [] , array $orderBy = [], int $limit = null, $fetchType = \PDO::FETCH_ASSOC, bool $fetchOneRow = false): array{
        $condition = $this->getCondition($condition,$logicalOperator);
        $sql = "SELECT ".implode(",",$selection).
            " FROM ".$this->table." ".$this->getJoin($join).
            " ". $condition["conditions"].
            " ". $this->getGroupBy($groupBy) .
            " ". $this->getOrderBy($orderBy) .
            " ". $this->getLimit($limit) .
            " ;"
        ;
        $stmt = static::$pdo->prepare($sql);
        try {
            empty($condition["conditions"]) ? $stmt->execute() : $stmt->execute($condition["values"]);
            $result = ($fetchOneRow === true ? $stmt->fetch($fetchType) : $stmt->fetchAll($fetchType));
            return empty($result) ? ["message"=>"The row didn't found in table ".$this->table] : $result ;
        }catch (\PDOException $e){
            return ["error"=> $e->getMessage() ,"message" => "error in reading the row in table ".$this->table ." failed."];
        }
    }
    public function update($column ,array $condition = [],array $logicalOperator = [] ,bool $autoDateUpdate = false): array{
        $query = $this->arrayToUpdateQuery($column);
        $sql = "UPDATE `".$this->table."` SET ".($autoDateUpdate === true ? "updated_at ='".$this->currentTime()."', " : "").$query["UpdatingColumns"];
        if (!empty($condition)) {
            $query2 = $this->getCondition($condition,$logicalOperator);
            $sql .= " ".$query2["conditions"];
            $query["allParameters"] = array_merge($query["Values"],$query2["values"]);
        }
        try {
            $stmt = static::$pdo->prepare($sql);
            $stmt->execute($query["allParameters"]);
            $result["message"]= "Row in the table ".$this->table.($stmt->rowCount() > 0 ? "" : " doesn't")." updated";
            return $result;
        }catch (\PDOException $e){
            $result["error"] = $e->getMessage();
            $result["message"] ="Updating row in the table ".$this->table ." failed.";
            return $result;
        }
    }
    function delete(array $conditions,array $logicalOperator = []): array{
        $query = $this->getCondition($conditions, $logicalOperator);
        $sql = "DELETE FROM " . $this->table . " " . $query["conditions"];
        try {
            $stmt = static::$pdo->prepare($sql);
            $stmt->execute($query["values"]);
            $rowDeleted = $stmt->rowCount();
            $result["message"]= $rowDeleted." Row deleted in table ".$this->table." successfully.";
            return $result;
        }catch (\PDOException $e){
            $result["error"] = $e->getMessage();
            $result["message"] ="Deleting the row in table ".$this->table." failed.";
            return $result;
        }
    }
    function softDelete(array $conditions,array $logicalOperator = [], bool $makeItHidden = true):array{
        $query = $this->getCondition($conditions,$logicalOperator);
        $sql = "UPDATE " . $this->table . " SET updated_at ='".$this->currentTime()."' , soft_delete = ".(int)$makeItHidden.$query["conditions"];
        try {
            $stmt = static::$pdo->prepare($sql);
            $stmt->execute($query["values"]);
            $rowDeleted = $stmt->rowCount();
            $result["message"]= $rowDeleted." Row deleted in table ".$this->table." successfully.";
            return $result;
        }catch (\PDOException $e){
            $result["error"] = $e->getMessage();
            $result["message"] ="Deleting the row in table ".$this->table." failed.";
            return $result;
        }
    }
    public function addColumn($columnName,$columnType): array{
        $sql    = "ALTER TABLE ".$this->table." ADD COLUMN ".$columnName." ".$columnType.";";
        $stmt   = static::$pdo->prepare($sql);
        try {
        $stmt->execute();
        $result["message"]="The column ".$columnName." added to the table ".$this->table." successfully.";
        return $result ;
        }catch (\PDOException $e) {
           $result["error"] = $e->getMessage();
           $result["message"] ="Adding column to the table ".$this->table ." failed.";
           return $result;
        }
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
        $values = $this->hashColumns($data);
        $params = array_map(function (){ return "?";},array_keys($data));
        $keys = implode(",",$keys);
        $params = implode(",",$params);

        return ["Keys"=>$keys,"Parameters"=>$params,"Values"=>$values];
    }
    private function hashColumns($data):array{
        return array_map(function ($column, $value){
            if(in_array($column,$this->hashColumns)) {
                if ($column === "password"){
                    $value = password_hash($value,constant($_ENV["PASS_HASH_ALG"]));
                }else{
                    $value = hash($_ENV["TEXT_HASH_ALG"], $value);
                }
            }
            return $value;
        },array_keys($data),array_values($data));
    }
    private function currentTime():string{
        $time = time();
        date_default_timezone_set('Asia/Baghdad');
        return date("Y-m-d h-i-s",$time);
    }
    private function getJoin(array $join):string {
        $joins = "";
        if (!empty($join)) {
            foreach ($join as $key => $value) {
                $joins .= " JOIN ".$key." ON ".$value;
            }
        }

      return $joins;
    }
    private function getCondition(array $condition ,array $logicalOperator ): array|string{
        $clauses = "";
        $values = [] ;
        if (!empty($condition)){
        $keys = array_keys($condition);
        $keys = array_map(function ($key){
            if (preg_match('/[<>=]/', $key)){
                return  $key;
            }else{
                return $key."=";
            }
            },$keys);
        $values = array_values($condition);
        $clauses .= "WHERE ".$keys[0]." ? ";
        for ($i=1 ; $i<sizeof($keys) ; $i++){
               $logicalOperator[$i-1] =  $logicalOperator[$i-1] ?? "AND" ;
               $clauses .= $logicalOperator[$i-1]." ".$keys[$i]." ? ";
        }
        }
        return ["conditions"=>$clauses,"values"=>$values];
    }
    private function getGroupBy(array $groupBy): string {
        return (empty($orderBy)) ? "" : " GROUP BY ".implode(",",$groupBy);
    }
    private function getOrderBy(array $orderBy): string {
        return (empty($orderBy)) ? "" : " ORDER BY ".implode(",",$orderBy);
    }
    private function getLimit(?int $limit):string {
        return (empty($limit) ? "" : " LIMIT ".$limit);
    }
}
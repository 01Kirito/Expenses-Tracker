<?php
// get parameters for the connection
require_once 'Parameter.php';
// we should know what column we have in the plan table so we fetch the column name by the below query
$sqlGetColumns = "SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = N'plans'";
// after that we should now how many categories we have so we add new categories to the plan table
$sqlGetCategories = "SELECT name FROM categories";

try {// make connection by this file
    $pdo = require_once 'connection.php';

    $pdo->beginTransaction();
    $stmt = $pdo->prepare($sqlGetColumns);
    $stmt->execute();
    $plansColumns = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $stmt = $pdo->prepare($sqlGetCategories);
    $stmt->execute();
    $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $columns =array_map(function ($row) {
        return strtolower($row['COLUMN_NAME']);
    }, $plansColumns);

  foreach ($categories as $category) {
    if (!in_array(strtolower($category["name"]),$columns)) {
        $addColumn = "ALTER TABLE plans ADD COLUMN " . strtolower($category["name"]) . " float NOT NULL default 0.00 ";
        $stmt = $pdo->prepare($addColumn);
        $stmt->execute();
        echo "Added " . $category["name"] . " to table 'plans' successfully<br>";
    }else {
        echo "Already exist column '" . $category["name"] . "' in table `plans` .<br>";
    }
    }
}catch (PDOException $e){
    echo $e->getMessage();
}
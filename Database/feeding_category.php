<?php
// get parameters for the connection
require_once 'Parameter.php';

//$sqlAddinCategories = "insert into categories (name,description) values ('Education','For education purposes like buy stuffs for study'),('Renting','For renting purposes like renting house or car ..etc'),('Food',default),('Clothe',default)";
$sqlAddinCategories = "insert into categories (name,description) values ('Card',default),('Service',default)";

try {
    // Connect to MySQL database
    $dsn = "mysql:host=".DB_HOST.";port=".DB_PORT.";dbname=".DB_NAME.";charset=".DB_CHAR;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to the server successfully<br>";

    $pdo->beginTransaction();
    $stmt = $pdo->prepare($sqlAddinCategories);
    if ($stmt->execute()){
        echo "Adding the category successfully<br>";
        $pdo->commit();
    }else{
        echo "Adding the category failed<br>";
    }

} catch (PDOException $e) {
    echo $e->getMessage();
}
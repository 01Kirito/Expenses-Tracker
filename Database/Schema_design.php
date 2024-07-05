<?php
// returns the querys we need from the 'Querys.php' file so we can run them by foreach loop easily
$querys = require_once 'Querys.php';
require_once 'Parameter.php';

// using try catch for the exceptions
try {
    // Connect to MySQL database
    $dsn = "mysql:host=$host;port=$port;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to the server successfully<br>";

//    run the querys we have one by one then print message if it's created or not
    foreach ($querys as $query) {
        $stmt = $pdo->prepare($query[0]);
        if ($stmt->execute()) {
            echo $query[1] . " successfully<br>";
        }else{
            echo $query[1] . " failed<br>";
        }
    }

} catch (PDOException $e) {
    echo $e->getMessage();
}
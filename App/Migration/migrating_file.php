<?php

require_once __DIR__.'/../../vendor/autoload.php';
require_once __DIR__.'/../../bootstrap.php';

// returns the query's we need from the 'querys.php' file, so we can run them by foreach loop easily
$querys = require_once __DIR__.'/querys.php';


try {
    // Connect to MySQL database
    $dsn = "mysql:host=".$_ENV["DB_HOST"].";port=".$_ENV["DB_PORT"].";charset=".$_ENV["DB_CHAR"];
    $pdo = new PDO($dsn, $_ENV["DB_USER"], $_ENV["DB_PASS"]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to the server successfully \n";

//    run the querys we have one by one then print message if it's created or not
    foreach ($querys as $query) {
        $stmt = $pdo->prepare($query[0]);
        if ($stmt->execute()) {
            echo $query[1] . " successfully \n";
        }else{
            echo $query[1] . " failed \n";
        }
    }

} catch (PDOException $e) {
    echo $e->getMessage();
}
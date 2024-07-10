<?php

$pdo = require_once 'connection.php';

// Number of users to seed
$numUsers = 1000;

// Function to generate a random string
function generateRandomString($length = 8)
{
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

// Prepare the SQL statement for inserting users
$sql = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at, soft_delete) 
        VALUES (:first_name, :last_name, :email, :password, :created_at, :updated_at, :soft_delete)";
$stmt = $pdo->prepare($sql);

// Seed users
for ($i = 0; $i < $numUsers; $i++) {
    $first_name = generateRandomString();
    $last_name = generateRandomString();
    $email = strtolower($first_name) . '.' . strtolower($last_name) . '@example.com'; // Generate unique email
    $password = password_hash('password123', PASSWORD_DEFAULT); // Hashed password example
    $created_at = date('Y-m-d H:i:s');
    $updated_at = date('Y-m-d H:i:s');
    $soft_delete = 0; // Assuming soft_delete is an integer (0 for active, 1 for deleted)

    // Bind parameters and execute the statement
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':created_at', $created_at);
    $stmt->bindParam(':updated_at', $updated_at);
    $stmt->bindParam(':soft_delete', $soft_delete);

    $stmt->execute();
}

echo "Users seeded successfully.";



<?php

// drop database
$sqlDropDatabase = "Drop database expenses_tracker";
$messageDropDatabase = "Database droped";

// create database
$sqlDatabase = "Create database expenses_tracker";
$messageDatabase = "Database expenses_tracker created ";

// create database
$sqlUseDatabase = "use expenses_tracker";
$messageUseDatabase = "Using expenses_tracker database";


// Create users table
$sqlUser = "CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    safe_delete BOOLEAN DEFAULT FALSE
)";
$messageUser = "Users table created";


// create category table 
$sqlCategories = "CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description VARCHAR(255)
)";
$messageCategories = "Categories table created";



// Create invoice table
$sqlInvoice = "CREATE TABLE invoice (
    invoice_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL references users(id),
    category_id INT NOT NULL references categories(category_id),
    amount DECIMAL(10,2) NOT NULL,
    description VARCHAR(255),
    purchase_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    
)";
$messageInvoice = "Invoice table created";


// Create budget table
$sqlBudget = "CREATE TABLE budget (
    user_id INT NOT NULL references users(id),
    total DECIMAL(10,2) NOT NULL,
    spent DECIMAL(10,2) NOT NULL,
    salary DECIMAL(10,2) NOT NULL
)";
$messageBudget = "Budget table created";


// Create preference table
$sqlPreference = "CREATE TABLE preference (
    user_id INT NOT NULL references users(id),
    theme VARCHAR(50) NOT NULL
)";
$messagePreference = "preference table created";


// Create plans table
$sqlPlans = "CREATE TABLE plans (
    user_id INT NOT NULL references users(id)
)";
$messagePlans = "plans table created";

$querys = [
 [$sqlDropDatabase,$messageDropDatabase]
,[$sqlDatabase,$messageDatabase]
,[$sqlUseDatabase,$messageUseDatabase]
,[$sqlUser,$messageUser]
,[$sqlCategories,$messageCategories]
,[$sqlInvoice,$messageInvoice]
,[$sqlBudget,$messageBudget]
,[$sqlPreference,$messagePreference]
,[$sqlPlans,$messagePlans],
];

return $querys; 
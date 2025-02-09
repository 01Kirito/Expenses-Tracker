<?php

// drop database
$sqlDropDatabase = "Drop database IF EXISTS expenses_tracker";
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
    soft_delete BOOLEAN DEFAULT FALSE
)";
$messageUser = "Users table created";


// create category table 
$sqlCategories = "CREATE TABLE categories (
    category_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT default null,
    name VARCHAR(50) NOT NULL unique ,
    description VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE 
)";
$messageCategories = "Categories table created";



// Create invoice table
$sqlInvoice = "CREATE TABLE invoices (
    invoice_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    category_id INT NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    description VARCHAR(255),
    purchase_date DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
)";
$messageInvoice = "Invoice table created";


// Create budget table
$sqlBudget = "CREATE TABLE budgets (
    user_id INT NOT NULL,
    total  DECIMAL(10,2) NOT NULL default 0.00,
    spent  DECIMAL(10,2) NOT NULL default 0.00,
    salary DECIMAL(10,2) NOT NULL default 0.00,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$messageBudget = "Budget table created";


// Create preference table
$sqlPreference = "CREATE TABLE preferences (
    user_id INT NOT NULL,
    theme VARCHAR(50) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$messagePreference = "preference table created";


// Create plans table
$sqlPlans = "CREATE TABLE plans (
    user_id INT NOT NULL ,
    custom_plan BOOLEAN NOT NULL DEFAULT FALSE, 
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE    
)";
$messagePlans = "plans table created";

// Create custom_plans table
$sqlCustomPlans = "CREATE TABLE custom_plans (
    user_id INT NOT NULL ,
    category_id INT NOT NULL ,
    category_plan DECIMAL(10,2) NOT NULL default 0.00,
    category_used DECIMAL(10,2) NOT NULL default 0.00,
    constraint UNIQUE_category_name UNIQUE (user_id, category_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ,
    FOREIGN KEY (category_id) REFERENCES categories(category_id) ON DELETE CASCADE
)";
$messageCustomPlans = "custom_plans table created";

// Create device_tokens table
$sqlDeviceToken = "CREATE TABLE device_tokens (
    token_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL ,
    token VARCHAR(220) NOT NULL ,
    device_type VARCHAR(200) NOT NULL ,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$messageDeviceToken = "device_tokens table created";



$querys = [
    [$sqlDropDatabase,$messageDropDatabase]
    ,[$sqlDatabase,$messageDatabase]
    ,[$sqlUseDatabase,$messageUseDatabase]
    ,[$sqlUser,$messageUser]
    ,[$sqlCategories,$messageCategories]
    ,[$sqlInvoice,$messageInvoice]
    ,[$sqlBudget,$messageBudget]
    ,[$sqlPreference,$messagePreference]
    ,[$sqlCustomPlans,$messageCustomPlans]
    ,[$sqlPlans,$messagePlans]
    ,[$sqlDeviceToken,$messageDeviceToken]
];

return $querys; 
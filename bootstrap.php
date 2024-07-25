<?php

use Dotenv\Dotenv;

// using below class to load the .env file that holds the sensitive data
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();
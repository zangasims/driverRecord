<?php
// connection.php

// Database credentials
$host     = 'localhost';
$dbname   = 'driverRecords';
$username = 'root';
$password = '';
$charset  = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, // throw exceptions on errors
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,       // fetch associative arrays
    PDO::ATTR_EMULATE_PREPARES   => false,                  // use real prepared statements
];

try {
    $conn = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // In production, you might log this instead of displaying it
    echo 'Database connection failed: ' . $e->getMessage();
    exit;
}
